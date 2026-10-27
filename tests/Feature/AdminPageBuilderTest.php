<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\User;
use App\Support\VideoEmbedUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPageBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_page_builder_is_protected_and_lists_the_homepage(): void
    {
        $this->get(route('admin.pages.index'))
            ->assertRedirect(route('admin.login'));

        $this->actingAs($this->admin())
            ->get(route('admin.pages.index'))
            ->assertOk()
            ->assertSee('Page Builder')
            ->assertSee('Beranda')
            ->assertSee('Buat halaman');
    }

    public function test_admin_can_create_and_publish_a_page_with_content_blocks(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.pages.store'), [
                'title' => 'Profil Daerah',
                'slug' => 'profil-daerah',
                'summary' => 'Informasi profil daerah.',
                'status' => 'published',
                'blocks' => [
                    ['type' => 'heading', 'text' => 'Sejarah'],
                    ['type' => 'paragraph', 'text' => 'Kabupaten ini berdiri untuk melayani warga.'],
                    ['type' => 'list', 'text' => "Utara\nSelatan"],
                    ['type' => 'quote', 'text' => 'Melayani masyarakat dengan baik.'],
                ],
            ])
            ->assertRedirect();

        $page = Page::query()->where('slug', 'profil-daerah')->firstOrFail();

        $this->assertSame('published', $page->status);
        $this->get(route('pages.show', $page->slug))
            ->assertOk()
            ->assertSee('Profil Daerah')
            ->assertSee('Kabupaten ini berdiri untuk melayani warga.')
            ->assertSee('<li>Utara</li>', false);
        $this->get(route('home'))->assertSee('Profil Daerah');
    }

    public function test_admin_can_edit_slug_and_delete_page(): void
    {
        $admin = $this->admin();
        $page = Page::query()->create([
            'title' => 'Halaman Lama',
            'slug' => 'halaman-lama',
            'summary' => null,
            'status' => 'draft',
            'blocks' => [['type' => 'paragraph', 'text' => 'Isi awal']],
        ]);

        $this->actingAs($admin)
            ->put(route('admin.pages.update', $page), [
                'title' => 'Halaman Baru',
                'slug' => 'halaman-baru',
                'summary' => 'Ringkasan baru',
                'status' => 'published',
                'blocks' => [['type' => 'paragraph', 'text' => 'Isi baru']],
            ])
            ->assertRedirect(route('admin.pages.edit', $page));

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'slug' => 'halaman-baru',
            'status' => 'published',
        ]);
        $this->get(route('pages.show', 'halaman-baru'))->assertOk()->assertSee('Isi baru');

        $this->delete(route('admin.pages.destroy', $page))
            ->assertRedirect(route('admin.pages.index'));

        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
        $this->get(route('pages.show', 'halaman-baru'))->assertNotFound();
    }

    public function test_admin_can_save_visual_editor_components_and_render_them_publicly(): void
    {
        $admin = $this->admin();
        $page = Page::query()->create([
            'title' => 'Layanan Publik',
            'slug' => 'layanan-publik',
            'summary' => 'Informasi layanan.',
            'status' => 'published',
            'blocks' => [['type' => 'paragraph', 'text' => 'Konten awal']],
        ]);

        $editorResponse = $this->actingAs($admin)
            ->get(route('admin.pages.visual-editor', $page))
            ->assertOk();

        $editorResponse
            ->assertSee('VEditor')
            ->assertSee('Kabupaten Nusantara')
            ->assertSee('Google Maps')
            ->assertSee('Hide On Desktop')
            ->assertSee('Hide On Tablet Portrait')
            ->assertSee('Hide On Mobile Portrait')
            ->assertSee('Custom CSS')
            ->assertSee('Global Setting')
            ->assertSee('Lebar halaman')
            ->assertSee('Stretch full lebar')
            ->assertSee('Lebar maksimum (px)')
            ->assertSee("headingSetting('Z-Index', 'z_index'", false)
            ->assertSee('global-settings-group', false)
            ->assertSee('id="componentPanel"', false)
            ->assertSee('id="settingsPanel"', false)
            ->assertSee('id="backToWidgets"', false)
            ->assertSee('id="panelToggle"', false)
            ->assertSee('aria-label="Sembunyikan panel widget"', false)
            ->assertSee('grid-template-columns: 320px 32px minmax(0, 1fr)', false)
            ->assertSee('.editor-body.show-settings .component-panel { display: none; }', false)
            ->assertSee('.editor-body.show-settings .settings-panel { display: block; }', false)
            ->assertSee('let selectedIndex = -1;', false)
            ->assertSee('.preview-block.paragraph, .preview-block.text { color: #48516a; font-size: 13px; line-height: 1.9;', false)
            ->assertSee('.preview-block.quote { margin: 25px 0; padding: 16px 20px;', false)
            ->assertSee('css.replace(/\\bselector\\b/gi, `#veditor-block-${index}`)', false)
            ->assertSee('window.innerWidth', false)
            ->assertSee('pageCanvas.style.zoom', false);

        $editorHtml = $editorResponse->getContent();
        $globalSettingsPosition = strpos($editorHtml, 'global-settings-group');
        $basicPosition = strpos($editorHtml, '>Basic</h3>');
        $layoutPosition = strpos($editorHtml, 'tabContent.append(advancedOptions[0])');
        $backgroundPosition = strpos($editorHtml, "tabContent.append(advancedPanel('Background', background))");
        $borderPosition = strpos($editorHtml, "tabContent.append(advancedPanel('Border', border))");
        $responsivePosition = strpos($editorHtml, 'advancedOptions.slice(1).forEach');
        $this->assertNotFalse($globalSettingsPosition);
        $this->assertNotFalse($basicPosition);
        $this->assertLessThan($basicPosition, $globalSettingsPosition);
        $this->assertNotFalse($layoutPosition);
        $this->assertNotFalse($backgroundPosition);
        $this->assertNotFalse($borderPosition);
        $this->assertNotFalse($responsivePosition);
        $this->assertLessThan($backgroundPosition, $layoutPosition);
        $this->assertLessThan($borderPosition, $backgroundPosition);
        $this->assertLessThan($responsivePosition, $borderPosition);

        $blocks = [
            [
                'type' => 'heading',
                'text' => 'Layanan Terpadu',
                'level' => 3,
                'settings' => [
                    'link' => 'https://layanan.example.test/profil',
                    'html_tag' => 'h1',
                    'alignment' => 'center',
                    'font_size' => 36,
                    'font_weight' => '700',
                    'text_color' => '#252f60',
                    'hover_text_color' => '#eeae43',
                    'margin_top' => 12,
                    'padding_bottom' => 8,
                    'background_color' => '#ffffff',
                    'hover_background_color' => '#252f60',
                    'border_type' => 'solid',
                    'border_color' => '#123456',
                    'z_index' => 7,
                    'border_width_top' => 1,
                    'border_width_right' => 2,
                    'border_width_bottom' => 3,
                    'border_width_left' => 4,
                    'hover_border_type' => 'dashed',
                    'hover_border_color' => '#654321',
                    'hover_border_width_top' => 5,
                    'hover_border_width_right' => 6,
                    'hover_border_width_bottom' => 7,
                    'hover_border_width_left' => 8,
                    'border_radius' => 4,
                    'border_radius_top' => 4,
                    'border_radius_right' => 8,
                    'border_radius_bottom' => 12,
                    'border_radius_left' => 16,
                    'hover_border_radius_top' => 1,
                    'hover_border_radius_right' => 2,
                    'hover_border_radius_bottom' => 3,
                    'hover_border_radius_left' => 4,
                    'hide_desktop' => true,
                    'hide_tablet' => false,
                    'hide_mobile' => true,
                    'custom_css' => "selector { color: #252f60; }\nselector:hover { opacity: .8; }\nselector::after { content: \"</style>\"; }",
                ],
            ],
            ['type' => 'image', 'text' => 'Gedung pelayanan', 'url' => 'https://example.test/gedung.jpg', 'settings' => ['z_index' => 4]],
            [
                'type' => 'text',
                'text' => 'Informasi layanan masyarakat.',
                'settings' => [
                    'hide_mobile' => true,
                    'custom_css' => 'selector { color: #123456; }',
                ],
            ],
            ['type' => 'video', 'text' => 'Video layanan', 'url' => 'https://example.test/layanan.mp4'],
            ['type' => 'button', 'text' => 'Buka layanan', 'url' => 'https://layanan.example.test'],
            ['type' => 'divider'],
            ['type' => 'spacer', 'height' => 90],
            ['type' => 'map', 'text' => 'Kantor Pemerintah Kabupaten'],
        ];

        $this->put(route('admin.pages.visual-editor.update', $page), [
            'blocks' => $blocks,
            'content_width_mode' => 'custom',
            'content_width' => 1180,
        ])
            ->assertRedirect(route('admin.pages.visual-editor', $page));

        $expectedBlocks = $blocks;
        $expectedBlocks[2]['text'] = '<p>Informasi layanan masyarakat.</p>';
        $this->assertSame($expectedBlocks, $page->fresh()->blocks);
        $this->assertSame('custom', $page->fresh()->content_width_mode);
        $this->assertSame(1180, $page->fresh()->content_width);
        $this->get(route('pages.show', $page->slug))
            ->assertOk()
            ->assertSee('href="https://layanan.example.test/profil"', false)
            ->assertSee('<h1 class="block heading" style="', false)
            ->assertSee('text-align: center', false)
            ->assertSee('font-size: 36px', false)
            ->assertSee('--heading-hover-color: #eeae43', false)
            ->assertSee('--heading-border-color: #123456', false)
            ->assertSee('position: relative; z-index: 7', false)
            ->assertSee('--heading-hover-border-color: #654321', false)
            ->assertSee('border-top-width: var(--heading-border-top-width)', false)
            ->assertSee('--heading-border-right-width: 2px', false)
            ->assertSee('--heading-hover-border-left-width: 8px', false)
            ->assertSee('--heading-border-radius: 4px 8px 12px 16px', false)
            ->assertSee('--heading-hover-border-radius: 1px 2px 3px 4px', false)
            ->assertSee('data-hide-desktop="true"', false)
            ->assertSee('data-hide-tablet="false"', false)
            ->assertSee('data-hide-mobile="true"', false)
            ->assertSee('#veditor-block-1 { position: relative; z-index: 4; }', false)
            ->assertSee('#veditor-block-0 { color: #252f60; }', false)
            ->assertSee('#veditor-block-2 { color: #123456; }', false)
            ->assertSee('id="veditor-block-2" data-hide-desktop="false" data-hide-tablet="false" data-hide-mobile="true"', false)
            ->assertSee('class="article" style="width: min(100%, 1180px)"', false)
            ->assertSee('\\3C /style>', false)
            ->assertDontSee('<script>', false)
            ->assertSee('@media (max-width: 767px)', false)
            ->assertSee('src="https://example.test/gedung.jpg"', false)
            ->assertSee('src="https://example.test/layanan.mp4"', false)
            ->assertSee('href="https://layanan.example.test"', false)
            ->assertSee('height: 90px', false)
            ->assertSee('Kantor Pemerintah Kabupaten');
    }

    public function test_visual_editor_rejects_unsafe_component_urls(): void
    {
        $page = Page::query()->create([
            'title' => 'Halaman Tautan',
            'slug' => 'halaman-tautan',
            'summary' => null,
            'status' => 'draft',
            'blocks' => [['type' => 'paragraph', 'text' => 'Konten awal']],
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.pages.visual-editor.update', $page), [
                'blocks' => [
                    ['type' => 'button', 'text' => 'Tautan berbahaya', 'url' => 'javascript:alert(1)'],
                ],
            ])
            ->assertSessionHasErrors(['blocks.0.url']);

        $this->assertSame([['type' => 'paragraph', 'text' => 'Konten awal']], $page->fresh()->blocks);
    }

    public function test_text_editor_supports_formatted_content_and_shared_heading_settings(): void
    {
        $page = Page::query()->create([
            'title' => 'Halaman Text Editor',
            'slug' => 'halaman-text-editor',
            'summary' => null,
            'status' => 'published',
            'blocks' => [['type' => 'paragraph', 'text' => 'Konten lama']],
        ]);

        $blocks = [[
            'type' => 'text',
            'text' => '<p><strong>Konten berformat</strong> dengan <a href="https://example.test" onclick="alert(1)">tautan</a> dan <a href="javascript:alert(1)">tautan tidak aman</a>.</p><script>alert(1)</script>',
            'settings' => [
                'font_size' => 18,
                'text_color' => '#252f60',
                'background_type' => 'color',
                'background_color' => '#ffffff',
                'border_type' => 'solid',
                'border_color' => '#123456',
                'drop_cap' => true,
                'columns' => 2,
                'columns_gap' => 24,
            ],
        ]];

        $this->actingAs($this->admin())
            ->put(route('admin.pages.visual-editor.update', $page), ['blocks' => $blocks])
            ->assertRedirect(route('admin.pages.visual-editor', $page));

        $savedText = $page->fresh()->blocks[0]['text'];
        $this->assertStringContainsString('<strong>Konten berformat</strong>', $savedText);
        $this->assertStringNotContainsString('<script>', $savedText);
        $this->assertStringNotContainsString('onclick', $savedText);
        $this->assertStringNotContainsString('javascript:', $savedText);

        $this->get(route('pages.show', $page->slug))
            ->assertOk()
            ->assertSee('class="block text-editor"', false)
            ->assertSee('data-drop-cap="true"', false)
            ->assertSee('data-columns="2"', false)
            ->assertSee('column-gap: 24px', false)
            ->assertSee('font-size: 18px', false)
            ->assertSee('--heading-border-color: #123456', false)
            ->assertSee('<strong>Konten berformat</strong>', false)
            ->assertDontSee('<script>alert(1)</script>', false);

        $this->get(route('admin.pages.visual-editor', $page))
            ->assertOk()
            ->assertSee('Pemformatan teks', false)
            ->assertSee('Drop Cap', false)
            ->assertSee('Columns Gap (px)', false)
            ->assertSee('Style', false)
            ->assertSee('Advanced', false)
            ->assertSee("panel.open = advancedPanelOpenStates[stateKey] ?? title === 'Layout';", false)
            ->assertSee("advancedPanel('Layout', layoutContent)", false);
    }

    public function test_visual_editor_rejects_unsafe_heading_style_settings(): void
    {
        $page = Page::query()->create([
            'title' => 'Halaman Judul',
            'slug' => 'halaman-judul',
            'summary' => null,
            'status' => 'draft',
            'blocks' => [['type' => 'heading', 'text' => 'Judul']],
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.pages.visual-editor.update', $page), [
                'blocks' => [
                    [
                        'type' => 'heading',
                        'text' => 'Judul',
                        'settings' => [
                            'html_tag' => 'script',
                            'text_color' => 'red; background:url(javascript:alert(1))',
                            'border_color' => 'red; background:url(javascript:alert(1))',
                            'z_index' => 10000,
                            'border_width_top' => 21,
                            'border_radius_left' => 101,
                            'background_image' => 'https://example.test/unsafe.png',
                            'hover_background_type' => 'video',
                            'hide_desktop' => 'visible',
                            'custom_css' => str_repeat('x', 10001),
                        ],
                    ],
                ],
            ])
            ->assertSessionHasErrors([
                'blocks.0.settings.html_tag',
                'blocks.0.settings.text_color',
                'blocks.0.settings.border_color',
                'blocks.0.settings.z_index',
                'blocks.0.settings.border_width_top',
                'blocks.0.settings.border_radius_left',
                'blocks.0.settings.background_image',
                'blocks.0.settings.hover_background_type',
                'blocks.0.settings.hide_desktop',
                'blocks.0.settings.custom_css',
            ]);
    }

    public function test_visual_editor_validates_and_renders_full_page_width(): void
    {
        $page = Page::query()->create([
            'title' => 'Halaman Full Width',
            'slug' => 'halaman-full-width',
            'summary' => null,
            'status' => 'published',
            'blocks' => [['type' => 'paragraph', 'text' => 'Konten lebar penuh']],
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.pages.visual-editor.update', $page), [
                'blocks' => [['type' => 'paragraph', 'text' => 'Konten lebar penuh']],
                'content_width_mode' => 'full',
                'content_width' => 1921,
            ])
            ->assertSessionHasErrors(['content_width']);

        $this->put(route('admin.pages.visual-editor.update', $page), [
            'blocks' => [['type' => 'paragraph', 'text' => 'Konten lebar penuh']],
            'content_width_mode' => 'full',
            'content_width' => 1200,
        ])->assertRedirect(route('admin.pages.visual-editor', $page));

        $this->assertSame('full', $page->fresh()->content_width_mode);
        $this->get(route('pages.show', $page->slug))
            ->assertOk()
            ->assertSee('class="article" style="width: 100%"', false);
    }

    public function test_admin_can_upload_and_use_heading_background_images(): void
    {
        Storage::fake('public');
        $page = Page::query()->create([
            'title' => 'Halaman Background',
            'slug' => 'halaman-background',
            'summary' => null,
            'status' => 'published',
            'blocks' => [['type' => 'heading', 'text' => 'Judul']],
        ]);

        $upload = $this->actingAs($this->admin())
            ->postJson(route('admin.pages.visual-editor.background-image', $page), [
                'image' => UploadedFile::fake()->image('background.webp'),
            ])
            ->assertCreated()
            ->assertJsonStructure(['url']);

        $imageUrl = $upload->json('url');
        $this->assertMatchesRegularExpression('#^/storage/heading-backgrounds/[A-Za-z0-9._-]+$#', $imageUrl);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $imageUrl));
        $imageResponse = $this->get($imageUrl);
        $imageResponse->assertOk()->assertHeader('Content-Type', 'image/webp');
        $this->assertStringContainsString('immutable', $imageResponse->headers->get('Cache-Control'));

        $blocks = [[
            'type' => 'heading',
            'text' => 'Judul dengan gambar',
            'settings' => [
                'background_type' => 'image',
                'background_image' => $imageUrl,
                'hover_background_type' => 'image',
                'hover_background_image' => $imageUrl,
            ],
        ]];

        $this->put(route('admin.pages.visual-editor.update', $page), ['blocks' => $blocks])
            ->assertRedirect(route('admin.pages.visual-editor', $page));
        $this->assertSame($blocks, $page->fresh()->blocks);

        $this->get(route('pages.show', $page->slug))
            ->assertOk()
            ->assertSee('--heading-background-image: url(&quot;'.$imageUrl.'&quot;)', false)
            ->assertSee('--heading-hover-background-image: url(&quot;'.$imageUrl.'&quot;)', false);
    }

    public function test_image_widget_supports_content_style_advanced_and_uploaded_images(): void
    {
        Storage::fake('public');
        $page = Page::query()->create([
            'title' => 'Halaman Gambar',
            'slug' => 'halaman-gambar',
            'summary' => null,
            'status' => 'published',
            'blocks' => [['type' => 'image', 'text' => '', 'url' => '']],
        ]);

        $upload = $this->actingAs($this->admin())
            ->postJson(route('admin.pages.visual-editor.image', $page), [
                'image' => UploadedFile::fake()->image('photo.jpg'),
            ])
            ->assertCreated()
            ->assertJsonStructure(['url']);

        $imageUrl = $upload->json('url');
        $this->assertMatchesRegularExpression('#^/storage/page-images/[A-Za-z0-9._-]+$#', $imageUrl);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $imageUrl));
        $this->get($imageUrl)->assertOk();

        $blocks = [[
            'type' => 'image',
            'text' => null,
            'url' => $imageUrl,
            'settings' => [
                'alt_text' => 'Gedung kantor pelayanan',
                'image_resolution' => 'large',
                'caption_type' => 'custom',
                'caption' => 'Kantor pelayanan masyarakat',
                'link_type' => 'custom',
                'link_url' => 'https://example.test/layanan',
                'image_alignment' => 'right',
                'image_width' => 640,
                'image_width_unit' => 'px',
                'image_max_width' => 80,
                'image_max_width_unit' => '%',
                'image_height' => 360,
                'image_height_unit' => 'px',
                'opacity' => 80,
                'hover_opacity' => 50,
                'image_border_radius' => 12,
                'margin_top' => 8,
                'z_index' => 3,
                'hide_mobile' => true,
                'custom_css' => 'selector { outline: 1px solid #252f60; }',
            ],
        ]];

        $this->put(route('admin.pages.visual-editor.update', $page), ['blocks' => $blocks])
            ->assertRedirect(route('admin.pages.visual-editor', $page));
        $this->assertSame($blocks, $page->fresh()->blocks);
        $this->get(route('pages.show', $page->slug))
            ->assertOk()
            ->assertSee('class="block image"', false)
            ->assertSee('data-align="right"', false)
            ->assertSee('data-opacity="true"', false)
            ->assertSee('data-hover-opacity="true"', false)
            ->assertSee('href="https://example.test/layanan"', false)
            ->assertSee('alt="Gedung kantor pelayanan"', false)
            ->assertSee('width: 640px', false)
            ->assertSee('max-width: 80%', false)
            ->assertSee('height: 360px', false)
            ->assertSee('border-radius: 12px', false)
            ->assertSee('Kantor pelayanan masyarakat', false)
            ->assertSee('#veditor-block-0 { outline: 1px solid #252f60; }', false);

        $sharedPage = Page::query()->create([
            'title' => 'Halaman Gambar Bersama',
            'slug' => 'halaman-gambar-bersama',
            'summary' => null,
            'status' => 'published',
            'blocks' => $blocks,
        ]);
        $emptyImageBlock = [['type' => 'image', 'text' => '', 'url' => '', 'settings' => []]];
        $imagePath = str_replace('/storage/', '', $imageUrl);

        $this->put(route('admin.pages.visual-editor.update', $page), ['blocks' => $emptyImageBlock])
            ->assertRedirect(route('admin.pages.visual-editor', $page));
        Storage::disk('public')->assertExists($imagePath);
        $this->get(route('pages.show', $page->slug))
            ->assertOk()
            ->assertDontSee('src=""', false);

        $this->put(route('admin.pages.visual-editor.update', $sharedPage), ['blocks' => $emptyImageBlock])
            ->assertRedirect(route('admin.pages.visual-editor', $sharedPage));
        Storage::disk('public')->assertMissing($imagePath);

        $externalPage = Page::query()->create([
            'title' => 'Halaman Gambar Eksternal',
            'slug' => 'halaman-gambar-eksternal',
            'summary' => null,
            'status' => 'published',
            'blocks' => $emptyImageBlock,
        ]);
        $externalImageUrl = 'https://example.test/photo.jpg';
        $this->put(route('admin.pages.visual-editor.update', $externalPage), [
            'blocks' => [['type' => 'image', 'text' => null, 'url' => $externalImageUrl]],
        ])->assertRedirect(route('admin.pages.visual-editor', $externalPage));
        $this->get(route('pages.show', $externalPage->slug))
            ->assertOk()
            ->assertSee('src="'.$externalImageUrl.'"', false);

        $this->get(route('admin.pages.visual-editor', $page))
            ->assertOk()
            ->assertSee('image-picker-preview-frame', false)
            ->assertSee('image-picker-placeholder', false)
            ->assertSee('image-picker-remove', false)
            ->assertSee('Image URL', false)
            ->assertSee('isUploadedPageImage', false)
            ->assertSee('Image Resolution', false)
            ->assertSee('Caption Text', false)
            ->assertSee('Link URL', false)
            ->assertSee('Max Width', false)
            ->assertSee('Hover', false)
            ->assertSee("renderHeadingSettings(form, 'image')", false)
            ->assertSee('renderHeadingAdvancedTab(tabContent)', false);
    }

    public function test_video_widget_supports_local_upload_external_sources_and_shared_advanced_settings(): void
    {
        Storage::fake('public');
        $page = Page::query()->create([
            'title' => 'Halaman Video',
            'slug' => 'halaman-video',
            'summary' => null,
            'status' => 'published',
            'blocks' => [['type' => 'video', 'text' => '', 'url' => '']],
        ]);

        $upload = $this->actingAs($this->admin())
            ->postJson(route('admin.pages.visual-editor.video', $page), [
                'video' => UploadedFile::fake()->create('profil.mp4', 100, 'video/mp4'),
            ])
            ->assertCreated()
            ->assertJsonStructure(['url']);

        $videoUrl = $upload->json('url');
        $this->assertMatchesRegularExpression('#^/storage/page-videos/[A-Za-z0-9._-]+\.mp4$#', $videoUrl);
        $videoPath = str_replace('/storage/', '', $videoUrl);
        Storage::disk('public')->assertExists($videoPath);
        $this->get($videoUrl)->assertOk();

        $videoBlock = [[
            'type' => 'video',
            'text' => null,
            'url' => $videoUrl,
            'settings' => [
                'video_source' => 'self_hosted',
                'video_aspect_ratio' => '21:9',
                'video_filter_brightness' => 120,
                'video_filter_contrast' => 90,
                'video_filter_saturation' => 110,
                'video_filter_hue' => 15,
                'video_filter_blur' => 1,
                'margin_top' => 8,
                'z_index' => 2,
                'hide_mobile' => true,
                'custom_css' => 'selector { outline: 1px solid #252f60; }',
            ],
        ]];
        $this->put(route('admin.pages.visual-editor.update', $page), ['blocks' => $videoBlock])
            ->assertRedirect(route('admin.pages.visual-editor', $page));
        $this->assertSame($videoBlock, $page->fresh()->blocks);
        $this->get(route('pages.show', $page->slug))
            ->assertOk()
            ->assertSee('<source src="'.$videoUrl.'">', false)
            ->assertSee('class="block video"', false)
            ->assertSee('aspect-ratio: 21 / 9', false)
            ->assertSee('brightness(120%)', false)
            ->assertSee('#veditor-block-0 { outline: 1px solid #252f60; }', false);

        $this->get(route('admin.pages.visual-editor', $page))
            ->assertOk()
            ->assertSee('videoUploadUrl', false)
            ->assertSee('Choose Video File', false)
            ->assertSee('YouTube', false)
            ->assertSee('CSS Filters', false)
            ->assertSee('renderHeadingAdvancedTab(tabContent)', false);

        $externalUrl = 'https://youtu.be/abc123DEF45';
        $externalBlock = [[
            'type' => 'video',
            'text' => null,
            'url' => $externalUrl,
            'settings' => ['video_source' => 'youtube', 'video_aspect_ratio' => '16:9'],
        ]];
        $this->put(route('admin.pages.visual-editor.update', $page), ['blocks' => $externalBlock])
            ->assertRedirect(route('admin.pages.visual-editor', $page));
        $this->get(route('pages.show', $page->slug))
            ->assertOk()
            ->assertSee('class="block video"', false)
            ->assertSee('src="https://www.youtube-nocookie.com/embed/abc123DEF45"', false)
            ->assertDontSee('<video controls', false);
        Storage::disk('public')->assertMissing($videoPath);

        $this->assertSame(
            'https://player.vimeo.com/video/123456',
            VideoEmbedUrl::make('vimeo', 'https://vimeo.com/123456')
        );
        $this->assertNull(VideoEmbedUrl::make('youtube', 'https://example.test/abc123DEF45'));
    }

    public function test_visual_editor_can_save_and_render_container_layouts(): void
    {
        $page = Page::query()->create([
            'title' => 'Halaman Container',
            'slug' => 'halaman-container',
            'summary' => null,
            'status' => 'published',
            'blocks' => [['type' => 'heading', 'text' => 'Mulai']],
        ]);
        $containerId = 'container-test-123';
        $blocks = [
            [
                'type' => 'container',
                'text' => null,
                'settings' => [
                    'container_id' => $containerId,
                    'container_layout' => 'columns-wide-left',
                    'container_slots' => 2,
                    'container_gap' => 18,
                    'margin_top' => 24,
                    'margin_left' => -25,
                    'padding_left' => 32,
                ],
            ],
            [
                'type' => 'heading',
                'text' => 'Kolom pertama',
                'settings' => ['container_id' => $containerId, 'container_column' => 0, 'margin_left' => -18],
            ],
            [
                'type' => 'paragraph',
                'text' => 'Konten kolom kedua.',
                'settings' => ['container_id' => $containerId, 'container_column' => 1],
            ],
        ];

        $this->actingAs($this->admin())
            ->put(route('admin.pages.visual-editor.update', $page), ['blocks' => $blocks])
            ->assertRedirect(route('admin.pages.visual-editor', $page));
        $this->assertSame($blocks, $page->fresh()->blocks);

        $this->get(route('pages.show', $page->slug))
            ->assertOk()
            ->assertSee('class="block-container container-layout-columns-wide-left"', false)
            ->assertSee('grid-template-columns:2fr 1fr', false)
            ->assertSee('grid-template-areas:&quot;a b&quot;', false)
            ->assertSee('margin-top: 24px', false)
            ->assertSee('margin-left: -25px', false)
            ->assertSee('margin-left: -18px !important', false)
            ->assertSee('padding-left: 32px', false)
            ->assertSee('Kolom pertama', false)
            ->assertSee('Konten kolom kedua.', false)
            ->assertSee('id="veditor-block-1"', false)
            ->assertSee('id="veditor-block-2"', false);

        $this->get(route('admin.pages.visual-editor', $page))
            ->assertOk()
            ->assertSee('id="structureToggle"', false)
            ->assertSee('id="structureWindow"', false)
            ->assertSee('id="structureWindowClose"', false)
            ->assertSee('id="structureTree"', false)
            ->assertSee('function renderStructureTree', false)
            ->assertSee('function getStructureSubtree', false)
            ->assertSee('function moveStructureBlock', false)
            ->assertSee('function structureIconMarkup', false)
            ->assertSee('function beginStructureRename', false)
            ->assertSee('function deleteStructureBlock', false)
            ->assertSee('structure-tree-action delete', false)
            ->assertSee('row.draggable = true', false)
            ->assertSee("targetBlock.type === 'container' && offset", false)
            ->assertSee('data-add-component="container"', false)
            ->assertSee('function createContainerBlock', false)
            ->assertSee('function createRootComponentEntries', false)
            ->assertSee("input.min = property === 'margin' ? '-500' : '0'", false)
            ->assertSee('structureBuilder', false)
            ->assertSee('Pilih struktur container', false)
            ->assertSee('3 atas, 2 bawah', false);
    }

    public function test_visual_editor_can_nest_containers_and_render_their_widgets(): void
    {
        $page = Page::query()->create([
            'title' => 'Container Bertingkat',
            'slug' => 'container-bertingkat',
            'summary' => null,
            'status' => 'published',
            'blocks' => [],
        ]);
        $parentId = 'container-root-123';
        $childId = 'container-child-123';
        $blocks = [
            [
                'type' => 'container',
                'text' => null,
                'settings' => [
                    'container_id' => $parentId,
                    'container_layout' => 'columns-2',
                    'container_slots' => 2,
                    'structure_label' => 'Outer group',
                ],
            ],
            [
                'type' => 'container',
                'text' => null,
                'settings' => [
                    'container_id' => $childId,
                    'parent_container_id' => $parentId,
                    'container_column' => 1,
                    'container_layout' => 'columns-2',
                    'container_slots' => 2,
                ],
            ],
            [
                'type' => 'heading',
                'text' => 'Widget di dalam container bertingkat',
                'settings' => ['container_id' => $childId, 'container_column' => 0],
            ],
        ];

        $this->actingAs($this->admin())
            ->put(route('admin.pages.visual-editor.update', $page), ['blocks' => $blocks])
            ->assertRedirect(route('admin.pages.visual-editor', $page));
        $this->assertSame($blocks, $page->fresh()->blocks);
        $this->assertSame('Outer group', $page->fresh()->blocks[0]['settings']['structure_label']);

        $response = $this->get(route('pages.show', $page->slug))
            ->assertOk()
            ->assertSee('id="veditor-container-'.$parentId.'"', false)
            ->assertSee('id="veditor-container-'.$childId.'"', false)
            ->assertSee('Widget di dalam container bertingkat', false);
        $content = $response->getContent();
        $parentPosition = strpos($content, 'id="veditor-container-'.$parentId.'"');
        $childPosition = strpos($content, 'id="veditor-container-'.$childId.'"');
        $parentSectionEnd = strpos($content, '</section>', $parentPosition);
        $this->assertLessThan($childPosition, $parentPosition);
        $this->assertLessThan($parentSectionEnd, $childPosition);
    }

    public function test_visual_editor_rejects_cyclic_container_nesting(): void
    {
        $page = Page::query()->create([
            'title' => 'Container Siklus',
            'slug' => 'container-siklus',
            'summary' => null,
            'status' => 'draft',
            'blocks' => [],
        ]);
        $blocks = [
            [
                'type' => 'container',
                'text' => null,
                'settings' => [
                    'container_id' => 'container-root-123',
                    'parent_container_id' => 'container-child-123',
                    'container_column' => 0,
                    'container_layout' => 'columns-2',
                    'container_slots' => 2,
                ],
            ],
            [
                'type' => 'container',
                'text' => null,
                'settings' => [
                    'container_id' => 'container-child-123',
                    'parent_container_id' => 'container-root-123',
                    'container_column' => 0,
                    'container_layout' => 'columns-2',
                    'container_slots' => 2,
                ],
            ],
        ];

        $this->actingAs($this->admin())
            ->from(route('admin.pages.visual-editor', $page))
            ->put(route('admin.pages.visual-editor.update', $page), ['blocks' => $blocks])
            ->assertRedirect(route('admin.pages.visual-editor', $page))
            ->assertSessionHasErrors('blocks.0.settings.parent_container_id');
    }

    public function test_public_page_wraps_legacy_root_widgets_in_a_container(): void
    {
        $page = Page::query()->create([
            'title' => 'Widget Tanpa Container',
            'slug' => 'widget-tanpa-container',
            'summary' => null,
            'status' => 'published',
            'blocks' => [['type' => 'heading', 'text' => 'Widget lama']],
        ]);

        $response = $this->get(route('pages.show', $page->slug))
            ->assertOk()
            ->assertSee('id="veditor-container-auto-0"', false)
            ->assertSee('id="veditor-block-0"', false)
            ->assertSee('Widget lama', false);
        $content = $response->getContent();
        $containerPosition = strpos($content, 'id="veditor-container-auto-0"');
        $widgetPosition = strpos($content, 'id="veditor-block-0"');
        $containerEnd = strpos($content, '</section>', $containerPosition);
        $this->assertLessThan($widgetPosition, $containerPosition);
        $this->assertLessThan($containerEnd, $widgetPosition);
    }

    public function test_heading_background_upload_rejects_non_image_files(): void
    {
        $page = Page::query()->create([
            'title' => 'Halaman Background',
            'slug' => 'halaman-background',
            'summary' => null,
            'status' => 'draft',
            'blocks' => [['type' => 'heading', 'text' => 'Judul']],
        ]);

        $this->actingAs($this->admin())
            ->postJson(route('admin.pages.visual-editor.background-image', $page), [
                'image' => UploadedFile::fake()->create('notes.txt', 10, 'text/plain'),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    }

    public function test_page_builder_preserves_heading_style_settings(): void
    {
        $page = Page::query()->create([
            'title' => 'Heading Bergaya',
            'slug' => 'heading-bergaya',
            'summary' => null,
            'status' => 'draft',
            'blocks' => [[
                'type' => 'heading',
                'text' => 'Judul',
                'settings' => [
                    'html_tag' => 'h1',
                    'text_color' => '#252f60',
                    'background_type' => 'image',
                    'background_image' => '/storage/heading-backgrounds/contoh.webp',
                    'hide_desktop' => true,
                    'custom_css' => 'selector { font-weight: 700; }',
                ],
            ]],
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.pages.edit', $page))
            ->assertOk()
            ->assertSee('data-setting="html_tag"', false)
            ->assertSee('data-setting="text_color"', false)
            ->assertSee('data-setting="background_image"', false);

        $this->put(route('admin.pages.update', $page), [
            'title' => 'Heading Bergaya',
            'slug' => 'heading-bergaya',
            'summary' => '',
            'status' => 'draft',
            'blocks' => [[
                'type' => 'heading',
                'text' => 'Judul diperbarui',
                'settings' => [
                    'html_tag' => 'h1',
                    'text_color' => '#252f60',
                    'background_type' => 'image',
                    'background_image' => '/storage/heading-backgrounds/contoh.webp',
                    'hide_desktop' => true,
                    'custom_css' => 'selector { font-weight: 700; }',
                ],
            ]],
        ])->assertRedirect(route('admin.pages.edit', $page));

        $this->assertSame([
            'type' => 'heading',
            'text' => 'Judul diperbarui',
            'settings' => [
                'html_tag' => 'h1',
                'text_color' => '#252f60',
                'background_type' => 'image',
                'background_image' => '/storage/heading-backgrounds/contoh.webp',
                'hide_desktop' => true,
                'custom_css' => 'selector { font-weight: 700; }',
            ],
        ], $page->fresh()->blocks[0]);
    }

    public function test_draft_pages_are_not_visible_to_public_and_blocks_are_validated(): void
    {
        $page = Page::query()->create([
            'title' => 'Halaman Draf',
            'slug' => 'halaman-draf',
            'summary' => null,
            'status' => 'draft',
            'blocks' => [['type' => 'paragraph', 'text' => 'Konten rahasia draf']],
        ]);

        $this->get(route('pages.show', $page->slug))->assertNotFound();

        $this->actingAs($this->admin())
            ->post(route('admin.pages.store'), [
                'title' => 'Halaman Bermasalah',
                'slug' => 'halaman-bermasalah',
                'summary' => '',
                'status' => 'published',
                'blocks' => [['type' => 'script', 'text' => '<script>alert(1)</script>']],
            ])
            ->assertSessionHasErrors(['blocks.0.type']);

        $this->assertDatabaseMissing('pages', ['slug' => 'halaman-bermasalah']);
    }

    public function test_non_admin_user_cannot_use_page_builder(): void
    {
        $this->actingAs(User::factory()->create([
            'username' => 'public-user',
            'is_admin' => false,
        ]))
            ->get(route('admin.pages.index'))
            ->assertForbidden();
    }

    private function admin(): User
    {
        return User::factory()->create([
            'username' => 'page-admin',
            'is_admin' => true,
        ]);
    }
}
