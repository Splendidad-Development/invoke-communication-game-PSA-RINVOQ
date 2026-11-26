# Graph Report - invoke-communication-game-PSA-RINVOQ  (2026-11-26)

## Corpus Check
- 104 files · ~24,939 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 22 file(s) not represented in the graph (top: (none) 17, .example 1, .neon 1)

## Summary
- 712 nodes · 1129 edges · 53 communities (27 shown, 26 thin omitted)
- Extraction: 96% EXTRACTED · 4% INFERRED · 0% AMBIGUOUS · INFERRED: 46 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `eebbdcf0`
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
- vue
- AppServiceProvider.php
- ResultModal.vue
- scripts
- PlayerExport
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
- Illuminate\Http\Request
- bootstrap/app.php
- BoardSlot.vue
- lib/game.ts
- Inertia\Response
- GameModal.vue
- App\Http\Controllers\ArrangementController
- useStickyEdgeScroll
- StoreArrangementRequest.php
- App\Http\Controllers\PlayerController
- .create

## God Nodes (most connected - your core abstractions)
1. `SymphonyBoard` - 32 edges
2. `compilerOptions` - 19 edges
3. `vue` - 17 edges
4. `PlayerExport` - 14 edges
5. `Answer` - 14 edges
6. `Arrangement` - 14 edges
7. `scripts` - 13 edges
8. `require-dev` - 12 edges
9. `playAs()` - 11 edges
10. `Player` - 11 edges

## Surprising Connections (you probably didn't know these)
- `{closure#2}()` --references--> `Arrangement`  [EXTRACTED]
  app/Http/Middleware/HandleInertiaRequests.php → app/Models/Arrangement.php
- `AnswerController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/AnswerController.php → app/Http/Controllers/Controller.php
- `ArrangementController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/ArrangementController.php → app/Http/Controllers/Controller.php
- `BoardController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/BoardController.php → app/Http/Controllers/Controller.php
- `ExportController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/ExportController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (53 total, 26 thin omitted)

### Community 1 - "useDeviceLayout.ts"
Cohesion: 0.40
Nodes (5): DeviceLayout, isPhone, isTouch, useDeviceLayout(), watchQuery()

### Community 2 - "package.json"
Cohesion: 0.05
Nodes (52): dependencies, clsx@^2.1.1, concurrently@^10.0.3, @inertiajs/vite@^3.0.0, laravel-vite-plugin@^3.1, tailwind-merge@^3.2.0, tailwindcss@^4.1.1, @tailwindcss/vite@^4.1.11 (+44 more)

### Community 3 - "Answer"
Cohesion: 0.05
Nodes (15): Answer, Arrangement, Player, User, {closure#1}(), {closure#2}(), {closure#3}(), AnswerFactory (+7 more)

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
Cohesion: 0.09
Nodes (8): @inertiajs/vue3@^3.0.0, @inertiajs/vue3, answerCount, boardCount, ExportAnswer, ExportPlayer, props, answer

### Community 8 - "with"
Cohesion: 0.10
Nodes (20): runs-on, steps, jobs, ci, name, on, pull_request, push (+12 more)

### Community 9 - "compilerOptions"
Cohesion: 0.10
Nodes (20): compilerOptions, allowJs, esModuleInterop, forceConsistentCasingInFileNames, isolatedModules, jsx, jsxImportSource, lib (+12 more)

### Community 10 - "vue"
Cohesion: 0.25
Nodes (6): vue, emit, props, slotStyle, BoardSection, *.vue

### Community 12 - "ResultModal.vue"
Cohesion: 0.06
Nodes (43): leaderboard, filter, filters, isPlaying(), leaderboard, player, playKey(), rows (+35 more)

### Community 13 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, ci:check, dev, lint, lint:check, post-autoload-dump, post-create-project-cmd, post-root-package-install (+5 more)

### Community 15 - "SymphonyBoard.php"
Cohesion: 0.15
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
Cohesion: 0.25
Nodes (8): require, barryvdh/laravel-dompdf, inertiajs/inertia-laravel, laravel/framework, laravel/tinker, laravel/wayfinder, openspout/openspout, php

### Community 25 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 30 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 42 - "Illuminate\Http\Request"
Cohesion: 0.32
Nodes (3): Controller, EnsurePlayerSession, RequireExportPassword

### Community 43 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (6): {closure#1}(), {closure#2}(), HandleInertiaRequests, {closure#1}(), {closure#2}(), {closure#3}()

### Community 44 - "BoardSlot.vue"
Cohesion: 0.15
Nodes (9): vue-draggable-plus@^0.6.1, vue-draggable-plus, emit, { isPhone, usesDragHandle }, list, onAdd(), props, emit (+1 more)

### Community 45 - "lib/game.ts"
Cohesion: 0.28
Nodes (7): colourKey, dragOptions, imageMap(), RESULT_PLAYER_KEY, sectionToneClasses, verdictFor(), imagesById

### Community 47 - "GameModal.vue"
Cohesion: 0.05
Nodes (42): activate(), deactivate(), emit, id, isMounted, isTopmost(), onKeydown(), openStack (+34 more)

### Community 49 - "useStickyEdgeScroll"
Cohesion: 0.52
Nodes (6): useStickyEdgeScroll(), onPointerMove(), scrollDirection(), start(), stop(), tick()

## Knowledge Gaps
- **26 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `vue` connect `vue` to `useDeviceLayout.ts`, `package.json`, `Board.vue`, `types/game.ts`, `ResultModal.vue`, `GameModal.vue`, `useStickyEdgeScroll`, `useBoardArrangement`?**
  _High betweenness centrality (0.050) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.052525252525252523 - nodes in this community are weakly interconnected._
- **Why does `SymphonyBoard` connect `SymphonyBoard` to `Answer`, `GameFlowTest.php`, `AppServiceProvider.php`, `Inertia\Response`, `PlayerExport`, `App\Http\Controllers\ArrangementController`, `SymphonyBoard.php`, `StoreArrangementRequest.php`?**
  _High betweenness centrality (0.046) - this node is a cross-community bridge._
- **Should `Answer` be split into smaller, more focused modules?**
  _Cohesion score 0.050921861281826165 - nodes in this community are weakly interconnected._
- **Why does `@inertiajs/vue3` connect `types/game.ts` to `package.json`, `ResultModal.vue`, `Board.vue`?**
  _High betweenness centrality (0.017) - this node is a cross-community bridge._
- **Should `GameFlowTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.08013937282229965 - nodes in this community are weakly interconnected._
- **Should `Illuminate\Database\Schema\Blueprint` be split into smaller, more focused modules?**
  _Cohesion score 0.10483870967741936 - nodes in this community are weakly interconnected._