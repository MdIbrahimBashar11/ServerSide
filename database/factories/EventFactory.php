<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domains\Projects\Models\Event;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $timestamp = $this->faker->dateTimeBetween('-7 days', 'now');
        
        return [
            'event_id' => 'evt_' . Str::random(12),
            'event_name' => $this->faker->randomElement(['PageView', 'AddToCart', 'Purchase', 'ViewContent', 'InitiateCheckout']),
            'event_time' => $timestamp,
            'user_data' => [
                'client_ip_address' => $this->faker->ipv4(),
                'client_user_agent' => $this->faker->userAgent(),
                'fbp' => 'fb.1.' . time() . '.' . mt_rand(100000000, 999999999),
                'fbc' => 'fb.1.' . time() . '.' . Str::random(10),
            ],
            'custom_data' => [
                'currency' => 'USD',
                'value' => mt_rand(10, 500)
            ],
            'source' => 'browser',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
    }
}
