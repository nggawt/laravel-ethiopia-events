<?php

namespace App\Jobs;

// use App\Mail\Event_created;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    public $params;
    private $fn;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params, $fn)
    {
        $this->params = $params;
        $this->fn = $fn;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->params['email'])->send(
            new $this->fn($this->params)
        );
    }
     public function failed(Exception $exception)
    {
        // Send user notification of failure, etc...
        // Mail::to('nggawt100@gmail.com')->send(
        //     new Event_created($this->params)
        // );
    }
}
