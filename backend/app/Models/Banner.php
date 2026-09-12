<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'title_gradient',
        'subtitle',
        'description',
        'image',
        'cta_text',
        'cta_link',
        'cta_2_text',
        'cta_2_link',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
