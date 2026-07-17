<?php

// Create request body for shot_plan
// app/Services/Director/ShotPlanCaller.php
// Input: the prompt pieces + model string. Output: raw shots array + the done_reason verdict.
// NOTE: no validation here — that's S3. This returns whatever the model said, as-is.


namespace App\Services\Director;

use App\services\director\PostProcessor;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Console\Input\StringInput;

class ShotPlanCaller
{
    public function call(string $systemPrompt, string $userPrompt, string $model): array{
        $format = [
            'type' => 'object',
            'properties' => [
                'shots' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'beat_id'     => ['type' => 'string'],
                            'shot_type'   => ['type' => 'string'],
                            'camera_move' => ['type' => 'string'],
                            'mood'        => ['type' => 'string'],
                            'intent'      => ['type' => 'string'],
                        ],
                        'required' => ['beat_id', 'shot_type', 'camera_move', 'mood', 'intent'],
                        'additionalProperties' => false,
                    ],
                ],
            ],
            'required' => ['shots'],
            'additionalProperties' => false,
        ];
        $body = [
            'model'    => $model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user',   'content' => $userPrompt],
            ],
            'stream'   => false,
            'think'    => false,                 // TOP-LEVEL — sibling of model, NOT in options
            'format'   => $format,               // the object from Block A
            'options'  => [
                'temperature' => 0.3,
                'num_ctx'     => 24576,
            ],
        ];

        try {
            $response = Http::timeout(120)->post(
                config('services.ollama.base_url').'/api/chat',
                $body
            );
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return [
                'shots'       => [],
                'done_reason' => 'connection_error',   // our own sentinel — Ollama never emits this,
                                                    // so S3 can tell "server down" from "model failed"
                'raw'         => null,
            ];
        }

        $data = $response->json();
        $done_reason = $data['done_reason'] ?? null;

        if($done_reason != 'stop'){
            $shots = null;
        }else{
            $content = json_decode($data['message']['content'] ?? '', true);
            $shots = is_array($content) ? ($content['shots'] ?? null) : null;
        }

        return [
            'shots' => $shots ?? [],
            'done_reason' => $done_reason,
            'raw' => $data
        ];
    }

    public function buildSystemPrompt(array $promptParams): string{
        $shot_types= implode(', ', $promptParams['shot_type_whitelist']);
        $camera_moves = implode(', ', $promptParams['camera_move_whitelist']);
        $moods= implode(', ', $promptParams['mood_enum']);

        $craftRules  = $promptParams['craft_rules'];

        return <<<PROMPT
            You are a film director planning the camera shots for a short-form video.
            You receive story beats with pre-computed scene context. Your only job is
            creative: choose shot composition, camera movement, mood, and intent per shot.

            ## OUTPUT FORMAT
            Respond with ONLY this JSON structure - no explanation, no markdown:
            {"shots":[{"beat_id":"...","shot_type":"...","camera_move":"...","mood":"...","intent":"..."}]}

            - One object per shot, in playback order.
            - Every shot has exactly these 5 fields. Never add fields. Never copy
            scene-state fields into your output.
            - Example of ONE valid shot:
            {"beat_id":"beat_01","shot_type":"medium","camera_move":"static","mood":"quiet_wrongness","intent":"hold the room; the anomaly sits small at the frame edge"}

            ## ALLOWED VALUES - copy tokens EXACTLY as written
            shot_type (choose only from): {$shot_types}
            camera_move (choose only from): {$camera_moves}
            mood (choose only from): {$moods}

            ## CRAFT RULES
            {$craftRules}

            ## PREFERENCES (soft - never block or delay output over these)
            - Prefer not to repeat the same shot_type in two consecutive shots when a
            reasonable alternative exists.

            ## PROCESS
            Write each shot once. Do not re-check, re-count, or revise. When every beat
            has its required number of shots, output the JSON and stop.
            PROMPT;
    }

    public function buildUserPrompt(array $beats, array $sceneState, array $estMap): string{
        $beatLines = '';
        foreach ($beats as $b) {
            $id  = $b['beat_id'];
            $est = $estMap[$id];
            $beatLines .= "- beat_id: {$id} | mood: {$b['mood']}\n";
            $beatLines .= "  intent: {$b['intent']}\n";
        }

        $stateJson = json_encode($sceneState, JSON_PRETTY_PRINT);

        $countParts = [];
        foreach ($sceneState as $beatId => $state) {
            $countParts[] = "{$beatId}: {$state['shot_count']}";
        }
        $countLines = implode(' · ', $countParts);
        $total = array_sum(array_column($sceneState, 'shot_count'));

        return <<<PROMPT
            STORY BEATS (plan shots for these, in order):
            {$beatLines}

            SCENE STATE (read-only context, computed by the system - use it to inform
            your choices; do NOT copy any of these fields into your output):
            {$stateJson}

            SHOT COUNT (exact - produce EXACTLY this many shots per beat, no more, no fewer):
            {$countLines}
            Total shots: {$total}
            PROMPT;
    }
}
