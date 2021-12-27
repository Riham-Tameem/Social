<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventRequest;
use App\Repositories\EventEloquent;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function __construct(EventEloquent $eventEloquent)
    {
        $this->event= $eventEloquent;
    }
    public function addEvent(EventRequest $request)
    {
        return $this->event->addEvent($request->all());
    }

}
