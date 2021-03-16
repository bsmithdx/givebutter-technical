<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //validate json payload for new contact
        //TODO: validate duplication of contacts
        //TODO: validate emails (only allow one primary)
        //TODO: validate phone_numbers (only allow one primary)
        $validated = $request->validate([
            'first' => 'required|string',
            'last' => 'required|string',
            'emails' => 'required|array|min:1',
            'emails.*.email' => 'required|email',
            'emails.*.primary' => 'required|boolean',
            'phone_numbers' => 'required|array|min:1',
            'phone_numbers.*.phone' => 'required|string|min:10|max:15',
            'phone_numbers.*.primary' => 'required|boolean',
        ]);
        //create new contact
        $contact = Contact::create($request->all());
        return response()->json(['id' => $contact->id], 201);
    }

    /**
     * Update the specified resource in storage.
     * NOTE: Only allows updating of first and last name attributes
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $contactId
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
        //TODO: validate duplication of contacts
        $validated = $request->validate([
            'first' => 'string',
            'last' => 'string',
        ]);
        //modify the contacts attributes
        $contact->fill($request->all());
        $contact->save();

        return response()->json(['message' => 'resource updated successfully']);
    }

    /**
     * Merge the specified contact with another contact by id.
     *
     * @param  \App\Models\Contact  $contact
     * @param int $contactId
     * @return \Illuminate\Http\Response
     */
    public function merge(int $contactId)
    {
        //
    }
}
