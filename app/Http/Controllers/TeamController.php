<?php

namespace App\Http\Controllers;

use App\Data\GameData;
use Illuminate\Http\Request;
use App\Services\FootballDataService;
use Inertia\Inertia;

class TeamController extends Controller
{
    protected FootballDataService $footballDataService;

    public function __construct(FootballDataService $footballDataService)
    {
        $this->footballDataService = $footballDataService;
    }

    public function index(Request $request)
    {
        $upcomingMatches = $this->footballDataService->getUpcomingGamesByTeam($request->input('id'));

        return Inertia::render('Team/Index', [
            'upcomingMatches' => GameData::collect($upcomingMatches),
            // 'upcomingMatches' => $upcomingMatches
        ]);
    }
}
