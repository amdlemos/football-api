<?php

namespace App\Http\Controllers;

use App\Data\GameData;
use App\Data\TeamData;
use App\Models\Team;
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
        // $teams = $this->footballDataService->getCompetitionTeams('ELC');
        $teams = Team::all();


        // dd($teams['teams']);
        return Inertia::render('Team/Index', [
            'teams' => TeamData::collect($teams),
        ]);
    }
    public function show(Request $request)
    {
        $upcomingMatches = $this->footballDataService->getUpcomingGamesByTeam($request->input('id'));

        return Inertia::render('Team/Show', [
            'upcomingMatches' => GameData::collect($upcomingMatches),
            // 'upcomingMatches' => $upcomingMatches
        ]);
    }
}
