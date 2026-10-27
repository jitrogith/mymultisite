<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavigationMenu;
use App\Models\NavigationMenuItem;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $menus = NavigationMenu::query()->with(['items.page'])->orderBy('name')->get();
        $selectedMenu = $request->integer('menu')
            ? $menus->firstWhere('id', $request->integer('menu'))
            : $menus->first();
        $items = $selectedMenu?->items ?? collect();

        return view('admin.menus.index', [
            'menus' => $menus,
            'selectedMenu' => $selectedMenu,
            'items' => $items,
            'pages' => Page::query()->orderBy('title')->get(),
            'editingItem' => $request->integer('edit_item')
                ? $items->firstWhere('id', $request->integer('edit_item'))
                : null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:navigation_menus,name'],
        ]);
        $menu = NavigationMenu::query()->create($validated);

        return redirect()->route('admin.menus.index', ['menu' => $menu->id])
            ->with('status', 'Menu berhasil dibuat.');
    }

    public function update(Request $request, NavigationMenu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('navigation_menus', 'name')->ignore($menu->id)],
        ]);
        $menu->update($validated);

        return redirect()->route('admin.menus.index', ['menu' => $menu->id])
            ->with('status', 'Nama menu berhasil diperbarui.');
    }

    public function destroy(NavigationMenu $menu): RedirectResponse
    {
        $menuId = (string) $menu->id;
        $usedByPages = Page::query()
            ->get(['title', 'blocks'])
            ->filter(fn (Page $page) => collect($page->blocks ?? [])
                ->contains(fn ($block) => is_array($block)
                    && ($block['type'] ?? null) === 'menu'
                    && (string) ($block['settings']['menu_id'] ?? '') === $menuId))
            ->pluck('title')
            ->all();

        if ($usedByPages !== []) {
            return back()->withErrors([
                'menu' => 'Menu ini masih digunakan pada halaman: '.implode(', ', $usedByPages).'. Hapus widget Menu dari halaman tersebut terlebih dahulu.',
            ]);
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')->with('status', 'Menu berhasil dihapus.');
    }

    public function storeItem(Request $request, NavigationMenu $menu): RedirectResponse
    {
        $validated = $this->validatedItem($request, $menu);
        $this->assertValidTree($menu, null, $validated['parent_id'] ?? null);
        $page = ($validated['type'] ?? null) === 'page' ? Page::query()->findOrFail($validated['page_id']) : null;

        $menu->items()->create([
            'parent_id' => $validated['parent_id'] ?? null,
            'page_id' => $page?->id,
            'type' => $validated['type'],
            'label' => $page?->title ?? $validated['label'],
            'url' => $page ? null : ($validated['url'] ?? null),
            'position' => ((int) $menu->items()->max('position')) + 1,
        ]);

        return redirect()->route('admin.menus.index', ['menu' => $menu->id])
            ->with('status', 'Isi menu berhasil ditambahkan.');
    }

    public function updateItem(Request $request, NavigationMenu $menu, NavigationMenuItem $item): RedirectResponse
    {
        abort_unless($item->menu_id === $menu->id, 404);
        $validated = $this->validatedItem($request, $menu);
        $this->assertValidTree($menu, $item, $validated['parent_id'] ?? null);
        $page = ($validated['type'] ?? null) === 'page' ? Page::query()->findOrFail($validated['page_id']) : null;

        $item->update([
            'parent_id' => $validated['parent_id'] ?? null,
            'page_id' => $page?->id,
            'type' => $validated['type'],
            'label' => $page?->title ?? $validated['label'],
            'url' => $page ? null : ($validated['url'] ?? null),
        ]);

        return redirect()->route('admin.menus.index', ['menu' => $menu->id])
            ->with('status', 'Isi menu berhasil diperbarui.');
    }

    public function destroyItem(NavigationMenu $menu, NavigationMenuItem $item): RedirectResponse
    {
        abort_unless($item->menu_id === $menu->id, 404);
        $item->delete();

        return redirect()->route('admin.menus.index', ['menu' => $menu->id])
            ->with('status', 'Isi menu dan submenu di bawahnya berhasil dihapus.');
    }

    public function reorderItems(Request $request, NavigationMenu $menu)
    {
        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*' => ['required', 'integer', 'distinct'],
        ]);
        $expected = $menu->items()->pluck('id')->map(fn ($id) => (int) $id)->sort()->values()->all();
        $received = collect($validated['items'])->map(fn ($id) => (int) $id)->sort()->values()->all();

        if ($expected !== $received) {
            throw ValidationException::withMessages([
                'items' => 'Urutan menu tidak valid. Muat ulang halaman lalu coba lagi.',
            ]);
        }

        DB::transaction(function () use ($menu, $validated): void {
            foreach ($validated['items'] as $position => $id) {
                $menu->items()->whereKey($id)->update(['position' => $position]);
            }
        });

        return response()->json(['message' => 'Urutan menu berhasil disimpan.']);
    }

    private function validatedItem(Request $request, NavigationMenu $menu): array
    {
        return $request->validate([
            'type' => ['required', Rule::in(['manual', 'page'])],
            'label' => ['required_if:type,manual', 'nullable', 'string', 'max:160'],
            'url' => ['nullable', 'url:http,https', 'max:2048'],
            'page_id' => ['required_if:type,page', 'nullable', 'integer', Rule::exists('pages', 'id')],
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('navigation_menu_items', 'id')->where('menu_id', $menu->id),
            ],
        ]);
    }

    private function assertValidTree(NavigationMenu $menu, ?NavigationMenuItem $item, ?int $parentId): void
    {
        $parents = $menu->items()->pluck('parent_id', 'id')->map(fn ($id) => $id === null ? null : (string) $id)->all();
        $key = $item ? (string) $item->id : 'new';
        $parents[$key] = $parentId === null ? null : (string) $parentId;
        $depthFor = function (string $id, array $path = []) use (&$depthFor, $parents): int {
            if (isset($path[$id])) {
                throw ValidationException::withMessages(['parent_id' => 'Susunan menu tidak boleh membentuk lingkaran.']);
            }
            $path[$id] = true;
            $parent = $parents[$id] ?? null;

            return $parent === null ? 1 : 1 + $depthFor($parent, $path);
        };

        foreach (array_keys($parents) as $id) {
            if ($depthFor((string) $id) > 3) {
                throw ValidationException::withMessages(['parent_id' => 'Menu hanya boleh memiliki maksimal 3 lapis.']);
            }
        }
    }
}
