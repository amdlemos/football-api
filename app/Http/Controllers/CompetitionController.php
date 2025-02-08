<?php

namespace App\Http\Controllers;

use App\Data\CompetitionData;
use App\Data\GameData;
use App\Services\FootballDataService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CompetitionController extends Controller
{

    protected FootballDataService $footballDataService;

    public function __construct(FootballDataService $footballDataService)
    {
        $this->footballDataService = $footballDataService;
    }

    /**
     * @param Request $request
     * @return App\Http\Controllers\Response
     * @throws BindingResolutionException
     */
    public function index(Request $request): Response
    {
        $competitionCode = $request['code'];
        $response = $this->footballDataService->getCompetitionTeams($competitionCode);
        $upcomingMatches = $this->footballDataService->getUpcomingGamesByCompetition($competitionCode);

        return Inertia::render('Competition/Index', [
            'competition' => CompetitionData::from($response),
            'upcomingMatches' => GameData::collect($upcomingMatches),
        ]);
    }

    public function matches(Request $request): Response
    {
        $response = $this->footballDataService->fetchCompetitionMatches('ELC');

        return Inertia::render('Competition/Matches', [
            // 'matches' => CompetitionData::from($response),
            'matches' => $response
        ]);
    }
}
