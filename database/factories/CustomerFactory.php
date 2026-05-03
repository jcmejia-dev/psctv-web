<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use App\Models\User;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'user_id'        => User::factory(), // ✅ auto-create linked User
            'account_number' => 'ACC-' . strtoupper($this->faker->bothify('####??')),
            'status'         => $this->faker->randomElement(['active', 'inactive', 'suspended']),
            'address'        => $this->faker->streetAddress(),
            'city'           => $this->faker->city(),
            'province'       => $this->faker->state(),
            'zip_code'       => $this->faker->postcode(),
            'gender'         => $this->faker->randomElement(['male', 'female', 'other']),
            'dob'            => $this->faker->date(),
            'phone'          => $this->faker->phoneNumber(),
            'plan'           => $this->faker->randomElement(['Basic', 'Pro', 'Premium']),
            'meta'           => json_encode([
                'notes' => $this->faker->sentence(),
            ]),
        ];
    }
}
