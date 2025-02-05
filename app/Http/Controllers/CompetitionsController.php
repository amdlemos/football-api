<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/** @package App\Http\Controllers */
class CompetitionsController extends Controller
{

    /**
     * @param Request $request
     * @return App\Http\Controllers\Response
     * @throws BindingResolutionException
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Competitions/Index', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }
    //
}
