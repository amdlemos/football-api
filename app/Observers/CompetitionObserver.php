<?php

namespace App\Observers;

use App\Models\Competition;
use Illuminate\Support\Facades\Cache;

class CompetitionObserver
{
    /**
     * Handle the Competition "created" event.
     */
    public function created(Competition $competition): void
    {
        $this->clearCache($competition);
    }

    /**
     * Handle the Competition "updated" event.
     */
    public function updated(Competition $competition): void
    {
        $this->clearCache($competition);
    }

    /**
     * Handle the Competition "deleted" event.
     */
    public function deleted(Competition $competition): void
    {
        $this->clearCache($competition);
    }

    /**
     * Handle the Competition "restored" event.
     */
    public function restored(Competition $competition): void
    {
        //
    }

    /**
     * Handle the Competition "force deleted" event.
     */
    public function forceDeleted(Competition $competition): void
    {
        //
    }

    private function clearCache(Competition $competition)
    {
        Cache::forget('competitions');
        Cache::forget("competition_{$competition->code}");
        Cache::forget("competition_matches_{$competition->code}");
    }
}
