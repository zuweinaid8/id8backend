<?php

namespace App\Http\Controllers\Messages;

use App\Exceptions\BaseException;
use App\Http\Resources\Messages\ContactResource;
use App\Services\Messages\ContactsService;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    /**
     * @throws BaseException
     */
    public function index()
    {
        $user = request()->user();

        $service = new ContactsService();
        $contacts = $service->getContactsForUser($user);

        return ContactResource::collection($contacts);
    }
}
