<?php

/** BEATS
 * @param array<int, array{
 *     beat_id: string,
 *     intent: string,
 *     mood: string,
 *     est_duration_sec: int,
 *     onscreen_text: string
 * }> $beats
 */

/** SCENE _STATE
 *  @param array<int, array{
 *   "beat_id": "beat_01",
 *   "beat_index": 1,
 *   "total_beats": 4,
 *   "arc_stage": "wrongness",
 *   "prior_mood": null,
 *   "cumulative_duration_sec_before": 0,
 *   "is_payoff_beat": false,
 *   "shot_count": 1
 * } > $scene_state
 */


/**
*     array $beats,           // from StoryOutline, in order
*    array $moodToArc,       // canonical_mood => arc_stage (many-to-one)
*    array $payoffStages,    // e.g. ['payoff']
*    int   $maxShotSec,      // pack.max_shot_sec
*    int   $maxShotsPerBeat  // recipe.max_shots_per_beat
*): array                    // keyed by beat_id
 */

namespace App\Services\Director;
class SceneStateComputer
{
    public function computeSceneState(array $beats, array $moodToArc, array $payoffStages, int $maxShotSec, int $maxShotsPerBeat): array{
        $scene_states = array();
        $n=1;
        $cum_duration = 0;
        $prior_mood = null;

        foreach($beats as $b){
            $scene_state = [];

            $scene_state['beat_id'] = $b['beat_id'];
            $scene_state['beat_index'] = $n;
            $scene_state['prior_mood'] = $prior_mood;
            $scene_state['cumulative_duration_sec_before'] = $cum_duration;

            if($b['need_review'] ?? false){
                $scene_state['arc_stage'] = 'needs_review';
                $scene_state['is_payoff_beat'] =false;
            }else{
                $scene_state['arc_stage'] = $moodToArc[$b['mood']];
                $scene_state['is_payoff_beat'] = in_array($scene_state['arc_stage'], $payoffStages, true);
            }
            $scene_state['shot_count'] = (int) min(max(ceil($b['est_duration_sec']/$maxShotSec)+($scene_state['is_payoff_beat'] ? 1 : 0),1),$maxShotsPerBeat);
            $scene_state['total_beats'] = count($beats);

            $n++;
            $cum_duration += $b['est_duration_sec'];
            $prior_mood = $b['mood'];

            $scene_states[$scene_state['beat_id']] = $scene_state;
        }

        return $scene_states;
    }
}
