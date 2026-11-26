# Graph Report - invoke-communication-game-PSA-RINVOQ  (2026-11-26)

## Corpus Check
- 98 files · ~23,222 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 22 file(s) not represented in the graph (top: (none) 17, .example 1, .neon 1)

## Summary
- 660 nodes · 1025 edges · 45 communities (28 shown, 17 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 32 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `cfd48720`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- SymphonyBoard
- useDeviceLayout.ts
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
- DatabaseSeeder.php
- useBoardArrangement
- config
- Pest.php
- require
- psr-4
- extra
- pnpm-workspace.yaml
- bootstrap/app.php
- BoardSlot.vue
- lib/game.ts
- GameModal.vue
- useStickyEdgeScroll

## God Nodes (most connected - your core abstractions)
1. `SymphonyBoard` - 32 edges
2. `compilerOptions` - 19 edges
3. `vue` - 16 edges
4. `Answer` - 14 edges
5. `Arrangement` - 14 edges
6. `scripts` - 13 edges
7. `require-dev` - 12 edges
8. `playAs()` - 11 edges
9. `Player` - 11 edges
10. `@inertiajs/vue3` - 10 edges

## Surprising Connections (you probably didn't know these)
- `{closure#2}()` --references--> `Arrangement`  [EXTRACTED]
  app/Http/Middleware/HandleInertiaRequests.php → app/Models/Arrangement.php
- `barClass()` --calls--> `cn()`  [EXTRACTED]
  resources/js/components/game/SymphonyNotes.vue → resources/js/lib/utils.ts
- `GameController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/GameController.php → app/Http/Controllers/Controller.php
- `useBoardArrangement()` --calls--> `shuffle()`  [EXTRACTED]
  resources/js/composables/useBoardArrangement.ts → resources/js/lib/game.ts
- `reset()` --calls--> `shuffle()`  [EXTRACTED]
  resources/js/composables/useBoardArrangement.ts → resources/js/lib/game.ts

## Import Cycles
- None detected.

## Communities (45 total, 17 thin omitted)

### Community 0 - "SymphonyBoard"
Cohesion: 0.06
Nodes (14): AnswerController, ArrangementController, BoardController, Controller, GameController, PlayerController, EnsurePlayerSession, {closure#1}() (+6 more)

### Community 1 - "useDeviceLayout.ts"
Cohesion: 0.40
Nodes (5): DeviceLayout, isPhone, isTouch, useDeviceLayout(), watchQuery()

### Community 2 - "package.json"
Cohesion: 0.05
Nodes (52): dependencies, concurrently@^10.0.3, @inertiajs/vite@^3.0.0, laravel-vite-plugin@^3.1, tailwind-merge@^3.2.0, tailwindcss@^4.1.1, @tailwindcss/vite@^4.1.11, typescript@^5.2.2 (+44 more)

### Community 3 - "Answer"
Cohesion: 0.07
Nodes (7): Answer, Arrangement, Player, User, AnswerFactory, PlayerFactory, UserFactory

### Community 4 - "GameFlowTest.php"
Cohesion: 0.08
Nodes (27): ArrangementFactory, {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+19 more)

### Community 5 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.10
Nodes (11): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+3 more)

### Community 6 - "Board.vue"
Cohesion: 0.06
Nodes (22): board, BoardView, edgeScroll, isLocked, isMistakesOpen, { isPhone, usesDragHandle }, isResultOpen, isSubmitting (+14 more)

### Community 7 - "types/game.ts"
Cohesion: 0.14
Nodes (3): @inertiajs/vue3@^3.0.0, @inertiajs/vue3, answer

### Community 8 - "with"
Cohesion: 0.10
Nodes (20): runs-on, steps, jobs, ci, name, on, pull_request, push (+12 more)

### Community 9 - "compilerOptions"
Cohesion: 0.10
Nodes (20): compilerOptions, allowJs, esModuleInterop, forceConsistentCasingInFileNames, isolatedModules, jsx, jsxImportSource, lib (+12 more)

### Community 10 - "index.ts"
Cohesion: 0.12
Nodes (15): emit, props, slotStyle, emit, emit, ArrangementResult, BoardColumn, BoardSection (+7 more)

### Community 12 - "ResultModal.vue"
Cohesion: 0.05
Nodes (45): clsx@^2.1.1, clsx, leaderboard, filter, filters, isPlaying(), leaderboard, player (+37 more)

### Community 13 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, ci:check, dev, lint, lint:check, post-autoload-dump, post-create-project-cmd, post-root-package-install (+5 more)

### Community 14 - "global.d.ts"
Cohesion: 0.19
Nodes (11): Auth, User, LeaderboardEntry, ComponentCustomProperties, GlobalDirectives, ImportMeta, ImportMetaEnv, InertiaConfig (+3 more)

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

### Community 20 - "useBoardArrangement"
Cohesion: 0.29
Nodes (7): TRAY, useBoardArrangement(), place(), removeFromTray(), reset(), slotOf(), shuffle()

### Community 22 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 24 - "require"
Cohesion: 0.33
Nodes (6): require, inertiajs/inertia-laravel, laravel/framework, laravel/tinker, laravel/wayfinder, php

### Community 25 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 30 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 43 - "bootstrap/app.php"
Cohesion: 0.28
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 44 - "BoardSlot.vue"
Cohesion: 0.18
Nodes (7): emit, { isPhone, usesDragHandle }, list, onAdd(), props, emit, dragOptions

### Community 45 - "lib/game.ts"
Cohesion: 0.28
Nodes (7): colourKey, dragHandleOptions, imageMap(), RESULT_PLAYER_KEY, sectionToneClasses, verdictFor(), imagesById

### Community 47 - "GameModal.vue"
Cohesion: 0.13
Nodes (20): activate(), deactivate(), emit, id, isMounted, isTopmost(), onKeydown(), openStack (+12 more)

### Community 49 - "useStickyEdgeScroll"
Cohesion: 0.31
Nodes (8): vue, useStickyEdgeScroll(), onPointerMove(), scrollDirection(), start(), stop(), tick(), *.vue

## Knowledge Gaps
- **17 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `vue` connect `useStickyEdgeScroll` to `useDeviceLayout.ts`, `package.json`, `Board.vue`, `types/game.ts`, `index.ts`, `ResultModal.vue`, `global.d.ts`, `GameModal.vue`, `useBoardArrangement`?**
  _High betweenness centrality (0.052) - this node is a cross-community bridge._
- **Should `SymphonyBoard` be split into smaller, more focused modules?**
  _Cohesion score 0.05775638652350981 - nodes in this community are weakly interconnected._
- **Why does `SymphonyBoard` connect `SymphonyBoard` to `Answer`, `AppServiceProvider.php`, `GameFlowTest.php`, `SymphonyBoard.php`?**
  _High betweenness centrality (0.045) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.053109713487071976 - nodes in this community are weakly interconnected._
- **Why does `@inertiajs/vue3` connect `types/game.ts` to `package.json`, `ResultModal.vue`, `Board.vue`?**
  _High betweenness centrality (0.017) - this node is a cross-community bridge._
- **Should `Answer` be split into smaller, more focused modules?**
  _Cohesion score 0.06531204644412192 - nodes in this community are weakly interconnected._
- **Should `GameFlowTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.08292682926829269 - nodes in this community are weakly interconnected._