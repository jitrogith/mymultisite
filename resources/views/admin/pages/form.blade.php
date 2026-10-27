<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#252f60">
    <title>{{ $isEditing ? 'Edit halaman' : 'Buat halaman' }} | Page Builder</title>
    <style>
        :root { color-scheme: light; --navy: #252f60; --navy-dark: #1b244e; --gold: #eeae43; --ink: #222b45; --muted: #747d94; --line: #e6e8ef; --canvas: #f5f6f9; font-family: "Segoe UI", Arial, sans-serif; }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; scroll-padding-top: 80px; }
        body { margin: 0; background: var(--canvas); color: var(--ink); }
        a { color: inherit; text-decoration: none; }
        button, input, textarea, select { font: inherit; }
        button { cursor: pointer; }
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
        .back-link { display: inline-block; margin-bottom: 13px; color: #67718b; font-size: 11px; font-weight: 600; }
        .back-link:hover { color: var(--navy); }
        .page-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 15px; margin-bottom: 18px; }
        .eyebrow { margin: 0 0 6px; color: #96702e; font-size: 10px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
        h1 { margin: 0; color: var(--navy); font-family: Georgia, "Times New Roman", serif; font-size: 24px; }
        .page-heading p { margin: 7px 0 0; color: var(--muted); font-size: 11px; }
        .notice { margin-bottom: 15px; padding: 11px 13px; border: 1px solid #cfe8da; border-radius: 4px; background: #f2fbf6; color: #286e50; font-size: 12px; }
        .errors { margin-bottom: 15px; padding: 11px 14px; border: 1px solid #f1d0cc; border-radius: 4px; background: #fff5f4; color: #a7443a; font-size: 11px; line-height: 1.6; }
        .content-section { margin-bottom: 14px; padding: 19px 20px 20px; border: 1px solid var(--line); border-radius: 6px; background: #fff; }
        .section-head { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; padding-bottom: 13px; border-bottom: 1px solid #eef0f4; }
        .section-number { display: grid; width: 27px; height: 27px; flex: 0 0 auto; place-items: center; border-radius: 5px; background: #fff4df; color: #87601e; font-size: 11px; font-weight: 800; }
        .section-head h2 { margin: 0; color: var(--navy); font-size: 14px; font-weight: 800; }
        .section-head p { margin: 4px 0 0; color: #8990a0; font-size: 10px; }
        .fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 13px 15px; }
        .field.full { grid-column: 1 / -1; }
        .field label { display: block; margin-bottom: 6px; color: #49516a; font-size: 11px; font-weight: 700; }
        .field input, .field textarea, .field select { width: 100%; min-height: 37px; padding: 8px 10px; border: 1px solid #dfe2e9; border-radius: 4px; outline: 0; background: #fff; color: var(--ink); font-size: 12px; }
        .field textarea { min-height: 90px; resize: vertical; line-height: 1.6; }
        .field input:focus, .field textarea:focus, .field select:focus { border-color: #8993b4; box-shadow: 0 0 0 3px #252f600d; }
        .helper { margin-top: 5px; color: #9399a7; font-size: 10px; }
        .block-list { display: grid; gap: 11px; }
        .block-card { padding: 13px; border: 1px solid #e8eaf0; border-radius: 5px; background: #fcfcfd; }
        .block-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 10px; }
        .block-title { color: #566079; font-size: 11px; font-weight: 800; }
        .block-controls { display: flex; align-items: center; gap: 5px; }
        .block-controls button { min-height: 25px; padding: 0 7px; border: 1px solid #e0e3ea; border-radius: 3px; background: #fff; color: #5b657e; font-size: 10px; }
        .block-controls button:hover { border-color: var(--navy); color: var(--navy); }
        .block-controls .remove-block { border-color: #f0d9d6; color: #a74d44; }
        .block-fields { display: grid; grid-template-columns: 150px minmax(0,1fr); gap: 11px; }
        .block-fields label { display: block; margin-bottom: 6px; color: #49516a; font-size: 11px; font-weight: 700; }
        .block-fields select, .block-fields textarea { width: 100%; min-height: 37px; padding: 8px 10px; border: 1px solid #dfe2e9; border-radius: 4px; outline: 0; background: #fff; color: var(--ink); font-size: 12px; }
        .block-fields textarea { min-height: 76px; resize: vertical; line-height: 1.6; }
        .block-fields select:focus, .block-fields textarea:focus { border-color: #8993b4; box-shadow: 0 0 0 3px #252f600d; }
        .block-help { margin-top: 5px; color: #9399a7; font-size: 10px; }
        .block-actions { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 8px; margin-top: 12px; }
        .add-block { min-height: 32px; padding: 0 10px; border: 1px dashed #bdc3d1; border-radius: 4px; background: #fff; color: var(--navy); font-size: 11px; font-weight: 700; }
        .add-block:hover { border-color: var(--navy); }
        .form-footer { position: sticky; bottom: 0; display: flex; align-items: center; justify-content: space-between; gap: 14px; margin-top: 18px; padding: 12px 14px; border: 1px solid var(--line); border-radius: 5px; background: #fffffff2; box-shadow: 0 -5px 18px #1d264f08; backdrop-filter: blur(8px); }
        .form-footer p { margin: 0; color: #7c8497; font-size: 10px; }
        .save-button { display: inline-flex; min-height: 37px; align-items: center; gap: 8px; padding: 0 14px; border: 0; border-radius: 4px; background: var(--gold); color: #2b2b36; font-size: 11px; font-weight: 800; }
        .save-button:hover { background: #f2bb5d; }
        .save-button svg { width: 14px; height: 14px; }
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
            .content-section { padding: 15px 13px; }
            .fields { grid-template-columns: 1fr; gap: 11px; }
            .field.full { grid-column: auto; }
            .block-fields { grid-template-columns: 1fr; }
            .form-footer { align-items: flex-start; flex-direction: column; }
            .save-button { width: 100%; justify-content: center; }
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
            <a class="active" href="{{ route('admin.pages.index') }}"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="4" y="3" width="16" height="18" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M8 8h8m-8 4h8m-8 4h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>Page Builder</a>
            <div class="nav-group"><a href="{{ route('admin.menus.index') }}">Pembuat Menu</a><a href="{{ route('admin.content.edit') }}"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 5h16v14H4zM8 9h8m-8 4h8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>Konten beranda</a></div>
            <p class="side-note">Susun halaman informasi dengan blok konten yang sederhana.</p>
        </nav>
        <main class="main">
            <a class="back-link" href="{{ route('admin.pages.index') }}">← Kembali ke daftar halaman</a>
            <div class="page-heading"><div><p class="eyebrow">Page Builder</p><h1>{{ $isEditing ? 'Edit halaman' : 'Buat halaman baru' }}</h1><p>Atur informasi halaman dan susun konten dengan blok.</p></div>@if ($isEditing)<a class="save-button" href="{{ route('admin.pages.visual-editor', $page) }}">Buka VEditor →</a>@endif</div>
            @if (session('status'))<div class="notice" role="status">{{ session('status') }}</div>@endif
            @if ($errors->any())<div class="errors" role="alert"><strong>Halaman belum tersimpan.</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form method="POST" action="{{ $isEditing ? route('admin.pages.update', $page) : route('admin.pages.store') }}" id="pageForm">
                @csrf
                @if ($isEditing) @method('PUT') @endif
                <section class="content-section">
                    <div class="section-head"><span class="section-number">01</span><span><h2>Informasi halaman</h2><p>Judul dan alamat halaman publik.</p></span></div>
                    <div class="fields">
                        <div class="field"><label for="title">Judul halaman</label><input id="title" name="title" value="{{ old('title', $page->title) }}" maxlength="160" required></div>
                        <div class="field"><label for="slug">Slug URL</label><input id="slug" name="slug" value="{{ old('slug', $page->slug) }}" maxlength="180" pattern="[a-z0-9]+(?:-[a-z0-9]+)*" required><div class="helper">Alamat: {{ url('/halaman') }}/<span id="slugPreview">{{ old('slug', $page->slug) ?: 'nama-halaman' }}</span></div></div>
                        <div class="field full"><label for="summary">Ringkasan halaman</label><textarea id="summary" name="summary" maxlength="300">{{ old('summary', $page->summary) }}</textarea></div>
                        <div class="field"><label for="status">Status halaman</label><select id="status" name="status" required><option value="draft" @selected(old('status', $page->status) === 'draft')>Draf — tidak tampil untuk publik</option><option value="published" @selected(old('status', $page->status) === 'published')>Terbit — dapat diakses publik</option></select></div>
                    </div>
                </section>
                <section class="content-section">
                    <div class="section-head"><span class="section-number">02</span><span><h2>Susun konten halaman</h2><p>Tambahkan blok dan atur urutannya. Konten ditampilkan dengan aman sebagai teks.</p></span></div>
                    <div class="block-list" id="blockList" data-block-list>
                        @foreach (old('blocks', $page->blocks ?? []) as $index => $block)
                            <article class="block-card" data-block>
                                <div class="block-top"><span class="block-title">Blok {{ $index + 1 }}</span><span class="block-controls"><button type="button" data-move="up" aria-label="Geser blok ke atas">↑</button><button type="button" data-move="down" aria-label="Geser blok ke bawah">↓</button><button class="remove-block" type="button" data-remove>Hapus</button></span></div>
                                <div class="block-fields">
                                    <div><label>Jenis blok</label><select data-field="type" name="blocks[{{ $index }}][type]" required><option value="heading" @selected($block['type'] === 'heading')>Subjudul</option><option value="paragraph" @selected($block['type'] === 'paragraph')>Paragraf</option><option value="quote" @selected($block['type'] === 'quote')>Kutipan</option><option value="list" @selected($block['type'] === 'list')>Daftar</option></select></div>
                                    <div><label>Isi konten</label><textarea data-field="text" name="blocks[{{ $index }}][text]" maxlength="5000" required>{{ $block['text'] }}</textarea><div class="block-help" data-block-help>Untuk daftar, masukkan satu butir per baris.</div></div>
                                </div>
                                @foreach (($block['settings'] ?? []) as $settingKey => $settingValue)
                                    <input type="hidden" data-setting="{{ $settingKey }}" name="blocks[{{ $index }}][settings][{{ $settingKey }}]" value="{{ $settingValue }}">
                                @endforeach
                            </article>
                        @endforeach
                    </div>
                    <div class="block-actions"><button class="add-block" type="button" data-add-block="list">+ Daftar</button><button class="add-block" type="button" data-add-block="quote">+ Kutipan</button><button class="add-block" type="button" data-add-block="paragraph">+ Paragraf</button><button class="add-block" type="button" data-add-block="heading">+ Subjudul</button></div>
                </section>
                <div class="form-footer"><p>{{ $isEditing && $page->status === 'published' ? 'Halaman sedang terbit. Perubahan tersimpan langsung ke halaman publik.' : 'Draf tidak akan terlihat oleh publik hingga diterbitkan.' }}</p><button class="save-button" type="submit"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 4h12l3 3v13H4V4h1Zm3 0v6h8V4M8 20v-7h8v7" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>{{ $isEditing ? 'Simpan perubahan' : 'Simpan halaman' }}</button></div>
            </form>
        </main>
    </div>
    <template id="blockTemplate"><article class="block-card" data-block><div class="block-top"><span class="block-title"></span><span class="block-controls"><button type="button" data-move="up" aria-label="Geser blok ke atas">↑</button><button type="button" data-move="down" aria-label="Geser blok ke bawah">↓</button><button class="remove-block" type="button" data-remove>Hapus</button></span></div><div class="block-fields"><div><label>Jenis blok</label><select data-field="type" required><option value="heading">Subjudul</option><option value="paragraph">Paragraf</option><option value="quote">Kutipan</option><option value="list">Daftar</option></select></div><div><label>Isi konten</label><textarea data-field="text" maxlength="5000" required></textarea><div class="block-help" data-block-help>Untuk daftar, masukkan satu butir per baris.</div></div></div></article></template>
    <script>
        const blockList = document.getElementById('blockList');
        const blockLimit = 30;

        function updateBlocks() {
            blockList.querySelectorAll('[data-block]').forEach((block, index) => {
                block.querySelector('.block-title').textContent = `Blok ${index + 1}`;
                block.querySelectorAll('[data-field]').forEach((field) => {
                    field.name = `blocks[${index}][${field.dataset.field}]`;
                });
                block.querySelectorAll('[data-setting]').forEach((field) => {
                    field.name = `blocks[${index}][settings][${field.dataset.setting}]`;
                });
                block.querySelector('[data-move="up"]').disabled = index === 0;
                block.querySelector('[data-move="down"]').disabled = index === blockList.children.length - 1;
                block.querySelector('[data-remove]').disabled = blockList.children.length === 1;
                const type = block.querySelector('[data-field="type"]').value;
                if (type !== 'heading') block.querySelectorAll('[data-setting]').forEach((field) => field.remove());
                block.querySelector('[data-block-help]').textContent = type === 'list' ? 'Untuk daftar, masukkan satu butir per baris.' : 'Teks ditampilkan sesuai jenis blok yang dipilih.';
            });
            document.querySelectorAll('[data-add-block]').forEach((button) => {
                button.disabled = blockList.children.length >= blockLimit;
            });
        }

        document.querySelectorAll('[data-add-block]').forEach((button) => {
            button.addEventListener('click', () => {
                if (blockList.children.length >= blockLimit) return;
                const fragment = document.getElementById('blockTemplate').content.cloneNode(true);
                fragment.querySelector('[data-field="type"]').value = button.dataset.addBlock;
                blockList.append(fragment);
                updateBlocks();
                blockList.lastElementChild.querySelector('[data-field="text"]').focus();
            });
        });

        blockList.addEventListener('input', (event) => {
            if (event.target.matches('[data-field="type"]')) updateBlocks();
        });

        blockList.addEventListener('change', (event) => {
            if (event.target.matches('[data-field="type"]')) updateBlocks();
        });

        blockList.addEventListener('click', (event) => {
            const removeButton = event.target.closest('[data-remove]');
            if (removeButton) {
                if (blockList.children.length > 1) removeButton.closest('[data-block]').remove();
                updateBlocks();
                return;
            }

            const moveButton = event.target.closest('[data-move]');
            if (!moveButton) return;
            const block = moveButton.closest('[data-block]');
            if (moveButton.dataset.move === 'up' && block.previousElementSibling) {
                blockList.insertBefore(block, block.previousElementSibling);
            } else if (moveButton.dataset.move === 'down' && block.nextElementSibling) {
                blockList.insertBefore(block.nextElementSibling, block);
            }
            updateBlocks();
        });

        document.getElementById('title').addEventListener('input', (event) => {
            const slug = document.getElementById('slug');
            if (slug.dataset.edited === 'true') return;
            slug.value = event.target.value
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-|-$/g, '');
            document.getElementById('slugPreview').textContent = slug.value || 'nama-halaman';
        });

        document.getElementById('slug').addEventListener('input', (event) => {
            event.target.dataset.edited = 'true';
            event.target.value = event.target.value
                .toLowerCase()
                .replace(/[^a-z0-9-]+/g, '-')
                .replace(/-{2,}/g, '-')
                .replace(/^-|-$/g, '');
            document.getElementById('slugPreview').textContent = event.target.value || 'nama-halaman';
        });

        updateBlocks();
    </script>
</body>
</html>
