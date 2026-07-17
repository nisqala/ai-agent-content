<?php

namespace App\Services\Director;

class ShotPlanGuard
{
    private function record(string $rule, string $beatId, ?int $shotIndex, string $field, mixed $got, mixed $allowed): array{
        return [
            'rule' => $rule,
            'beat_id' => $beatId,
            'shot_index' => $shotIndex,
            'field' => $field,
            'got' => $got,
            'allowed' => $allowed
        ];
    }
    public function validate(array $shots, array $sceneStates, array $guardParams): array{
        // validate shots
        $violations = [];
        $warnings = [];

        foreach($shots as $i => $s){
            if(!in_array($s['beat_id'], array_keys($sceneStates))){
                $violations[] = $this->record('unknown_beat_id', $s['beat_id'], $i, 'beat_id', $s['beat_id'], array_keys($sceneStates));
                continue;
            }

            if(!in_array($s['shot_type'], $guardParams['shot_type_whitelist'])){
                $violations[] = $this->record('enum_shot_type', $s['beat_id'], $i, 'shot_type', $s['shot_type'], $guardParams['shot_type_whitelist']);
            }

            if(!in_array($s['camera_move'], $guardParams['camera_move_whitelist'])){
                $violations[] = $this->record('enum_camera_move', $s['beat_id'], $i, 'camera_move', $s['camera_move'], $guardParams['camera_move_whitelist']);
            }

            if(!in_array($s['mood'], $guardParams['mood_enum'])){
                $violations[] = $this->record('enum_mood', $s['beat_id'], $i, 'mood', $s['mood'], $guardParams['mood_enum']);
            }

            if(!$sceneStates[$s['beat_id']]['is_payoff_beat'] && in_array($s['camera_move'], $guardParams['payoff_gated_moves'])){
                $violations[] = $this->record('payoff_gate', $s['beat_id'], $i, 'camera_move', $s['camera_move'],  $guardParams['payoff_gated_moves']);
            }

            if(!$sceneStates[$s['beat_id']]['is_payoff_beat'] && in_array($s['camera_move'], $guardParams['payoff_soft_moves'])){
                $warnings[] = $this->record('payoff_gate_soft', $s['beat_id'], $i, 'camera_move', $s['camera_move'], $guardParams['payoff_soft_moves']);
            }
        }

        $shots_beat_id_count = array_count_values(array_column($shots, 'beat_id'));

        foreach($sceneStates as $key => $s){
            $actual = $shots_beat_id_count[$key] ?? 0;
            if($s['shot_count'] != $actual){
                $violations[] = $this->record('count_mismatch', $key, null, 'shot_count', $actual, $s['shot_count']);
            }
        }
        return ['violations' => $violations, 'warnings' => $warnings];
    }

    // assewmble, label & package ready ship
    public function stamp(array $shots, array $sceneStates, array $estMap, array $naskahMap, array $stampParams): array{
        $scenes = [];
        // regroup & sequence. no null check cause it should be all validated by validate() already
        // sort first. no need shot index in $shots?
        uasort($sceneStates, fn($a, $b) =>
            $a['beat_index'] <=> $b['beat_index']
        );

        foreach($shots as $s){
            $sceneStates[$s['beat_id']]['shots'][] =$s;
        }

        foreach($sceneStates as $ss){
            foreach($ss['shots'] as $i => $s){
                $temp =[];
                // Identity stamps
                $temp['scene_id'] = $ss['beat_id'] . '_shot_' . sprintf('%02d', $i+1);
                $temp['beat_id'] = $ss['beat_id'];
                $temp['shot_index_in_beat'] = $i+1;
                // add fields
                $temp['shot_type'] = $s['shot_type'];
                $temp['camera_move'] = $s['camera_move'];
                $temp['mood'] = $s['mood'];
                $temp['intent'] = $s['intent'];
                $temp['narasi'] = $naskahMap[$ss['beat_id']]['narasi'] ?? '';
                $temp['onscreen_text'] = $naskahMap[$ss['beat_id']]['onscreen_text'] ?? '';
                // Duration Math
                $temp['duration_sec'] = round(min($stampParams['max_shot_sec'], max($stampParams['min_shot_sec'], $estMap[$ss['beat_id']] / $ss['shot_count'])),1);
                $scenes[] = $temp;
            }
        }

        return $scenes;
    }
}
