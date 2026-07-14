<?php

// in = SceneStateComputer DTO + normalized beats DTO
// Make sure no shot <2 secs
/**
 * sceneStates = from SceneStateComputer (only shot_count matters)
 * estMap = mapped estimated duration per beats
 * the rest is, the name means it self.
 */

// out =
/**
 * new shot_count (if updated)
*/

namespace App\services\director;

class PostProcessor
{
    public function process(array $sceneStates, array $estMap, int $minShotSec, int $maxShotSec, int $maxShotsPerBeat): array{
        foreach($sceneStates as $s){
            $est_sec_per_beat = $estMap[$s['beat_id']];
            $cur_shot_count = $s['shot_count'];

            if($est_sec_per_beat/$cur_shot_count < $minShotSec){
                // floor shot sount limit
                $floor_limit = (max(1, floor($est_sec_per_beat/$minShotSec)));
                $new_shot_count = min($cur_shot_count, $floor_limit);

                if($est_sec_per_beat/$new_shot_count<2 || $new_shot_count > $maxShotsPerBeat){
                    $sceneStates[$s['beat_id']]['need_review'] = true;
                }

                $sceneStates[$s['beat_id']]['shot_count'] = $new_shot_count;
            }
        }

        return $sceneStates;
    }
}
