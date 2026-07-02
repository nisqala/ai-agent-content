# Pass 0 — Eval Scores
One row per run. Rubric definitions live in eval-rubric.md.

| date | premise | run | stage1 | hook | arc | intent | style | build | TOTAL | verdict | weakest | notes |
|---|---|---|---|---|---|---|---|---|---|---|---|---|
| 2026-06-23 | t_001 | 1 | PASS | 4 | 3 | 4 | 4 | 3 | 18 | PASS | arc | timing_mode drifted |
| 2026-06-23 | out_false | 1 | FAIL | 3 | 4 | 4 | 4 | 3 | 18 | COERCIBLE PASS | following json output format | enums, folklore objects missing |
| 2026-06-23 | out_false | 2 | FAIL | 4 | 4 | 4 | 4 | 1 | 17 | product-FAIL | mute-fail | mute-first crater |
| 2026-06-23 | out_false | 3 | FAIL | 3 | 4 | 5 | 5 | 4 | 21 | coercible-PASS | enums | enums missing |
| 2026-06-24 | out_true | 1 | FAIL | - | - | - | - | - | - | product-FAIL | mute-fail | enums broken |
| 2026-06-24 | out_true | 2 | FAIL | - | - | - | - | - | - | product-FAIL | mute-fail | enums broken, temp 0.2 |
| 2026-06-24 | out_true | 3 | PASS | 4 | 4 | 4 | 4 | 4 | 20 | product-PASS | arc | think:TRUE, sharpened prompt, corrected enums, num_ctx 16384 |
| 2026-06-24 | out_false | 4 | FAIL | - | - | - | - | - | - | product-FAIL | mute-fail | same config as #3 but think off → still failed |
| 2026-06-25 | out_close_v3 | 5 | PASS | 2 | 4 | 4 | 3 | 3 | 16 | product-PASS | hook | hook restates premise (#4 confirmed); 11.8min; v3 prompt |
| 2026-06-25 | out_close_v3 | 6 | PASS | 3 | 4 | 4 | 3 | 4 | 18 | product-PASS | hook | strongest run; "nenek tersenyum padahal sudah tiada lama" reveal; 13.7min; v3 prompt |
| 2026-06-25 | out_close_v3 | 7 | PASS | 3 | 3 | 4 | 2 | 4 | 16 | product-PASS | style | folklore_refs:Kuyup suspect; near-rumination (26k thinking) escaped; 22.2min; v3 prompt |
