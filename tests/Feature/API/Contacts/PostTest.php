<?php

namespace Tests\Feature\API\Contacts;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests that sending invalid data for an contact via POST is unprocessable (422)
     *
     * @return void
     */
    public function test_api_contact_post_failed()
    {
        //Don't include all required data for a contact
        $response = $this->postJson('api/contacts', [
            'first' => 'Sally',
            'last' => 'Jones',
        ]);

        //assert that the request is unprocessable (422)
        $response->assertStatus(422);
    }


    /**
     * Tests that sending all necessary data for a contact via POST results in a contact being created (201)
     *
     * @return void
     */
    public function test_api_contact_post_created()
    {
        $response = $this->postJson('api/contacts', [
            'first' => 'Sally',
            'last' => 'Jones',
            'emails' => [
                [
                    'email' => 'sally.jones@firstplace.com',
                    'primary' => true,
                ],
                [
                    'email' => 'sally.jones@secondplace.com',
                    'primary' => false,
                ],
            ],
            'phone_numbers' => [
                [
                    'phone' => '8029881983',
                    'primary' => true,
                ],
                [
                    'phone' => '8026782378',
                    'primary' => false,
                ],
            ],
        ]);
        //assert that we receive an http status of created (201) and the id of the newly created contact
        $response
            ->assertCreated()
            ->assertJson([
                'id' => 1
            ]);

    }
}
