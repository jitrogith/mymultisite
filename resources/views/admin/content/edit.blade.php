<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#252f60">
    <title>Kelola Konten | Portal Pemerintah</title>
    <style>
        :root { color-scheme: light; --navy: #252f60; --navy-dark: #1b244e; --gold: #eeae43; --ink: #222b45; --muted: #747d94; --line: #e6e8ef; --canvas: #f5f6f9; font-family: "Segoe UI", Arial, sans-serif; }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; scroll-padding-top: 92px; }
        body { margin: 0; background: var(--canvas); color: var(--ink); }
        a { color: inherit; text-decoration: none; }
        button, input, textarea { font: inherit; }
        button { cursor: pointer; }
        .topbar { position: sticky; top: 0; z-index: 10; display: flex; height: 66px; align-items: center; justify-content: space-between; padding: 0 27px; border-bottom: 1px solid var(--line); background: #fff; }
        .brand { display: flex; align-items: center; gap: 10px; color: var(--navy); }
        .brand-mark { display: grid; width: 35px; height: 35px; place-items: center; border-radius: 7px; background: var(--navy); color: var(--gold); }
        .brand-mark svg { width: 21px; height: 21px; }
        .brand strong { display: block; font-family: Georgia, "Times New Roman", serif; font-size: 12px; }
        .brand small { display: block; margin-top: 3px; color: var(--muted); font-size: 8px; }
        .topbar-actions { display: flex; align-items: center; gap: 17px; }
        .public-link { color: #545e78; font-size: 10px; font-weight: 600; }
        .public-link:hover { color: var(--navy); }
        .user-label { color: var(--muted); font-size: 9px; }
        .logout-button { min-height: 32px; padding: 0 11px; border: 1px solid var(--line); border-radius: 4px; background: #fff; color: #545d75; font-size: 9px; font-weight: 700; }
        .layout { display: grid; max-width: 1440px; grid-template-columns: 218px minmax(0, 1fr); gap: 25px; margin: 0 auto; padding: 25px 27px 45px; }
        .side-nav { position: sticky; top: 91px; align-self: start; padding: 16px 10px; border: 1px solid var(--line); border-radius: 6px; background: #fff; }
        .side-caption { margin: 0 8px 12px; color: #9298a7; font-size: 8px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .side-nav a { display: block; padding: 10px 9px; border-radius: 4px; color: #58617a; font-size: 9px; font-weight: 600; }
        .side-nav a:hover { background: #f5f6fa; color: var(--navy); }
        .side-nav .nav-group { margin-top: 14px; }
        .side-note { margin: 17px 8px 5px; padding-top: 13px; border-top: 1px solid var(--line); color: #8990a0; font-size: 8px; line-height: 1.6; }
        .main { min-width: 0; }
        .page-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 15px; margin-bottom: 18px; }
        .eyebrow { margin: 0 0 6px; color: #96702e; font-size: 8px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
        h1 { margin: 0; color: var(--navy); font-family: Georgia, "Times New Roman", serif; font-size: 25px; }
        .page-heading p { margin: 7px 0 0; color: var(--muted); font-size: 9px; }
        .state-pill { display: inline-flex; align-items: center; gap: 6px; padding: 7px 9px; border: 1px solid #dcebe4; border-radius: 20px; background: #f3faf6; color: #348064; font-size: 8px; font-weight: 700; white-space: nowrap; }
        .state-pill:before { width: 6px; height: 6px; border-radius: 50%; background: #49a57e; content: ""; }
        .notice { margin-bottom: 15px; padding: 11px 13px; border: 1px solid #cfe8da; border-radius: 4px; background: #f2fbf6; color: #286e50; font-size: 10px; }
        .errors { margin-bottom: 15px; padding: 11px 14px; border: 1px solid #f1d0cc; border-radius: 4px; background: #fff5f4; color: #a7443a; font-size: 9px; line-height: 1.6; }
        .content-section { margin-bottom: 14px; padding: 19px 20px 20px; border: 1px solid var(--line); border-radius: 6px; background: #fff; }
        .section-head { display: flex; align-items: center; gap: 10px; margin-bottom: 17px; padding-bottom: 13px; border-bottom: 1px solid #eef0f4; }
        .section-number { display: grid; width: 27px; height: 27px; flex: 0 0 auto; place-items: center; border-radius: 5px; background: var(--gold-light, #fff4df); color: #87601e; font-size: 9px; font-weight: 800; }
        .section-head h2 { margin: 0; color: var(--navy); font-size: 12px; font-weight: 800; }
        .section-head p { margin: 4px 0 0; color: #8990a0; font-size: 8px; }
        .fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 13px 15px; }
        .field.full { grid-column: 1 / -1; }
        .field label { display: block; margin-bottom: 6px; color: #49516a; font-size: 9px; font-weight: 700; }
        .field input, .field textarea { width: 100%; min-height: 36px; padding: 8px 10px; border: 1px solid #dfe2e9; border-radius: 4px; outline: 0; background: #fff; color: var(--ink); font-size: 10px; }
        .field textarea { min-height: 70px; resize: vertical; line-height: 1.55; }
        .field input:focus, .field textarea:focus { border-color: #8993b4; box-shadow: 0 0 0 3px #252f600d; }
        .field input.is-invalid, .field textarea.is-invalid { border-color: #c84f48; }
        .field-help { margin-top: 5px; color: #9399a7; font-size: 8px; }
        .repeater-list { display: grid; gap: 11px; }
        .repeat-card { position: relative; padding: 14px; border: 1px solid #ebedf2; border-radius: 4px; background: #fcfcfd; }
        .repeat-head { display: flex; align-items: center; justify-content: space-between; gap: 9px; margin-bottom: 11px; }
        .repeat-title { color: #566079; font-size: 9px; font-weight: 800; }
        .remove-item { border: 0; background: transparent; color: #b1534a; font-size: 8px; font-weight: 700; }
        .remove-item:hover { text-decoration: underline; }
        .repeat-actions { display: flex; justify-content: flex-end; margin-top: 12px; }
        .add-item { min-height: 31px; padding: 0 10px; border: 1px dashed #bdc3d1; border-radius: 4px; background: #fff; color: var(--navy); font-size: 9px; font-weight: 700; }
        .add-item:hover { border-color: var(--navy); }
        .form-footer { position: sticky; bottom: 0; display: flex; align-items: center; justify-content: space-between; gap: 14px; margin-top: 18px; padding: 12px 14px; border: 1px solid var(--line); border-radius: 5px; background: #fffffff2; box-shadow: 0 -5px 18px #1d264f08; backdrop-filter: blur(8px); }
        .form-footer p { margin: 0; color: #7c8497; font-size: 8px; }
        .save-button { display: inline-flex; min-height: 37px; align-items: center; gap: 8px; padding: 0 14px; border: 0; border-radius: 4px; background: var(--gold); color: #2b2b36; font-size: 9px; font-weight: 800; }
        .save-button:hover { background: #f2bb5d; }
        .save-button svg { width: 14px; height: 14px; }
        @media (max-width: 850px) {
            .layout { grid-template-columns: 1fr; gap: 13px; padding: 18px 16px 36px; }
            .side-nav { position: static; display: flex; overflow-x: auto; align-items: center; gap: 4px; padding: 6px; }
            .side-caption, .side-note { display: none; }
            .side-nav .nav-group { display: contents; }
            .side-nav a { flex: 0 0 auto; padding: 9px 10px; font-size: 8px; }
            .topbar { padding: 0 16px; }
        }
        @media (max-width: 560px) {
            .topbar { height: 59px; }
            .brand small, .user-label { display: none; }
            .topbar-actions { gap: 10px; }
            .public-link { font-size: 8px; }
            .logout-button { min-height: 29px; padding: 0 8px; font-size: 8px; }
            .layout { padding-right: 11px; padding-left: 11px; }
            .page-heading { align-items: flex-start; flex-direction: column; }
            h1 { font-size: 22px; }
            .content-section { padding: 15px 13px; }
            .fields { grid-template-columns: 1fr; gap: 11px; }
            .field.full { grid-column: auto; }
            .repeat-card { padding: 11px; }
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
        <div class="topbar-actions">
            <a class="public-link" href="{{ route('home') }}" target="_blank" rel="noopener">Lihat situs publik ↗</a>
            <span class="user-label">{{ auth()->user()->username }}</span>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="logout-button" type="submit">Keluar</button></form>
        </div>
    </header>

    <div class="layout">
        <nav class="side-nav" aria-label="Bagian konten">
            <p class="side-caption">Konten situs</p>
            <a href="{{ route('admin.pages.index') }}">Page Builder</a>
            <a href="{{ route('admin.menus.index') }}">Pembuat Menu</a>
            <a href="#identitas">Identitas &amp; kontak</a>
            <a href="#beranda">Beranda utama</a>
            <a href="#pengumuman">Pengumuman</a>
            <div class="nav-group"><a href="#layanan">Layanan</a><a href="#berita">Berita</a><a href="#agenda">Agenda</a></div>
            <div class="nav-group"><a href="#transparansi">Transparansi &amp; footer</a></div>
            <p class="side-note">Perubahan disimpan ke basis data dan langsung ditampilkan pada situs publik.</p>
        </nav>

        <main class="main">
            <div class="page-heading">
                <div><p class="eyebrow">Administrasi situs</p><h1>Kelola konten halaman</h1><p>Perbarui informasi publik yang ditampilkan di beranda.</p></div>
                <span class="state-pill">Konten publik</span>
            </div>

            @if (session('status'))
                <div class="notice" role="status">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="errors" role="alert"><strong>Konten belum tersimpan.</strong> Periksa bagian berikut:
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.content.update') }}" id="contentForm">
                @csrf
                @method('PUT')

                <section class="content-section" id="identitas">
                    <div class="section-head"><span class="section-number">01</span><span><h2>Identitas dan kontak instansi</h2><p>Nama daerah, informasi lokasi, dan kontak yang ditampilkan di situs.</p></span></div>
                    <div class="fields">
                        <div class="field"><label for="site_name">Nama daerah (tanpa awalan Kabupaten)</label><input id="site_name" name="site_name" value="{{ old('site_name', $content['site_name']) }}" required></div>
                        <div class="field"><label for="province">Provinsi</label><input id="province" name="province" value="{{ old('province', $content['province']) }}" required></div>
                        <div class="field"><label for="location">Lokasi singkat</label><input id="location" name="location" value="{{ old('location', $content['location']) }}" required></div>
                        <div class="field"><label for="address">Alamat kantor</label><input id="address" name="address" value="{{ old('address', $content['address']) }}" required></div>
                        <div class="field"><label for="phone">Nomor telepon</label><input id="phone" name="phone" value="{{ old('phone', $content['phone']) }}" required></div>
                        <div class="field"><label for="email">Alamat email</label><input id="email" name="email" type="email" value="{{ old('email', $content['email']) }}" required></div>
                    </div>
                </section>

                <section class="content-section" id="beranda">
                    <div class="section-head"><span class="section-number">02</span><span><h2>Beranda utama</h2><p>Pesan utama yang tampil pada banner halaman publik.</p></span></div>
                    <div class="fields">
                        <div class="field"><label for="hero_eyebrow">Kalimat pengantar</label><input id="hero_eyebrow" name="hero_eyebrow" value="{{ old('hero_eyebrow', $content['hero_eyebrow']) }}" required></div>
                        <div class="field"><label for="hero_title">Judul utama</label><input id="hero_title" name="hero_title" value="{{ old('hero_title', $content['hero_title']) }}" required></div>
                        <div class="field full"><label for="hero_highlight">Teks judul berwarna</label><input id="hero_highlight" name="hero_highlight" value="{{ old('hero_highlight', $content['hero_highlight']) }}" required></div>
                        <div class="field full"><label for="hero_description">Deskripsi</label><textarea id="hero_description" name="hero_description" required>{{ old('hero_description', $content['hero_description']) }}</textarea></div>
                    </div>
                </section>

                <section class="content-section" id="pengumuman">
                    <div class="section-head"><span class="section-number">03</span><span><h2>Pengumuman</h2><p>Informasi singkat yang tampil di baris pengumuman teratas.</p></span></div>
                    <div class="fields">
                        <div class="field"><label for="announcement_label">Label</label><input id="announcement_label" name="announcement_label" value="{{ old('announcement_label', $content['announcement_label']) }}" required></div>
                        <div class="field"><label for="announcement_title">Awalan judul</label><input id="announcement_title" name="announcement_title" value="{{ old('announcement_title', $content['announcement_title']) }}" required></div>
                        <div class="field full"><label for="announcement_body">Isi pengumuman</label><textarea id="announcement_body" name="announcement_body" required>{{ old('announcement_body', $content['announcement_body']) }}</textarea></div>
                    </div>
                </section>

                <section class="content-section" id="layanan">
                    <div class="section-head"><span class="section-number">04</span><span><h2>Layanan publik</h2><p>Tambah atau hapus kartu layanan yang tampil pada halaman.</p></span></div>
                    <div class="repeater-list" data-repeater="services">
                        @foreach (old('services', $content['services']) as $index => $service)
                            <div class="repeat-card" data-item>
                                <div class="repeat-head"><span class="repeat-title">Layanan {{ $index + 1 }}</span><button class="remove-item" type="button">Hapus</button></div>
                                <div class="fields">
                                    <div class="field"><label>Nama layanan</label><input name="services[{{ $index }}][title]" value="{{ $service['title'] }}" maxlength="100" required></div>
                                    <div class="field"><label>Deskripsi</label><input name="services[{{ $index }}][description]" value="{{ $service['description'] }}" maxlength="220" required></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="repeat-actions"><button class="add-item" type="button" data-add="services">+ Tambah layanan</button></div>
                </section>

                <section class="content-section" id="berita">
                    <div class="section-head"><span class="section-number">05</span><span><h2>Berita dan informasi</h2><p>Judul dan ringkasan berita pada halaman depan.</p></span></div>
                    <div class="repeater-list" data-repeater="news">
                        @foreach (old('news', $content['news']) as $index => $news)
                            <div class="repeat-card" data-item>
                                <div class="repeat-head"><span class="repeat-title">Berita {{ $index + 1 }}</span><button class="remove-item" type="button">Hapus</button></div>
                                <div class="fields">
                                    <div class="field"><label>Tanggal</label><input name="news[{{ $index }}][date]" value="{{ $news['date'] }}" required></div>
                                    <div class="field"><label>Unit / penulis</label><input name="news[{{ $index }}][author]" value="{{ $news['author'] }}" required></div>
                                    <div class="field"><label>Kategori</label><input name="news[{{ $index }}][category]" value="{{ $news['category'] }}" required></div>
                                    <div class="field"><label>Judul berita</label><input name="news[{{ $index }}][title]" value="{{ $news['title'] }}" required></div>
                                    <div class="field full"><label>Ringkasan berita</label><textarea name="news[{{ $index }}][excerpt]" required>{{ $news['excerpt'] }}</textarea></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="repeat-actions"><button class="add-item" type="button" data-add="news">+ Tambah berita</button></div>
                </section>

                <section class="content-section" id="agenda">
                    <div class="section-head"><span class="section-number">06</span><span><h2>Agenda kegiatan</h2><p>Jadwal kegiatan yang ditampilkan pada beranda.</p></span></div>
                    <div class="repeater-list" data-repeater="agendas">
                        @foreach (old('agendas', $content['agendas']) as $index => $agenda)
                            <div class="repeat-card" data-item>
                                <div class="repeat-head"><span class="repeat-title">Agenda {{ $index + 1 }}</span><button class="remove-item" type="button">Hapus</button></div>
                                <div class="fields">
                                    <div class="field"><label>Tanggal (angka)</label><input name="agendas[{{ $index }}][day]" value="{{ $agenda['day'] }}" maxlength="2" required></div>
                                    <div class="field"><label>Bulan</label><input name="agendas[{{ $index }}][month]" value="{{ $agenda['month'] }}" required></div>
                                    <div class="field full"><label>Nama kegiatan</label><input name="agendas[{{ $index }}][title]" value="{{ $agenda['title'] }}" required></div>
                                    <div class="field"><label>Tempat / penyelenggara</label><input name="agendas[{{ $index }}][location]" value="{{ $agenda['location'] }}" required></div>
                                    <div class="field"><label>Waktu</label><input name="agendas[{{ $index }}][time]" value="{{ $agenda['time'] }}" required></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="repeat-actions"><button class="add-item" type="button" data-add="agendas">+ Tambah agenda</button></div>
                </section>

                <section class="content-section" id="transparansi">
                    <div class="section-head"><span class="section-number">07</span><span><h2>Transparansi dan footer</h2><p>Pesan keterbukaan informasi dan keterangan instansi di bagian bawah situs.</p></span></div>
                    <div class="fields">
                        <div class="field full"><label for="transparency_title">Judul transparansi</label><input id="transparency_title" name="transparency_title" value="{{ old('transparency_title', $content['transparency_title']) }}" required></div>
                        <div class="field full"><label for="transparency_description">Deskripsi PPID</label><textarea id="transparency_description" name="transparency_description" required>{{ old('transparency_description', $content['transparency_description']) }}</textarea></div>
                        <div class="field full"><label for="footer_description">Deskripsi footer situs</label><textarea id="footer_description" name="footer_description" required>{{ old('footer_description', $content['footer_description']) }}</textarea></div>
                    </div>
                </section>

                <div class="form-footer">
                    <p>Konten teks dipublikasikan langsung setelah disimpan.</p>
                    <button class="save-button" type="submit"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 4h12l3 3v13H4V4h1Zm3 0v6h8V4M8 20v-7h8v7" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>Simpan dan publikasikan</button>
                </div>
            </form>
        </main>
    </div>
    <template id="serviceTemplate"><div class="repeat-card" data-item><div class="repeat-head"><span class="repeat-title"></span><button class="remove-item" type="button">Hapus</button></div><div class="fields"><div class="field"><label>Nama layanan</label><input data-name="title" maxlength="100" required></div><div class="field"><label>Deskripsi</label><input data-name="description" maxlength="220" required></div></div></div></template>
    <template id="newsTemplate"><div class="repeat-card" data-item><div class="repeat-head"><span class="repeat-title"></span><button class="remove-item" type="button">Hapus</button></div><div class="fields"><div class="field"><label>Tanggal</label><input data-name="date" required></div><div class="field"><label>Unit / penulis</label><input data-name="author" required></div><div class="field"><label>Kategori</label><input data-name="category" required></div><div class="field"><label>Judul berita</label><input data-name="title" required></div><div class="field full"><label>Ringkasan berita</label><textarea data-name="excerpt" required></textarea></div></div></div></template>
    <template id="agendaTemplate"><div class="repeat-card" data-item><div class="repeat-head"><span class="repeat-title"></span><button class="remove-item" type="button">Hapus</button></div><div class="fields"><div class="field"><label>Tanggal (angka)</label><input data-name="day" maxlength="2" required></div><div class="field"><label>Bulan</label><input data-name="month" required></div><div class="field full"><label>Nama kegiatan</label><input data-name="title" required></div><div class="field"><label>Tempat / penyelenggara</label><input data-name="location" required></div><div class="field"><label>Waktu</label><input data-name="time" required></div></div></div></template>
    <script>
        const repeaterLabels = { services: 'Layanan', news: 'Berita', agendas: 'Agenda' };
        const repeaterTemplates = { services: 'serviceTemplate', news: 'newsTemplate', agendas: 'agendaTemplate' };
        const repeaterLimits = { services: 8, news: 8, agendas: 8 };

        function refreshRepeater(repeater) {
            const key = repeater.dataset.repeater;
            repeater.querySelectorAll('[data-item]').forEach((item, index) => {
                item.querySelector('.repeat-title').textContent = `${repeaterLabels[key]} ${index + 1}`;
                item.querySelectorAll('[data-name]').forEach((input) => {
                    input.name = `${key}[${index}][${input.dataset.name}]`;
                });
                item.querySelectorAll('input[name], textarea[name]').forEach((input) => {
                    input.name = input.name.replace(new RegExp(`^${key}\\[\\d+\\]`), `${key}[${index}]`);
                });
                item.querySelector('.remove-item').hidden = repeater.querySelectorAll('[data-item]').length <= 1;
            });
            const button = document.querySelector(`[data-add="${key}"]`);
            button.disabled = repeater.querySelectorAll('[data-item]').length >= repeaterLimits[key];
        }

        document.querySelectorAll('[data-repeater]').forEach(refreshRepeater);
        document.querySelectorAll('[data-add]').forEach((button) => {
            button.addEventListener('click', () => {
                const key = button.dataset.add;
                const template = document.getElementById(repeaterTemplates[key]);
                const repeater = document.querySelector(`[data-repeater="${key}"]`);
                if (repeater.querySelectorAll('[data-item]').length >= repeaterLimits[key]) return;
                repeater.append(template.content.cloneNode(true));
                refreshRepeater(repeater);
            });
        });
        document.querySelectorAll('[data-repeater]').forEach((repeater) => {
            repeater.addEventListener('click', (event) => {
                if (!event.target.matches('.remove-item')) return;
                if (repeater.querySelectorAll('[data-item]').length <= 1) return;
                event.target.closest('[data-item]').remove();
                refreshRepeater(repeater);
            });
        });
    </script>
</body>
</html>
