<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Http\Requests\SaveContactMessage;

use App\Http\Resources\ContactMessageResource;

use App\Services\ContactMessageService;

use App\Utilities;

class ContactController extends Controller
{
    protected $contactMessageService;

    public function __construct()
    {
        $this->contactMessageService = new ContactMessageService;
    }

    public function contactPage()
    {
        return Inertia::render("Contact");
    }

    public function save(SaveContactMessage $request)
    {
        try{
            $data = $request->validated();
            if(Auth::User()) {
                $data['name'] = Auth::user()->name;
                $data['email'] = Auth::user()->email;
                $data['userId'] = Auth::user()->id;
            }
            $this->contactMessageService->save($data);

            return back();
        }catch (\Exception $e) {
            Utilities::error($e);
            return back()->withErrors([
                'error' => 'An error occurred while attempting to save Message..',
            ]);
        }
    }
}
