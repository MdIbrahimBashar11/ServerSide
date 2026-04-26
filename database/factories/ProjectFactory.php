<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domains\Projects\Models\Project;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domains\Projects\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $domain = $this->faker->domainName();
        return [
            'name' => $this->faker->company(),
            'custom_domain' => 'trk.' . $domain,
            'tracking_id' => 'trk_' . strtoupper(Str::random(12)),
            'is_active' => true,
            'website_url' => 'https://www.' . $domain,
            'platform' => $this->faker->randomElement(['laravel', 'wordpress', 'shopify', 'custom']),
        ];
    }
}
