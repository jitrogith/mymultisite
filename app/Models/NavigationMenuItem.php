<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NavigationMenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'page_id',
        'type',
        'label',
        'url',
        'position',
    ];

    protected function casts(): array
    {
        return ['position' => 'integer'];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(NavigationMenu::class, 'menu_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
