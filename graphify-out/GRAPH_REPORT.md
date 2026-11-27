# Graph Report - invoke-communication-game-PSA-RINVOQ  (2026-11-27)

## Corpus Check
- 104 files · ~25,166 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 22 file(s) not represented in the graph (top: (none) 17, .example 1, .neon 1)

## Summary
- 713 nodes · 1130 edges · 53 communities (29 shown, 24 thin omitted)
- Extraction: 96% EXTRACTED · 4% INFERRED · 0% AMBIGUOUS · INFERRED: 45 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `bdc53c00`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- SymphonyBoard
- useDeviceLayout.ts
- package.json
- PlayerExport.php
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
- PlayerExport
- SymphonyBoard.php
- require-dev
- updates
- composer.json
- DatabaseSeeder.php
- useBoardArrangement
- UserFactory.php
- config
- Pest.php
- require
- psr-4
- extra
- pnpm-workspace.yaml
- global.d.ts
- bootstrap/app.php
- BoardSlot.vue
- ResultModal.vue
- Arrangement
- ImagePickerModal.vue
- ExportTest.php
- useStickyEdgeScroll
- User.php
- Illuminate\Database\Eloquent\Factories\Factory
- ImageTile.vue

## God Nodes (most connected - your core abstractions)
1. `SymphonyBoard` - 35 edges
2. `compilerOptions` - 19 edges
3. `vue` - 17 edges
4. `PlayerExport` - 14 edges
5. `Answer` - 14 edges
6. `Arrangement` - 14 edges
7. `scripts` - 13 edges
8. `require-dev` - 12 edges
9. `Player` - 11 edges
10. `playAs()` - 11 edges

## Surprising Connections (you probably didn't know these)
- `{closure#2}()` --references--> `Arrangement`  [EXTRACTED]
  app/Http/Middleware/HandleInertiaRequests.php → app/Models/Arrangement.php
- `PlayerExport` --references--> `SymphonyBoard`  [EXTRACTED]
  app/PlayerExport.php → app/SymphonyBoard.php
- `ExportController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/ExportController.php → app/Http/Controllers/Controller.php
- `barClass()` --calls--> `cn()`  [EXTRACTED]
  resources/js/components/game/SymphonyNotes.vue → resources/js/lib/utils.ts
- `AnswerController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/AnswerController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (53 total, 24 thin omitted)

### Community 0 - "SymphonyBoard"
Cohesion: 0.05
Nodes (16): AnswerController, ArrangementController, BoardController, Controller, GameController, PlayerController, EnsurePlayerSession, {closure#1}() (+8 more)

### Community 1 - "useDeviceLayout.ts"
Cohesion: 0.40
Nodes (5): DeviceLayout, isPhone, isTouch, useDeviceLayout(), watchQuery()

### Community 2 - "package.json"
Cohesion: 0.06
Nodes (50): dependencies, concurrently@^10.0.3, @inertiajs/vite@^3.0.0, laravel-vite-plugin@^3.1, tailwind-merge@^3.2.0, tailwindcss@^4.1.1, @tailwindcss/vite@^4.1.11, typescript@^5.2.2 (+42 more)

### Community 3 - "PlayerExport.php"
Cohesion: 0.18
Nodes (4): Player, {closure#1}(), {closure#2}(), {closure#3}()

### Community 4 - "GameFlowTest.php"
Cohesion: 0.07
Nodes (27): ArrangementFactory, {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+19 more)

### Community 5 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.10
Nodes (11): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+3 more)

### Community 6 - "Board.vue"
Cohesion: 0.06
Nodes (24): imageMap(), board, BoardView, edgeScroll, imagesById, isLocked, isMistakesOpen, { isPhone, usesDragHandle } (+16 more)

### Community 7 - "types/game.ts"
Cohesion: 0.09
Nodes (8): @inertiajs/vue3@^3.0.0, @inertiajs/vue3, answerCount, boardCount, ExportAnswer, ExportPlayer, props, answer

### Community 8 - "with"
Cohesion: 0.10
Nodes (20): runs-on, steps, jobs, ci, name, on, pull_request, push (+12 more)

### Community 9 - "compilerOptions"
Cohesion: 0.10
Nodes (20): compilerOptions, allowJs, esModuleInterop, forceConsistentCasingInFileNames, isolatedModules, jsx, jsxImportSource, lib (+12 more)

### Community 10 - "index.ts"
Cohesion: 0.13
Nodes (14): emit, props, slotStyle, emit, ArrangementResult, BoardColumn, BoardSection, ColumnTone (+6 more)

### Community 12 - "ResultModal.vue"
Cohesion: 0.05
Nodes (46): clsx@^2.1.1, clsx, activate(), deactivate(), emit, id, isMounted, isTopmost() (+38 more)

### Community 13 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, ci:check, dev, lint, lint:check, post-autoload-dump, post-create-project-cmd, post-root-package-install (+5 more)

### Community 15 - "SymphonyBoard.php"
Cohesion: 0.13
Nodes (5): {closure#12}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#7}()

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

### Community 42 - "global.d.ts"
Cohesion: 0.19
Nodes (11): Auth, User, LeaderboardEntry, ComponentCustomProperties, GlobalDirectives, ImportMeta, ImportMetaEnv, InertiaConfig (+3 more)

### Community 43 - "bootstrap/app.php"
Cohesion: 0.28
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 44 - "BoardSlot.vue"
Cohesion: 0.17
Nodes (12): vue-draggable-plus@^0.6.1, vue-draggable-plus, emit, { isPhone, usesDragHandle }, list, onAdd(), props, colourKey (+4 more)

### Community 45 - "ResultModal.vue"
Cohesion: 0.19
Nodes (12): close(), emit, hasPlayed, isPlaying, leaderboard, notes, openLeaderboard(), player (+4 more)

### Community 47 - "ImagePickerModal.vue"
Cohesion: 0.28
Nodes (8): available, confirm(), emit, onKeydown(), props, selected, step(), track

### Community 48 - "ExportTest.php"
Cohesion: 0.27
Nodes (5): asExporter(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}()

### Community 49 - "useStickyEdgeScroll"
Cohesion: 0.31
Nodes (8): vue, useStickyEdgeScroll(), onPointerMove(), scrollDirection(), start(), stop(), tick(), *.vue

## Knowledge Gaps
- **24 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `SymphonyBoard` connect `SymphonyBoard` to `AppServiceProvider.php`, `GameFlowTest.php`, `PlayerExport`, `SymphonyBoard.php`?**
  _High betweenness centrality (0.052) - this node is a cross-community bridge._
- **Should `SymphonyBoard` be split into smaller, more focused modules?**
  _Cohesion score 0.053297199638663056 - nodes in this community are weakly interconnected._
- **Why does `vue` connect `useStickyEdgeScroll` to `useDeviceLayout.ts`, `package.json`, `Board.vue`, `types/game.ts`, `index.ts`, `global.d.ts`, `ResultModal.vue`, `ResultModal.vue`, `ImagePickerModal.vue`, `useBoardArrangement`?**
  _High betweenness centrality (0.050) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.05505279034690799 - nodes in this community are weakly interconnected._
- **Why does `@inertiajs/vue3` connect `types/game.ts` to `package.json`, `ResultModal.vue`, `ResultModal.vue`, `Board.vue`?**
  _High betweenness centrality (0.017) - this node is a cross-community bridge._
- **Should `GameFlowTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.07149758454106281 - nodes in this community are weakly interconnected._
- **Should `Illuminate\Database\Schema\Blueprint` be split into smaller, more focused modules?**
  _Cohesion score 0.10483870967741936 - nodes in this community are weakly interconnected._