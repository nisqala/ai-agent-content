# STYLE BIBLE — HORROR

Internal language: English. Injected raw into LLM prompts (Pass 0 reads §1/§5/§6).
Living document — revise after rejections during Fase 3 validation.
Hybrid base: James Wan (scare mechanics) + Joko Anwar minus religious core
(Indonesian myth/folklore grounding) + David Fincher (methodical precision).

---

## §1 JIWA / SOUL (locked — Chat 1/2)

Fear should follow the viewer out of the screen and into their real life — paranoia, not shock.

The dread is built, never sprung: quiet wrongness in the background and the periphery (a knock at the window, a light flickering on–off–on, an object falling on its own) stacked and escalated until an earned payoff.

The horror is grounded in shared Indonesian/Southeast-Asian relatability — local history, myth, folklore, and the gossip already circulating — because what feels familiar and "could really happen" unsettles far more than religious-violation tropes do.

Atmosphere is the whole engine: sound, silence, pacing, color grade, and coherent world-building must make the viewer feel they are inside the scene, not watching it.

This is horror built on thriller-and-mystery bones — escalation, restraint, and a plot that actually makes sense — so the scare lands precisely because everything before it was earned.

---

## §2 KAMUS RASA → PARAMETER

Abstract mood → concrete SHOT fields. Right-hand values are prompt-ready English,
mapped to SHOT object fields: shot_type · camera_move · framing · lighting ·
lens · color_texture · pacing_note · sound_note.

| mood | shot_type | camera_move | framing | lighting | lens | color/texture | pacing | sound |
|---|---|---|---|---|---|---|---|---|
| **built dread** (dread yang dibangun) | wide → medium | slow dolly push-in, controlled | negative space — empty space as threat | low-key, single warm practical source | wide–normal, deep focus | desaturated cool, green-amber shadows, 16mm grain | hold, decelerate | room tone + subsonic drone, no music |
| **quiet wrongness** (kejanggalan periferal) | medium, static | static / locked | foreground subject, anomaly small in background or frame edge | practical dim, motivated | deep focus (so the background reads) | muted, low contrast | hold — do NOT cut to the anomaly | ambience continues unbothered (crickets, fan hum) |
| **paranoia / RL-bleed** | POV or OTS | slow pan that finds nothing, leading shot | lead room into darkness, foreground obstruction (peeking) | pools of light, heavy falloff to black | normal 50mm, shallow-to-rack focus | desaturated, vignette | breath before beat | foley isolated (own footsteps, breath), silence between |
| **tense-silence** (sunyi-tegang) | medium, static | static / locked | centered or precise thirds, negative space | low-key, hard edges | normal | high contrast, dark | hold, cut on pause | total silence — weapon |
| **investigation** (thriller/mystery beat) | medium + insert/cutaway of clues | slow track, precise pan | rule of thirds, clean and ordered (Fincher) | motivated practical (desk lamp, phone screen) | normal–tele, controlled shallow DOF | green-amber cold, disciplined grade | methodical, even rhythm | quiet ambience, paper/object foley |
| **escalation beat** (anomali naik level) | repeat earlier framing, tighter | same move as before, slightly faster or closer | same composition as the earlier "wrongness" shot — now worse | same source, now flickering/failing | same lens — continuity sells it | same grade | accelerate across beats, not within shot | the familiar sound now wrong (knock pattern changes) |
| **earned payoff / terror** | extreme close-up or sudden wide reveal | whip pan OR static reveal (the worse option) | imbalance, headroom broken | hard rim, silhouette | tele or wide — break the established norm | motion blur, crushed blacks | quick cut ONLY here | sting/sfx cue AFTER full silence — earned, never cheap |
| **aftermath / hollow** (hampa) | extreme wide | very slow pull-out | subject small, negative space | flat dim, pre-dawn blue hour | wide, deep focus | cool desaturated, low contrast | long hold | thin wind, distant kampung ambience returning |

Rule: every mood is a STAGE of one escalation arc (wrongness → dread → escalation → payoff → aftermath), not interchangeable flavors. Pass 2 should pick moods that match the beat's position in the arc.

---

## §3 ATURAN KAMERA

- **Default: locked / static, precise (Fincher).** The camera observes; it never panics before the audience does. Composition is deliberate — centered or exact thirds, nothing sloppy.
- **Movement is always controlled and motivated (Wan):** slow dolly push-in to build tension; slow pan/track that *reveals empty space* behind or beside the subject. The move itself is the scare delivery system — it guides the eye to where something should be, or shouldn't.
- **Camera moves earn their speed:** the same push-in may recur across beats slightly faster/closer (escalation through repetition). Whip pan reserved exclusively for the earned payoff.
- **Handheld: rare, brief, only at peak panic** — never the default texture. One shot, then back to locked.
- **Deep focus by default (Joko):** the background must stay readable so peripheral wrongness can live there. Shallow DOF only for intimacy/isolation or investigation inserts.
- **Angles:** eye-level default (realism, "could really happen"). Low-angle or dutch sparingly, only when wrongness has been established — never as a cheap mood sticker.
- **POV/OTS for paranoia beats:** put the viewer inside the search.
- **180° axis locked per scene;** eyelines consistent. Breaking the axis is allowed exactly once per video — at the payoff — so the break itself feels wrong.
- **Lens grammar:** wide 24–35mm for space/establishing, normal 50mm as workhorse (neutral, real), tele 85mm+ only for compression/isolation in investigation or payoff.

---

## §4 WARNA & CAHAYA

