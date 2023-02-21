<?php

namespace Paralelo28\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Paralelo28\Subscriber;
use Exception;

class ProcessEmailQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $subject;
    protected $content;
    protected $imageLink;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Subscriber $subscriber, $subject, $content, $imageLink)
    {
        $this->email = $subscriber['email'];
        $this->subject = $subject;
        $this->content = $content;
        $this->imageLink = $imageLink;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $data = [
                "email" => $this->email,
                "name" => explode('@', $this->email)[0],
                "subject" => $this->subject,
                "content" => $this->content,
                "imageLink" => $this->imageLink
            ];

            Mail::send('emails.newsletterPromotion', $data, function($message) use ($data) {
                $message->to($data['email'], $data['name'])->subject($data["subject"]);
                $message->from('noreply@editorialparalelo28.com', 'EditorialParalelo28');
            });
        }catch (Exception $e){
            return;
        }
    }
}
