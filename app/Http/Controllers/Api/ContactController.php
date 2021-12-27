<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Repositories\ContactEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function __construct(ContactEloquent $contactEloquent)
    {
        $this->contact= $contactEloquent;
    }
    public function addcontact(Request $request)
    {
        return $this->contact->addcontact($request->all());
    }
    public function contactlist(Request $request)
    {
        return $this->contact->contactlist($request->all());
    }
}
