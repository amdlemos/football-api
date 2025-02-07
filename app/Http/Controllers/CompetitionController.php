<?php

namespace App\Http\Controllers;

use App\Data\CompetitionData;
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
        $response = $this->footballDataService->getCompetitionTeams($request['code']);

        return Inertia::render('Competition/Index', [
            'teams' => CompetitionData::from($response),
        ]);
    }
}
