<?php

namespace Tests\Feature\API\Contacts\Emails;

use App\Models\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests that sending a PATCH request with an id of a contact that is not in the database results in a 404
     *
     * @return void
     */
    public function test_api_contact_emails_patch_failed()
    {
        //Try and set emails on a non-existent contact
        $response = $this->postJson('api/contacts/1/emails', [
                    'email' => 'thirdoption@thirdplace.com',
                    'primary' => false,
        ]);

        //assert that the resource is not found (404)
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'resource not found'
            ]);
    }


    /**
     * Tests that sending a PATCH request for a contact's emails results in adding a new email for that contact
     *
     * @return void
     */
    public function test_api_contact_emails_patch_success()
    {
        //create new contact from factory
        $contact = contact::factory()
            ->create();
        //store the contact's current emails
        $currentEmails = $contact->emails;
        //assert that our new models are alone in the database
        $this->assertDatabaseCount('contacts', 1);
        $response = $this->patchjson('api/contacts/1/emails', [
            'email' => 'thirdoption@thirdplace.com',
            'primary' => false,
        ]);

        //assert that the model and database table reflect a change to the contacts attributes
        $contact->refresh();
        $this->assertDatabaseHas('contacts', [
            'id' => 1,
            'emails' => json_encode(array_merge($currentEmails,[
                [
                    'email' => 'thirdoption@thirdplace.com',
                    'primary' => false,
                ]
            ])),
        ]);
        //assert that we receive an http status of 200
        $response
            ->assertOk()
            ->assertJson([
                'message' => 'resource updated successfully'
            ]);
    }
}
