<?php

namespace Tests\Feature\API\Contacts;

use App\Models\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MergeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests that trying to merge into a contact that doesn't exist results in 404
     *
     * @return void
     */
    public function test_api_contact_merge_failed_no_resource()
    {
        //include all required data for a contact merge
        $response = $this->postJson('api/contacts/5/merge', [
            'contact_to_merge_id' => 2
        ]);

        //assert that specified resource is not found (404)
        $response->assertStatus(404);
    }

    /**
     * Tests that sending invalid data for merging a contact via POST is unprocessable (422)
     *
     * @return void
     */
    public function test_api_contact_merge_failed_no_id()
    {
        //create new contact from factory
        $contact = contact::factory()
            ->create();
        //assert that our new models are alone in the database
        $this->assertDatabaseCount('contacts', 1);
        //Don't include all required data for a contact
        $response = $this->postJson('api/contacts/1/merge', [
        ]);

        //assert that the request is unprocessable (422)
        $response->assertStatus(422);
    }

    /**
     * Tests that trying to merge from a contact that doesn't exist results in 422
     *
     * @return void
     */
    public function test_api_contact_merge_failed_merge_from_doesnt_exist()
    {
        //create new contact from factory
        $contact = contact::factory()
            ->create();
        //assert that our new models are alone in the database
        $this->assertDatabaseCount('contacts', 1);
        //Don't include all required data for a contact
        $response = $this->postJson('api/contacts/1/merge', [
            'contact_to_merge_id' => 2
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
        //create first contact from factory
        $firstContact = Contact::create([
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
            ]
        ]
        );
        //create second contact from factory to merge into the first
        $secondContact = Contact::create([
                'first' => 'Sally',
                'last' => 'Jonez',
                'emails' => [
                    [
                        'email' => 'sally.jones@firstplace.com',
                        'primary' => true,
                    ],
                    [
                        'email' => 'sally.jonez@secondplace.com',
                        'primary' => false,
                    ],
                ],
                'phone_numbers' => [
                    [
                        'phone' => '8029881983',
                        'primary' => true,
                    ],
                    [
                        'phone' => '8026782379',
                        'primary' => false,
                    ],
                ]
            ]
        );
        //assert that our new models are alone in the database
        $this->assertDatabaseCount('contacts', 2);
        $response = $this->postJson('api/contacts/1/merge', [
            'contact_to_merge_id' => 2,
        ]);
        $this->assertDeleted($secondContact);
        $this->assertDatabaseHas('contacts', [
            'id' => 1,
            'first' => 'Sally',
            'last' => 'Jones',
            'emails' => json_encode([
                [
                    'email' => 'sally.jones@firstplace.com',
                    'primary' => true,
                ],
                [
                    'email' => 'sally.jones@secondplace.com',
                    'primary' => false,
                ],
                [
                    'email' => 'sally.jonez@secondplace.com',
                    'primary' => false,
                ],
            ]),
            'phone_numbers' => json_encode([
                [
                    'phone' => '8029881983',
                    'primary' => true,
                ],
                [
                    'phone' => '8026782378',
                    'primary' => false,
                ],
                [
                    'phone' => '8026782379',
                    'primary' => false,
                ],
            ])
        ]);
        //assert that we receive an http status of created (200) and the id of the merged contact
        $response
            ->assertOk()
            ->assertJson([
                'id' => 1
            ]);

    }
}
