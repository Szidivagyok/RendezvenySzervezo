<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
     protected function withFaker()
    {
        return \Faker\Factory::create('hu_HU');
    }
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $number = 0;
        $current = $number;   // eltesszük az aktuális értéket
        $number++;

        return [
            'name' => 'Megrendelo' . $current,
            'email' => 'megrendelo' . $current . "@gmail.com",
            'password' => Hash::make('123'),
            'role' => 2,
            'idNumber' => strtoupper($this->faker->bothify('??######')),
            'city' => $this->faker->city(),
            'street' => $this->faker->streetName(),
            'houseNumber' => $this->faker->numberBetween(1, 200),
            'postCode' => $this->faker->postcode(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
