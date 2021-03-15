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
        //validate that the contact exists
        $contact = Contact::find($contactId);
        if(!$contact) {
            return response()->json(['message' => 'resource not found'], 404);
        }
        //validate json payload for new contact
        //TODO: validate emails (only allow one primary)
        $validated = $request->validate([
            'emails.*.email' => 'required|email',
            'emails.*.primary' => 'required|boolean',
        ]);
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
        //validate that the contact exists
        $contact = Contact::find($contactId);
        if(!$contact) {
            return response()->json(['message' => 'resource not found'], 404);
        }
        //validate the JSON payload
        //TODO: validate emails (check for existing primary if adding primary)
        //TODO: validate emails (check for duplicates)
        $validated = $request->validate([
            'emails.*.email' => 'required|email',
            'emails.*.primary' => 'required|boolean',
        ]);
        //modify the contacts attributes
        $contact->emails = array_merge($contact->emails, [
            [
                'email' => $request->input('email'),
                'primary' => $request->input('primary')
                ]
        ]);
        $contact->save();

        return response()->json(['message' => 'resource updated successfully']);
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
