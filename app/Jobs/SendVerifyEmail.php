<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\DemoVerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendVerifyEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected User   $user,
        protected string $token
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user->notify(new DemoVerifyEmail($this->token));
    }
}
