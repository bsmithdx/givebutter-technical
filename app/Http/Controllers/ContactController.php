<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //validate json payload for new order
        //TODO: validate duplication of contacts
        $validated = $request->validate([
            'first' => 'required|string',
            'last' => 'required|string',
            'emails' => 'required|array|min:1',
            'emails.*.email' => 'required|email',
            'emails.*.primary' => 'required|boolean',
            'phone-numbers' => 'required|array|min:1',
            'phone-numbers.*.phone' => 'required|string|min:10|max:11',
            'phone-numbers.*.primary' => 'required|boolean',
        ]);
        //create new contact
        $contact = Contact::create($request->all());
        return response()->json(['id' => $contact->id], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $contactId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $contactId)
    {
        //validate the JSON payload
        //TODO: validate duplication of contacts
        $validated = $request->validate([
            'first' => 'string',
            'last' => 'string',
        ]);
        //validate that the contact exists
        $contact = Contact::find($contactId);
        if(!$contact) {
            return response()->json(['message' => 'resource not found'], 404);
        }
        //modify the contacts attributes
        $contact->fill($request->all());
        $contact->save();

        return response()->json(['message' => 'resource updated successfully']);
    }

    /**
     * Merge the specified contact with another contact by id.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function merge(Contact $contact)
    {
        //
    }
}
