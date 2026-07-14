<?php

return [
    'recipe' => 'shorts',
    'max_shots_per_beat' => 3,  // upper cap in: clamp(ceil(est/8)+(payoff?1:0), 1, this)
];
