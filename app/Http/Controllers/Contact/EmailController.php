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
        $validated = $request->validate([
            'email' => 'required|email',
            'primary' => 'required|boolean',
        ]);
        if ($request->input('primary')) {
            //TODO: return 422 if there is already a primary email set
        }
        //TODO: handle duplicate email (don't add, but allow changing of primary value)
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
     * Remove the specified email from the contact.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $contactId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $contactId)
    {
        //validate that the contact exists
        $contact = Contact::find($contactId);
        if(!$contact) {
            return response()->json(['message' => 'resource not found'], 404);
        }
        //validate the JSON payload
        $validated = $request->validate([
            'email' => 'required|email',
        ]);
        //filter the contacts emails to remove the passed email and then re-index by value (to ensure json column data format)
        $contact->emails = array_values(array_filter($contact->emails, fn($v) => $v['email'] !== $request->input('email')));
        $contact->save();

        return response()->json(['message' => 'resource deleted successfully']);
    }
}
