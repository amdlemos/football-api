<?php

namespace App\Http\Controllers;

use App\Data\CompetitionData;
use App\Models\Competition;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\FootballDataService;

/** @package App\Http\Controllers */
class CompetitionsController extends Controller
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
        $competitions = Competition::with(['area', 'currentSeason'])->get();
        $competitionsData = CompetitionData::collect($competitions);
        // dd($competitionsData);
        $response = $this->footballDataService->getCompetitions();
        // dd($response);

        return Inertia::render('Competitions/Index', [
            'competitions' => $competitionsData,
        ]);
    }
    //
}
