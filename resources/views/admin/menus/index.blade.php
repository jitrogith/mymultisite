<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#252f60">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembuat Menu | Portal Pemerintah</title>
    <style>
        :root { color-scheme: light; --navy: #252f60; --gold: #eeae43; --ink: #222b45; --muted: #747d94; --line: #e6e8ef; --canvas: #f5f6f9; font-family: "Segoe UI", Arial, sans-serif; }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--canvas); color: var(--ink); }
        a { color: inherit; text-decoration: none; }
        button, input, select { font: inherit; }
        button { cursor: pointer; }
        .topbar { position: sticky; top: 0; z-index: 2; display: flex; height: 66px; align-items: center; justify-content: space-between; padding: 0 27px; border-bottom: 1px solid var(--line); background: #fff; }
        .brand { display: flex; align-items: center; gap: 10px; color: var(--navy); }
        .brand-mark { display: grid; width: 35px; height: 35px; place-items: center; border-radius: 7px; background: var(--navy); color: var(--gold); }
        .brand strong, .brand small { display: block; }
        .brand strong { font-family: Georgia, "Times New Roman", serif; font-size: 14px; }
        .brand small { margin-top: 3px; color: var(--muted); font-size: 10px; }
        .top-actions { display: flex; align-items: center; gap: 16px; color: #545e78; font-size: 11px; }
        .logout { min-height: 31px; padding: 0 10px; border: 1px solid var(--line); border-radius: 4px; background: white; color: #545d75; font-weight: 700; }
        .layout { display: grid; max-width: 1440px; grid-template-columns: 218px minmax(0, 1fr); gap: 25px; margin: 0 auto; padding: 25px 27px 45px; }
        .side-nav { position: sticky; top: 91px; align-self: start; padding: 16px 10px; border: 1px solid var(--line); border-radius: 6px; background: #fff; }
        .side-caption { margin: 0 8px 12px; color: #9298a7; font-size: 10px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .side-nav a { display: flex; padding: 10px 9px; border-radius: 4px; color: #58617a; font-size: 11px; font-weight: 600; }
        .side-nav a.active, .side-nav a:hover { background: #f3f4f8; color: var(--navy); }
        .side-nav .nav-group { margin-top: 12px; padding-top: 9px; border-top: 1px solid var(--line); }
        .side-note { margin: 14px 8px 5px; padding-top: 12px; border-top: 1px solid var(--line); color: #8990a0; font-size: 10px; line-height: 1.6; }
        .main { min-width: 0; }
        .page-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 15px; margin-bottom: 18px; }
        .eyebrow { margin: 0 0 6px; color: #96702e; font-size: 10px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
        h1 { margin: 0; color: var(--navy); font-family: Georgia, "Times New Roman", serif; font-size: 25px; }
        .page-heading p { margin: 7px 0 0; color: var(--muted); font-size: 11px; }
        .notice, .errors { margin: 0 0 14px; padding: 11px 13px; border: 1px solid #cfe8da; border-radius: 4px; background: #f2fbf6; color: #286e50; font-size: 12px; }
        .errors { border-color: #f0d9d6; background: #fff4f2; color: #a74d44; }
        .errors ul { margin: 7px 0 0; padding-left: 19px; }
        .card { margin-bottom: 16px; border: 1px solid var(--line); border-radius: 6px; background: #fff; }
        .card-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 16px; border-bottom: 1px solid var(--line); }
        .card-head strong { color: var(--navy); font-size: 12px; }
        .card-head span { color: var(--muted); font-size: 10px; }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 10px; padding: 14px; }
        .menu-card { min-width: 0; padding: 12px; border: 1px solid var(--line); border-radius: 5px; background: #fff; }
        .menu-card.active { border-color: #b9c1d5; box-shadow: inset 3px 0 var(--navy); }
        .menu-card-name { display: block; overflow: hidden; margin-bottom: 10px; color: #303a57; font-size: 12px; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
        .menu-card-actions { display: flex; align-items: center; gap: 7px; }
        .menu-card-actions form { margin: 0; }
        .button, .small-button { display: inline-flex; min-height: 32px; align-items: center; justify-content: center; padding: 0 11px; border: 1px solid #dfe2e9; border-radius: 4px; background: #fff; color: #4b5571; font-size: 10px; font-weight: 700; }
        .button.primary { border-color: var(--gold); background: var(--gold); color: #292d3d; }
        .button.danger, .small-button.danger { border-color: #f0d9d6; color: #a74d44; }
        .button:hover, .small-button:hover { border-color: var(--navy); color: var(--navy); }
        .inline-form { display: flex; flex-wrap: wrap; align-items: end; gap: 9px; padding: 14px 16px; border-top: 1px solid var(--line); background: #fbfbfc; }
        .inline-form label, .item-form label { display: grid; min-width: 150px; flex: 1; gap: 5px; color: #566078; font-size: 10px; font-weight: 700; }
        input, select { width: 100%; min-height: 35px; padding: 7px 9px; border: 1px solid #d9dde6; border-radius: 4px; background: white; color: var(--ink); font-size: 11px; }
        input:focus, select:focus { border-color: #8490af; outline: 2px solid #252f6018; }
        .workspace { display: grid; grid-template-columns: minmax(0, 1fr) minmax(270px, 340px); align-items: start; gap: 16px; }
        .item-list { padding: 7px 13px 13px; }
        .item-row { display: grid; min-height: 55px; grid-template-columns: 26px minmax(0, 1fr) auto; align-items: center; gap: 9px; padding: 7px 9px; border-bottom: 1px solid #eff0f4; background: #fff; cursor: grab; }
        .item-row:last-child { border-bottom: 0; }
        .item-row:active { cursor: grabbing; }
        .item-row.dragging { opacity: .45; }
        .item-row.drop-before { box-shadow: inset 0 3px var(--gold); }
        .item-row.drop-after { box-shadow: inset 0 -3px var(--gold); }
        .drag-handle { color: #9299a8; font-size: 19px; text-align: center; }
        .item-info { min-width: 0; }
        .item-info strong { display: block; overflow: hidden; color: #303a57; font-size: 11px; text-overflow: ellipsis; white-space: nowrap; }
        .item-info small { display: block; overflow: hidden; margin-top: 4px; color: #858d9e; font-size: 9px; text-overflow: ellipsis; white-space: nowrap; }
        .item-actions { display: flex; gap: 5px; }
        .small-button { min-height: 27px; padding: 0 8px; font-size: 9px; }
        .item-form { padding: 15px; }
        .item-form h2 { margin: 0 0 13px; color: var(--navy); font-size: 13px; }
        .item-form label { margin-bottom: 11px; }
        .field-help { margin: -6px 0 10px; color: #858d9e; font-size: 9px; line-height: 1.5; }
        .item-actions-form { display: flex; gap: 7px; margin-top: 4px; }
        .empty { padding: 27px 15px; color: #858d9e; font-size: 11px; text-align: center; }
        .toast { position: fixed; right: 20px; bottom: 20px; z-index: 5; max-width: 340px; padding: 11px 14px; border-radius: 4px; background: var(--navy); color: white; font-size: 11px; opacity: 0; pointer-events: none; transform: translateY(8px); transition: .18s; }
        .toast.visible { opacity: 1; transform: translateY(0); }
        @media (max-width: 900px) { .workspace { grid-template-columns: 1fr; } .item-form { order: -1; } }
        @media (max-width: 760px) { .layout { grid-template-columns: 1fr; gap: 13px; padding: 18px 16px 36px; } .side-nav { position: static; display: flex; overflow-x: auto; gap: 4px; padding: 6px; } .side-caption, .side-note { display: none; } .side-nav a { flex: 0 0 auto; } .side-nav .nav-group { display: contents; } .topbar { padding: 0 16px; } }
        @media (max-width: 560px) { .topbar { height: 59px; } .brand small, .user-name { display: none; } .top-actions { gap: 8px; font-size: 10px; } .layout { padding-right: 11px; padding-left: 11px; } .page-heading { align-items: flex-start; flex-direction: column; } h1 { font-size: 22px; } .item-row { grid-template-columns: 22px minmax(0, 1fr); } .item-actions { grid-column: 2; } }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('admin.content.edit') }}"><span class="brand-mark" aria-hidden="true">◆</span><span><strong>Portal Pemerintah</strong><small>Panel pengelolaan konten</small></span></a>
        <div class="top-actions"><a href="{{ route('home') }}" target="_blank" rel="noopener">Lihat situs publik ↗</a><span class="user-name">{{ auth()->user()->username }}</span><form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="logout" type="submit">Keluar</button></form></div>
    </header>
    <div class="layout">
        <nav class="side-nav" aria-label="Menu admin">
            <p class="side-caption">Pengelolaan situs</p>
            <a href="{{ route('admin.pages.index') }}">Page Builder</a>
            <a class="active" href="{{ route('admin.menus.index') }}" aria-current="page">Pembuat Menu</a>
            <div class="nav-group"><a href="{{ route('admin.content.edit') }}">Konten beranda</a></div>
            <p class="side-note">Kelola susunan navigasi yang digunakan pada halaman situs.</p>
        </nav>
        <main class="main">
            <div class="page-heading"><div><p class="eyebrow">Pengelolaan situs</p><h1>Pembuat Menu</h1><p>Buat navigasi dari teks manual atau halaman, susun urutan dan submenu hingga tiga lapis.</p></div></div>
            @if (session('status'))<div class="notice" role="status">{{ session('status') }}</div>@endif
            @if ($errors->any())<div class="errors" role="alert"><strong>Perubahan belum tersimpan.</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

            <section class="card" aria-labelledby="menus-title">
                <div class="card-head"><strong id="menus-title">Menu situs</strong><span>{{ $menus->count() }} menu</span></div>
                @if ($menus->isNotEmpty())
                    <div class="menu-grid">
                        @foreach ($menus as $menu)
                            <div class="menu-card {{ $selectedMenu?->id === $menu->id ? 'active' : '' }}">
                                <a class="menu-card-name" href="{{ route('admin.menus.index', ['menu' => $menu->id]) }}">{{ $menu->name }}</a>
                                <div class="menu-card-actions">
                                    <form class="rename-form" method="POST" action="{{ route('admin.menus.update', $menu) }}">@csrf @method('PUT')<input type="hidden" name="name" value="{{ $menu->name }}"><button class="small-button" type="button">Ubah nama</button></form>
                                    <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" onsubmit="return confirm('Hapus menu {{ $menu->name }} beserta seluruh isinya?')">@csrf @method('DELETE')<button class="small-button danger" type="submit">Hapus</button></form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <form class="inline-form" method="POST" action="{{ route('admin.menus.store') }}">
                    @csrf
                    <label>Nama menu baru<input type="text" name="name" maxlength="120" required value="{{ old('name') }}" placeholder="Contoh: Menu Utama"></label>
                    <button class="button primary" type="submit">Buat menu</button>
                </form>
            </section>

            @if ($selectedMenu)
                @php
                    $itemsById = $items->keyBy('id');
                    $depthOf = function ($item) use ($itemsById): int {
                        $depth = 1;
                        $parentId = $item->parent_id;
                        while ($parentId && $depth < 4) {
                            $depth++;
                            $parentId = $itemsById->get($parentId)?->parent_id;
                        }
                        return $depth;
                    };
                    $excludedParentIds = [];
                    if ($editingItem) {
                        foreach ($items as $candidate) {
                            $ancestorId = $candidate->id;
                            while ($ancestorId) {
                                if ($ancestorId === $editingItem->id) {
                                    $excludedParentIds[] = $candidate->id;
                                    break;
                                }
                                $ancestorId = $itemsById->get($ancestorId)?->parent_id;
                            }
                        }
                    }
                    $parentOptions = $items->filter(fn ($item) => $depthOf($item) < 3 && ! in_array($item->id, $excludedParentIds, true));
                @endphp
                <div class="workspace">
                    <section class="card" aria-labelledby="items-title">
                        <div class="card-head"><strong id="items-title">Isi menu: {{ $selectedMenu->name }}</strong><span>{{ $items->count() }} item · seret untuk mengurutkan</span></div>
                        <div class="item-list" id="menuItemList" data-reorder-url="{{ route('admin.menus.items.reorder', $selectedMenu) }}">
                            @forelse ($items as $item)
                                @php
                                    $itemPage = $item->page;
                                    $itemLabel = $item->type === 'page' && $itemPage ? $itemPage->title : $item->label;
                                @endphp
                                <div class="item-row" draggable="true" data-item-id="{{ $item->id }}" style="padding-left: {{ 9 + (($depthOf($item) - 1) * 22) }}px">
                                    <span class="drag-handle" aria-hidden="true">⠿</span>
                                    <span class="item-info"><strong>{{ $itemLabel }}</strong><small>{{ $item->type === 'page' ? 'Halaman' : ($item->url ?: 'Teks tanpa tautan') }}@if ($item->parent_id) · Submenu @endif</small></span>
                                    <span class="item-actions">
                                        <a class="small-button" href="{{ route('admin.menus.index', ['menu' => $selectedMenu->id, 'edit_item' => $item->id]) }}">Edit</a>
                                        <form method="POST" action="{{ route('admin.menus.items.destroy', [$selectedMenu, $item]) }}" onsubmit="return confirm('Hapus item ini beserta submenu di bawahnya?')">@csrf @method('DELETE')<button class="small-button danger" type="submit">Hapus</button></form>
                                    </span>
                                </div>
                            @empty
                                <div class="empty">Belum ada isi pada menu ini. Tambahkan item dari formulir.</div>
                            @endforelse
                        </div>
                    </section>
                    <section class="card item-form-card">
                        <form class="item-form" method="POST" action="{{ $editingItem ? route('admin.menus.items.update', [$selectedMenu, $editingItem]) : route('admin.menus.items.store', $selectedMenu) }}">
                            @csrf
                            @if ($editingItem) @method('PUT') @endif
                            <h2>{{ $editingItem ? 'Edit item menu' : 'Tambah item menu' }}</h2>
                            <label>Jenis item
                                <select name="type" id="itemType">
                                    <option value="manual" @selected(old('type', $editingItem?->type ?? 'manual') === 'manual')>Teks manual</option>
                                    <option value="page" @selected(old('type', $editingItem?->type) === 'page')>Halaman</option>
                                </select>
                            </label>
                            <label id="labelField">Nama menu<input type="text" name="label" maxlength="160" value="{{ old('label', $editingItem && $editingItem->type === 'manual' ? $editingItem->label : '') }}" placeholder="Contoh: Profil Daerah"></label>
                            <label id="urlField">Link (opsional)<input type="url" name="url" maxlength="2048" value="{{ old('url', $editingItem?->url) }}" placeholder="https://contoh.go.id"></label>
                            <p class="field-help" id="urlHelp">Biarkan kosong jika teks menu tidak perlu menjadi tautan.</p>
                            <label id="pageField">Pilih halaman
                                <select name="page_id">
                                    <option value="">Pilih halaman</option>
                                    @foreach ($pages as $page)
                                        <option value="{{ $page->id }}" @selected((string) old('page_id', $editingItem?->page_id) === (string) $page->id)>{{ $page->title }} ({{ $page->status === 'published' ? 'Terbit' : 'Draf' }})</option>
                                    @endforeach
                                </select>
                            </label>
                            <label>Jadikan submenu dari
                                <select name="parent_id">
                                    <option value="">Menu utama (tanpa induk)</option>
                                    @foreach ($parentOptions as $parentOption)
                                        <option value="{{ $parentOption->id }}" @selected((string) old('parent_id', $editingItem?->parent_id) === (string) $parentOption->id)>{{ str_repeat('— ', $depthOf($parentOption)) }}{{ $parentOption->type === 'page' && $parentOption->page ? $parentOption->page->title : $parentOption->label }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <p class="field-help">Maksimal tiga lapis menu. Item dengan submenu yang ada tidak dapat dipindahkan melebihi batas ini.</p>
                            <div class="item-actions-form"><button class="button primary" type="submit">{{ $editingItem ? 'Simpan perubahan' : 'Tambah item' }}</button>@if ($editingItem)<a class="button" href="{{ route('admin.menus.index', ['menu' => $selectedMenu->id]) }}">Batal</a>@endif</div>
                        </form>
                    </section>
                </div>
            @endif
        </main>
    </div>
    <div class="toast" id="menuToast" role="status" aria-live="polite"></div>
    <script>
        document.querySelectorAll('.rename-form').forEach((form) => {
            form.querySelector('button').addEventListener('click', () => {
                const name = window.prompt('Nama menu:', form.querySelector('[name="name"]').value);
                if (name === null || !name.trim()) return;
                form.querySelector('[name="name"]').value = name.trim();
                form.submit();
            });
        });

        const itemType = document.getElementById('itemType');
        const updateItemFields = () => {
            if (!itemType) return;
            const isPage = itemType.value === 'page';
            document.getElementById('labelField').hidden = isPage;
            document.getElementById('urlField').hidden = isPage;
            document.getElementById('urlHelp').hidden = isPage;
            document.getElementById('pageField').hidden = !isPage;
            document.querySelector('#labelField input').disabled = isPage;
            document.querySelector('#urlField input').disabled = isPage;
            document.querySelector('#pageField select').disabled = !isPage;
        };
        itemType?.addEventListener('change', updateItemFields);
        updateItemFields();

        const itemList = document.getElementById('menuItemList');
        if (itemList) {
            let draggedRow = null;
            let toastTimer;
            const toast = (message) => {
                const node = document.getElementById('menuToast');
                node.textContent = message;
                node.classList.add('visible');
                window.clearTimeout(toastTimer);
                toastTimer = window.setTimeout(() => node.classList.remove('visible'), 3000);
            };
            const saveOrder = async () => {
                const ids = Array.from(itemList.querySelectorAll('.item-row')).map((row) => Number(row.dataset.itemId));
                try {
                    const response = await fetch(itemList.dataset.reorderUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ items: ids }),
                    });
                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message || Object.values(result.errors || {}).flat()[0] || 'Urutan menu gagal disimpan.');
                    toast(result.message);
                } catch (error) {
                    toast(error instanceof Error ? error.message : 'Urutan menu gagal disimpan.');
                    window.setTimeout(() => window.location.reload(), 1200);
                }
            };
            itemList.querySelectorAll('.item-row').forEach((row) => {
                row.addEventListener('dragstart', (event) => {
                    if (event.target.closest('a, button, form')) {
                        event.preventDefault();
                        return;
                    }
                    draggedRow = row;
                    row.classList.add('dragging');
                    event.dataTransfer.effectAllowed = 'move';
                    event.dataTransfer.setData('text/plain', row.dataset.itemId);
                });
                row.addEventListener('dragend', () => {
                    row.classList.remove('dragging');
                    itemList.querySelectorAll('.item-row').forEach((item) => item.classList.remove('drop-before', 'drop-after'));
                    draggedRow = null;
                });
                row.addEventListener('dragover', (event) => {
                    if (!draggedRow || draggedRow === row) return;
                    event.preventDefault();
                    const before = event.clientY < row.getBoundingClientRect().top + row.offsetHeight / 2;
                    row.classList.toggle('drop-before', before);
                    row.classList.toggle('drop-after', !before);
                });
                row.addEventListener('dragleave', (event) => {
                    if (!row.contains(event.relatedTarget)) row.classList.remove('drop-before', 'drop-after');
                });
                row.addEventListener('drop', (event) => {
                    if (!draggedRow || draggedRow === row) return;
                    event.preventDefault();
                    const before = event.clientY < row.getBoundingClientRect().top + row.offsetHeight / 2;
                    itemList.insertBefore(draggedRow, before ? row : row.nextSibling);
                    row.classList.remove('drop-before', 'drop-after');
                    saveOrder();
                });
            });
        }
    </script>
</body>
</html>
