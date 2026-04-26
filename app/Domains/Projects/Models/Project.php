<?php

namespace App\Domains\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'custom_domain', 'tracking_id', 'is_active', 'website_url', 'platform'];

    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\ProjectFactory::new();
    }
}
