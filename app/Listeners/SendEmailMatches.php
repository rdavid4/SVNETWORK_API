<?php

namespace App\Listeners;

use App\Events\MatchProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailMatches
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MatchProcessed $event): void
    {
        $match = $event->match;

    }
}
