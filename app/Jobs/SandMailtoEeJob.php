<?php

namespace App\Jobs;

use App\Mail\SandMailToEe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SandMailToEeJob implements ShouldQueue
{
    private $props;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($props)
    {
        //
        $this->props = $props;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to('nggawt100@gmail.com')->send(
            new SandMailToEe($this->props)
        );
    }
}
