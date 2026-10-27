<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function edit(): View
    {
        return view('admin.content.edit', [
            'content' => SiteContent::current(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'province' => ['required', 'string', 'max:120'],
            'location' => ['required', 'string', 'max:160'],
            'address' => ['required', 'string', 'max:240'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:160'],
            'hero_eyebrow' => ['required', 'string', 'max:120'],
            'hero_title' => ['required', 'string', 'max:160'],
            'hero_highlight' => ['required', 'string', 'max:160'],
            'hero_description' => ['required', 'string', 'max:500'],
            'announcement_label' => ['required', 'string', 'max:40'],
            'announcement_title' => ['required', 'string', 'max:80'],
            'announcement_body' => ['required', 'string', 'max:240'],
            'services' => ['required', 'array', 'min:1', 'max:8'],
            'services.*' => ['required', 'array:title,description'],
            'services.*.title' => ['required', 'string', 'max:100'],
            'services.*.description' => ['required', 'string', 'max:220'],
            'news' => ['required', 'array', 'min:1', 'max:8'],
            'news.*' => ['required', 'array:date,author,category,title,excerpt'],
            'news.*.date' => ['required', 'string', 'max:60'],
            'news.*.author' => ['required', 'string', 'max:80'],
            'news.*.category' => ['required', 'string', 'max:50'],
            'news.*.title' => ['required', 'string', 'max:180'],
            'news.*.excerpt' => ['required', 'string', 'max:500'],
            'agendas' => ['required', 'array', 'min:1', 'max:8'],
            'agendas.*' => ['required', 'array:day,month,title,location,time'],
            'agendas.*.day' => ['required', 'string', 'max:2'],
            'agendas.*.month' => ['required', 'string', 'max:12'],
            'agendas.*.title' => ['required', 'string', 'max:180'],
            'agendas.*.location' => ['required', 'string', 'max:180'],
            'agendas.*.time' => ['required', 'string', 'max:40'],
            'transparency_title' => ['required', 'string', 'max:180'],
            'transparency_description' => ['required', 'string', 'max:500'],
            'footer_description' => ['required', 'string', 'max:500'],
        ]);

        SiteContent::query()->updateOrCreate(
            ['id' => 1],
            ['content' => $validated],
        );

        return redirect()
            ->route('admin.content.edit')
            ->with('status', 'Perubahan konten berhasil disimpan dan sudah tampil di situs.');
    }
}
