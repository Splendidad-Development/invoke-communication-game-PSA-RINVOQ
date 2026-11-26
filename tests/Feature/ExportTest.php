<?php

use App\Models\Answer;
use App\Models\Arrangement;
use App\Models\Player;
use Inertia\Testing\AssertableInertia as Assert;
use OpenSpout\Reader\XLSX\Reader;

beforeEach(function () {
    config(['symphony.export.password' => 'secret-export']);
});

function asExporter(): array
{
    return ['Authorization' => 'Basic '.base64_encode('export:secret-export')];
}

it('is turned off when no export password is set', function () {
    config(['symphony.export.password' => null]);

    $this->get(route('export.index'))->assertNotFound();
    $this->get(route('export.excel'))->assertNotFound();
});

it('asks for the password', function () {
    $this->get(route('export.index'))
        ->assertUnauthorized()
        ->assertHeader('WWW-Authenticate');

    $this->get(route('export.pdf'), ['Authorization' => 'Basic '.base64_encode('export:wrong')])
        ->assertUnauthorized();
});

it('lists every player with their answers', function () {
    $sara = Player::factory()->create(['name' => 'Sara']);
    $answer = Answer::factory()->for($sara)->create(['group' => 2, 'body' => 'Lead with the safety data.']);
    Arrangement::factory()->for($answer)->create(['score' => 9]);
    Arrangement::factory()->for($answer)->create(['score' => 14]);
    Player::factory()->create(['name' => 'Omar']);

    $this->get(route('export.index'), asExporter())
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('export/Index')
            ->where('title', config('symphony.export.title'))
            ->has('players', 2)
            ->where('players.0.name', 'Sara')
            ->where('players.0.answers.0.group', 2)
            ->where('players.0.answers.0.persona', config('symphony.groups.2.persona'))
            ->where('players.0.answers.0.body', 'Lead with the safety data.')
            ->where('players.0.answers.0.boards', 2)
            ->where('players.0.answers.0.bestScore', 14)
            ->where('players.1.name', 'Omar')
            ->has('players.1.answers', 0));
});

it('downloads a PDF', function () {
    Answer::factory()->for(Player::factory()->create(['name' => 'Sara']))->create();

    $response = $this->get(route('export.pdf'), asExporter());

    $response->assertOk()->assertHeader('Content-Type', 'application/pdf');
    expect($response->headers->get('Content-Disposition'))->toContain('-players-')->toContain('.pdf')
        ->and($response->getContent())->toStartWith('%PDF');
});

it('downloads an Excel workbook with one row per answer', function () {
    $sara = Player::factory()->create(['name' => 'Sara']);
    Answer::factory()->for($sara)->create(['group' => 1, 'body' => 'First answer']);
    Answer::factory()->for($sara)->create(['group' => 3, 'body' => 'Second answer']);
    Player::factory()->create(['name' => 'Omar']);

    $response = $this->get(route('export.excel'), asExporter());

    $response->assertOk()->assertDownload();
    expect($response->headers->get('Content-Disposition'))->toContain('.xlsx');

    $reader = new Reader;
    $reader->open($response->getFile()->getPathname());
    $rows = [];
    foreach ($reader->getSheetIterator() as $sheet) {
        foreach ($sheet->getRowIterator() as $row) {
            $rows[] = $row->toArray();
        }
    }
    $reader->close();

    expect($rows)->toHaveCount(4)
        ->and($rows[0][0])->toBe('Player')
        ->and([$rows[1][0], $rows[1][2], $rows[1][4]])->toBe(['Sara', 1, 'First answer'])
        ->and([$rows[2][0], $rows[2][2], $rows[2][4]])->toBe(['Sara', 3, 'Second answer'])
        ->and([$rows[3][0], $rows[3][4]])->toBe(['Omar', 'No answer yet']);
});
