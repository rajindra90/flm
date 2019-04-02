<?php

namespace App\Mail;

use App\Friends;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FriendRequest extends Mailable
{
    use Queueable, SerializesModels;

    protected $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Friends $request)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->request->url = url('/api/friend/email/accept?token=') . $this->request->request_token;
        return $this->view('emails.request')->with('request', $this->request);
    }
}
