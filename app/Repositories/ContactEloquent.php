<?php

namespace App\Repositories;

use App\Http\Controllers\Api\BaseController;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContactEloquent extends BaseController
{
    private $model;
    public function __construct(Contact $contact)
    {
        $this->model = $contact;
    }
    public function addcontact(array $data)
    {
        $validator = Validator::make($data, [
            'contact_id' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'name'=> 'required',
            'photo'=> 'nullable|image'
        ]);
        if ($validator->fails()) {
          //  return response(['errors'=>$validator->errors()->all()], 422);
            return  $this->sendError($validator->errors()->all());
        }
        $contact= Contact::create([
            'contact_id'=>$data['contact_id'],
            'user_id'=>Auth::user()->id,
            'phone'=>$data['phone'],
            'email'=>$data['email'],
            'name'=>$data['name'],
        ]);
        if( $data['photo']) {
            $filename=$data['photo']->store('public/images');
            $imagename=$data['photo']->hashName();
            $data['photo'] = $imagename;
            $contact->update([
                'photo' =>   $data['photo']
            ]);
        }
        return $this->sendResponse('contact is add successfully',$contact);

    }
    public function contactlist(array $data)
    {
        $mycontacts_id=Contact::where('user_id',\Auth::user()->id)->pluck('id')->toArray();
        $page_size = $data['page_size'] ?? 10;
        //dd($page_size);
        $current_page = $data['current_page'] ?? 1;
        $contacts_id = array_unique($mycontacts_id) ;
        $objects = Contact::whereIn('id',$contacts_id);
        $total_records = $objects->count();
        $total_page= ceil($total_records /$page_size);
        $skip=$page_size *($current_page-1);
        $contacts = $objects->skip($skip)->take($page_size)->get();
        $data = [
            'items' =>[
                'contacts'=>$contacts,
                'total_records'=> $total_records,
                'current_page'=> intval($current_page),
                'total_pages'=> $total_page,
                'page_size' => $page_size
            ],
        ];
        return $this->sendResponse('Success',$data);

    }
}
