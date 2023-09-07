<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\PasswordResetFormSubmitted;
use App\Services\MarketingCloudService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendPasswordResetEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        public MarketingCloudService $marketingCloudService,
    ) {
    }

    /**
     * Handle the event.
     *
     * @param PasswordResetFormSubmitted $event
     */
    public function handle(PasswordResetFormSubmitted $event): void
    {
        $this->marketingCloudService->sendPasswordReset($event->user, $event->token);
    }
}
