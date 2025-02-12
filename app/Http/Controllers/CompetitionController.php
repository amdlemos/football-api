<?php

namespace App\Http\Controllers;

use App\Data\CompetitionData;
use App\Data\GameData;
use App\Services\FootballDataService;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use LogicException;
use RuntimeException;
use ValueError;
use TypeError;
use Spatie\LaravelData\Exceptions\CannotCreateData;
use Spatie\LaravelData\Exceptions\CannotSetComputedValue;

/** @package App\Http\Controllers */
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
        $response = $this->footballDataService->getCompetitions();

        return Inertia::render('Competition/Index', [
            'competitions' => CompetitionData::collect($response),
        ]);
    }

    /**
     * @param Request $request
     * @return App\Http\Controllers\Response
     * @throws BindingResolutionException
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

    /**
     * @param Request $request
     * @return Response
     * @throws FatalRequestException
     * @throws RequestException
     * @throws LogicException
     * @throws RuntimeException
     * @throws ValueError
     * @throws TypeError
     * @throws BindingResolutionException
     * @throws CannotCreateData
     * @throws CannotSetComputedValue
     */
    public function matches(Request $request): Response
    {
        $code = $request['code'];
        $code = 'ELC';

        // $competitionMatches = $this->footballDataService->fetchCompetitionMatches($code);

        static $today = Carbon::now();
        $dateTo = $today->format('Y-m-d');
        $dateFrom = $today->addDays(-10)->format('Y-m-d');
        // $matches = $this->footballDataService->fetchMatches($dateFrom, $dateTo);

        $teams = $this->footballDataService->fetchMatchesByTeam(
            11,
            null,
            null,
            null,
            null,
            null,
            null,
            10,
        );

        // dd($response['matches
        return Inertia::render('Competition/Matches', [
            // 'matches' => GameData::from($response['matches']),
            // 'matches' => $matches,
            // 'competitionMatches' => $competitionMatches,
            'teams' => $teams,
        ]);
    }
}
