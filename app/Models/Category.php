<?php

namespace App\Models;

use App\Models\Concerns\HasCatalogIcon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasCatalogIcon;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function subCategories(): HasMany
    {
        return $this->hasMany(SubCategory::class);
    }
}
