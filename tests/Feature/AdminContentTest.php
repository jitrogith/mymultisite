<?php

namespace Tests\Feature;

use App\Models\SiteContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_content_page_requires_an_admin_login(): void
    {
        $this->get(route('admin.content.edit'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Masuk administrator');
    }

    public function test_admin_can_save_and_publish_homepage_content(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'is_admin' => true,
        ]);
        $content = SiteContent::defaults();
        $content['site_name'] = 'Kabupaten Contoh';
        $content['services'] = [
            ['title' => 'Layanan Baru', 'description' => 'Deskripsi layanan baru.'],
        ];

        $this->actingAs($admin)
            ->put(route('admin.content.update'), $content)
            ->assertRedirect(route('admin.content.edit'))
            ->assertSessionHas('status');

        $this->assertSame('Kabupaten Contoh', SiteContent::current()['site_name']);
        $this->assertCount(1, SiteContent::current()['services']);
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Kabupaten Contoh')
            ->assertSee('Layanan Baru');
    }

    public function test_non_admin_user_cannot_access_content_editor(): void
    {
        $user = User::factory()->create([
            'username' => 'public-user',
            'is_admin' => false,
        ]);

        $this->actingAs($user)
            ->get(route('admin.content.edit'))
            ->assertForbidden();
    }

    public function test_admin_can_sign_in_and_sign_out(): void
    {
        User::factory()->create([
            'username' => 'admin',
            'is_admin' => true,
            'password' => 'correct-password',
        ]);

        $this->post(route('admin.login.store'), [
            'username' => 'admin',
            'password' => 'correct-password',
        ])->assertRedirect(route('admin.content.edit'));

        $this->get(route('admin.content.edit'))->assertOk();

        $this->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.content.edit'))
            ->assertRedirect(route('admin.login'));
    }
}
