# Graph Report - invoke-communication-game-PSA-RINVOQ  (2026-11-25)

## Corpus Check
- 97 files · ~22,646 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 22 file(s) not represented in the graph (top: (none) 17, .example 1, .neon 1)

## Summary
- 643 nodes · 1011 edges · 51 communities (35 shown, 16 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 31 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `dbedb3ac`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- SymphonyBoard
- symphony.ts
- package.json
- Answer
- GameFlowTest.php
- Illuminate\Database\Schema\Blueprint
- Board.vue
- types/game.ts
- with
- compilerOptions
- index.ts
- AppServiceProvider.php
- ResultModal.vue
- scripts
- global.d.ts
- SymphonyBoard.php
- require-dev
- updates
- composer.json
- vue
- useBoardArrangement
- ImagePickerModal.vue
- config
- Pest.php
- require
- psr-4
- StoreArrangementRequest
- extra
- pnpm-workspace.yaml
- LeaderboardModal.vue
- bootstrap/app.php
- BoardSlot.vue
- lib/game.ts
- LeaderboardButton.vue
- GameModal.vue
- vue
- useStickyEdgeScroll
- useSymphonyPlayer

## God Nodes (most connected - your core abstractions)
1. `SymphonyBoard` - 31 edges
2. `compilerOptions` - 19 edges
3. `vue` - 16 edges
4. `Answer` - 15 edges
5. `Arrangement` - 15 edges
6. `Player` - 13 edges
7. `scripts` - 13 edges
8. `require-dev` - 12 edges
9. `playAs()` - 11 edges
10. `@inertiajs/vue3` - 10 edges

## Surprising Connections (you probably didn't know these)
- `playAs()` --references--> `Player`  [EXTRACTED]
  tests/Feature/GameFlowTest.php → app/Models/Player.php
- `{closure#2}()` --references--> `Arrangement`  [EXTRACTED]
  app/Http/Middleware/HandleInertiaRequests.php → app/Models/Arrangement.php
- `barClass()` --calls--> `cn()`  [EXTRACTED]
  resources/js/components/game/SymphonyNotes.vue → resources/js/lib/utils.ts
- `useSymphonyPlayer()` --calls--> `performSymphony()`  [EXTRACTED]
  resources/js/composables/useSymphonyPlayer.ts → resources/js/lib/symphony.ts
- `play()` --calls--> `performSymphony()`  [EXTRACTED]
  resources/js/composables/useSymphonyPlayer.ts → resources/js/lib/symphony.ts

## Import Cycles
- None detected.

## Communities (51 total, 16 thin omitted)

### Community 0 - "SymphonyBoard"
Cohesion: 0.06
Nodes (15): AnswerController, ArrangementController, BoardController, Controller, GameController, PlayerController, EnsurePlayerSession, {closure#1}() (+7 more)

### Community 1 - "symphony.ts"
Cohesion: 0.27
Nodes (10): currentStep, playingKey, CHORDS, hz(), MELODY, pad(), performSymphony(), reverbImpulse() (+2 more)

### Community 2 - "package.json"
Cohesion: 0.05
Nodes (52): dependencies, clsx@^2.1.1, concurrently@^10.0.3, @inertiajs/vite@^3.0.0, laravel-vite-plugin@^3.1, tailwind-merge@^3.2.0, tailwindcss@^4.1.1, @tailwindcss/vite@^4.1.11 (+44 more)

### Community 3 - "Answer"
Cohesion: 0.08
Nodes (4): User, PlayerFactory, UserFactory, DatabaseSeeder

### Community 4 - "GameFlowTest.php"
Cohesion: 0.08
Nodes (27): ArrangementFactory, {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+19 more)

### Community 5 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.10
Nodes (11): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+3 more)

### Community 6 - "Board.vue"
Cohesion: 0.06
Nodes (25): imageMap(), board, BoardView, edgeScroll, imagesById, isLocked, isMistakesOpen, isPhone (+17 more)

### Community 7 - "types/game.ts"
Cohesion: 0.14
Nodes (9): @inertiajs/vue3@^3.0.0, @inertiajs/vue3, answer, ArrangementResult, ColumnTone, Group, GroupCard, Player (+1 more)

### Community 8 - "with"
Cohesion: 0.10
Nodes (20): runs-on, steps, jobs, ci, name, on, pull_request, push (+12 more)

### Community 9 - "compilerOptions"
Cohesion: 0.10
Nodes (20): compilerOptions, allowJs, esModuleInterop, forceConsistentCasingInFileNames, isolatedModules, jsx, jsxImportSource, lib (+12 more)

### Community 10 - "index.ts"
Cohesion: 0.20
Nodes (6): emit, emit, emit, User, GameImage, Mistake

### Community 12 - "ResultModal.vue"
Cohesion: 0.19
Nodes (11): close(), emit, hasPlayed, isPlaying, leaderboard, notes, openLeaderboard(), player (+3 more)

### Community 13 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, ci:check, dev, lint, lint:check, post-autoload-dump, post-create-project-cmd, post-root-package-install (+5 more)

### Community 14 - "global.d.ts"
Cohesion: 0.22
Nodes (10): Auth, LeaderboardEntry, ComponentCustomProperties, GlobalDirectives, ImportMeta, ImportMetaEnv, InertiaConfig, @inertiajs/core (+2 more)

### Community 15 - "SymphonyBoard.php"
Cohesion: 0.14
Nodes (4): {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}()

### Community 16 - "require-dev"
Cohesion: 0.17
Nodes (12): require-dev, fakerphp/faker, larastan/larastan, laravel/boost, laravel/pail, laravel/pao, laravel/pint, laravel/sail (+4 more)

### Community 17 - "updates"
Cohesion: 0.17
Nodes (11): default-days, patterns, github-actions, interval, updates, cooldown, directory, groups (+3 more)

### Community 18 - "composer.json"
Cohesion: 0.18
Nodes (10): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, Tests\\ (+2 more)

### Community 19 - "vue"
Cohesion: 0.33
Nodes (5): isLoading, isOpen, useLeaderboard(), open(), refresh()

### Community 20 - "useBoardArrangement"
Cohesion: 0.36
Nodes (6): useBoardArrangement(), place(), removeFromTray(), reset(), slotOf(), shuffle()

### Community 21 - "ImagePickerModal.vue"
Cohesion: 0.28
Nodes (8): available, confirm(), emit, onKeydown(), props, selected, step(), track

### Community 22 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 24 - "require"
Cohesion: 0.33
Nodes (6): require, inertiajs/inertia-laravel, laravel/framework, laravel/tinker, laravel/wayfinder, php

### Community 25 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 27 - "StoreArrangementRequest"
Cohesion: 0.15
Nodes (3): StoreAnswerRequest, StoreArrangementRequest, StorePlayerRequest

### Community 30 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 42 - "LeaderboardModal.vue"
Cohesion: 0.18
Nodes (8): filter, filters, isPlaying(), leaderboard, player, playKey(), rows, summary

### Community 43 - "bootstrap/app.php"
Cohesion: 0.28
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 44 - "BoardSlot.vue"
Cohesion: 0.18
Nodes (10): vue-draggable-plus@^0.6.1, vue-draggable-plus, emit, isPhone, list, onAdd(), props, isPhone (+2 more)

### Community 45 - "lib/game.ts"
Cohesion: 0.24
Nodes (8): emit, props, slotStyle, colourKey, RESULT_PLAYER_KEY, sectionToneClasses, verdictFor(), BoardSection

### Community 46 - "LeaderboardButton.vue"
Cohesion: 0.36
Nodes (5): leaderboard, barClass(), props, noteHeight(), cn()

### Community 47 - "GameModal.vue"
Cohesion: 0.23
Nodes (12): activate(), deactivate(), emit, id, isMounted, isTopmost(), onKeydown(), openStack (+4 more)

### Community 48 - "vue"
Cohesion: 0.33
Nodes (3): vue, TRAY, *.vue

### Community 49 - "useStickyEdgeScroll"
Cohesion: 0.67
Nodes (6): useStickyEdgeScroll(), onPointerMove(), scrollDirection(), start(), stop(), tick()

### Community 50 - "useSymphonyPlayer"
Cohesion: 1.00
Nodes (4): useSymphonyPlayer(), play(), stop(), toggle()

## Knowledge Gaps
- **16 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `vue` connect `vue` to `symphony.ts`, `package.json`, `Board.vue`, `types/game.ts`, `LeaderboardModal.vue`, `ResultModal.vue`, `lib/game.ts`, `BoardSlot.vue`, `GameModal.vue`, `global.d.ts`, `vue`, `ImagePickerModal.vue`?**
  _High betweenness centrality (0.048) - this node is a cross-community bridge._
- **Should `SymphonyBoard` be split into smaller, more focused modules?**
  _Cohesion score 0.05771604938271605 - nodes in this community are weakly interconnected._
- **Why does `SymphonyBoard` connect `SymphonyBoard` to `Answer`, `GameFlowTest.php`, `AppServiceProvider.php`, `SymphonyBoard.php`, `StoreArrangementRequest`?**
  _High betweenness centrality (0.043) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.053109713487071976 - nodes in this community are weakly interconnected._
- **Why does `Answer` connect `SymphonyBoard` to `Answer`, `StoreArrangementRequest`, `GameFlowTest.php`?**
  _High betweenness centrality (0.018) - this node is a cross-community bridge._
- **Should `Answer` be split into smaller, more focused modules?**
  _Cohesion score 0.07575757575757576 - nodes in this community are weakly interconnected._
- **Should `GameFlowTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.08414634146341464 - nodes in this community are weakly interconnected._