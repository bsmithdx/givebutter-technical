<?php

namespace Tests\Feature\API\Contacts\PhoneNumbers;

use App\Models\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests that sending invalid data for setting phone numbers for a contact via POST is unprocessable (422)
     *
     * @return void
     */
    public function test_api_contact_phone_post_failed()
    {
        //Try and set phone numbers on a non-existent contact
        $response = $this->postJson('api/contacts/1/phone-numbers', [
            'phone_numbers' => [
                [
                    'phone' => '8023456789',
                    'primary' => true,
                ],
                [
                    'phone' => '8029876543',
                    'primary' => false,
                ]
            ],
        ]);

        //assert that the resource is not found (404)
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'resource not found'
            ]);
    }


    /**
     * Tests that sending all necessary data for setting phone numbers for a contact via POST results in the phone numbers being set (201)
     *
     * @return void
     */
    public function test_api_contact_phone_post_created()
    {
        //create new contact from factory
        $contact = contact::factory()
            ->create();
        //assert that our new models are alone in the database
        $this->assertDatabaseCount('contacts', 1);
        $response = $this->postJson('api/contacts/1/phone-numbers', [
            'phone_numbers' => [
                [
                    'phone' => '8023456789',
                    'primary' => true,
                ],
                [
                    'phone' => '8029876543',
                    'primary' => false,
                ]
            ],
        ]);

        //assert that the model and database table reflect a change to the contacts attributes
        $contact->refresh();
        $this->assertDatabaseHas('contacts', [
            'id' => 1,
            'phone_numbers' => json_encode([
                [
                    'phone' => '8023456789',
                    'primary' => true,
                ],
                [
                    'phone' => '8029876543',
                    'primary' => false,
                ]
            ]),
        ]);
        //assert that we receive an http status of 201
        $response
            ->assertCreated()
            ->assertJson([
                'phone_numbers' => [
                    [
                        'phone' => '8023456789',
                        'primary' => true,
                    ],
                    [
                        'phone' => '8029876543',
                        'primary' => false,
                    ]
                ],
            ]);

    }
}
