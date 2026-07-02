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
    ]
]

?>
