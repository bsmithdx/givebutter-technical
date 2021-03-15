<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $contactId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, int $contactId)
    {
        //validate json payload for new contact
        //TODO: validate emails (only allow one primary)
        $validated = $request->validate([
            'emails.*.email' => 'required|email',
            'emails.*.primary' => 'required|boolean',
        ]);
        //validate that the contact exists
        $contact = Contact::find($contactId);
        if(!$contact) {
            return response()->json(['message' => 'resource not found'], 404);
        }
        //set the contact's emails
        $contact->emails = $request->input('emails');
        $contact->save();
        return response()->json(['emails' => $contact->emails], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $contactId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $contactId)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $contactId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $contactId)
    {
        //
    }
}
