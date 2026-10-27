<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'blocks',
        'status',
        'content_width_mode',
        'content_width',
    ];

    protected function casts(): array
    {
        return [
            'blocks' => 'array',
            'content_width' => 'integer',
        ];
    }
}
