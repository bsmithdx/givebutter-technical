<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class PhoneController extends Controller
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
        //TODO: validate phone numbers (only allow one primary)
        $validated = $request->validate([
            'phone_numbers.*.phone' => 'required|string|min:10|max:15',
            'phone_numbers.*.primary' => 'required|boolean',
        ]);
        //set the contact's phone numbers
        $contact->phone_numbers = $request->input('phone_numbers');
        $contact->save();
        return response()->json(['phone_numbers' => $contact->phone_numbers], 201);
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
            'phone' => 'required|string|min:10|max:15',
            'primary' => 'required|boolean',
        ]);
        if ($request->input('primary')) {
            //TODO: return 422 if there is already a primary phone number set
        }
        //TODO: handle duplicate phone number (don't add, but allow changing of primary value)
        //modify the contacts attributes
        $contact->phone_numbers = array_merge($contact->phone_numbers, [
            [
                'phone' => $request->input('phone'),
                'primary' => $request->input('primary')
                ]
        ]);
        $contact->save();

        return response()->json(['message' => 'resource updated successfully']);
    }

    /**
     * Remove the specified phone number from the contact.
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
            'phone' => 'required|string|min:10|max:15',
        ]);
        //filter the contacts phone numbers to remove the passed phone number and then re-index by value (to ensure json column data format)
        $contact->phone_numbers = array_values(array_filter($contact->phone_numbers, fn($v) => $v['phone'] !== $request->input('phone')));
        $contact->save();

        return response()->json(['message' => 'resource deleted successfully']);
    }
}
