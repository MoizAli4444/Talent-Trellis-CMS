<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Mail\PostCreatedMail;

class SendPostNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public User $user;
    public Post $post;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, Post $post)
    {
        $this->user = $user;
        $this->post = $post;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Job Started for ' . $this->user->email);

        try {
            Mail::to($this->user->email)->send(new PostCreatedMail($this->post));
            Log::info('Email sent successfully to ' . $this->user->email);
        } catch (Exception $e) {
            Log::error('Email failed to send: ' . $e->getMessage());
            $this->fail($e); // Mark job as failed
        }
    }
}
