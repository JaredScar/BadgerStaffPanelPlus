<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\Commend;
use App\Models\Kick;
use App\Models\Note;
use App\Models\Player;
use App\Models\PlayerData;
use App\Models\Server;
use App\Models\Staff;
use App\Models\Warn;
use App\Models\Watchlist;
use App\Support\PanelActivity;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class PanelHubController extends Controller
{
    public function overview(): View
    {
        $serverId = Session::get('server_id');
        $players = Player::query()->when($serverId, fn ($q) => $q->where('server_id', $serverId))->get();
        $playerIds = $players->pluck('player_id');
        $dataRows = PlayerData::query()->whereIn('player_id', $playerIds)->get()->keyBy('player_id');

        $online = $dataRows->filter(fn ($row) => $row->last_join_date && Carbon::parse($row->last_join_date)->gt(now()->subMinutes(15)))->count();
        $today = $dataRows->filter(fn ($row) => $row->last_join_date && Carbon::parse($row->last_join_date)->isToday())->count();
        $avgTrust = $dataRows->count() ? (int) round($dataRows->avg('trust_score')) : 0;
        $playtime = PanelActivity::playtime((int) $dataRows->sum('playtime'));

        $recentJoins = $players->sortByDesc(function ($player) use ($dataRows) {
            $date = optional($dataRows[$player->player_id] ?? null)->last_join_date;
            return $date ? Carbon::parse($date)->timestamp : 0;
        })->take(8)->values();

        $lowTrust = $players->filter(function ($player) use ($dataRows) {
            $score = optional($dataRows[$player->player_id] ?? null)->trust_score;
            return $score !== null && $score < 50;
        })->take(6)->values();

        return view('verified.hub.overview', [
            'data' => $this->page('OVERVIEW'),
            'stats' => [
                'players' => $players->count(),
                'online' => $online,
                'today' => $today,
                'avg_trust' => $avgTrust,
                'playtime' => $playtime,
                'warns' => $this->countFor(Warn::class, $serverId),
                'kicks' => $this->countFor(Kick::class, $serverId),
                'bans' => $this->countFor(Ban::class, $serverId),
                'notes' => $this->countFor(Note::class, $serverId),
                'commends' => $this->countFor(Commend::class, $serverId),
                'watchlist' => Schema::hasTable('watchlist')
                    ? Watchlist::query()->when($serverId, fn ($q) => $q->where('server_id', $serverId))->count()
                    : 0,
            ],
            'activity' => PanelActivity::recent($serverId ? (int) $serverId : null, null, 10),
            'recentJoins' => $recentJoins,
            'playerData' => $dataRows,
            'lowTrust' => $lowTrust,
        ]);
    }

    public function lookup(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $serverId = Session::get('server_id');
        $results = collect();

        if ($q !== '') {
            $results = Player::query()
                ->when($serverId, fn ($query) => $query->where('server_id', $serverId))
                ->where(function ($inner) use ($q) {
                    $inner->where('last_player_name', 'like', '%' . $q . '%')
                        ->orWhere('game_license', 'like', '%' . $q . '%')
                        ->orWhere('steam_id', 'like', '%' . $q . '%')
                        ->orWhere('discord_id', 'like', '%' . $q . '%')
                        ->orWhere('ip', 'like', '%' . $q . '%')
                        ->orWhere('player_id', $q);
                })
                ->limit(25)
                ->get();
        }

        $dataRows = PlayerData::query()
            ->whereIn('player_id', $results->pluck('player_id'))
            ->get()
            ->keyBy('player_id');

        return view('verified.hub.lookup', [
            'data' => $this->page('LOOKUP'),
            'q' => $q,
            'results' => $results,
            'playerData' => $dataRows,
        ]);
    }

    public function online(): View
    {
        $serverId = Session::get('server_id');
        $players = Player::query()->when($serverId, fn ($q) => $q->where('server_id', $serverId))->get();
        $dataRows = PlayerData::query()
            ->whereIn('player_id', $players->pluck('player_id'))
            ->get()
            ->keyBy('player_id');

        $online = $players->filter(function ($player) use ($dataRows) {
            $date = optional($dataRows[$player->player_id] ?? null)->last_join_date;
            return $date && Carbon::parse($date)->gt(now()->subMinutes(15));
        })->values();

        return view('verified.hub.online', [
            'data' => $this->page('PLAYERS_ONLINE'),
            'players' => $online,
            'playerData' => $dataRows,
        ]);
    }

    public function activeBans(): View
    {
        $serverId = Session::get('server_id');
        $bans = Ban::query()
            ->when($serverId, fn ($q) => $q->where('server_id', $serverId))
            ->orderByDesc('created_at')
            ->get()
            ->filter(fn ($ban) => $this->banIsActive($ban))
            ->values();

        return view('verified.hub.active_bans', [
            'data' => $this->page('ACTIVE_BANS'),
            'bans' => $bans,
            'players' => Player::query()->whereIn('player_id', $bans->pluck('player_id'))->get()->keyBy('player_id'),
            'staff' => Staff::query()->whereIn('staff_id', $bans->pluck('staff_id'))->get()->keyBy('staff_id'),
        ]);
    }

    public function expiringBans(): View
    {
        $serverId = Session::get('server_id');
        $bans = Ban::query()
            ->when($serverId, fn ($q) => $q->where('server_id', $serverId))
            ->where('expires', 1)
            ->whereNotNull('expiredDate')
            ->where('expiredDate', '>', now())
            ->where('expiredDate', '<=', now()->copy()->addDays(7))
            ->orderBy('expiredDate')
            ->get();

        $dayEnd = now()->copy()->addDay();
        $withinDay = $bans->filter(fn ($ban) => Carbon::parse($ban->expiredDate)->lte($dayEnd))->values();
        $withinWeek = $bans->reject(fn ($ban) => Carbon::parse($ban->expiredDate)->lte($dayEnd))->values();
        $playerIds = $bans->pluck('player_id');

        return view('verified.hub.expiring_bans', [
            'data' => $this->page('EXPIRING_BANS'),
            'withinDay' => $withinDay,
            'withinWeek' => $withinWeek,
            'players' => Player::query()->whereIn('player_id', $playerIds)->get()->keyBy('player_id'),
            'staff' => Staff::query()->whereIn('staff_id', $bans->pluck('staff_id'))->get()->keyBy('staff_id'),
        ]);
    }

    public function lowTrust(): View
    {
        $serverId = Session::get('server_id');
        $players = Player::query()->when($serverId, fn ($q) => $q->where('server_id', $serverId))->get();
        $dataRows = PlayerData::query()
            ->whereIn('player_id', $players->pluck('player_id'))
            ->get()
            ->keyBy('player_id');

        $low = $players->filter(function ($player) use ($dataRows) {
            $score = optional($dataRows[$player->player_id] ?? null)->trust_score;
            return $score !== null && (int) $score < 50;
        })->sortBy(function ($player) use ($dataRows) {
            return (int) optional($dataRows[$player->player_id] ?? null)->trust_score;
        })->values();

        $lastWarns = Warn::query()
            ->whereIn('player_id', $low->pluck('player_id'))
            ->orderByDesc('created_at')
            ->get()
            ->unique('player_id')
            ->keyBy('player_id');

        return view('verified.hub.low_trust', [
            'data' => $this->page('LOW_TRUST'),
            'players' => $low,
            'playerData' => $dataRows,
            'lastWarns' => $lastWarns,
        ]);
    }

    public function staffShow(int $staffId): View
    {
        $serverId = Session::get('server_id');
        $member = Staff::query()
            ->when($serverId, fn ($q) => $q->where('server_id', $serverId))
            ->where('staff_id', $staffId)
            ->firstOrFail();

        $bans = Ban::query()->where('staff_id', $member->staff_id)->orderByDesc('created_at')->get();
        $warns = Warn::query()->where('staff_id', $member->staff_id)->orderByDesc('created_at')->get();
        $kicks = Kick::query()->where('staff_id', $member->staff_id)->orderByDesc('created_at')->get();
        $notes = Note::query()->where('staff_id', $member->staff_id)->orderByDesc('created_at')->get();
        $commends = Commend::query()->where('staff_id', $member->staff_id)->orderByDesc('created_at')->get();

        $playerIds = $bans->pluck('player_id')
            ->merge($warns->pluck('player_id'))
            ->merge($kicks->pluck('player_id'))
            ->merge($notes->pluck('player_id'))
            ->merge($commends->pluck('player_id'))
            ->unique();

        return view('verified.hub.staff_profile', [
            'data' => $this->page('STAFF_PROFILE'),
            'member' => $member,
            'players' => Player::query()->whereIn('player_id', $playerIds)->get()->keyBy('player_id'),
            'records' => [
                'Bans' => $bans,
                'Warns' => $warns,
                'Kicks' => $kicks,
                'Notes' => $notes,
                'Commends' => $commends,
            ],
        ]);
    }

    public function server(): View
    {
        $serverId = Session::get('server_id');
        $server = Server::query()->find($serverId);
        $players = Player::query()->when($serverId, fn ($q) => $q->where('server_id', $serverId))->get();
        $dataRows = PlayerData::query()->whereIn('player_id', $players->pluck('player_id'))->get();
        $online = $dataRows->filter(fn ($row) => $row->last_join_date && Carbon::parse($row->last_join_date)->gt(now()->subMinutes(15)))->count();

        return view('verified.hub.server', [
            'data' => $this->page('SERVER'),
            'server' => $server,
            'stats' => [
                'players' => $players->count(),
                'online' => $online,
                'staff' => Staff::query()->when($serverId, fn ($q) => $q->where('server_id', $serverId))->count(),
                'bans' => $this->countFor(Ban::class, $serverId),
                'warns' => $this->countFor(Warn::class, $serverId),
                'watchlist' => Schema::hasTable('watchlist')
                    ? Watchlist::query()->when($serverId, fn ($q) => $q->where('server_id', $serverId))->count()
                    : 0,
            ],
        ]);
    }

    public function staffDirectory(): View
    {
        $serverId = Session::get('server_id');
        $staff = Staff::query()
            ->when($serverId, fn ($q) => $q->where('server_id', $serverId))
            ->orderBy('staff_username')
            ->get();

        return view('verified.hub.staff', [
            'data' => $this->page('STAFF_DIRECTORY'),
            'staff' => $staff,
        ]);
    }

    public function joins(): View
    {
        $serverId = Session::get('server_id');
        $players = Player::query()
            ->when($serverId, fn ($q) => $q->where('server_id', $serverId))
            ->get();
        $dataRows = PlayerData::query()
            ->whereIn('player_id', $players->pluck('player_id'))
            ->get()
            ->keyBy('player_id');

        $sorted = $players->sortByDesc(function ($player) use ($dataRows) {
            $date = optional($dataRows[$player->player_id] ?? null)->last_join_date;
            return $date ? Carbon::parse($date)->timestamp : 0;
        })->values();

        return view('verified.hub.joins', [
            'data' => $this->page('RECENT_JOINS'),
            'players' => $sorted,
            'playerData' => $dataRows,
        ]);
    }

    public function watchlist(): View
    {
        $serverId = Session::get('server_id');
        $items = Schema::hasTable('watchlist')
            ? Watchlist::query()->when($serverId, fn ($q) => $q->where('server_id', $serverId))->orderByDesc('created_at')->get()
            : collect();
        $players = Player::query()->whereIn('player_id', $items->pluck('player_id'))->get()->keyBy('player_id');
        $staff = Staff::query()->whereIn('staff_id', $items->pluck('staff_id'))->get()->keyBy('staff_id');
        $allPlayers = Player::query()->when($serverId, fn ($q) => $q->where('server_id', $serverId))->orderBy('last_player_name')->get();

        return view('verified.hub.watchlist', [
            'data' => $this->page('WATCHLIST'),
            'items' => $items,
            'players' => $players,
            'staff' => $staff,
            'allPlayers' => $allPlayers,
        ]);
    }

    public function addWatch(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'player_id' => 'required|integer|exists:players,player_id',
            'reason' => 'nullable|string|max:255',
        ]);

        Watchlist::query()->firstOrCreate(
            [
                'server_id' => Session::get('server_id'),
                'player_id' => $validated['player_id'],
            ],
            [
                'staff_id' => Auth::id(),
                'reason' => $validated['reason'] ?: 'Flagged for review',
            ]
        );

        return back()->with('status', 'Player added to the watchlist.');
    }

    public function removeWatch(int $id): RedirectResponse
    {
        Watchlist::query()
            ->when(Session::get('server_id'), fn ($q) => $q->where('server_id', Session::get('server_id')))
            ->where('id', $id)
            ->delete();

        return redirect()->route('WATCHLIST')->with('status', 'Removed from the watchlist.');
    }

    public function myProfile(): View
    {
        $staff = Staff::find(Auth::id());
        abort_unless($staff, 404);

        $serverId = $staff->server_id ?: Session::get('server_id');

        return view('verified.hub.my_profile', [
            'data' => $this->page('MY_PROFILE'),
            'staff' => $staff,
            'counts' => [
                'warns' => Warn::query()->where('staff_id', $staff->staff_id)->count(),
                'kicks' => Kick::query()->where('staff_id', $staff->staff_id)->count(),
                'bans' => Ban::query()->where('staff_id', $staff->staff_id)->count(),
                'commends' => Commend::query()->where('staff_id', $staff->staff_id)->count(),
                'notes' => Note::query()->where('staff_id', $staff->staff_id)->count(),
            ],
            'activity' => PanelActivity::recent($serverId ? (int) $serverId : null, null, 15)
                ->where('staff_id', $staff->staff_id)
                ->values(),
        ]);
    }

    public function performance(): View
    {
        $serverId = Session::get('server_id');
        $staff = Staff::query()->when($serverId, fn ($q) => $q->where('server_id', $serverId))->get();

        $rows = $staff->map(function (Staff $member) {
            return [
                'staff' => $member,
                'warns' => Warn::query()->where('staff_id', $member->staff_id)->count(),
                'kicks' => Kick::query()->where('staff_id', $member->staff_id)->count(),
                'bans' => Ban::query()->where('staff_id', $member->staff_id)->count(),
                'commends' => Commend::query()->where('staff_id', $member->staff_id)->count(),
                'notes' => Note::query()->where('staff_id', $member->staff_id)->count(),
            ];
        })->map(function (array $row) {
            $row['total'] = $row['warns'] + $row['kicks'] + $row['bans'] + $row['commends'] + $row['notes'];
            return $row;
        })->sortByDesc('total')->values();

        return view('verified.hub.performance', [
            'data' => $this->page('STAFF_PERFORMANCE'),
            'rows' => $rows,
        ]);
    }

    public function publicBans(): View|RedirectResponse
    {
        if (!filter_var(env('PUBLIC_BANS', true), FILTER_VALIDATE_BOOLEAN) && !Auth::guard('web')->check()) {
            return redirect()->route('START');
        }

        $bans = Ban::query()->orderByDesc('created_at')->limit(100)->get();
        $players = Player::query()->whereIn('player_id', $bans->pluck('player_id'))->get()->keyBy('player_id');
        $staff = Staff::query()->whereIn('staff_id', $bans->pluck('staff_id'))->get()->keyBy('staff_id');

        return view('verified.hub.public_bans', [
            'data' => $this->page('PUBLIC_BANS', Auth::guard('web')->check() ? 'players' : 'login/auth'),
            'bans' => $bans,
            'players' => $players,
            'staff' => $staff,
            'authenticated' => Auth::guard('web')->check(),
        ]);
    }

    public function settings(): View
    {
        $server = Server::query()->find(Session::get('server_id'));

        return view('verified.hub.settings', [
            'data' => $this->page('SETTINGS'),
            'server' => $server,
        ]);
    }

    public function saveSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'server_name' => 'required|string|max:255',
            'discord_webhook_url' => 'nullable|url|max:500',
            'webhook_enabled' => 'nullable|boolean',
        ]);

        $server = Server::query()->find(Session::get('server_id'));
        if (!$server) {
            return back()->with('error', 'No server is selected.');
        }

        $server->server_name = $validated['server_name'];
        if (Schema::hasColumn('servers', 'discord_webhook_url')) {
            $server->discord_webhook_url = $validated['discord_webhook_url'] ?? null;
        }
        if (Schema::hasColumn('servers', 'webhook_enabled')) {
            $server->webhook_enabled = $request->boolean('webhook_enabled');
        }
        $server->save();

        Session::put('server_name', $server->server_name);

        return back()->with('status', 'Server settings saved.');
    }

    private function page(string $viewName, string $css = 'players'): array
    {
        return [
            'css_path' => $css,
            'view_name' => $viewName,
            'customize' => false,
        ];
    }

    private function banIsActive(Ban $ban): bool
    {
        $expires = $ban->expires;
        $permanent = $expires === 0 || $expires === '0' || $expires === false || $expires === 'false' || $expires === null;
        if ($permanent) {
            return true;
        }

        $end = $ban->expiredDate ?? null;
        return $end && Carbon::parse($end)->isFuture();
    }

    private function countFor(string $model, mixed $serverId): int
    {
        return $model::query()->when($serverId, fn ($q) => $q->where('server_id', $serverId))->count();
    }
}
