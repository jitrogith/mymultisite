<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#252f60">
    <title>Page Builder | Portal Pemerintah</title>
    <style>
        :root { color-scheme: light; --navy: #252f60; --navy-dark: #1b244e; --gold: #eeae43; --ink: #222b45; --muted: #747d94; --line: #e6e8ef; --canvas: #f5f6f9; font-family: "Segoe UI", Arial, sans-serif; }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--canvas); color: var(--ink); }
        a { color: inherit; text-decoration: none; }
        button { font: inherit; cursor: pointer; }
        .topbar { position: sticky; top: 0; z-index: 10; display: flex; height: 66px; align-items: center; justify-content: space-between; padding: 0 27px; border-bottom: 1px solid var(--line); background: #fff; }
        .brand { display: flex; align-items: center; gap: 10px; color: var(--navy); }
        .brand-mark { display: grid; width: 35px; height: 35px; place-items: center; border-radius: 7px; background: var(--navy); color: var(--gold); }
        .brand-mark svg { width: 21px; height: 21px; }
        .brand strong { display: block; font-family: Georgia, "Times New Roman", serif; font-size: 14px; }
        .brand small { display: block; margin-top: 3px; color: var(--muted); font-size: 10px; }
        .topbar-actions { display: flex; align-items: center; gap: 17px; }
        .topbar-actions a { color: #545e78; font-size: 12px; font-weight: 600; }
        .user-label { color: var(--muted); font-size: 11px; }
        .logout-button { min-height: 32px; padding: 0 11px; border: 1px solid var(--line); border-radius: 4px; background: #fff; color: #545d75; font-size: 11px; font-weight: 700; }
        .layout { display: grid; max-width: 1440px; grid-template-columns: 218px minmax(0, 1fr); gap: 25px; margin: 0 auto; padding: 25px 27px 45px; }
        .side-nav { position: sticky; top: 91px; align-self: start; padding: 16px 10px; border: 1px solid var(--line); border-radius: 6px; background: #fff; }
        .side-caption { margin: 0 8px 12px; color: #9298a7; font-size: 10px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .side-nav a { display: flex; align-items: center; gap: 9px; padding: 10px 9px; border-radius: 4px; color: #58617a; font-size: 11px; font-weight: 600; }
        .side-nav a:hover { background: #f5f6fa; color: var(--navy); }
        .side-nav a.active { background: #f3f4f8; color: var(--navy); }
        .side-nav svg { width: 15px; height: 15px; flex: 0 0 auto; }
        .side-nav .nav-group { margin-top: 12px; padding-top: 9px; border-top: 1px solid var(--line); }
        .side-nav .side-note { margin: 14px 8px 5px; padding-top: 12px; border-top: 1px solid var(--line); color: #8990a0; font-size: 10px; line-height: 1.6; }
        .main { min-width: 0; }
        .page-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 15px; margin-bottom: 18px; }
        .eyebrow { margin: 0 0 6px; color: #96702e; font-size: 10px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
        h1 { margin: 0; color: var(--navy); font-family: Georgia, "Times New Roman", serif; font-size: 25px; }
        .page-heading p { margin: 7px 0 0; color: var(--muted); font-size: 11px; }
        .create-button { display: inline-flex; min-height: 36px; align-items: center; gap: 7px; padding: 0 12px; border: 0; border-radius: 4px; background: var(--gold); color: #292d3d; font-size: 11px; font-weight: 800; }
        .create-button:hover { background: #f2bb5d; }
        .create-button svg { width: 14px; height: 14px; }
        .notice { margin-bottom: 15px; padding: 11px 13px; border: 1px solid #cfe8da; border-radius: 4px; background: #f2fbf6; color: #286e50; font-size: 12px; }
        .list-card { overflow: hidden; border: 1px solid var(--line); border-radius: 6px; background: #fff; }
        .list-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 15px 17px; border-bottom: 1px solid var(--line); }
        .list-header strong { color: var(--navy); font-size: 11px; }
        .list-header span { color: var(--muted); font-size: 11px; }
        .page-row { display: grid; grid-template-columns: minmax(0, 1fr) 105px 242px; align-items: center; gap: 12px; padding: 15px 17px; border-bottom: 1px solid #eff0f4; }
        .page-row:last-child { border-bottom: 0; }
        .page-info { display: flex; min-width: 0; align-items: flex-start; gap: 10px; }
        .page-icon { display: grid; width: 32px; height: 32px; flex: 0 0 auto; place-items: center; border-radius: 5px; background: #f0f2f8; color: var(--navy); }
        .page-icon svg { width: 16px; height: 16px; }
        .page-text { min-width: 0; padding-top: 1px; }
        .page-text strong { display: block; overflow: hidden; color: #303a57; font-size: 12px; text-overflow: ellipsis; white-space: nowrap; }
        .page-text small { display: block; overflow: hidden; margin-top: 5px; color: #8b91a0; font-size: 10px; text-overflow: ellipsis; white-space: nowrap; }
        .badge { justify-self: start; padding: 5px 7px; border-radius: 12px; background: #f2f3f6; color: #747d91; font-size: 10px; font-weight: 700; }
        .badge.published { background: #edf7f1; color: #358064; }
        .row-actions { display: flex; justify-content: flex-end; gap: 7px; }
        .action-link, .delete-button { display: inline-flex; min-height: 29px; align-items: center; justify-content: center; padding: 0 9px; border: 1px solid #e1e4eb; border-radius: 4px; background: #fff; color: #4b5571; font-size: 10px; font-weight: 700; }
        .action-link:hover { border-color: var(--navy); color: var(--navy); }
        .delete-button { border-color: #f0d9d6; color: #a74d44; }
        .delete-button:hover { background: #fff4f2; }
        .empty-state { padding: 50px 20px; text-align: center; }
        .empty-icon { display: grid; width: 47px; height: 47px; margin: 0 auto 13px; place-items: center; border-radius: 50%; background: #fff4df; color: #96702e; }
        .empty-icon svg { width: 22px; height: 22px; }
        .empty-state h2 { margin: 0; color: var(--navy); font-family: Georgia, "Times New Roman", serif; font-size: 17px; }
        .empty-state p { margin: 7px 0 15px; color: var(--muted); font-size: 11px; }
        .system-page { background: #fbfbfc; }
        @media (max-width: 850px) {
            .layout { grid-template-columns: 1fr; gap: 13px; padding: 18px 16px 36px; }
            .side-nav { position: static; display: flex; overflow-x: auto; align-items: center; gap: 4px; padding: 6px; }
            .side-caption, .side-note { display: none; }
            .side-nav .nav-group { display: contents; }
            .side-nav a { flex: 0 0 auto; padding: 9px 10px; font-size: 10px; }
            .topbar { padding: 0 16px; }
        }
        @media (max-width: 560px) {
            .topbar { height: 59px; }
            .brand small, .user-label { display: none; }
            .topbar-actions { gap: 10px; }
            .topbar-actions a { font-size: 10px; }
            .logout-button { min-height: 29px; padding: 0 8px; font-size: 10px; }
            .layout { padding-right: 11px; padding-left: 11px; }
            .page-heading { align-items: flex-start; flex-direction: column; }
            h1 { font-size: 22px; }
            .page-row { grid-template-columns: minmax(0, 1fr) auto; gap: 8px; padding: 12px; }
            .badge { grid-column: 1; margin-left: 42px; margin-top: -4px; }
            .row-actions { grid-column: 2; grid-row: 1 / span 2; flex-direction: column; }
            .action-link, .delete-button { min-height: 27px; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('admin.content.edit') }}">
            <span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M3 20h18M5 17V8l7-4 7 4v9M8 17v-5h8v5M3 8l9-5 9 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            <span><strong>Portal Pemerintah</strong><small>Panel pengelolaan konten</small></span>
        </a>
        <div class="topbar-actions"><a href="{{ route('home') }}" target="_blank" rel="noopener">Lihat situs publik ↗</a><span class="user-label">{{ auth()->user()->username }}</span><form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="logout-button" type="submit">Keluar</button></form></div>
    </header>
    <div class="layout">
        <nav class="side-nav" aria-label="Menu admin">
            <p class="side-caption">Pengelolaan situs</p>
            <a class="active" href="{{ route('admin.pages.index') }}" aria-current="page"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="4" y="3" width="16" height="18" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M8 8h8m-8 4h8m-8 4h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>Page Builder</a>
            <div class="nav-group"><a href="{{ route('admin.menus.index') }}">Pembuat Menu</a><a href="{{ route('admin.content.edit') }}"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 5h16v14H4zM8 9h8m-8 4h8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>Konten beranda</a></div>
            <p class="side-note">Buat dan kelola halaman informasi untuk situs publik.</p>
        </nav>
        <main class="main">
            <div class="page-heading">
                <div><p class="eyebrow">Pengelolaan halaman</p><h1>Page Builder</h1><p>Tambah, ubah, terbitkan, dan kelola halaman situs.</p></div>
                <a class="create-button" href="{{ route('admin.pages.create') }}"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14m-7-7h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>Buat halaman</a>
            </div>
            @if (session('status'))
                <div class="notice" role="status">{{ session('status') }}</div>
            @endif
            <section class="list-card" aria-labelledby="pages-heading">
                <div class="list-header"><strong id="pages-heading">Halaman di situs</strong><span>{{ $pages->count() + 1 }} halaman</span></div>
                <div class="page-row system-page">
                    <div class="page-info"><span class="page-icon"><svg viewBox="0 0 24 24" fill="none"><path d="M3 20h18M5 17V8l7-4 7 4v9M8 17v-5h8v5M3 8l9-5 9 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></span><span class="page-text"><strong>Beranda</strong><small>/ · Halaman utama situs</small></span></div>
                    <span class="badge published">Halaman utama</span>
                    <div class="row-actions"><a class="action-link" href="{{ route('home') }}" target="_blank" rel="noopener">Lihat situs</a><a class="action-link" href="{{ route('admin.content.edit') }}">Edit konten</a></div>
                </div>
                @forelse ($pages as $page)
                    <div class="page-row">
                        <div class="page-info"><span class="page-icon"><svg viewBox="0 0 24 24" fill="none"><path d="M5 3.5h9l5 5v12H5v-17Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M14 3.5v5h5m-10 4h6m-6 4h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></span><span class="page-text"><strong>{{ $page->title }}</strong><small>/halaman/{{ $page->slug }} · Diperbarui {{ $page->updated_at->format('d M Y, H:i') }}</small></span></div>
                        <span class="badge {{ $page->status === 'published' ? 'published' : '' }}">{{ $page->status === 'published' ? 'Terbit' : 'Draf' }}</span>
                        <div class="row-actions">
                            @if ($page->status === 'published')<a class="action-link" href="{{ route('pages.show', $page->slug) }}" target="_blank" rel="noopener">Lihat</a>@endif
                            <a class="action-link" href="{{ route('admin.pages.visual-editor', $page) }}">VEditor</a>
                            <a class="action-link" href="{{ route('admin.pages.edit', $page) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" data-confirm-delete data-title="{{ $page->title }}">@csrf @method('DELETE')<button class="delete-button" type="submit">Hapus</button></form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <span class="empty-icon"><svg viewBox="0 0 24 24" fill="none"><path d="M5 3.5h9l5 5v12H5v-17Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M14 3.5v5h5m-10 4h6m-6 4h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></span>
                        <h2>Belum ada halaman tambahan</h2>
                        <p>Mulai susun halaman baru dengan blok konten yang mudah dikelola.</p>
                        <a class="create-button" href="{{ route('admin.pages.create') }}">Buat halaman pertama</a>
                    </div>
                @endforelse
            </section>
        </main>
    </div>
    <script>
        document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (!window.confirm(`Hapus halaman "${form.dataset.title}"? Tindakan ini tidak dapat dibatalkan.`)) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
