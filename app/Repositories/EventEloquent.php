<?php

namespace App\Repositories;

use App\Http\Controllers\Api\BaseController;
use App\Jobs\EventSend;
use App\Models\Event;
use App\Models\EventContact;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EventEloquent extends BaseController
{

    private $model;

    public function __construct(Event $event)
    {
        $this->model = $event;
    }

    public function addEvent(array $data)
    {
        $user = Auth::user()->id;
        $videoPath = $data['video']->store('public/videos');
        $data['video'] = $data['video']->hashName();
        $event = Event::create([
            'name' => $data['name'],
            'date' => $data['date'],
            'description' => $data['description'],
            'type' => $data['type'],
            'video' => $data['video'],
            'user_id' => $user,
        ]);
        foreach ($data['contact_ids'] as $contact_id) {
            $eventContact = new EventContact();
            $eventContact->event_id = $event->id;
            $eventContact->contact_id = $contact_id;
            $eventContact->save();
        }
        $event_contact = array(
            'event'=>$event,
            'contacts' => $data['contact_ids'],
        );
        $event_job = (new EventSend($event_contact))->delay(Carbon::now()->second(5));
        $this->dispatch($event_job);

        return $this->sendResponse('Successfully added Event ', $event_contact);
    }
}
