<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first' => $this->faker->firstName,
            'last' => $this->faker->lastName,
            'emails' => [
                [
                    'email' => $this->faker->email,
                    'primary' => true,
                ],
                [
                    'email' => $this->faker->email,
                    'primary' => false,
                ],
            ],
            'phone-numbers' => [
                [
                    'phone' => $this->faker->phoneNumber,
                    'primary' => true,
                ],
                [
                    'phone' => $this->faker->phoneNumber,
                    'primary' => false,
                ],
            ],
        ];
    }
}