- **Base grade (disciplined, one LUT per video — Fincher):** desaturated, cool-leaning, **green-amber cast in the shadows**, crushed but readable blacks, restrained highlights. No grade drift between shots.
- **Warm practicals as accents inside the cold world (Joko grounding):** bare tungsten bulb, candle, kerosene lamp, warung neon — visible, motivated sources. The warmth is *in the frame*, not in the grade.
- **Low-key throughout (Wan):** single-source logic, hard falloff, large areas of true dark. Darkness is negative space — usable threat, not missing information.
- **Light behaves like a character:** flicker on–off–on, a practical dying across beats, moonlight through a window shifting — light state is an escalation channel.
- **Pre-dawn blue hour / moonlight** for aftermath and exteriors; never daylight horror in this style.
- **Texture mandatory (anti "too clean"):** 16mm film grain on everything, subtle vignette, motion blur at 1/50 shutter feel. Imperfection = realism.

**STYLE_BLOCK — final (copy verbatim into every visual_prompt of one video):**
```
low-key single-source practical lighting, desaturated cool palette with green-amber shadows, crushed blacks, negative space, locked precise framing, deep focus, 16mm film grain, subtle vignette, cinematic
```

**Negative prompt (baku):**
```
deformed, distorted face, extra limbs, extra fingers, warped objects, text, watermark, logo, blurry, lowres, oversaturated, cartoon, jitter, clean digital look
```

---

## §5 PACING / RITME

- **Methodical, never hurried (Fincher):** even, confident rhythm in build sections. The video should feel like it knows something the viewer doesn't.
- **Escalation across beats, restraint within shots:** individual shots HOLD; the *sequence* accelerates. Shot lengths trend shorter as the arc climbs (e.g. ~8s → ~5s → ~3s), but never frantic cutting inside a build.
- **Silence → sting discipline (Wan, earned only):** every payoff is preceded by a full beat of true silence (`breath before beat`). The sting lands only after the silence has stretched past comfort. No sting without silence; no silence without a built reason.
- **Hold on wrongness:** when an anomaly appears, do NOT cut to it or punch in. Hold the wide/medium and let the viewer find it. The cut that "helps" the viewer kills the paranoia.
- **Cut on pause:** edits land on natural breath/ambience pauses, never mid-action — invisible editing (precision again).
- **Repetition as rhythm:** revisit the same framing/sound with one element worse. The pattern teaches the viewer to dread the next repetition.
- **One payoff per short.** Everything before it serves it; everything after it is short aftermath, then loop/out.
- **Default shot length 3–8s;** dread/wrongness shots may run longer (hold). Vary shot size — never two identical sizes back-to-back.

---

## §6 PANTANGAN / FORBIDDEN (locked — Chat 1/2)

- Cheap, loud jumpscares; shock with no build-up
- Sudden ghost reveals that no escalation earned
- Cheap "burik" CGI ghosts; lazy creature design
- Over-acting; forced theatrical possession (flailing, flying around)
- Pointless gore; killings with no motive, no setup, no narrative weight
- Anything forced (maksain): forced culture, forced myth, forced situations, forced scares
- Plot holes; incoherent plot; twists that don't make sense or weren't set up
- Weak world-building from the opening
- Poor color grade; ill-fitting or cheap music/sound that breaks the vibe
- Pacing that drags or rushes; scenes that don't serve the story
- No character building; deaths with no horror/thriller weight
- Religious-violation as the core scare engine (mukena/azan tropes as fear source)
- "Too clean" AI look — no grain, no imperfection, uncanny sterility

---

## §7 REFERENSI (moodboard — hybrid)

Visual anchors for hook_visual & visual_prompt. English, prompt-ready.

**Space & wrongness (Wan — empty space as threat):**
- dark hallway ending at a closed door
- empty chair facing the wall
- doorway with darkness beyond, light spilling from one room
- a shadow in the corner that resolves into nothing — or doesn't

**Grounded Indonesian texture (Joko minus religious):**
- old dim wooden house, single bare bulb
- narrow kampung alley at night, distant warung light
- old well behind the house
- faded family portrait on a peeling wall
- flickering neon sign
- silent figure standing far down the road
- kerosene lamp on a wooden table
- rain on a zinc roof, water stain spreading

**Precision & investigation (Fincher):**
- desk at night lit by a single lamp, documents and photographs laid out
- phone screen glow on a face in a dark room
- rain-slick street, sodium light
- ordered room where one object is out of place

**Calibration viewing (optional):** Pengabdi Setan / Impetigore (texture & grounding, ignore the religious engine), Shutter (2004) (escalation from a minor anomaly), The Conjuring (scare construction), Zodiac / Se7en (investigation rhythm & grade).

---

## §8 Caption-Typography

Caption style: high-contrast, word-by-word animated (terbukti terbaik short-form),
font tebal, hindari overlap subjek. Satu aturan = lolos semua platform.

---

## §9 Universal safe-zone

Canvas 9:16 (1080×1920). Taruh onscreen_text di CENTER-UPPER third. HINDARI:
  • bawah ~20–25%  (caption/username/CTA native semua platform)
  • kanan ~12–15%  (ikon like/comment/share TikTok+YT+Reels)
  • atas  ~10%     (UI sebagian platform)


---

*Hybrid locked: Wan mechanics + Joko grounding (minus religious) + Fincher discipline.
Pass 0 reads §1/§5/§6. Pass 2 reads §2 (slice) + scene state. QC reads §6.
Every rejection in Fase 3 → distill into a new line here.*
