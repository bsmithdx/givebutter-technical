<?php

namespace Tests\Feature\API\Contacts;

use App\Models\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests that sending a PATCH request with an id of a model that is not in the database results in a 404
     *
     * @return void
     */
    public function test_api_contact_patch_failed()
    {
        //Try to update a non-existent contact
        $response = $this->patchJson('api/contacts/4', [
            'id' => 4,
            'first' => 'new-first',
            'last' => 'new-last',
        ]);

        //assert that the resource is not found (404)
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'resource not found'
            ]);
    }


    /**
     * Tests that sending a PATCH request for an contact that DOES exist in the database results in that contact's
     * attributes (other than emails and phone-numbers) being correctly modified
     *
     * @return void
     */
    public function test_api_contact_patch_update_fulfilled()
    {
        //create new contact from factory
        $contact = contact::factory()
            ->create();
        //assert that our new models are alone in the database
        $this->assertDatabaseCount('contacts', 1);
        $response = $this->patchJson('api/contacts/1', [
            'id' => 1,
            'first' => 'new-first',
            'last' => 'new-last',
        ]);

        //assert that the model and database table reflect a change to the contacts attributes
        $contact->refresh();
        $this->assertDatabaseHas('contacts', [
            'id' => 1,
            'first' => 'new-first',
            'last' => 'new-last',
        ]);
        //assert that we receive an http status of 200
        $response
            ->assertOk()
            ->assertJson([
                'message' => 'resource updated successfully'
            ]);
    }
}
