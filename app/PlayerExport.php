<?php

namespace App;

use App\Models\Answer;
use App\Models\Player;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Entity\SheetView;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Writer\XLSX\Writer;

/**
 * Every player with their written answers, for the /export page and its PDF and Excel downloads.
 * Times are in the app timezone (UTC).
 *
 * @phpstan-type ExportAnswer array{group: int, persona: string, body: string, answeredAt: string, boards: int, bestScore: ?int}
 * @phpstan-type ExportPlayer array{id: int, name: string, joinedAt: string, answers: list<ExportAnswer>}
 */
class PlayerExport
{
    private const DATE_FORMAT = 'Y-m-d H:i';

    /** @var list<string> */
    private const COLUMNS = ['Player', 'Joined (UTC)', 'Group', 'HCP profile', 'Answer', 'Answered (UTC)', 'Boards submitted', 'Best score'];

    public function __construct(private SymphonyBoard $board) {}

    public function title(): string
    {
        return config('symphony.export.title');
    }

    /**
     * Boxes on the board: what a best score is out of.
     */
    public function totalSlots(): int
    {
        return count($this->board->slotIds());
    }

    /**
     * Players in the order they joined, each with their answers in the order they wrote them.
     *
     * @return list<ExportPlayer>
     */
    public function players(): array
    {
        return array_values(Player::query()
            ->with(['answers' => fn (HasMany $query) => $query
                ->withCount('arrangements')
                ->withMax('arrangements', 'score')
                ->orderBy('created_at')
                ->orderBy('id')])
            ->orderBy('created_at')
            ->orderBy('id')
            ->get()
            ->map(fn (Player $player): array => [
                'id' => $player->id,
                'name' => $player->name,
                'joinedAt' => $this->date($player->created_at),
                'answers' => array_values($player->answers->map(fn (Answer $answer): array => [
                    'group' => $answer->group,
                    'persona' => $this->persona($answer->group),
                    'body' => $answer->body,
                    'answeredAt' => $this->date($answer->created_at),
                    'boards' => (int) $answer->getAttribute('arrangements_count'),
                    'bestScore' => $answer->getAttribute('arrangements_max_score') === null
                        ? null
                        : (int) $answer->getAttribute('arrangements_max_score'),
                ])->all()),
            ])
            ->all());
    }

    /**
     * Save the export as an Excel workbook: one row per answer, and one row for a player who has not answered yet.
     *
     * @param  list<ExportPlayer>  $players
     */
    public function writeXlsx(array $players, string $path): void
    {
        $options = new Options;
        $options->setColumnWidth(22, 1);
        $options->setColumnWidth(17, 2, 6);
        $options->setColumnWidth(8, 3);
        $options->setColumnWidth(40, 4);
        $options->setColumnWidth(70, 5);
        $options->setColumnWidth(12, 7, 8);

        $writer = new Writer($options);
        $writer->openToFile($path);
        $writer->getCurrentSheet()->setName('Players');
        $writer->getCurrentSheet()->setSheetView((new SheetView)->withFreezeRow(2));

        $header = new Style(fontBold: true, fontColor: 'FFFFFF', backgroundColor: '2B2340');
        $columns = self::COLUMNS;
        $columns[7] = "Best score (out of {$this->totalSlots()})";
        $writer->addRow(Row::fromValuesWithStyle($columns, $header));

        $wrapped = new Style(shouldWrapText: true);

        foreach ($players as $player) {
            if ($player['answers'] === []) {
                $writer->addRow(Row::fromValuesWithStyle([$player['name'], $player['joinedAt'], null, '', 'No answer yet', '', 0, null], $wrapped));

                continue;
            }

            foreach ($player['answers'] as $answer) {
                $writer->addRow(Row::fromValuesWithStyle([
                    $player['name'],
                    $player['joinedAt'],
                    $answer['group'],
                    $answer['persona'],
                    $answer['body'],
                    $answer['answeredAt'],
                    $answer['boards'],
                    $answer['bestScore'],
                ], $wrapped));
            }
        }

        $writer->close();
    }

    /**
     * Download name, e.g. "rinvoq-axspa-communication-game-players-2026-11-26.xlsx".
     */
    public function fileName(string $extension): string
    {
        return str($this->title())->slug()->append('-players-', now()->format('Y-m-d'), '.', $extension)->toString();
    }

    private function persona(int $group): string
    {
        return in_array($group, $this->board->groupNumbers(), true)
            ? $this->board->group($group)['persona']
            : '';
    }

    private function date(?CarbonInterface $date): string
    {
        return $date?->format(self::DATE_FORMAT) ?? '';
    }
}
