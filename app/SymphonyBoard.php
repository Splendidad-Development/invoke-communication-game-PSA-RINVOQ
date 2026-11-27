<?php

namespace App;

use Illuminate\Support\Collection;

/**
 * Reads the symphony board definition from config/symphony.php and scores arrangements.
 *
 * @phpstan-type Section array{title: string, tone: string, slots: int, answers: list<int>}
 * @phpstan-type Column array{key: string, tone: string, title: string, description: ?string, rows: list<list<string>>}
 */
class SymphonyBoard
{
    /**
     * @param  array<string, Section>  $sections
     * @param  list<Column>  $columns
     * @param  array<int, array{persona: string, caption: list<array{text: string, bold?: bool}>, question: string, example: string, footnote: string}>  $groups
     */
    public function __construct(
        private array $sections,
        private array $columns,
        private array $groups,
        private int $imageCount,
        private string $imagesPath,
        private string $footnote,
        /** @var array<int, int> Tile number => the image it is a second copy of (see config symphony.image_copies). */
        private array $imageCopies = [],
    ) {}

    public static function fromConfig(): self
    {
        return new self(
            sections: config('symphony.sections'),
            columns: config('symphony.columns'),
            groups: config('symphony.groups'),
            imageCount: config('symphony.image_count'),
            imagesPath: config('symphony.images_path'),
            footnote: config('symphony.footnote'),
            imageCopies: config('symphony.image_copies', []),
        );
    }

    /**
     * Every slot id in board (and playback) order, mapped to its section key.
     *
     * @return Collection<string, string>
     */
    public function slots(): Collection
    {
        $slots = [];

        foreach ($this->columns as $column) {
            foreach ($column['rows'] as $row) {
                foreach ($row as $sectionKey) {
                    for ($index = 0; $index < $this->sections[$sectionKey]['slots']; $index++) {
                        $slots["{$sectionKey}-{$index}"] = $sectionKey;
                    }
                }
            }
        }

        return collect($slots);
    }

    /**
     * @return list<string>
     */
    public function slotIds(): array
    {
        return $this->slots()->keys()->all();
    }

    /**
     * @return list<int>
     */
    public function imageIds(): array
    {
        return range(1, $this->imageCount);
    }

    /**
     * @return list<int>
     */
    public function groupNumbers(): array
    {
        return array_keys($this->groups);
    }

    /**
     * @return array{persona: string, question: string, example: string, footnote: string}
     */
    public function group(int $group): array
    {
        return [
            'persona' => $this->groups[$group]['persona'],
            'question' => $this->groups[$group]['question'],
            'example' => $this->groups[$group]['example'],
            'footnote' => $this->groups[$group]['footnote'],
        ];
    }

    /**
     * The abbreviations shown on the group picker.
     */
    public function footnote(): string
    {
        return $this->footnote;
    }

    /**
     * Every group with the caption shown under its card on the group picker.
     *
     * @return list<array{number: int, caption: list<array{text: string, bold: bool}>}>
     */
    public function groupCards(): array
    {
        return array_map(fn (int $group): array => [
            'number' => $group,
            'caption' => array_map(fn (array $part): array => [
                'text' => $part['text'],
                'bold' => $part['bold'] ?? false,
            ], $this->groups[$group]['caption']),
        ], $this->groupNumbers());
    }

    /**
     * The image a tile shows and is scored as: a copy counts as its original.
     */
    public function imageOf(int $tileId): int
    {
        return $this->imageCopies[$tileId] ?? $tileId;
    }

    /**
     * The tiles that show an image: the image's own tile, then its copies.
     *
     * @return list<int>
     */
    private function tilesOf(int $imageId): array
    {
        return [$imageId, ...array_keys(array_filter($this->imageCopies, fn (int $original): bool => $original === $imageId))];
    }

    /**
     * Whether each slot holds a correct image, slot id => bool in board order. Within a section the images can go
     * in any order, but each answer counts once: two copies of an image in a section that needs it once score one box.
     *
     * @param  array<string, int>  $placements
     * @return array<string, bool>
     */
    public function correctSlots(array $placements): array
    {
        $unfilled = array_map(fn (array $section): array => $section['answers'], $this->sections);
        $correct = [];

        foreach ($this->slots() as $slotId => $sectionKey) {
            $tileId = $placements[$slotId] ?? null;
            $index = $tileId === null ? false : array_search($this->imageOf($tileId), $unfilled[$sectionKey], true);

            if ($index !== false) {
                unset($unfilled[$sectionKey][$index]);
            }

            $correct[$slotId] = $index !== false;
        }

        return $correct;
    }

    /**
     * Score an arrangement. The pattern holds one character per slot in board order: "1" correct, "0" wrong.
     *
     * @param  array<string, int>  $placements
     * @return array{score: int, pattern: string}
     */
    public function score(array $placements): array
    {
        $pattern = collect($this->correctSlots($placements))
            ->map(fn (bool $isCorrect): string => $isCorrect ? '1' : '0')
            ->implode('');

        return [
            'score' => substr_count($pattern, '1'),
            'pattern' => $pattern,
        ];
    }

