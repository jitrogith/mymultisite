<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NavigationMenu extends Model
{
    protected $fillable = ['name'];

    public function items(): HasMany
    {
        return $this->hasMany(NavigationMenuItem::class, 'menu_id')->orderBy('position');
    }

    public function tree(): array
    {
        $items = $this->relationLoaded('items') ? $this->items : $this->items()->with('page')->get();
        $children = $items->groupBy(fn (NavigationMenuItem $item) => $item->parent_id ?? 0);

        $build = function (int $parentId, int $depth = 1) use (&$build, $children): array {
            if ($depth > 3) {
                return [];
            }

            return $children->get($parentId, new Collection)
                ->map(function (NavigationMenuItem $item) use (&$build, $depth): array {
                    $page = $item->page;

                    return [
                        'id' => $item->id,
                        'label' => $item->type === 'page' && $page ? $page->title : $item->label,
                        'url' => $item->type === 'page' && $page ? route('pages.show', $page->slug) : $item->url,
                        'children' => $build($item->id, $depth + 1),
                    ];
                })
                ->all();
        };

        return $build(0);
    }
}
