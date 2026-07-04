<?php

return [
    'mood_to_arc' => [
        'quiet_wrongness' => 'wrongness',
        'built_dread'=> 'dread',
        'paranoia_rl_bleed'=> 'dread',
        'tense_silence'=> 'dread',
        'investigation'=> 'dread',
        'escalation_beat'=> 'escalation',
        'earned_payoff_terror'=> 'payoff',
        'aftermath_hollow'=> 'aftermath'
    ],
    'mood_enum' => [
        'quiet_wrongness', 'built_dread', 'paranoia_rl_bleed', 'tense_silence','investigation', 'escalation_beat', 'earned_payoff_terror', 'aftermath_hollow',
    ],
    'arc_stages' =>[
        'wrongness', 'dread', 'escalation', 'payoff', 'aftermath'
    ],
    'shot_type_whitelist' =>[
        'extreme_wide', 'wide', 'medium', 'medium_close', 'close_up', 'extreme_close', 'ots', 'pov', 'two_shot', 'insert_cutaway'
    ],
    'camera_move_whitelist' => [
        'static_locked', 'slow_dolly_push_in', 'slow_dolly_pull_out','pan_left', 'pan_right', 'tilt_up', 'tilt_down', 'tracking', 'following', 'leading', 'crane_jib', 'aerial_drone', 'handheld', 'pov_walking', 'whip_pan', 'dolly_zoom', 'arc_orbit'
    ],
    'payoff_gated_moves' => [
        'whip_pan', 'dolly_zoom'
    ],
    'shot_duration' => [
        'min' => 3, 'max' => 8
    ],
    'mood_keywords' => [  // Tier 3 — lowercase tokens for fuzzy / substring / keyword match
        'quiet_wrongness'      => ['quiet','wrong','off','subtle','uneasy','eerie','unsettling','melancholy','wistful','strange','odd','faint','disquiet'],
        'built_dread'          => ['dread','oppressive','dreadful','ominous','foreboding','heavy','looming','building','suffocating','menacing','gloom'],
        'paranoia_rl_bleed'    => ['paranoia','paranoid','watched','followed','anxious','creeping','hunted','pursued','exposed','presence','unseen','vulnerable'],
        'tense_silence'        => ['tense','silence','silent','still','suspense','suspenseful','breathless','anticipation','hush','motionless','waiting'],
        'investigation'        => ['investigation','curious','suspicious','searching','methodical','probing','mystery','clue','uncovering','examining','procedural', 'investigative'],
        'escalation_beat'      => ['escalation','escalating','rising','mounting','intensifying','worsening','climbing','spiraling','alarming','frantic','urgent','accelerating', 'intense'],
        'earned_payoff_terror' => ['terror','terrifying','horrifying','horror','shocking','frightening','petrifying','nightmarish','monstrous','reveal','climax','peak'],
        'aftermath_hollow'     => ['aftermath','hollow','empty','desolate','bleak','mournful','grief','numb','lonely','somber','resigned','void','forlorn'],
    ],
]

?>
