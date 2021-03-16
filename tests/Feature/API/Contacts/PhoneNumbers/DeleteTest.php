<?php

namespace Tests\Feature\API\Contacts\PhoneNumbers;

use App\Models\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    //TODO: Add unit test for trying to delete phone number that isn't associated with contact

    /**
     * Tests that sending a DELETE request for the phone number of a non-existent contact results in a 404
     *
     * @return void
     */
    public function test_api_contact_phone_delete_failed()
    {
        //Try to update a non-existent Contact
        $response = $this->delete('api/contacts/4/phone-numbers');

        //assert that the resource is not found (404)
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'resource not found'
            ]);
    }


    /**
     * Tests that sending a DELETE request for a phone number of a contact in the database results in the phone number being deleted and returning 200
     *
     * @return void
     */
    public function test_api_contact_phone_delete_success()
    {
        //create new contact from factory
        $contact = contact::factory()
            ->create();
        $firstPhoneObject = $contact->phone_numbers[0];
        //print_r($firstPhoneObject);
        $secondPhoneNumber = $contact->phone_numbers[1]['phone'];
        //print($secondPhoneNumber);
        //assert that our new models are alone in the database
        $this->assertDatabaseCount('contacts', 1);
        //test delete request
        $response = $this->delete('api/contacts/1/phone-numbers', [
            'phone' => $secondPhoneNumber,
        ]);
        //assert that the contact's phone number was deleted
        $contact->refresh();
        $this->assertDatabaseHas('contacts', [
            'id' => 1,
            'phone_numbers' => json_encode([
                $firstPhoneObject,
            ]),
        ]);
        //assert that we receive an http status of 200
        $response
            ->assertOk()
            ->assertJson([
                'message' => 'resource deleted successfully'
            ]);
    }
}
