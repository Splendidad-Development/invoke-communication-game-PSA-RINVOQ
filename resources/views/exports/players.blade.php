{{-- PDF export of every player and their answers (rendered by dompdf: CSS 2.1, DejaVu Sans covers non-Latin names). --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title }} – players</title>
    <style>
        @page { margin: 28px 30px 36px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9.5px; color: #1d1830; }
        h1 { margin: 0 0 2px; font-size: 16px; color: #2b2340; }
        .meta { margin: 0 0 12px; color: #6b6580; }
        table { width: 100%; border-collapse: collapse; }
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        th { background: #2b2340; color: #fff; font-weight: bold; text-align: left; padding: 6px; }
        td { border-bottom: 1px solid #ddd8e8; padding: 6px; vertical-align: top; }
        td.player { font-weight: bold; background: #f6f4fa; }
        td.player small { display: block; font-weight: normal; color: #6b6580; }
        td.answer { white-space: pre-wrap; }
        td.num { text-align: center; white-space: nowrap; }
        .empty { color: #6b6580; font-style: italic; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p class="meta">
        {{ count($players) }} {{ \Illuminate\Support\Str::plural('player', count($players)) }}
        @php($answerCount = collect($players)->sum(fn ($player) => count($player['answers'])))
        · {{ $answerCount }} {{ \Illuminate\Support\Str::plural('answer', $answerCount) }}
        · Exported {{ $generatedAt }} UTC
    </p>

    <table>
        <thead>
            <tr>
                <th style="width: 14%">Player</th>
                <th style="width: 5%">Group</th>
                <th style="width: 22%">HCP profile</th>
                <th>Answer</th>
                <th style="width: 10%">Answered (UTC)</th>
                <th style="width: 6%">Boards</th>
                <th style="width: 7%">Best score</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($players as $player)
                @forelse ($player['answers'] as $answer)
                    <tr>
                        @if ($loop->first)
                            <td class="player" rowspan="{{ count($player['answers']) }}">
                                {{ $player['name'] }}
                                <small>Joined {{ $player['joinedAt'] }}</small>
                            </td>
                        @endif
                        <td class="num">{{ $answer['group'] }}</td>
                        <td>{{ $answer['persona'] }}</td>
                        <td class="answer">{{ $answer['body'] }}</td>
                        <td>{{ $answer['answeredAt'] }}</td>
                        <td class="num">{{ $answer['boards'] }}</td>
                        <td class="num">{{ $answer['bestScore'] === null ? '–' : $answer['bestScore'].' / '.$totalSlots }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="player">
                            {{ $player['name'] }}
                            <small>Joined {{ $player['joinedAt'] }}</small>
                        </td>
                        <td colspan="6" class="empty">No answer yet</td>
                    </tr>
                @endforelse
            @empty
                <tr>
                    <td colspan="7" class="empty">No players yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
