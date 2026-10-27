<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavigationMenu;
use App\Models\Page;
use App\Models\SiteContent;
use App\Support\TextEditorContent;
use App\Support\VideoEmbedUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.index', [
            'pages' => Page::query()->latest('updated_at')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.pages.form', [
            'page' => new Page([
                'status' => 'draft',
                'blocks' => [['type' => 'paragraph', 'text' => '']],
            ]),
            'isEditing' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $page = Page::query()->create($this->validatedData($request));

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', 'Halaman berhasil dibuat.');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.form', [
            'page' => $page,
            'isEditing' => true,
        ]);
    }

    public function visualEditor(Page $page): View
    {
        return view('admin.pages.visual-editor', [
            'page' => $page,
            'siteContent' => SiteContent::current(),
            'menus' => NavigationMenu::query()
                ->with(['items.page'])
                ->orderBy('name')
                ->get()
                ->map(fn (NavigationMenu $menu) => [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'items' => $menu->tree(),
                ])
                ->values(),
        ]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $validated = $this->validatedData($request, $page);
        $previousImageUrls = $this->pageImageUrls($page->blocks ?? []);
        $previousVideoUrls = $this->pageVideoUrls($page->blocks ?? []);
        $page->update($validated);
        $this->deleteUnusedPageImages($previousImageUrls);
        $this->deleteUnusedPageVideos($previousVideoUrls);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', 'Perubahan halaman berhasil disimpan.');
    }

    public function updateVisualEditor(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'content_width_mode' => ['sometimes', 'required', Rule::in(['full', 'custom'])],
            'content_width' => ['sometimes', 'required', 'integer', 'min:320', 'max:1920'],
            'blocks' => ['required', 'array', 'min:1', 'max:60'],
            'blocks.*' => ['required', 'array:type,text,url,level,height,settings'],
            'blocks.*.type' => [
                'required',
                Rule::in(['heading', 'paragraph', 'quote', 'list', 'text', 'image', 'video', 'button', 'divider', 'spacer', 'map', 'menu', 'container']),
            ],
            'blocks.*.text' => ['nullable', 'string', 'max:5000'],
            'blocks.*.url' => ['nullable', 'string', 'max:2048', $this->componentUrlRule($request)],
            'blocks.*.level' => ['nullable', 'integer', Rule::in([2, 3, 4])],
            'blocks.*.height' => ['nullable', 'integer', 'min:16', 'max:400'],
            ...$this->headingSettingsRules(),
        ], [
            'blocks.min' => 'Tambahkan minimal satu komponen ke halaman.',
            'blocks.*.url.starts_with' => 'URL komponen harus diawali http:// atau https://.',
        ]);

        $this->validateVisualBlocks($request);
        $this->validateContainers($validated['blocks']);
        $previousImageUrls = $this->pageImageUrls($page->blocks ?? []);
        $previousVideoUrls = $this->pageVideoUrls($page->blocks ?? []);
        foreach ($validated['blocks'] as &$block) {
            if (($block['type'] ?? null) === 'text') {
                $block['text'] = TextEditorContent::sanitize((string) ($block['text'] ?? ''));
            }
        }
        unset($block);

        $page->update([
            'blocks' => $validated['blocks'],
            ...array_intersect_key($validated, array_flip(['content_width_mode', 'content_width'])),
        ]);
        $this->deleteUnusedPageImages($previousImageUrls);
        $this->deleteUnusedPageVideos($previousVideoUrls);

        return redirect()
            ->route('admin.pages.visual-editor', $page)
            ->with('status', 'Perubahan VEditor berhasil disimpan.');
    }

    public function uploadHeadingBackground(Request $request, Page $page)
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'image.image' => 'Pilih berkas gambar yang valid.',
            'image.mimes' => 'Format gambar yang didukung: JPG, PNG, atau WebP.',
            'image.max' => 'Ukuran gambar maksimal 5 MB.',
        ]);

        $path = $validated['image']->store('heading-backgrounds', 'public');

        return response()->json([
            'url' => '/storage/'.$path,
        ], 201);
    }

    public function showHeadingBackground(string $filename)
    {
        abort_unless(
            preg_match('/^[A-Za-z0-9._-]+\.(jpg|jpeg|png|webp)$/i', $filename) === 1,
            404
        );

        $path = 'heading-backgrounds/'.$filename;
        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response($path, $filename, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    public function uploadPageImage(Request $request, Page $page)
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ], [
            'image.image' => 'Pilih berkas gambar yang valid.',
            'image.mimes' => 'Format gambar yang didukung: JPG, PNG, WebP, atau GIF.',
            'image.max' => 'Ukuran gambar maksimal 5 MB.',
        ]);

        $path = $validated['image']->store('page-images', 'public');

        return response()->json([
            'url' => '/storage/'.$path,
        ], 201);
    }

    public function showPageImage(string $filename)
    {
        abort_unless(
            preg_match('/^[A-Za-z0-9._-]+\.(jpg|jpeg|png|webp|gif)$/i', $filename) === 1,
            404
        );

        $path = 'page-images/'.$filename;
        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response($path, $filename, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    public function uploadPageVideo(Request $request, Page $page)
    {
        $validated = $request->validate([
            'video' => ['required', 'file', 'mimetypes:video/mp4,video/webm,video/ogg', 'max:51200'],
        ], [
            'video.mimetypes' => 'Format video yang didukung: MP4, WebM, atau OGG.',
            'video.max' => 'Ukuran video maksimal 50 MB.',
        ]);

        $path = $validated['video']->store('page-videos', 'public');

        return response()->json([
            'url' => '/storage/'.$path,
        ], 201);
    }

    public function showPageVideo(string $filename)
    {
        abort_unless(
            preg_match('/^[A-Za-z0-9._-]+\.(mp4|webm|ogg)$/i', $filename) === 1,
            404
        );

        $path = 'page-videos/'.$filename;
        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response($path, $filename, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    public function destroy(Page $page): RedirectResponse
    {
        $previousImageUrls = $this->pageImageUrls($page->blocks ?? []);
        $previousVideoUrls = $this->pageVideoUrls($page->blocks ?? []);
        $page->delete();
        $this->deleteUnusedPageImages($previousImageUrls);
        $this->deleteUnusedPageVideos($previousVideoUrls);

        return redirect()
            ->route('admin.pages.index')
            ->with('status', 'Halaman berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Page $page = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'slug' => [
                'required',
                'string',
                'max:180',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('pages', 'slug')->ignore($page?->id),
            ],
            'summary' => ['nullable', 'string', 'max:300'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'blocks' => ['required', 'array', 'min:1', 'max:30'],
            'blocks.*' => ['required', 'array:type,text,url,level,height,settings'],
            'blocks.*.type' => [
                'required',
                Rule::in(['heading', 'paragraph', 'quote', 'list', 'text', 'image', 'video', 'button', 'divider', 'spacer', 'map', 'menu', 'container']),
            ],
            'blocks.*.text' => ['nullable', 'string', 'max:5000'],
            'blocks.*.url' => ['nullable', 'string', 'max:2048', $this->componentUrlRule($request)],
            'blocks.*.level' => ['nullable', 'integer', Rule::in([2, 3, 4])],
            'blocks.*.height' => ['nullable', 'integer', 'min:16', 'max:400'],
            ...$this->headingSettingsRules(),
        ], [
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung.',
            'blocks.min' => 'Tambahkan minimal satu blok konten.',
        ]);

        $this->validateVisualBlocks($request);
        $this->validateContainers($validated['blocks']);

        return $validated;
    }

    private function validateVisualBlocks(Request $request): void
    {
        $request->validate([
            'blocks' => ['required', 'array'],
            'blocks.*.text' => ['required_unless:blocks.*.type,divider,spacer,image,video,menu,container'],
            'blocks.*.url' => ['required_if:blocks.*.type,button'],
            'blocks.*.height' => ['required_if:blocks.*.type,spacer'],
        ], [
            'blocks.*.text.required_unless' => 'Isi teks pada komponen ini wajib diisi.',
            'blocks.*.url.required_if' => 'URL komponen ini wajib diisi.',
            'blocks.*.height.required_if' => 'Tentukan tinggi spacer.',
        ]);

        foreach ($request->input('blocks', []) as $index => $block) {
            if (($block['type'] ?? null) === 'menu' && empty($block['settings']['menu_id'])) {
                throw ValidationException::withMessages([
                    "blocks.$index.settings.menu_id" => 'Pilih menu yang sudah dibuat di dashboard admin.',
                ]);
            }
        }
    }

    private function validateContainers(array $blocks): void
    {
        $errors = [];
        $containers = [];
        $containerIndices = [];
        if (count(array_filter($blocks, fn ($block) => ($block['type'] ?? null) !== 'container')) > 30) {
            $errors['blocks'] = 'Maksimal 30 widget dalam satu halaman.';
        }
        $allowedLayouts = [
            'column' => 1,
            'row' => 1,
            'columns-2' => 2,
            'columns-wide-left' => 2,
            'columns-wide-right' => 2,
            'columns-4' => 4,
            'columns-3-center' => 3,
            'rows-2' => 2,
            'top-wide' => 3,
            'left-tall' => 3,
            'grid-3x2' => 6,
            'top-3-bottom-2' => 5,
            'top-2-bottom-3' => 5,
        ];

        foreach ($blocks as $index => $block) {
            $settings = $block['settings'] ?? [];
            if (($block['type'] ?? null) === 'container') {
                $id = $settings['container_id'] ?? null;
                $layout = $settings['container_layout'] ?? null;
                $slots = $allowedLayouts[$layout] ?? null;
                if (! is_string($id) || preg_match('/^[A-Za-z0-9-]{8,64}$/', $id) !== 1 || isset($containers[$id])) {
                    $errors["blocks.$index.settings.container_id"] = 'ID container tidak valid atau digunakan lebih dari sekali.';

                    continue;
                }
                if ($slots === null || (int) ($settings['container_slots'] ?? 0) !== $slots) {
                    $errors["blocks.$index.settings.container_layout"] = 'Pilih struktur container yang valid.';

                    continue;
                }
                $containers[$id] = $slots;
                $containerIndices[$id] = $index;
            }
        }

        $containerParents = [];
        foreach ($blocks as $index => $block) {
            $settings = $block['settings'] ?? [];
            $isContainer = ($block['type'] ?? null) === 'container';
            $parentId = $isContainer ? ($settings['parent_container_id'] ?? null) : ($settings['container_id'] ?? null);
            if ($isContainer) {
                $containerParents[$settings['container_id']] = $parentId;
            }
            if ($parentId === null) {
                continue;
            }

            $idField = $isContainer ? 'parent_container_id' : 'container_id';
            $column = filter_var($settings['container_column'] ?? null, FILTER_VALIDATE_INT);
            if (! is_string($parentId) || ! isset($containers[$parentId])) {
                $errors["blocks.$index.settings.$idField"] = 'Widget harus ditempatkan pada container yang tersedia.';
            } elseif ($column === false || $column < 0 || $column >= $containers[$parentId]) {
                $errors["blocks.$index.settings.container_column"] = 'Kolom container tidak valid.';
            }
            if ($isContainer && $parentId === ($settings['container_id'] ?? null)) {
                $errors["blocks.$index.settings.parent_container_id"] = 'Container tidak dapat ditempatkan di dalam dirinya sendiri.';
            }
        }

        foreach ($containerParents as $containerId => $parentId) {
            $visited = [$containerId => true];
            while ($parentId !== null && isset($containerParents[$parentId])) {
                if (isset($visited[$parentId])) {
                    $index = $containerIndices[$containerId];
                    $errors["blocks.$index.settings.parent_container_id"] = 'Struktur container tidak boleh membentuk siklus.';
                    break;
                }
                $visited[$parentId] = true;
                $parentId = $containerParents[$parentId];
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }

    private function componentUrlRule(Request $request): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail) use ($request): void {
            if ($value === null || $value === '') {
                return;
            }

            preg_match('/^blocks\.(\d+)\.url$/', $attribute, $matches);
            $blockType = isset($matches[1]) ? $request->input('blocks.'.$matches[1].'.type') : null;
            $blockIndex = $matches[1] ?? null;
            $videoSource = $blockIndex !== null ? $request->input('blocks.'.$blockIndex.'.settings.video_source', 'self_hosted') : 'self_hosted';
            $isUploadedImage = $blockType === 'image'
                && preg_match('#^/storage/page-images/[A-Za-z0-9._-]+\.(jpg|jpeg|png|webp|gif)$#i', $value) === 1;
            $isUploadedVideo = $blockType === 'video'
                && $videoSource === 'self_hosted'
                && preg_match('#^/storage/page-videos/[A-Za-z0-9._-]+\.(mp4|webm|ogg)$#i', $value) === 1;

            if ($isUploadedImage || $isUploadedVideo) {
                return;
            }

            $scheme = is_string($value) ? parse_url($value, PHP_URL_SCHEME) : null;
            if (! filter_var($value, FILTER_VALIDATE_URL) || ! in_array(strtolower((string) $scheme), ['http', 'https'], true)) {
                $message = match ($blockType) {
                    'image' => 'Unggah gambar dari perangkat atau gunakan URL http:// atau https://.',
                    'video' => 'Unggah video dari perangkat atau gunakan URL dari sumber video yang dipilih.',
                    default => 'URL komponen harus berupa alamat web http:// atau https://.',
                };
                $fail($message);

                return;
            }

            if ($blockType === 'video' && $videoSource !== 'self_hosted'
                && VideoEmbedUrl::make((string) $videoSource, $value) === null) {
                $fail('URL video tidak sesuai dengan sumber yang dipilih.');
            }
        };
    }

    private function pageImageUrls(array $blocks): array
    {
        $urls = [];
        foreach ($blocks as $block) {
            $url = $block['url'] ?? null;
            if (($block['type'] ?? null) === 'image'
                && is_string($url)
                && preg_match('#^/storage/page-images/[A-Za-z0-9._-]+\.(jpg|jpeg|png|webp|gif)$#i', $url) === 1) {
                $urls[] = $url;
            }
        }

        return array_values(array_unique($urls));
    }

    private function deleteUnusedPageImages(array $candidateUrls): void
    {
        if ($candidateUrls === []) {
            return;
        }

        $usedUrls = [];
        foreach (Page::query()->get(['blocks']) as $page) {
            $usedUrls = [...$usedUrls, ...$this->pageImageUrls($page->blocks ?? [])];
        }

        foreach (array_diff($candidateUrls, $usedUrls) as $url) {
            $path = substr($url, strlen('/storage/'));
            $disk = Storage::disk('public');
            if ($disk->exists($path) && ! $disk->delete($path)) {
                Log::warning('Unused page image could not be deleted from storage.', ['path' => $path]);
            }
        }
    }

    private function pageVideoUrls(array $blocks): array
    {
        $urls = [];
        foreach ($blocks as $block) {
            $url = $block['url'] ?? null;
            if (($block['type'] ?? null) === 'video'
                && is_string($url)
                && preg_match('#^/storage/page-videos/[A-Za-z0-9._-]+\.(mp4|webm|ogg)$#i', $url) === 1) {
                $urls[] = $url;
            }
        }

        return array_values(array_unique($urls));
    }

    private function deleteUnusedPageVideos(array $candidateUrls): void
    {
        if ($candidateUrls === []) {
            return;
        }

        $usedUrls = [];
        foreach (Page::query()->get(['blocks']) as $page) {
            $usedUrls = [...$usedUrls, ...$this->pageVideoUrls($page->blocks ?? [])];
        }

        foreach (array_diff($candidateUrls, $usedUrls) as $url) {
            $path = substr($url, strlen('/storage/'));
            $disk = Storage::disk('public');
            if ($disk->exists($path) && ! $disk->delete($path)) {
                Log::warning('Unused page video could not be deleted from storage.', ['path' => $path]);
            }
        }
    }

    private function headingSettingsRules(): array
    {
        return [
            'blocks.*.settings' => ['sometimes', 'array:link,html_tag,alignment,font_size,font_weight,blend_mode,text_color,hover_text_color,text_stroke_width,text_stroke_color,text_shadow,hover_text_shadow,margin_top,margin_right,margin_bottom,margin_left,padding_top,padding_right,padding_bottom,padding_left,width,align_self,z_index,background_type,hover_background_type,background_color,hover_background_color,background_image,hover_background_image,border_type,hover_border_type,border_color,hover_border_color,border_width_top,border_width_right,border_width_bottom,border_width_left,hover_border_width_top,hover_border_width_right,hover_border_width_bottom,hover_border_width_left,border_radius,border_radius_top,border_radius_right,border_radius_bottom,border_radius_left,hover_border_radius_top,hover_border_radius_right,hover_border_radius_bottom,hover_border_radius_left,box_shadow,hover_box_shadow,hide_desktop,hide_tablet,hide_mobile,custom_css,drop_cap,columns,columns_gap,image_resolution,image_alignment,caption_type,caption,link_type,link_url,alt_text,image_width,image_width_unit,image_max_width,image_max_width_unit,image_height,image_height_unit,opacity,hover_opacity,image_border_radius,video_source,video_aspect_ratio,video_filter_brightness,video_filter_contrast,video_filter_saturation,video_filter_hue,video_filter_blur,container_id,parent_container_id,container_layout,container_slots,container_gap,container_column,structure_label,menu_id,menu_item_color,menu_item_hover_color,menu_item_active_color,menu_item_background,menu_item_hover_background,menu_item_active_background,menu_item_font_size,menu_item_font_weight,menu_item_padding_x,menu_item_padding_y,menu_item_gap,submenu_item_color,submenu_item_hover_color,submenu_item_active_color,submenu_item_background,submenu_item_hover_background,submenu_item_active_background,submenu_item_font_size,submenu_item_font_weight,submenu_item_padding_x,submenu_item_padding_y,submenu_item_gap,submenu_background,submenu_min_width,submenu_padding,submenu_border_color,submenu_border_width,submenu_border_radius,submenu_shadow'],
            'blocks.*.settings.link' => ['nullable', 'url', 'starts_with:http://,https://', 'max:2048'],
            'blocks.*.settings.html_tag' => ['nullable', Rule::in(['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span'])],
            'blocks.*.settings.alignment' => ['nullable', Rule::in(['left', 'center', 'right', 'justify'])],
            'blocks.*.settings.font_size' => ['nullable', 'integer', 'min:12', 'max:72'],
            'blocks.*.settings.font_weight' => ['nullable', Rule::in(['400', '500', '600', '700', '800'])],
            'blocks.*.settings.blend_mode' => ['nullable', Rule::in(['normal', 'multiply', 'screen', 'overlay', 'darken', 'lighten'])],
            'blocks.*.settings.text_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.hover_text_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.text_stroke_width' => ['nullable', 'integer', 'min:0', 'max:3'],
            'blocks.*.settings.text_stroke_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.text_shadow' => ['nullable', Rule::in(['none', 'soft', 'strong'])],
            'blocks.*.settings.hover_text_shadow' => ['nullable', Rule::in(['none', 'soft', 'strong'])],
            'blocks.*.settings.margin_top' => ['nullable', 'integer', 'min:-500', 'max:500'],
            'blocks.*.settings.margin_right' => ['nullable', 'integer', 'min:-500', 'max:500'],
            'blocks.*.settings.margin_bottom' => ['nullable', 'integer', 'min:-500', 'max:500'],
            'blocks.*.settings.margin_left' => ['nullable', 'integer', 'min:-500', 'max:500'],
            'blocks.*.settings.padding_top' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.padding_right' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.padding_bottom' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.padding_left' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.width' => ['nullable', Rule::in(['default', 'full', 'inline'])],
            'blocks.*.settings.align_self' => ['nullable', Rule::in(['left', 'center', 'right', 'stretch'])],
            'blocks.*.settings.z_index' => ['nullable', 'integer', 'min:-9999', 'max:9999'],
            'blocks.*.settings.background_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.hover_background_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.background_type' => ['nullable', Rule::in(['color', 'image'])],
            'blocks.*.settings.hover_background_type' => ['nullable', Rule::in(['color', 'image'])],
            'blocks.*.settings.background_image' => ['nullable', 'regex:/^\/storage\/heading-backgrounds\/[A-Za-z0-9._-]+$/'],
            'blocks.*.settings.hover_background_image' => ['nullable', 'regex:/^\/storage\/heading-backgrounds\/[A-Za-z0-9._-]+$/'],
            'blocks.*.settings.border_type' => ['nullable', Rule::in(['none', 'solid', 'dashed', 'dotted'])],
            'blocks.*.settings.hover_border_type' => ['nullable', Rule::in(['none', 'solid', 'dashed', 'dotted'])],
            'blocks.*.settings.border_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.hover_border_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.border_width_top' => ['nullable', 'integer', 'min:0', 'max:20'],
            'blocks.*.settings.border_width_right' => ['nullable', 'integer', 'min:0', 'max:20'],
            'blocks.*.settings.border_width_bottom' => ['nullable', 'integer', 'min:0', 'max:20'],
            'blocks.*.settings.border_width_left' => ['nullable', 'integer', 'min:0', 'max:20'],
            'blocks.*.settings.hover_border_width_top' => ['nullable', 'integer', 'min:0', 'max:20'],
            'blocks.*.settings.hover_border_width_right' => ['nullable', 'integer', 'min:0', 'max:20'],
            'blocks.*.settings.hover_border_width_bottom' => ['nullable', 'integer', 'min:0', 'max:20'],
            'blocks.*.settings.hover_border_width_left' => ['nullable', 'integer', 'min:0', 'max:20'],
            'blocks.*.settings.border_radius_top' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.border_radius_right' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.border_radius_bottom' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.border_radius_left' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.hover_border_radius_top' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.hover_border_radius_right' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.hover_border_radius_bottom' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.hover_border_radius_left' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.border_radius' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.box_shadow' => ['nullable', Rule::in(['none', 'soft', 'strong'])],
            'blocks.*.settings.hover_box_shadow' => ['nullable', Rule::in(['none', 'soft', 'strong'])],
            'blocks.*.settings.hide_desktop' => ['nullable', 'boolean'],
            'blocks.*.settings.hide_tablet' => ['nullable', 'boolean'],
            'blocks.*.settings.hide_mobile' => ['nullable', 'boolean'],
            'blocks.*.settings.custom_css' => ['nullable', 'string', 'max:10000'],
            'blocks.*.settings.drop_cap' => ['nullable', 'boolean'],
            'blocks.*.settings.columns' => ['nullable', 'integer', 'min:1', 'max:4'],
            'blocks.*.settings.columns_gap' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.image_resolution' => ['nullable', Rule::in(['thumbnail', 'medium', 'large', 'full'])],
            'blocks.*.settings.image_alignment' => ['nullable', Rule::in(['left', 'center', 'right'])],
            'blocks.*.settings.caption_type' => ['nullable', Rule::in(['none', 'custom'])],
            'blocks.*.settings.caption' => ['nullable', 'string', 'max:500'],
            'blocks.*.settings.link_type' => ['nullable', Rule::in(['none', 'custom', 'media'])],
            'blocks.*.settings.link_url' => ['nullable', 'url', 'starts_with:http://,https://', 'max:2048'],
            'blocks.*.settings.alt_text' => ['nullable', 'string', 'max:500'],
            'blocks.*.settings.image_width' => ['nullable', 'integer', 'min:0', 'max:1920'],
            'blocks.*.settings.image_width_unit' => ['nullable', Rule::in(['px', '%', 'vw'])],
            'blocks.*.settings.image_max_width' => ['nullable', 'integer', 'min:0', 'max:1920'],
            'blocks.*.settings.image_max_width_unit' => ['nullable', Rule::in(['px', '%', 'vw'])],
            'blocks.*.settings.image_height' => ['nullable', 'integer', 'min:0', 'max:1920'],
            'blocks.*.settings.image_height_unit' => ['nullable', Rule::in(['px', '%', 'vh'])],
            'blocks.*.settings.opacity' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.hover_opacity' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.image_border_radius' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.video_source' => ['nullable', Rule::in(['youtube', 'vimeo', 'dailymotion', 'videopress', 'self_hosted'])],
            'blocks.*.settings.video_aspect_ratio' => ['nullable', Rule::in(['1:1', '3:2', '4:3', '16:9', '21:9', '9:16'])],
            'blocks.*.settings.video_filter_brightness' => ['nullable', 'integer', 'min:0', 'max:200'],
            'blocks.*.settings.video_filter_contrast' => ['nullable', 'integer', 'min:0', 'max:200'],
            'blocks.*.settings.video_filter_saturation' => ['nullable', 'integer', 'min:0', 'max:200'],
            'blocks.*.settings.video_filter_hue' => ['nullable', 'integer', 'min:0', 'max:360'],
            'blocks.*.settings.video_filter_blur' => ['nullable', 'integer', 'min:0', 'max:20'],
            'blocks.*.settings.container_id' => ['nullable', 'string', 'regex:/^[A-Za-z0-9-]{8,64}$/'],
            'blocks.*.settings.parent_container_id' => ['nullable', 'string', 'regex:/^[A-Za-z0-9-]{8,64}$/'],
            'blocks.*.settings.container_layout' => ['nullable', Rule::in(['column', 'row', 'columns-2', 'columns-wide-left', 'columns-wide-right', 'columns-4', 'columns-3-center', 'rows-2', 'top-wide', 'left-tall', 'grid-3x2', 'top-3-bottom-2', 'top-2-bottom-3'])],
            'blocks.*.settings.container_slots' => ['nullable', 'integer', 'min:1', 'max:6'],
            'blocks.*.settings.container_gap' => ['nullable', 'integer', 'min:0', 'max:60'],
            'blocks.*.settings.container_column' => ['nullable', 'integer', 'min:0', 'max:5'],
            'blocks.*.settings.structure_label' => ['nullable', 'string', 'max:80'],
            'blocks.*.settings.menu_id' => ['nullable', 'integer', 'exists:navigation_menus,id'],
            'blocks.*.settings.menu_item_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.menu_item_hover_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.menu_item_active_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.menu_item_background' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.menu_item_hover_background' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.menu_item_active_background' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.menu_item_font_size' => ['nullable', 'integer', 'min:8', 'max:48'],
            'blocks.*.settings.menu_item_font_weight' => ['nullable', Rule::in(['400', '500', '600', '700', '800'])],
            'blocks.*.settings.menu_item_padding_x' => ['nullable', 'integer', 'min:0', 'max:60'],
            'blocks.*.settings.menu_item_padding_y' => ['nullable', 'integer', 'min:0', 'max:60'],
            'blocks.*.settings.menu_item_gap' => ['nullable', 'integer', 'min:0', 'max:60'],
            'blocks.*.settings.submenu_item_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.submenu_item_hover_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.submenu_item_active_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.submenu_item_background' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.submenu_item_hover_background' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.submenu_item_active_background' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.submenu_item_font_size' => ['nullable', 'integer', 'min:8', 'max:48'],
            'blocks.*.settings.submenu_item_font_weight' => ['nullable', Rule::in(['400', '500', '600', '700', '800'])],
            'blocks.*.settings.submenu_item_padding_x' => ['nullable', 'integer', 'min:0', 'max:60'],
            'blocks.*.settings.submenu_item_padding_y' => ['nullable', 'integer', 'min:0', 'max:60'],
            'blocks.*.settings.submenu_item_gap' => ['nullable', 'integer', 'min:0', 'max:60'],
            'blocks.*.settings.submenu_background' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.submenu_min_width' => ['nullable', 'integer', 'min:80', 'max:600'],
            'blocks.*.settings.submenu_padding' => ['nullable', 'integer', 'min:0', 'max:60'],
            'blocks.*.settings.submenu_border_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.settings.submenu_border_width' => ['nullable', 'integer', 'min:0', 'max:20'],
            'blocks.*.settings.submenu_border_radius' => ['nullable', 'integer', 'min:0', 'max:100'],
            'blocks.*.settings.submenu_shadow' => ['nullable', Rule::in(['none', 'soft', 'strong'])],
        ];
    }
}
