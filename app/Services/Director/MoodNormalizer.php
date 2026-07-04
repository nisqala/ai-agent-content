<?php

/**
 * @param array<int, array{
 *     beat_id: string,
 *     intent: string,
 *     mood: string,
 *     est_duration_sec: int,
 *     onscreen_text: string
 * }> $beats
 */

namespace App\Services\Director;

class MoodNormalizer{
    public function normalizeMood(array $beats): array{

        $moods_enum = config('genre.horror.mood_enum');
        $mood_keywords = config('genre.horror.mood_keywords');// 8 canonical mood enums

        foreach ($beats as &$b) {
            $b['mood_raw'] = $b['mood'];
            $mood = strtolower($b['mood']);
            $b['need_review'] = false;
            // Tier 1: Exact Match
            if(in_array($mood, $moods_enum, true)){
                $b['mood'] = $mood;
                continue;
            }else if(array_any($mood_keywords, fn ($values)=>in_array($mood, $values, true))){ // Tier 3: Synonyms and tier 2: Aliases (same thing)- alias = synonyms
                $mood = array_find_key($mood_keywords, fn($values) => in_array($mood, $values, true));
            }else{
                $candidates = array_merge($moods_enum, array_merge(...array_values($mood_keywords)));
                $distance = null;
                $possible_mood = null;

                // Check typos
                foreach ($candidates as $c) {
                    $current_distance = levenshtein(strtolower($mood), strtolower($c));

                    if ($distance === null || $current_distance < $distance) {
                        $distance = $current_distance;
                        $possible_mood = $c;
                    }
                }

                // check typo AFTER finding closest mood
                if ($distance <= 2) {
                    if(!in_array($possible_mood, $moods_enum, true)){
                        $possible_mood = array_find_key($mood_keywords, fn($values) => in_array($possible_mood, $values, true));
                        $mood=$possible_mood;
                    }
                } else {
                    $b['need_review'] = true;
                    $mood = $b['mood_raw'];
                }
            }

            $b['mood'] = $mood;
        }

        unset($b);
        return $beats;
    }
}
