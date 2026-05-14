<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditPackage extends Model
{
    protected $fillable = [
        'name',
        'price',
        'credits',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'credits' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
