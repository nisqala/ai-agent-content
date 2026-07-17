<?php

return [
    'max_shot_sec' => 8,      // divisor in: ceil(est_duration / this)
    'min_shot_sec' => 2,    // floor for post-split shot duration in #4
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
    'payoff_stages' => ['payoff'],
    'shot_type_whitelist' =>[
        'extreme_wide', 'wide', 'medium', 'medium_close', 'close_up', 'extreme_close', 'ots', 'pov', 'two_shot', 'insert_cutaway'
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
    'shot_type_whitelist' => [
        'extreme_wide', 'wide', 'medium', 'medium_close_up',
        'close_up', 'extreme_close_up', 'over_the_shoulder',
        'pov', 'insert',
    ],
    'camera_move_whitelist' => [
        'static', 'slow_push_in', 'slow_pull_out',
        'pan_left', 'pan_right', 'tracking', 'leading',
        'whip_pan', 'handheld',
    ],
    'payoff_gated_moves' => [
        'whip_pan'
    ],
    'payoff_soft_moves' => [
        'handheld'
    ],

    'craft_rules' => <<<'RULES'
    - Every beat's scene state includes an arc_stage. Match your shot choices to it:
    wrongness  -> static medium or wide; deep space; the anomaly sits SMALL in the
                    frame. HOLD - never cut to the anomaly or punch in.
    dread      -> wide or medium with slow_push_in; empty negative space reads as threat.
    escalation -> REPEAT a framing used earlier in this story, but tighter
                    (e.g. medium -> medium_close_up); the familiar, now worse.
    payoff     -> break the established pattern: extreme_close_up OR a sudden wide.
                    This is the ONLY stage where whip_pan or handheld is allowed.
    aftermath  -> extreme_wide with slow_pull_out; subject small, long hold.
    - Reserve whip_pan and handheld exclusively for payoff beats.
    - pov and over_the_shoulder are for paranoia moments - put the viewer inside the search.
    - insert is for investigation details (clues, objects) only.
    - Vary shot size across consecutive shots; the sequence accelerates, individual
    shots hold.
    - intent: one short sentence - what this shot makes the viewer feel or notice.
    RULES,
];
