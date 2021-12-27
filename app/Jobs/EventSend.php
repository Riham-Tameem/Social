<?php

namespace App\Jobs;

use App\Mail\HelloEmail;
use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EventSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $event_data;

    public function __construct($event_data)
    {
        $this->event_data = $event_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //  $email = new HelloEmail($this->event);
        $event = $this->event_data['event'];
        $contacts = $this->event_data['contacts'];

        $contacts = Contact::whereIn('id', $contacts)->get();
        foreach ($contacts as $contact) {
            $data = array(
                'email' => $contact->email,
                'to' => $contact->name,
                'from' => $event->user->email,
                'subject' => $event->name,
            );
//            $email = new HelloEmail();
//        Mail::to('johndoe@tests.com')->send($email);
//            Mail::to('riham.tameem22r@gmail.com')->send('riham.tameem22r@gmail.com');
            Mail::send('mail.testEmails', $data, function ($message) use ($data) {
                $message->from($data['from']);
                $message->to($data['email']);
                $message->subject($data['subject']);
            });

//        echo 2;
        }
    }
}
