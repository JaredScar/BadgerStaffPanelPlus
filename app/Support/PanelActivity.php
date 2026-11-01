<?php

namespace App\Support;

use App\Models\Ban;
use App\Models\Commend;
use App\Models\Kick;
use App\Models\Note;
use App\Models\Player;
use App\Models\Staff;
use App\Models\Warn;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PanelActivity
{
    public static function recent(?int $serverId = null, ?int $playerId = null, int $limit = 40): Collection
    {
        $sources = [
            ['model' => Ban::class, 'type' => 'Ban', 'verb' => 'banned', 'badge' => 'bg-danger', 'text' => 'reason'],
            ['model' => Warn::class, 'type' => 'Warn', 'verb' => 'warned', 'badge' => 'bg-warning text-dark', 'text' => 'reason'],
            ['model' => Kick::class, 'type' => 'Kick', 'verb' => 'kicked', 'badge' => 'bg-orange', 'text' => 'reason'],
            ['model' => Commend::class, 'type' => 'Commend', 'verb' => 'commended', 'badge' => 'bg-success', 'text' => 'reason'],
            ['model' => Note::class, 'type' => 'Note', 'verb' => 'noted', 'badge' => 'bg-info', 'text' => 'note'],
        ];

        $events = collect();

        foreach ($sources as $source) {
            $query = $source['model']::query();
            if ($serverId) {
                $query->where('server_id', $serverId);
            }
            if ($playerId) {
                $query->where('player_id', $playerId);
            }

            foreach ($query->orderByDesc('created_at')->limit($limit)->get() as $row) {
                $events->push([
                    'type' => $source['type'],
                    'verb' => $source['verb'],
                    'badge' => $source['badge'],
                    'reason' => (string) ($row->{$source['text']} ?? '—'),
                    'player_id' => $row->player_id,
                    'staff_id' => $row->staff_id,
                    'created_at' => $row->created_at,
                    'time' => self::when($row->created_at),
                ]);
            }
        }

        $playerNames = Player::query()
            ->whereIn('player_id', $events->pluck('player_id')->filter()->unique())
            ->pluck('last_player_name', 'player_id');
        $staffNames = Staff::query()
            ->whereIn('staff_id', $events->pluck('staff_id')->filter()->unique())
            ->pluck('staff_username', 'staff_id');

        return $events
            ->map(function (array $event) use ($playerNames, $staffNames) {
                $event['player'] = $playerNames[$event['player_id']] ?? 'Unknown';
                $event['admin'] = $staffNames[$event['staff_id']] ?? 'Staff';
                return $event;
            })
            ->sortByDesc(function (array $event) {
                $at = $event['created_at'];
                if ($at instanceof Carbon) {
                    return $at->timestamp;
                }
                return $at ? Carbon::parse($at)->timestamp : 0;
            })
            ->take($limit)
            ->values();
    }

    public static function when(mixed $value): string
    {
        if (!$value) {
            return 'Unknown';
        }

        try {
            return Carbon::parse($value)->diffForHumans();
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }

    public static function playtime(?int $minutes): string
    {
        $minutes = max(0, (int) $minutes);
        $hours = intdiv($minutes, 60);
        $remain = $minutes % 60;

        return $hours . 'h ' . $remain . 'm';
    }

    public static function initials(?string $name): string
    {
        $parts = preg_split('/[\s_]+/', trim((string) $name)) ?: [];
        $letters = array_slice(array_filter($parts), 0, 2);

        return strtoupper(implode('', array_map(fn ($part) => mb_substr($part, 0, 1), $letters))) ?: '?';
    }
}
