<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Mail\ArticlePublishedMail;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class SendArticlePublishedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $article;

    /**
     * Create a new job instance.
     */
    public function __construct($article)
    {
        $this->article = $article;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Mail::to($this->article->user->email)->send(new ArticlePublishedMail($this->article));
        try {
            Mail::to($this->article->user->email)->send(new ArticlePublishedMail($this->article));
            Log::info('Email sent successfully to ' . $this->article->user->email);
            // dd('try block ');
        } catch (Exception $e) {
            Log::error('Email failed to send: ' . $e->getMessage());
            // dd('Email Error: ' . $e->getMessage()); // Stop execution & show error
            $this->fail($e); // Mark job as failed
        }
    }
}
