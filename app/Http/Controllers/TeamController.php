<?php

namespace App\Http\Controllers;

use App\Data\GameData;
use App\Data\TeamData;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Services\FootballDataService;
use Carbon\Carbon;
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
        $team = Team::find($request->input('id'));
        $upcomingMatches = $team->upcomingMatches()->get();
        $previousMatches = $team->pastMatches()->get();
        // $upcomingMatches = $this->footballDataService->getUpcomingGamesByTeam($request->input('id'));
        // $previousMatches = $this->footballDataService->getPrGamesByTeam($request->input('id'));

        return Inertia::render('Team/Show', [
            'upcomingMatches' => GameData::collect($upcomingMatches),
            'previousMatches' => GameData::collect($previousMatches),
        ]);
    }
}