    /**
     * The boxes a scored arrangement got wrong, in board order: the image the player put there and where it belongs.
     * Only sent with a submitted arrangement, so the answers stay hidden while the board is in play.
     *
     * @param  array<string, int>  $placements
     * @return list<array{slotId: string, imageId: ?int, placedIn: string, belongsIn: ?string}>
     */
    public function mistakes(array $placements): array
    {
        $correct = $this->correctSlots($placements);

        return array_values($this->slots()
            ->reject(fn (string $sectionKey, string $slotId): bool => $correct[$slotId])
            ->map(fn (string $sectionKey, string $slotId): array => [
                'slotId' => $slotId,
                'imageId' => $placements[$slotId] ?? null,
                'placedIn' => $this->sections[$sectionKey]['title'],
                'belongsIn' => $this->sectionTitleFor($placements[$slotId] ?? null),
            ])
            ->all());
    }

    /**
     * The correct board for a scored arrangement, as slot id => image id in board order.
     * Within a section the images can go in any order, so boxes the player got right keep their image
     * and the section's remaining correct images fill its other boxes. Only sent with a submitted arrangement.
     *
     * @param  array<string, int>  $placements
     * @return array<string, int>
     */
    public function solution(array $placements): array
    {
        $correct = $this->correctSlots($placements);
        $kept = array_filter($placements, fn (string $slotId): bool => $correct[$slotId] ?? false, ARRAY_FILTER_USE_KEY);
        $usedTiles = array_values($kept);
        $solution = [];

        foreach ($this->slots()->groupBy(fn (string $sectionKey): string => $sectionKey, preserveKeys: true) as $sectionKey => $sectionSlots) {
            $remaining = $this->sections[$sectionKey]['answers'];

            foreach ($sectionSlots->keys() as $slotId) {
                if (isset($kept[$slotId])) {
                    unset($remaining[array_search($this->imageOf($kept[$slotId]), $remaining, true)]);
                }
            }

            foreach ($sectionSlots->keys() as $slotId) {
                if (isset($kept[$slotId])) {
                    $solution[$slotId] = $kept[$slotId];

                    continue;
                }

                // A tile not used elsewhere: an image wanted in two places gets its copy in the second one.
                $imageId = array_shift($remaining);
                $tileId = collect($this->tilesOf($imageId))->first(fn (int $tile): bool => ! in_array($tile, $usedTiles, true)) ?? $imageId;
                $usedTiles[] = $tileId;
                $solution[$slotId] = $tileId;
            }
        }

        return array_merge(array_fill_keys($this->slotIds(), null), $solution);
    }

    /**
     * Where a tile belongs; an image wanted in more than one section lists them all ("A or B").
     */
    private function sectionTitleFor(?int $tileId): ?string
    {
        if ($tileId === null) {
            return null;
        }

        $titles = collect($this->sections)
            ->filter(fn (array $section): bool => in_array($this->imageOf($tileId), $section['answers'], true))
            ->pluck('title')
            ->unique();

        return $titles->isEmpty() ? null : $titles->implode(' or ');
    }

    /**
     * The board layout for the client. Correct answers are deliberately left out.
     *
     * @return list<array{key: string, tone: string, title: string, description: ?string, rows: list<list<array{key: string, title: string, tone: string, slots: list<string>}>>}>
     */
    public function layout(): array
    {
        return array_map(fn (array $column): array => [
            'key' => $column['key'],
            'tone' => $column['tone'],
            'title' => $column['title'],
            'description' => $column['description'],
            'rows' => array_map(fn (array $row): array => array_map(fn (string $sectionKey): array => [
                'key' => $sectionKey,
                'title' => $this->sections[$sectionKey]['title'],
                'tone' => $this->sections[$sectionKey]['tone'],
                'slots' => array_map(
                    fn (int $index): string => "{$sectionKey}-{$index}",
                    range(0, $this->sections[$sectionKey]['slots'] - 1),
                ),
            ], $row), $column['rows']),
        ], $this->columns);
    }

    /**
     * @return list<array{id: int, url: string}>
     */
    public function images(): array
    {
        return array_map(fn (int $imageId): array => [
            'id' => $imageId,
            'url' => asset($this->imageFile($imageId)),
        ], $this->imageIds());
    }

    /**
     * An image is {number}.png when that file exists in public/, otherwise {number}.jpg. A copy uses its original's file.
     */
    private function imageFile(int $tileId): string
    {
        $imageId = $this->imageOf($tileId);
        $png = "{$this->imagesPath}/{$imageId}.png";

        return is_file(public_path($png)) ? $png : "{$this->imagesPath}/{$imageId}.jpg";
    }

    public function backgroundUrl(): string
    {
        return asset("{$this->imagesPath}/background.jpg");
    }
}
