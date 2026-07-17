<?php

    // ===== RESOLVE CONFIG — sekali, di puncak =====
    $pack   = config('genre.horror');
    $recipe = config('recipes.shorts');
    $model = config('services.ollama.model');

    // ===== LOAD GOLDEN =====
    $raw  = json_decode(file_get_contents(base_path('tests/test-json/outputs/out_close_6.json')), true);
    $story = json_decode($raw['message']['content'], true);
    $beats  = $story['beats'];
    $estMap = array_column($beats, 'est_duration_sec', 'beat_id');

    // ===== S0 =====
    $s0 = new \App\Services\Director\MoodNormalizer();
    $beats = $s0->normalize($beats, $pack['mood_enum'], $pack['mood_keywords']);

    // ===== S1 =====
    $s1 = new \App\Services\Director\SceneStateComputer();
    $sceneStates = $s1->compute($beats, $pack['mood_to_arc'], $pack['payoff_stages'], $pack['max_shot_sec'], $recipe['max_shots_per_beat']);

    // ===== S4 =====
    $s4 = new \App\Services\Director\PostProcessor();
    $sceneStates = $s4->process($sceneStates, $estMap, $pack['min_shot_sec'], $pack['max_shot_sec'], $recipe['max_shots_per_beat']);

    // ===== S2 =====
    $s2 = new \App\Services\Director\ShotPlanCaller();
    $system = $s2->buildSystemPrompt($pack);
    $user = $s2->buildUserPrompt($beats, $sceneStates, $estMap);
    $s3 = new \App\Services\Director\ShotPlanGuard();

    // ===== FIRE (L4.2 + L4.3) =====
    $naskahMap    = [];  // Pass 1 skipped

    $needReview   = false;
    foreach($sceneStates as $ss){
        if($ss['need_review'] ?? null){
            $needReview = true;
            break;
        }
    }
    $violationLog = [];
    $shots        = null;
    $warnings     = [];
    $attemptsMade = 3;

    for ($attempt = 1; $attempt <= 3; $attempt++) {
        $callResult = $s2->call($system, $user, $model);

        // GATE 1 — done_reason
        if ($callResult['done_reason'] != 'stop'){
            $violationLog[] = [
                'attempt' => $attempt,
                'done_reason' => $callResult['done_reason'],
                'violations' => [[
                        'rule' => 'transport',
                        'beat_id' => null,
                        'shot_index' => null,
                        'field' => null,
                        'got' => $callResult['done_reason'],
                        'allowed' => 'stop'
                    ]],
            ];
            continue;
        };

        // GATE 2 — validate
        $guardResult = $s3->validate($callResult['shots']  ?? [], $sceneStates, $pack);
        if (!empty($guardResult['violations'])) {
            $violationLog[] = [
                'attempt' => $attempt,
                'done_reason' => $callResult['done_reason'],
                'violations' => $guardResult['violations']
            ];
            continue;
        }

        $shots = $callResult['shots'];
        $warnings = $guardResult['warnings'];
        $attemptsMade = $attempt;
        break;
    }

    // ===== SHIP (L4.4) =====
    if ($shots !== null) {
        $stamped  = $s3->stamp($shots, $sceneStates, $estMap, $naskahMap, $pack);
        $envelope = [
            'status'        => 'ok',
            'shots'         => $stamped,
            'need_review'   => $needReview,
            'warnings'      => $warnings,
            'attempts'      => $attemptsMade,
            'violation_log' => $violationLog
        ];

    } else {
        $envelope = [
            'status'        => 'flagged',
            'shots'         => [],
            'need_review'   => $needReview,
            'warnings'      => $warnings,
            'attempts'      => $attemptsMade,
            'violation_log' => $violationLog
        ];
    }
    dd($envelope);
