<?php

namespace Tests\Feature\API\Contacts\Emails;

use App\Models\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    //TODO: Add unit test for trying to delete email that isn't associated with contact

    /**
     * Tests that sending a DELETE request for the email of a non-existent contact results in a 404
     *
     * @return void
     */
    public function test_api_contact_email_delete_failed()
    {
        //Try to update a non-existent Contact
        $response = $this->delete('api/contacts/4/emails');

        //assert that the resource is not found (404)
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'resource not found'
            ]);
    }


    /**
     * Tests that sending a DELETE request for an email of a contact in the database results in the email being deleted and returning 200
     *
     * @return void
     */
    public function test_api_contact_email_delete_success()
    {
        //create new contact from factory
        $contact = contact::factory()
            ->create();
        $firstEmail = $contact->emails[0];
        $secondEmailAddress = $contact->emails[1]['email'];
        //assert that our new models are alone in the database
        $this->assertDatabaseCount('contacts', 1);
        //test delete request
        $response = $this->delete('api/contacts/1/emails', [
            'email' => $secondEmailAddress,
        ]);
        //assert that the contact's email was deleted
        $contact->refresh();
        $this->assertDatabaseHas('contacts', [
            'id' => 1,
            'emails' => json_encode([
                $firstEmail,
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
