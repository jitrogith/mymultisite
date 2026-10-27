<?php

namespace Tests\Feature;

use App\Models\NavigationMenu;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_menus_and_nested_manual_and_page_items(): void
    {
        $this->get(route('admin.menus.index'))->assertRedirect(route('admin.login'));
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.menus.store'), ['name' => 'Menu Utama'])
            ->assertRedirect();
        $menu = NavigationMenu::query()->where('name', 'Menu Utama')->firstOrFail();

        $this->post(route('admin.menus.items.store', $menu), [
            'type' => 'manual',
            'label' => 'Informasi',
            'url' => null,
        ])->assertRedirect();
        $parent = $menu->items()->firstOrFail();

        $page = Page::query()->create([
            'title' => 'Profil Daerah',
            'slug' => 'profil-daerah',
            'summary' => null,
            'status' => 'published',
            'blocks' => [['type' => 'paragraph', 'text' => 'Tentang daerah']],
        ]);
        $this->post(route('admin.menus.items.store', $menu), [
            'type' => 'page',
            'page_id' => $page->id,
            'parent_id' => $parent->id,
        ])->assertRedirect();
        $child = $menu->items()->where('page_id', $page->id)->firstOrFail();
        $this->post(route('admin.menus.items.store', $menu), [
            'type' => 'manual',
            'label' => 'Portal Layanan',
            'url' => 'https://layanan.example.go.id',
            'parent_id' => $child->id,
        ])->assertRedirect();
        $grandchild = $menu->items()->where('label', 'Portal Layanan')->firstOrFail();

        $this->get(route('admin.menus.index', ['menu' => $menu->id]))
            ->assertOk()
            ->assertSee('Pembuat Menu')
            ->assertSee('Informasi')
            ->assertSee('Profil Daerah')
            ->assertSee('seret untuk mengurutkan');

        $this->get(route('admin.pages.visual-editor', $page))
            ->assertOk()
            ->assertSee('data-add-component="menu"', false)
            ->assertSee('menuOptions')
            ->assertSee('Pilih Menu')
            ->assertSee('Main Menu Items')
            ->assertSee('Dropdown Items')
            ->assertSee('Dropdown Panel')
            ->assertSee("headingSetting('Hover Background Color', 'menu_item_hover_background'", false)
            ->assertSee("headingSetting('Border Radius (px)', 'submenu_border_radius'", false);

        $this->postJson(route('admin.menus.items.reorder', $menu), [
            'items' => [$grandchild->id, $child->id, $parent->id],
        ])->assertOk()->assertJsonPath('message', 'Urutan menu berhasil disimpan.');
        $this->assertSame([$grandchild->id, $child->id, $parent->id], $menu->items()->pluck('id')->all());

        $this->actingAs($admin)->put(route('admin.pages.visual-editor.update', $page), [
            'blocks' => [
                ['type' => 'menu', 'text' => '', 'settings' => [
                    'menu_id' => (string) $menu->id,
                    'alignment' => 'center',
                    'text_color' => '#252f60',
                    'hide_mobile' => true,
                    'custom_css' => 'selector { letter-spacing: 1px; }',
                    'menu_item_color' => '#252f60',
                    'menu_item_hover_background' => '#eeae43',
                    'submenu_background' => '#ffffff',
                    'submenu_min_width' => 220,
                    'submenu_border_radius' => 6,
                    'submenu_shadow' => 'strong',
                ]],
            ],
        ])->assertRedirect(route('admin.pages.visual-editor', $page));

        $this->delete(route('admin.menus.destroy', $menu))
            ->assertSessionHasErrors('menu');
        $this->assertDatabaseHas('navigation_menus', ['id' => $menu->id]);

        $this->get(route('pages.show', $page->slug))
            ->assertOk()
            ->assertSee('aria-label="Menu Utama"', false)
            ->assertSee('Informasi')
            ->assertSee('Profil Daerah')
            ->assertSee('Portal Layanan')
            ->assertSee('https://layanan.example.go.id')
            ->assertSee('data-hide-mobile="true"', false)
            ->assertSee('letter-spacing: 1px', false)
            ->assertSee('--menu-item-color: #252f60', false)
            ->assertSee('--menu-item-hover-background: #eeae43', false)
            ->assertSee('--submenu-min-width: 220px', false)
            ->assertSee('--submenu-border-radius: 6px', false)
            ->assertSee('--submenu-shadow: 0 8px 20px #18204435', false)
            ->assertSee(route('pages.show', $page->slug), false);
    }

    public function test_menu_items_cannot_exceed_three_levels_or_form_a_cycle(): void
    {
        $this->actingAs($this->admin());
        $menu = NavigationMenu::query()->create(['name' => 'Menu Bertingkat']);
        $parentId = null;
        $items = [];

        foreach (['Satu', 'Dua', 'Tiga'] as $label) {
            $this->post(route('admin.menus.items.store', $menu), [
                'type' => 'manual',
                'label' => $label,
                'parent_id' => $parentId,
            ])->assertRedirect();
            $item = $menu->items()->where('label', $label)->firstOrFail();
            $items[] = $item;
            $parentId = $item->id;
        }

        $this->from(route('admin.menus.index', ['menu' => $menu->id]))
            ->post(route('admin.menus.items.store', $menu), [
                'type' => 'manual',
                'label' => 'Empat',
                'parent_id' => $parentId,
            ])
            ->assertSessionHasErrors('parent_id');

        $this->from(route('admin.menus.index', ['menu' => $menu->id]))
            ->put(route('admin.menus.items.update', [$menu, $items[0]]), [
                'type' => 'manual',
                'label' => 'Satu',
                'parent_id' => $items[2]->id,
            ])
            ->assertSessionHasErrors('parent_id');

        $this->assertSame(3, $menu->items()->count());
    }

    public function test_admin_can_rename_and_delete_menus_and_edit_and_delete_items(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.menus.store'), ['name' => 'Navigasi'])
            ->assertRedirect();
        $menu = NavigationMenu::query()->where('name', 'Navigasi')->firstOrFail();

        $this->put(route('admin.menus.update', $menu), ['name' => 'Navigasi Publik'])
            ->assertRedirect(route('admin.menus.index', ['menu' => $menu->id]));
        $this->assertDatabaseHas('navigation_menus', ['id' => $menu->id, 'name' => 'Navigasi Publik']);

        $this->post(route('admin.menus.items.store', $menu), [
            'type' => 'manual',
            'label' => 'Kontak',
            'url' => null,
        ])->assertRedirect();
        $item = $menu->items()->firstOrFail();
        $this->put(route('admin.menus.items.update', [$menu, $item]), [
            'type' => 'manual',
            'label' => 'Hubungi Kami',
            'url' => 'https://example.go.id/kontak',
        ])->assertRedirect();
        $this->assertDatabaseHas('navigation_menu_items', [
            'id' => $item->id,
            'label' => 'Hubungi Kami',
            'url' => 'https://example.go.id/kontak',
        ]);

        $this->delete(route('admin.menus.items.destroy', [$menu, $item]))
            ->assertRedirect(route('admin.menus.index', ['menu' => $menu->id]));
        $this->delete(route('admin.menus.destroy', $menu))->assertRedirect(route('admin.menus.index'));
        $this->assertDatabaseMissing('navigation_menus', ['id' => $menu->id]);
    }

    private function admin(): User
    {
        return User::factory()->create([
            'username' => 'menu-admin',
            'is_admin' => true,
        ]);
    }
}
