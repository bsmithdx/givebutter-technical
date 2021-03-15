<?php

namespace Tests\Feature\API\Contacts\Emails;

use App\Models\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests that sending invalid data for setting emails for a contact via POST is unprocessable (422)
     *
     * @return void
     */
    public function test_api_contact_emails_post_failed()
    {
        //Try and set emails on a non-existent contact
        $response = $this->postJson('api/contacts/1/emails', [
            'first' => 'Sally',
            'last' => 'Jones',
        ]);

        //assert that the resource was not found (404)
        $response->assertStatus(404);
    }


    /**
     * Tests that sending all necessary data for setting emails for a contact via POST results in the emails being set (201)
     *
     * @return void
     */
    public function test_api_contact_emails_post_created()
    {
        //create new contact from factory
        $contact = contact::factory()
            ->create();
        //assert that our new models are alone in the database
        $this->assertDatabaseCount('contacts', 1);
        $response = $this->postJson('api/contacts/1/emails', [
            'emails' => [
                [
                    'email' => 'thirdoption@thirdplace.com',
                    'primary' => true,
                ],
                [
                    'email' => 'fourthoption@fourthplace.com',
                    'primary' => false,
                ]
            ],
        ]);

        //assert that the model and database table reflect a change to the contacts attributes
        $contact->refresh();
        $this->assertDatabaseHas('contacts', [
            'id' => 1,
            'emails' => json_encode([
                [
                    'email' => 'thirdoption@thirdplace.com',
                    'primary' => true,
                ],
                [
                    'email' => 'fourthoption@fourthplace.com',
                    'primary' => false,
                ]
            ]),
        ]);
        //assert that we receive an http status of 201
        $response
            ->assertCreated()
            ->assertJson([
                'emails' => [
                    [
                        'email' => 'thirdoption@thirdplace.com',
                        'primary' => true,
                    ],
                    [
                        'email' => 'fourthoption@fourthplace.com',
                        'primary' => false,
                    ]
                ],
            ]);

    }
}
