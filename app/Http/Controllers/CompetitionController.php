<?php

namespace App\Http\Controllers;

use App\Data\CompetitionData;
use App\Data\GameData;
use App\Services\FootballDataService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 *  Handles the logic for managing competitions, including retrieving and processing competition data.
 *
 * @package App\Http\Controllers */
class CompetitionController extends Controller
{
    protected FootballDataService $footballDataService;

    public function __construct(FootballDataService $footballDataService)
    {
        $this->footballDataService = $footballDataService;
    }


    /**
     * Displays a list of all available competitions.
     *
     * @param Request $request The incoming request.
     * @return Response The rendered view with a list of competitions.
     */
    public function index(Request $request): Response
    {
        $response = $this->footballDataService->getCompetitions();

        return Inertia::render('Competition/Index', [
            'competitions' => CompetitionData::collect($response),
        ]);
    }

    /**
     ** Displays detailed information about a specific competition,
     *  including its teams and upcoming and previous matches.
     *
     * @param Request $request The incoming request containing the competition code.
     * @return Response The rendered view with competition data and match details.
     */
    public function show(Request $request): Response
    {
        $competitionCode = $request['code'];
        $response = $this->footballDataService->getCompetitionTeams($competitionCode);
        $upcomingMatches = $this->footballDataService->getUpcomingGamesByCompetition($competitionCode);
        $previousMatches = $this->footballDataService->getPreviousGamesByCompetition($competitionCode);

        return Inertia::render('Competition/Show', [
            'competition' => CompetitionData::from($response),
            'upcomingMatches' => GameData::collect($upcomingMatches),
            'previousMatches' => GameData::collect($previousMatches),
        ]);
    }
}
