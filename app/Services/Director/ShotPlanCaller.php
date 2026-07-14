<?php

// Create request body for shot_plan
// app/Services/Director/ShotPlanCaller.php
// Input: the prompt pieces + model string. Output: raw shots array + the done_reason verdict.
// NOTE: no validation here — that's S3. This returns whatever the model said, as-is.


namespace App\Services\Director;

use Illuminate\Support\Facades\Http;

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
}
