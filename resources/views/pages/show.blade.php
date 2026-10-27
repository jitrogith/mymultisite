<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#252f60">
    <meta name="description" content="{{ $page->summary ?: $page->title }}">
    <title>{{ $page->title }} | Pemerintah Kabupaten {{ $siteContent['site_name'] }}</title>
    <style>
        :root { color-scheme: light; --navy: #252f60; --navy-dark: #1b244e; --gold: #eeae43; --ink: #222b45; --muted: #69728a; --line: #e7e9ef; font-family: "Segoe UI", Arial, sans-serif; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #fff; color: var(--ink); }
        a { color: inherit; text-decoration: none; }
        .top { min-height: 35px; padding: 10px max(24px, calc((100% - 1120px) / 2)); background: var(--navy-dark); color: #e6e8f1; font-size: 10px; }
        .header { border-bottom: 1px solid var(--line); }
        .header-inner { display: flex; min-height: 85px; align-items: center; justify-content: space-between; gap: 15px; width: min(1120px, calc(100% - 40px)); margin: auto; }
        .identity { color: var(--navy); font-family: Georgia, "Times New Roman", serif; font-size: 17px; font-weight: 700; }
        .identity small { display: block; margin-bottom: 4px; color: #9a783d; font-family: "Segoe UI", Arial, sans-serif; font-size: 8px; letter-spacing: .12em; text-transform: uppercase; }
        .home-link { color: var(--navy); font-size: 10px; font-weight: 700; }
        .hero { padding: 53px 20px 43px; background: var(--navy); color: #fff; }
        .hero-inner { width: min(900px, 100%); margin: auto; }
        .eyebrow { margin: 0 0 12px; color: #f1cb84; font-size: 9px; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; }
        h1 { margin: 0; font-family: Georgia, "Times New Roman", serif; font-size: clamp(30px, 5vw, 46px); line-height: 1.18; }
        .summary { max-width: 700px; margin: 15px 0 0; color: #d7dbea; font-size: 12px; line-height: 1.8; }
        .article { width: min(100%, 760px); max-width: none; min-height: 320px; margin: 0 auto; padding: 34px 20px 58px; }
        .block-container { min-width: 0; margin: 22px 0; padding: 16px; }
        .block-container-column { min-width: 0; }
        .block-container-column > .block:last-child { margin-bottom: 0; }
        .block { margin: 0 0 19px; }
        .block.heading { margin-top: 33px; color: var(--navy); font-family: Georgia, "Times New Roman", serif; font-size: 23px; line-height: 1.4; }
        .block.heading, .block.text-editor { background-color: var(--heading-background, transparent); background-image: var(--heading-background-image, none); background-size: cover; background-position: center; background-repeat: no-repeat; border-color: var(--heading-border-color, transparent); border-top-width: var(--heading-border-top-width, 1px); border-right-width: var(--heading-border-right-width, 1px); border-bottom-width: var(--heading-border-bottom-width, 1px); border-left-width: var(--heading-border-left-width, 1px); }
        .block.heading:hover, .block.text-editor:hover { color: var(--heading-hover-color, inherit); background-color: var(--heading-hover-background, var(--heading-background, transparent)); background-image: var(--heading-hover-background-image, var(--heading-background-image, none)); background-size: cover; background-position: center; border-color: var(--heading-hover-border-color, var(--heading-border-color, transparent)); border-style: var(--heading-hover-border, inherit); border-top-width: var(--heading-hover-border-top-width, var(--heading-border-top-width, 1px)); border-right-width: var(--heading-hover-border-right-width, var(--heading-border-right-width, 1px)); border-bottom-width: var(--heading-hover-border-bottom-width, var(--heading-border-bottom-width, 1px)); border-left-width: var(--heading-hover-border-left-width, var(--heading-border-left-width, 1px)); border-radius: var(--heading-hover-border-radius, var(--heading-border-radius, 0)); box-shadow: var(--heading-hover-box-shadow, none); text-shadow: var(--heading-hover-text-shadow, none); }
        .block-heading-link { color: inherit; text-decoration: none; }
        .block.paragraph { color: #48516a; font-size: 13px; line-height: 1.9; white-space: pre-line; }
        .block.text-editor { color: #48516a; font-size: 13px; line-height: 1.9; }
        .block.text-editor p { margin: 0 0 10px; }
        .block.text-editor p:last-child { margin-bottom: 0; }
        .block.text-editor[data-drop-cap="true"] > p:first-child:first-letter { float: left; margin: .08em .12em 0 0; color: var(--navy); font-size: 3em; font-weight: 700; line-height: .8; }
        .block.text-editor[data-columns="2"] { column-count: 2; }
        .block.text-editor[data-columns="3"] { column-count: 3; }
        .block.text-editor[data-columns="4"] { column-count: 4; }
        .block.text-editor a { color: var(--navy); text-decoration: underline; }
        .block.text-editor img { max-width: 100%; height: auto; }
        .block.quote { margin: 25px 0; padding: 16px 20px; border-left: 4px solid var(--gold); background: #fbf8f0; color: #535d76; font-family: Georgia, "Times New Roman", serif; font-size: 16px; font-style: italic; line-height: 1.7; white-space: pre-line; }
        .block.list { padding-left: 22px; color: #48516a; font-size: 12px; line-height: 1.9; }
        .block.list li { padding-left: 4px; margin-bottom: 4px; }
        .block.image img, .block.video video { display: block; max-width: 100%; margin: 0 auto; border-radius: 3px; }
        .block.image[data-align="left"] { text-align: left; }
        .block.image[data-align="center"] { text-align: center; }
        .block.image[data-align="right"] { text-align: right; }
        .block.image[data-align="left"] img { margin-left: 0; }
        .block.image[data-align="right"] img { margin-right: 0; }
        .block.image[data-align="center"] img { margin-right: auto; margin-left: auto; }
        .block.image[data-opacity] img { opacity: var(--image-opacity); }
        .block.image[data-hover-opacity]:hover img { opacity: var(--image-hover-opacity); }
        .block.image a { display: inline-block; max-width: 100%; }
        .block.image img { max-height: 650px; object-fit: contain; }
        .block.image figcaption, .block.video figcaption { margin-top: 7px; color: #7b8393; font-size: 10px; }
        .block.video .video-frame { width: 100%; overflow: hidden; background: #11172e; }
        .block.video video, .block.video iframe { display: block; width: 100%; height: 100%; border: 0; background: #11172e; }
        .block.button-wrap { padding: 8px 0; }
        .block.button-link { display: inline-flex; min-height: 40px; align-items: center; padding: 0 16px; border-radius: 3px; background: var(--gold); color: #292d3d; font-size: 11px; font-weight: 700; }
        .block.divider { margin: 22px 0; border: 0; border-top: 1px solid var(--line); }
        .block.spacer { margin: 0; padding: 0; }
        .block.map { display: flex; min-height: 105px; align-items: center; justify-content: center; gap: 12px; background: #eef0ed; color: #58634f; }
        .block.map svg { width: 27px; height: 27px; flex: 0 0 auto; }
        .block.map strong, .block.map small { display: block; }
        .block.map strong { font-size: 11px; }
        .block.map small { margin-top: 5px; color: #727b6e; font-size: 10px; }
        .block.menu { position: relative; min-width: 0; background-color: var(--heading-background, transparent); background-image: var(--heading-background-image, none); background-size: cover; background-position: center; background-repeat: no-repeat; border-color: var(--heading-border-color, transparent); border-top-width: var(--heading-border-top-width, 1px); border-right-width: var(--heading-border-right-width, 1px); border-bottom-width: var(--heading-border-bottom-width, 1px); border-left-width: var(--heading-border-left-width, 1px); }
        .menu-list, .menu-list ul { display: flex; flex-wrap: wrap; align-items: center; gap: var(--menu-item-gap, 4px); margin: 0; padding: 0; list-style: none; }
        .menu-list li { position: relative; }
        .menu-list > li > a, .menu-list > li > span { display: block; padding: var(--menu-item-padding-y, 9px) var(--menu-item-padding-x, 12px); color: var(--menu-item-color, inherit); font-size: var(--menu-item-font-size, inherit); font-weight: var(--menu-item-font-weight, inherit); background: var(--menu-item-background, transparent); text-decoration: none; }
        .menu-list > li > a:hover { color: var(--menu-item-hover-color, var(--menu-item-color, inherit)); background: var(--menu-item-hover-background, transparent); }
        .menu-list > li > a[aria-current="page"] { color: var(--menu-item-active-color, var(--menu-item-color, inherit)); background: var(--menu-item-active-background, transparent); }
        .menu-list ul { position: absolute; top: 100%; left: 0; z-index: 2; display: none; min-width: var(--submenu-min-width, 180px); flex-direction: column; align-items: stretch; gap: var(--submenu-item-gap, 0); padding: var(--submenu-padding, 5px); border: var(--submenu-border-width, 1px) solid var(--submenu-border-color, #e1e4ea); border-radius: var(--submenu-border-radius, 0); background: var(--submenu-background, #fff); box-shadow: var(--submenu-shadow, 0 4px 12px #18204420); text-align: left; }
        .menu-list ul li > a, .menu-list ul li > span { display: block; padding: var(--submenu-item-padding-y, 9px) var(--submenu-item-padding-x, 12px); color: var(--submenu-item-color, #303954); font-size: var(--submenu-item-font-size, inherit); font-weight: var(--submenu-item-font-weight, inherit); background: var(--submenu-item-background, transparent); text-decoration: none; white-space: nowrap; }
        .menu-list ul li > a:hover { color: var(--submenu-item-hover-color, var(--submenu-item-color, #303954)); background: var(--submenu-item-hover-background, #f2f3f7); }
        .menu-list ul li > a[aria-current="page"] { color: var(--submenu-item-active-color, var(--submenu-item-color, #303954)); background: var(--submenu-item-active-background, #f2f3f7); }
        .menu-list li:hover > ul, .menu-list li:focus-within > ul { display: block; }
        .menu-list ul li ul { top: 0; left: 100%; }
        .block.menu:hover { color: var(--heading-hover-color, inherit); background-color: var(--heading-hover-background, var(--heading-background, transparent)); background-image: var(--heading-hover-background-image, var(--heading-background-image, none)); border-color: var(--heading-hover-border-color, var(--heading-border-color, transparent)); border-style: var(--heading-hover-border, inherit); border-top-width: var(--heading-hover-border-top-width, var(--heading-border-top-width, 1px)); border-right-width: var(--heading-hover-border-right-width, var(--heading-border-right-width, 1px)); border-bottom-width: var(--heading-hover-border-bottom-width, var(--heading-border-bottom-width, 1px)); border-left-width: var(--heading-hover-border-left-width, var(--heading-border-left-width, 1px)); border-radius: var(--heading-hover-border-radius, 0); box-shadow: var(--heading-hover-box-shadow, none); text-shadow: var(--heading-hover-text-shadow, none); }
        @media (max-width: 767px) { .menu-list, .menu-list ul { gap: 0; } .menu-list ul { position: static; display: block; min-width: 0; padding: 0 0 0 14px; border: 0; box-shadow: none; } }
        @media (min-width: 1025px) { [data-hide-desktop="true"] { display: none !important; } }
        @media (min-width: 768px) and (max-width: 1024px) { [data-hide-tablet="true"] { display: none !important; } }
        @media (max-width: 767px) { [data-hide-mobile="true"] { display: none !important; } }
        .footer { padding: 20px; background: var(--navy-dark); color: #d7dbea; text-align: center; font-size: 9px; }
        .footer strong { color: white; }
        @media (max-width: 520px) { .top { padding-right: 16px; padding-left: 16px; } .header-inner { width: calc(100% - 30px); min-height: 72px; } .identity { font-size: 14px; } .hero { padding: 38px 18px 32px; } .summary { font-size: 11px; } .article { padding: 26px 18px 42px; } }
        @media (max-width: 767px) { .block.text-editor { column-count: 1 !important; } .block-container { grid-template-columns: 1fr !important; grid-template-rows: auto !important; grid-template-areas: none !important; } .block-container-column { grid-area: auto !important; } }
    </style>
</head>
<body>
    <div class="top">{{ $siteContent['location'] }}</div>
    <header class="header"><div class="header-inner"><a class="identity" href="{{ route('home') }}"><small>Situs Resmi Pemerintah</small>Kabupaten {{ $siteContent['site_name'] }}</a><a class="home-link" href="{{ route('home') }}">← Kembali ke beranda</a></div></header>
    <main>
        <section class="hero"><div class="hero-inner"><p class="eyebrow">Informasi publik</p><h1>{{ $page->title }}</h1>@if ($page->summary)<p class="summary">{{ $page->summary }}</p>@endif</div></section>
        @php
            $contentWidthMode = in_array($page->content_width_mode, ['full', 'custom'], true) ? $page->content_width_mode : 'custom';
            $contentWidth = max(320, min(1920, (int) ($page->content_width ?? 760)));
            $articleWidthStyle = $contentWidthMode === 'full' ? 'width: 100%' : 'width: min(100%, '.$contentWidth.'px)';
        @endphp
        <article class="article" style="{{ $articleWidthStyle }}">
            @php
                $containerPresets = [
                    'column' => ['tracks' => '1fr', 'areas' => ['a']],
                    'row' => ['tracks' => '1fr', 'areas' => ['a']],
                    'columns-2' => ['tracks' => '1fr 1fr', 'areas' => ['a b']],
                    'columns-wide-left' => ['tracks' => '2fr 1fr', 'areas' => ['a b']],
                    'columns-wide-right' => ['tracks' => '1fr 2fr', 'areas' => ['a b']],
                    'columns-4' => ['tracks' => '1fr 1fr 1fr 1fr', 'areas' => ['a b c d']],
                    'columns-3-center' => ['tracks' => '1fr 2fr 1fr', 'areas' => ['a b c']],
                    'rows-2' => ['tracks' => '1fr', 'areas' => ['a', 'b']],
                    'top-wide' => ['tracks' => '1fr 1fr', 'areas' => ['a a', 'b c']],
                    'left-tall' => ['tracks' => '1fr 1fr', 'areas' => ['a b', 'a c']],
                    'grid-3x2' => ['tracks' => '1fr 1fr 1fr', 'areas' => ['a b c', 'd e f']],
                    'top-3-bottom-2' => ['tracks' => '1fr 1fr 1fr 1fr 1fr 1fr', 'areas' => ['a a b b c c', 'd d d e e e']],
                    'top-2-bottom-3' => ['tracks' => '1fr 1fr 1fr 1fr 1fr 1fr', 'areas' => ['a a a b b b', 'c c d d e e']],
                ];
                $pageBlocks = $page->blocks ?? [];
                $containerMap = [];
                $containerChildren = [];
                foreach ($pageBlocks as $blockIndex => $candidateBlock) {
                    $candidateSettings = $candidateBlock['settings'] ?? [];
                    if (($candidateBlock['type'] ?? null) === 'container') {
                        $containerMap[$candidateSettings['container_id'] ?? ''] = $blockIndex;
                        $parentId = $candidateSettings['parent_container_id'] ?? null;
                    } else {
                        $parentId = $candidateSettings['container_id'] ?? null;
                    }
                    if ($parentId !== null && isset($candidateSettings['container_column'])) {
                        $containerChildren[$parentId][(int) $candidateSettings['container_column']][] = $blockIndex;
                    }
                }
                $renderEntries = [];
                $appendBlock = function (int $blockIndex, array $ancestors = []) use (&$appendBlock, &$renderEntries, $pageBlocks, $containerPresets, $containerChildren, $containerMap): void {
                    $candidateBlock = $pageBlocks[$blockIndex];
                    if (($candidateBlock['type'] ?? null) !== 'container') {
                        $renderEntries[] = ['type' => 'block', 'index' => $blockIndex, 'block' => $candidateBlock];

                        return;
                    }

                    $settings = $candidateBlock['settings'] ?? [];
                    $containerId = $settings['container_id'] ?? '';
                    if ($containerId === '' || in_array($containerId, $ancestors, true)) {
                        return;
                    }
                    $ancestors[] = $containerId;
                    $layout = $settings['container_layout'] ?? 'row';
                    $preset = $containerPresets[$layout] ?? $containerPresets['row'];
                    $areas = $preset['areas'];
                    $slots = [];
                    foreach ($areas as $areaRow) {
                        foreach (explode(' ', $areaRow) as $areaName) {
                            if (! in_array($areaName, $slots, true)) $slots[] = $areaName;
                        }
                    }
                    $renderEntries[] = [
                        'type' => 'container_open',
                        'id' => $containerId,
                        'index' => $blockIndex,
                        'layout' => $layout,
                        'tracks' => $preset['tracks'],
                        'areas' => $areas,
                        'gap' => max(0, min(60, (int) ($settings['container_gap'] ?? 12))),
                        'styles' => collect(['margin', 'padding'])
                            ->flatMap(fn ($property) => collect(['top', 'right', 'bottom', 'left'])
                                ->filter(fn ($side) => isset($settings[$property.'_'.$side]))
                                ->map(fn ($side) => $property.'-'.$side.': '.($property === 'margin'
                                    ? max(-500, min(500, (int) $settings[$property.'_'.$side]))
                                    : max(0, min(100, (int) $settings[$property.'_'.$side]))).'px'))
                            ->merge(match ($settings['width'] ?? 'default') {
                                'full' => ['width: 100%'],
                                'inline' => ['width: fit-content'],
                                default => [],
                            })
                            ->merge(match ($settings['align_self'] ?? 'left') {
                                'center' => ['margin-left: auto', 'margin-right: auto'],
                                'right' => ['margin-left: auto'],
                                'stretch' => ['width: 100%'],
                                default => [],
                            })
                            ->merge(filter_var($settings['z_index'] ?? null, FILTER_VALIDATE_INT) !== false
                                && (int) $settings['z_index'] >= -9999
                                && (int) $settings['z_index'] <= 9999
                                ? ['position: relative', 'z-index: '.(int) $settings['z_index']]
                                : [])
                            ->all(),
                    ];
                    foreach ($slots as $columnIndex => $slot) {
                        $renderEntries[] = ['type' => 'container_column_open', 'slot' => $slot];
                        foreach ($containerChildren[$containerId][$columnIndex] ?? [] as $childIndex) {
                            if (($pageBlocks[$childIndex]['type'] ?? null) === 'container'
                                && ! isset($containerMap[$pageBlocks[$childIndex]['settings']['container_id'] ?? ''])) {
                                continue;
                            }
                            $appendBlock($childIndex, $ancestors);
                        }
                        $renderEntries[] = ['type' => 'container_column_close'];
                    }
                    $renderEntries[] = ['type' => 'container_close'];
                };
                foreach ($pageBlocks as $blockIndex => $candidateBlock) {
                    if (($candidateBlock['type'] ?? null) === 'container') {
                        $settings = $candidateBlock['settings'] ?? [];
                        if (! isset($containerMap[$settings['parent_container_id'] ?? ''])) {
                            $appendBlock($blockIndex);
                        }
                    } elseif (! isset($containerMap[$candidateBlock['settings']['container_id'] ?? ''])) {
                        $renderEntries[] = [
                            'type' => 'container_open',
                            'id' => 'auto-'.$blockIndex,
                            'index' => null,
                            'layout' => 'column',
                            'tracks' => '1fr',
                            'areas' => ['a'],
                            'gap' => 12,
                            'styles' => [],
                        ];
                        $renderEntries[] = ['type' => 'container_column_open', 'slot' => 'a'];
                        $appendBlock($blockIndex);
                        $renderEntries[] = ['type' => 'container_column_close'];
                        $renderEntries[] = ['type' => 'container_close'];
                    }
                }
            @endphp
            @foreach ($renderEntries as $renderEntry)
                @if ($renderEntry['type'] === 'container_open')
                    @php
                        $containerSettings = $renderEntry['index'] === null ? [] : ($pageBlocks[$renderEntry['index']]['settings'] ?? []);
                        $containerResponsive = 'data-hide-desktop="'.(filter_var($containerSettings['hide_desktop'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false').'" data-hide-tablet="'.(filter_var($containerSettings['hide_tablet'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false').'" data-hide-mobile="'.(filter_var($containerSettings['hide_mobile'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false').'"';
                    @endphp
                    <section class="block-container container-layout-{{ $renderEntry['layout'] }}" id="veditor-container-{{ $renderEntry['id'] }}" {!! $containerResponsive !!} style="display:grid;grid-template-columns:{{ $renderEntry['tracks'] }};grid-template-rows:repeat({{ count($renderEntry['areas']) }},minmax(90px,auto));grid-template-areas:{{ implode(' ', array_map(fn ($area) => '"'.$area.'"', $renderEntry['areas'])) }};gap:{{ $renderEntry['gap'] }}px;{{ implode(';', $renderEntry['styles']) }}">
                    @continue
                @elseif ($renderEntry['type'] === 'container_column_open')
                    <div class="block-container-column" style="grid-area:{{ $renderEntry['slot'] }}">
                    @continue
                @elseif ($renderEntry['type'] === 'container_column_close')
                    </div>
                    @continue
                @elseif ($renderEntry['type'] === 'container_close')
                    </section>
                    @continue
                @endif
                @php
                    $block = $renderEntry['block'];
                    $blockIndex = $renderEntry['index'];
                @endphp
                @php
                    $blockSettings = is_array($block['settings'] ?? null) ? $block['settings'] : [];
                    $blockCustomCss = trim((string) ($blockSettings['custom_css'] ?? ''));
                    $blockCustomCss = preg_replace('/\bselector\b/i', '#veditor-block-'.$blockIndex, $blockCustomCss);
                    $blockCustomCss = str_replace('<', '\\3C ', $blockCustomCss);
                    $blockMarginStyles = [];
                    foreach (['top', 'right', 'bottom', 'left'] as $marginSide) {
                        $marginKey = 'margin_'.$marginSide;
                        if (isset($blockSettings[$marginKey])) {
                            $blockMarginStyles[] = 'margin-'.$marginSide.': '.max(-500, min(500, (int) $blockSettings[$marginKey])).'px !important';
                        }
                    }
                    if ($blockMarginStyles !== []) {
                        $blockCustomCss .= "\n#veditor-block-".$blockIndex." { ".implode('; ', $blockMarginStyles)."; }";
                    }
                    $blockZIndex = filter_var($blockSettings['z_index'] ?? null, FILTER_VALIDATE_INT);
                    if ($blockZIndex !== false && $blockZIndex >= -9999 && $blockZIndex <= 9999) {
                        $blockCustomCss .= "\n#veditor-block-".$blockIndex." { position: relative; z-index: ".$blockZIndex."; }";
                    }
                    $responsiveAttributes = 'id="veditor-block-'.$blockIndex.'" data-hide-desktop="'.(filter_var($blockSettings['hide_desktop'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false').'" data-hide-tablet="'.(filter_var($blockSettings['hide_tablet'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false').'" data-hide-mobile="'.(filter_var($blockSettings['hide_mobile'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false').'"';
                @endphp
                @if ($block['type'] === 'heading')
                    @php
                        $settings = $blockSettings;
                        $legacyLevel = in_array((int) ($block['level'] ?? 2), [2, 3, 4], true) ? (int) ($block['level'] ?? 2) : 2;
                        $headingTag = in_array($settings['html_tag'] ?? null, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span'], true) ? $settings['html_tag'] : 'h'.$legacyLevel;
                        $allowedValues = [
                            'alignment' => ['left', 'center', 'right', 'justify'],
                            'font_weight' => ['400', '500', '600', '700', '800'],
                            'blend_mode' => ['normal', 'multiply', 'screen', 'overlay', 'darken', 'lighten'],
                            'text_shadow' => ['none' => 'none', 'soft' => '1px 2px 5px #18204440', 'strong' => '2px 3px 8px #18204480'],
                            'box_shadow' => ['none' => 'none', 'soft' => '0 2px 8px #18204426', 'strong' => '0 5px 15px #18204440'],
                            'border_type' => ['none', 'solid', 'dashed', 'dotted'],
                            'width' => ['default', 'full', 'inline'],
                            'align_self' => ['left', 'center', 'right', 'stretch'],
                        ];
                        $headingStyles = [];
                        if (in_array($settings['alignment'] ?? null, $allowedValues['alignment'], true)) $headingStyles[] = 'text-align: '.$settings['alignment'];
                        if (isset($settings['font_size'])) $headingStyles[] = 'font-size: '.max(12, min(72, (int) $settings['font_size'])).'px';
                        if (in_array($settings['font_weight'] ?? null, $allowedValues['font_weight'], true)) $headingStyles[] = 'font-weight: '.$settings['font_weight'];
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $settings['text_color'] ?? '')) $headingStyles[] = 'color: '.$settings['text_color'];
                        if (in_array($settings['blend_mode'] ?? null, $allowedValues['blend_mode'], true)) $headingStyles[] = 'mix-blend-mode: '.$settings['blend_mode'];
                        if ((int) ($settings['text_stroke_width'] ?? 0) > 0 && preg_match('/^#[0-9a-fA-F]{6}$/', $settings['text_stroke_color'] ?? '')) $headingStyles[] = '-webkit-text-stroke: '.max(0, min(3, (int) $settings['text_stroke_width'])).'px '.$settings['text_stroke_color'];
                        if (isset($allowedValues['text_shadow'][$settings['text_shadow'] ?? ''])) $headingStyles[] = 'text-shadow: '.$allowedValues['text_shadow'][$settings['text_shadow']];
                        foreach (['margin', 'padding'] as $spaceProperty) {
                            foreach (['top', 'right', 'bottom', 'left'] as $spaceSide) {
                                $spaceKey = $spaceProperty.'_'.$spaceSide;
                                if (isset($settings[$spaceKey])) $headingStyles[] = $spaceProperty.'-'.$spaceSide.': '.($spaceProperty === 'margin'
                                    ? max(-500, min(500, (int) $settings[$spaceKey]))
                                    : max(0, min(100, (int) $settings[$spaceKey]))).'px';
                            }
                        }
                        if (($settings['width'] ?? null) === 'full' || ($settings['align_self'] ?? null) === 'stretch') $headingStyles[] = 'width: 100%';
                        if (($settings['width'] ?? null) === 'inline') $headingStyles[] = 'width: fit-content';
                        if (($settings['align_self'] ?? null) === 'center') $headingStyles[] = 'margin-left: auto; margin-right: auto';
                        if (($settings['align_self'] ?? null) === 'right') $headingStyles[] = 'margin-left: auto';
                        $zIndex = filter_var($settings['z_index'] ?? null, FILTER_VALIDATE_INT);
                        if ($zIndex !== false && $zIndex >= -9999 && $zIndex <= 9999) {
                            $headingStyles[] = 'position: relative';
                            $headingStyles[] = 'z-index: '.$zIndex;
                        }
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $settings['background_color'] ?? '')) $headingStyles[] = '--heading-background: '.$settings['background_color'];
                        if (($settings['background_type'] ?? 'color') === 'image' && preg_match('#^/storage/heading-backgrounds/[A-Za-z0-9._-]+$#', $settings['background_image'] ?? '')) {
                            $headingStyles[] = '--heading-background-image: url("'.$settings['background_image'].'")';
                        }
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $settings['hover_text_color'] ?? '')) $headingStyles[] = '--heading-hover-color: '.$settings['hover_text_color'];
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $settings['hover_background_color'] ?? '')) $headingStyles[] = '--heading-hover-background: '.$settings['hover_background_color'];
                        if (($settings['hover_background_type'] ?? 'color') === 'image' && preg_match('#^/storage/heading-backgrounds/[A-Za-z0-9._-]+$#', $settings['hover_background_image'] ?? '')) {
                            $headingStyles[] = '--heading-hover-background-image: url("'.$settings['hover_background_image'].'")';
                        }
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $settings['border_color'] ?? '')) $headingStyles[] = '--heading-border-color: '.$settings['border_color'];
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $settings['hover_border_color'] ?? '')) $headingStyles[] = '--heading-hover-border-color: '.$settings['hover_border_color'];
                        if (in_array($settings['border_type'] ?? null, $allowedValues['border_type'], true) && $settings['border_type'] !== 'none') {
                            $headingStyles[] = 'border-style: '.$settings['border_type'];
                            foreach (['top', 'right', 'bottom', 'left'] as $borderSide) {
                                $borderWidth = isset($settings['border_width_'.$borderSide]) ? max(0, min(20, (int) $settings['border_width_'.$borderSide])) : 1;
                                $headingStyles[] = '--heading-border-'.$borderSide.'-width: '.$borderWidth.'px';
                                $headingStyles[] = 'border-'.$borderSide.'-width: var(--heading-border-'.$borderSide.'-width)';
                            }
                        }
                        $radiusValues = [];
                        foreach (['top', 'right', 'bottom', 'left'] as $radiusSide) {
                            $radius = $settings['border_radius_'.$radiusSide] ?? $settings['border_radius'] ?? 0;
                            $radiusValues[] = max(0, min(100, (int) $radius)).'px';
                        }
                        $headingStyles[] = '--heading-border-radius: '.implode(' ', $radiusValues);
                        if (isset($settings['border_radius']) || array_intersect(['border_radius_top', 'border_radius_right', 'border_radius_bottom', 'border_radius_left'], array_keys($settings))) {
                            $headingStyles[] = 'border-radius: '.implode(' ', $radiusValues);
                        }
                        $hoverRadiusValues = [];
                        $hasHoverRadius = false;
                        foreach (['top', 'right', 'bottom', 'left'] as $radiusSide) {
                            $key = 'hover_border_radius_'.$radiusSide;
                            $radius = $settings[$key] ?? $settings['border_radius_'.$radiusSide] ?? $settings['border_radius'] ?? 0;
                            $hasHoverRadius = $hasHoverRadius || isset($settings[$key]);
                            $hoverRadiusValues[] = max(0, min(100, (int) $radius)).'px';
                        }
                        if ($hasHoverRadius) $headingStyles[] = '--heading-hover-border-radius: '.implode(' ', $hoverRadiusValues);
                        if (isset($allowedValues['box_shadow'][$settings['box_shadow'] ?? ''])) $headingStyles[] = 'box-shadow: '.$allowedValues['box_shadow'][$settings['box_shadow']];
                        if (in_array($settings['hover_border_type'] ?? null, $allowedValues['border_type'], true) && $settings['hover_border_type'] !== 'none') {
                            $headingStyles[] = '--heading-hover-border: '.$settings['hover_border_type'];
                            foreach (['top', 'right', 'bottom', 'left'] as $borderSide) {
                                if (isset($settings['hover_border_width_'.$borderSide])) {
                                    $headingStyles[] = '--heading-hover-border-'.$borderSide.'-width: '.max(0, min(20, (int) $settings['hover_border_width_'.$borderSide])).'px';
                                }
                            }
                        }
                        if (isset($allowedValues['text_shadow'][$settings['hover_text_shadow'] ?? ''])) $headingStyles[] = '--heading-hover-text-shadow: '.$allowedValues['text_shadow'][$settings['hover_text_shadow']];
                        if (isset($allowedValues['box_shadow'][$settings['hover_box_shadow'] ?? ''])) $headingStyles[] = '--heading-hover-box-shadow: '.$allowedValues['box_shadow'][$settings['hover_box_shadow']];
                        $headingStyle = implode('; ', $headingStyles);
                    @endphp
                    @if (preg_match('/^https?:\/\/\S+$/i', $settings['link'] ?? ''))
                        <a class="block-heading-link" href="{{ $settings['link'] }}">
                    @endif
                    {!! '<'.$headingTag.' class="block heading" style="'.e($headingStyle).'" '.$responsiveAttributes.'>'.e($block['text'] ?? '').'</'.$headingTag.'>' !!}
                    @if (preg_match('/^https?:\/\/\S+$/i', $settings['link'] ?? ''))
                        </a>
                    @endif
                @elseif ($block['type'] === 'text')
                    @php
                        $settings = $blockSettings;
                        $textStyles = [];
                        if (in_array($settings['alignment'] ?? null, ['left', 'center', 'right', 'justify'], true)) $textStyles[] = 'text-align: '.$settings['alignment'];
                        if (isset($settings['font_size'])) $textStyles[] = 'font-size: '.max(12, min(72, (int) $settings['font_size'])).'px';
                        if (in_array($settings['font_weight'] ?? null, ['400', '500', '600', '700', '800'], true)) $textStyles[] = 'font-weight: '.$settings['font_weight'];
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $settings['text_color'] ?? '')) $textStyles[] = 'color: '.$settings['text_color'];
                        if ((int) ($settings['text_stroke_width'] ?? 0) > 0 && preg_match('/^#[0-9a-fA-F]{6}$/', $settings['text_stroke_color'] ?? '')) $textStyles[] = '-webkit-text-stroke: '.max(0, min(3, (int) $settings['text_stroke_width'])).'px '.$settings['text_stroke_color'];
                        $textShadows = ['none' => 'none', 'soft' => '1px 2px 5px #18204440', 'strong' => '2px 3px 8px #18204480'];
                        $boxShadows = ['none' => 'none', 'soft' => '0 2px 8px #18204426', 'strong' => '0 5px 15px #18204440'];
                        if (isset($textShadows[$settings['text_shadow'] ?? ''])) $textStyles[] = 'text-shadow: '.$textShadows[$settings['text_shadow']];
                        foreach (['margin', 'padding'] as $property) {
                            foreach (['top', 'right', 'bottom', 'left'] as $side) {
                                if (isset($settings[$property.'_'.$side])) $textStyles[] = $property.'-'.$side.': '.($property === 'margin'
                                    ? max(-500, min(500, (int) $settings[$property.'_'.$side]))
                                    : max(0, min(100, (int) $settings[$property.'_'.$side]))).'px';
                            }
                        }
                        if (($settings['width'] ?? null) === 'full' || ($settings['align_self'] ?? null) === 'stretch') $textStyles[] = 'width: 100%';
                        if (($settings['width'] ?? null) === 'inline') $textStyles[] = 'width: fit-content';
                        if (($settings['align_self'] ?? null) === 'center') $textStyles[] = 'margin-left: auto; margin-right: auto';
                        if (($settings['align_self'] ?? null) === 'right') $textStyles[] = 'margin-left: auto';
                        $zIndex = filter_var($settings['z_index'] ?? null, FILTER_VALIDATE_INT);
                        if ($zIndex !== false && $zIndex >= -9999 && $zIndex <= 9999) {
                            $textStyles[] = 'position: relative';
                            $textStyles[] = 'z-index: '.$zIndex;
                        }
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $settings['background_color'] ?? '')) $textStyles[] = '--heading-background: '.$settings['background_color'];
                        if (($settings['background_type'] ?? 'color') === 'image' && preg_match('#^/storage/heading-backgrounds/[A-Za-z0-9._-]+$#', $settings['background_image'] ?? '')) $textStyles[] = '--heading-background-image: url("'.$settings['background_image'].'")';
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $settings['hover_text_color'] ?? '')) $textStyles[] = '--heading-hover-color: '.$settings['hover_text_color'];
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $settings['hover_background_color'] ?? '')) $textStyles[] = '--heading-hover-background: '.$settings['hover_background_color'];
                        if (($settings['hover_background_type'] ?? 'color') === 'image' && preg_match('#^/storage/heading-backgrounds/[A-Za-z0-9._-]+$#', $settings['hover_background_image'] ?? '')) $textStyles[] = '--heading-hover-background-image: url("'.$settings['hover_background_image'].'")';
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $settings['border_color'] ?? '')) $textStyles[] = '--heading-border-color: '.$settings['border_color'];
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $settings['hover_border_color'] ?? '')) $textStyles[] = '--heading-hover-border-color: '.$settings['hover_border_color'];
                        if (in_array($settings['border_type'] ?? null, ['none', 'solid', 'dashed', 'dotted'], true) && $settings['border_type'] !== 'none') {
                            $textStyles[] = 'border-style: '.$settings['border_type'];
                            foreach (['top', 'right', 'bottom', 'left'] as $side) {
                                $width = isset($settings['border_width_'.$side]) ? max(0, min(20, (int) $settings['border_width_'.$side])) : 1;
                                $textStyles[] = '--heading-border-'.$side.'-width: '.$width.'px';
                                $textStyles[] = 'border-'.$side.'-width: var(--heading-border-'.$side.'-width)';
                            }
                        }
                        $radii = [];
                        foreach (['top', 'right', 'bottom', 'left'] as $side) $radii[] = max(0, min(100, (int) ($settings['border_radius_'.$side] ?? $settings['border_radius'] ?? 0))).'px';
                        $textStyles[] = '--heading-border-radius: '.implode(' ', $radii);
                        if (isset($settings['border_radius']) || array_intersect(['border_radius_top', 'border_radius_right', 'border_radius_bottom', 'border_radius_left'], array_keys($settings))) $textStyles[] = 'border-radius: '.implode(' ', $radii);
                        if (in_array($settings['hover_border_type'] ?? null, ['none', 'solid', 'dashed', 'dotted'], true) && $settings['hover_border_type'] !== 'none') {
                            $textStyles[] = '--heading-hover-border: '.$settings['hover_border_type'];
                            foreach (['top', 'right', 'bottom', 'left'] as $side) {
                                if (isset($settings['hover_border_width_'.$side])) $textStyles[] = '--heading-hover-border-'.$side.'-width: '.max(0, min(20, (int) $settings['hover_border_width_'.$side])).'px';
                            }
                        }
                        $hoverRadii = [];
                        $hasHoverRadius = false;
                        foreach (['top', 'right', 'bottom', 'left'] as $side) {
                            $hasHoverRadius = $hasHoverRadius || isset($settings['hover_border_radius_'.$side]);
                            $hoverRadii[] = max(0, min(100, (int) ($settings['hover_border_radius_'.$side] ?? $settings['border_radius_'.$side] ?? $settings['border_radius'] ?? 0))).'px';
                        }
                        if ($hasHoverRadius) $textStyles[] = '--heading-hover-border-radius: '.implode(' ', $hoverRadii);
                        if (isset($boxShadows[$settings['box_shadow'] ?? ''])) $textStyles[] = 'box-shadow: '.$boxShadows[$settings['box_shadow']];
                        if (isset($boxShadows[$settings['hover_box_shadow'] ?? ''])) $textStyles[] = '--heading-hover-box-shadow: '.$boxShadows[$settings['hover_box_shadow']];
                        if (isset($textShadows[$settings['hover_text_shadow'] ?? ''])) $textStyles[] = '--heading-hover-text-shadow: '.$textShadows[$settings['hover_text_shadow']];
                        $columns = max(1, min(4, (int) ($settings['columns'] ?? 2)));
                        $columnsGap = max(0, min(100, (int) ($settings['columns_gap'] ?? 0)));
                    @endphp
                    <div class="block text-editor" {!! $responsiveAttributes !!} data-drop-cap="{{ filter_var($settings['drop_cap'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false' }}" data-columns="{{ $columns }}" style="{{ implode('; ', $textStyles) }}; column-gap: {{ $columnsGap }}px">{!! \App\Support\TextEditorContent::sanitize((string) ($block['text'] ?? '')) !!}</div>
                @elseif ($block['type'] === 'paragraph')
                    <p class="block paragraph" {!! $responsiveAttributes !!}>{{ $block['text'] }}</p>
                @elseif ($block['type'] === 'quote')
                    <blockquote class="block quote" {!! $responsiveAttributes !!}>{{ $block['text'] }}</blockquote>
                @elseif ($block['type'] === 'list')
                    <ul class="block list" {!! $responsiveAttributes !!}>
                        @foreach (preg_split('/\r\n|\r|\n/', $block['text']) as $item)
                            @if (trim($item) !== '')<li>{{ $item }}</li>@endif
                        @endforeach
                    </ul>
                @elseif ($block['type'] === 'image')
                    @php
                        $settings = $blockSettings;
                        $resolutionWidths = ['thumbnail' => 150, 'medium' => 300, 'large' => 1024];
                        $imageWidth = (int) ($settings['image_width'] ?? 0);
                        $imageWidthUnit = in_array($settings['image_width_unit'] ?? null, ['px', '%', 'vw'], true) ? $settings['image_width_unit'] : 'px';
                        $imageMaxWidth = (int) ($settings['image_max_width'] ?? 0);
                        $imageMaxWidthUnit = in_array($settings['image_max_width_unit'] ?? null, ['px', '%', 'vw'], true) ? $settings['image_max_width_unit'] : '%';
                        $imageHeight = (int) ($settings['image_height'] ?? 0);
                        $imageHeightUnit = in_array($settings['image_height_unit'] ?? null, ['px', '%', 'vh'], true) ? $settings['image_height_unit'] : 'px';
                        $imageStyles = [];
                        if ($imageWidth > 0) $imageStyles[] = 'width: '.$imageWidth.$imageWidthUnit;
                        $maxWidth = $imageMaxWidth > 0 ? $imageMaxWidth.$imageMaxWidthUnit : (isset($resolutionWidths[$settings['image_resolution'] ?? '']) ? $resolutionWidths[$settings['image_resolution']].'px' : '100%');
                        $imageStyles[] = 'max-width: '.$maxWidth;
                        if ($imageHeight > 0) $imageStyles[] = 'height: '.$imageHeight.$imageHeightUnit;
                        if (isset($settings['image_border_radius'])) $imageStyles[] = 'border-radius: '.max(0, min(100, (int) $settings['image_border_radius'])).'px';
                        $imageOpacity = isset($settings['opacity']) ? max(0, min(100, (int) $settings['opacity'])) : null;
                        $imageHoverOpacity = isset($settings['hover_opacity']) ? max(0, min(100, (int) $settings['hover_opacity'])) : null;
                        $imageAlignment = in_array($settings['image_alignment'] ?? null, ['left', 'center', 'right'], true) ? $settings['image_alignment'] : 'center';
                        $captionType = $settings['caption_type'] ?? (! empty($block['text']) ? 'legacy' : 'none');
                        $captionText = $settings['caption'] ?? ($captionType === 'legacy' ? ($block['text'] ?? '') : '');
                        $imageLink = match ($settings['link_type'] ?? 'none') {
                            'custom' => preg_match('#^https?://\S+$#i', $settings['link_url'] ?? '') ? $settings['link_url'] : null,
                            'media' => $block['url'] ?? null,
                            default => null,
                        };
                        $imageAlt = $settings['alt_text'] ?? ($block['text'] ?? '');
                    @endphp
                    @if (!empty($block['url']))
                        <figure class="block image" {!! $responsiveAttributes !!} data-align="{{ $imageAlignment }}" @if ($imageOpacity !== null) data-opacity="true" style="--image-opacity: {{ $imageOpacity / 100 }}; @if ($imageHoverOpacity !== null)--image-hover-opacity: {{ $imageHoverOpacity / 100 }};@endif" @elseif ($imageHoverOpacity !== null) data-hover-opacity="true" style="--image-hover-opacity: {{ $imageHoverOpacity / 100 }}" @endif @if ($imageHoverOpacity !== null && $imageOpacity !== null) data-hover-opacity="true" @endif>
                            @if ($imageLink)<a href="{{ $imageLink }}">@endif
                            <img src="{{ $block['url'] }}" alt="{{ $imageAlt }}" loading="lazy" style="{{ implode('; ', $imageStyles) }}">
                            @if ($imageLink)</a>@endif
                            @if ($captionType !== 'none' && $captionText)<figcaption>{{ $captionText }}</figcaption>@endif
                        </figure>
                    @endif
                @elseif ($block['type'] === 'video')
                    @php
                        $settings = $blockSettings;
                        $videoUrl = (string) ($block['url'] ?? '');
                        $videoSource = in_array($settings['video_source'] ?? null, ['youtube', 'vimeo', 'dailymotion', 'videopress', 'self_hosted'], true) ? $settings['video_source'] : 'self_hosted';
                        $videoEmbed = $videoSource === 'self_hosted' ? null : \App\Support\VideoEmbedUrl::make($videoSource, $videoUrl);
                        $localVideo = preg_match('#^/storage/page-videos/[A-Za-z0-9._-]+\.(mp4|webm|ogg)$#i', $videoUrl) === 1;
                        $remoteVideo = filter_var($videoUrl, FILTER_VALIDATE_URL) && in_array(strtolower((string) parse_url($videoUrl, PHP_URL_SCHEME)), ['http', 'https'], true) && preg_match('/\.(mp4|webm|ogg)$/i', (string) parse_url($videoUrl, PHP_URL_PATH)) === 1;
                        $videoAspect = in_array($settings['video_aspect_ratio'] ?? null, ['1:1', '3:2', '4:3', '16:9', '21:9', '9:16'], true) ? $settings['video_aspect_ratio'] : '16:9';
                        $videoFilters = 'brightness('.max(0, min(200, (int) ($settings['video_filter_brightness'] ?? 100))).'%) contrast('.max(0, min(200, (int) ($settings['video_filter_contrast'] ?? 100))).'%) saturate('.max(0, min(200, (int) ($settings['video_filter_saturation'] ?? 100))).'%) hue-rotate('.max(0, min(360, (int) ($settings['video_filter_hue'] ?? 0))).'deg) blur('.max(0, min(20, (int) ($settings['video_filter_blur'] ?? 0))).'px)';
                        $videoStyles = [];
                        foreach (['top', 'right', 'bottom', 'left'] as $side) {
                            foreach (['margin', 'padding'] as $property) {
                                $key = $property.'_'.$side;
                                if (isset($settings[$key])) $videoStyles[] = $property.'-'.$side.': '.($property === 'margin'
                                    ? max(-500, min(500, (int) $settings[$key]))
                                    : max(0, min(100, (int) $settings[$key]))).'px';
                            }
                        }
                        if (($settings['width'] ?? null) === 'full' || ($settings['align_self'] ?? null) === 'stretch') $videoStyles[] = 'width: 100%';
                        if (($settings['width'] ?? null) === 'inline') $videoStyles[] = 'width: fit-content';
                        if (($settings['align_self'] ?? null) === 'center') $videoStyles[] = 'margin-left: auto; margin-right: auto';
                        if (($settings['align_self'] ?? null) === 'right') $videoStyles[] = 'margin-left: auto';
                        $videoZIndex = filter_var($settings['z_index'] ?? null, FILTER_VALIDATE_INT);
                        if ($videoZIndex !== false && $videoZIndex >= -9999 && $videoZIndex <= 9999) $videoStyles[] = 'position: relative; z-index: '.$videoZIndex;
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $settings['background_color'] ?? '')) $videoStyles[] = 'background-color: '.$settings['background_color'];
                        if (in_array($settings['border_type'] ?? null, ['solid', 'dashed', 'dotted'], true)) {
                            $videoStyles[] = 'border-style: '.$settings['border_type'];
                            if (preg_match('/^#[0-9a-fA-F]{6}$/', $settings['border_color'] ?? '')) $videoStyles[] = 'border-color: '.$settings['border_color'];
                            $videoStyles[] = 'border-width: '.max(0, min(20, (int) ($settings['border_width_top'] ?? 1))).'px '.max(0, min(20, (int) ($settings['border_width_right'] ?? 1))).'px '.max(0, min(20, (int) ($settings['border_width_bottom'] ?? 1))).'px '.max(0, min(20, (int) ($settings['border_width_left'] ?? 1))).'px';
                        }
                        $videoStyles[] = 'border-radius: '.max(0, min(100, (int) ($settings['border_radius_top'] ?? $settings['border_radius'] ?? 0))).'px '.max(0, min(100, (int) ($settings['border_radius_right'] ?? $settings['border_radius'] ?? 0))).'px '.max(0, min(100, (int) ($settings['border_radius_bottom'] ?? $settings['border_radius'] ?? 0))).'px '.max(0, min(100, (int) ($settings['border_radius_left'] ?? $settings['border_radius'] ?? 0))).'px';
                        if (isset($settings['box_shadow']) && in_array($settings['box_shadow'], ['soft', 'strong'], true)) $videoStyles[] = 'box-shadow: '.($settings['box_shadow'] === 'soft' ? '0 2px 8px #18204426' : '0 5px 15px #18204440');
                    @endphp
                    @if ($videoEmbed || ($videoSource === 'self_hosted' && ($localVideo || $remoteVideo)))
                        <figure class="block video" {!! $responsiveAttributes !!} style="{{ implode('; ', $videoStyles) }}">
                            <div class="video-frame" style="aspect-ratio: {{ str_replace(':', ' / ', $videoAspect) }}; filter: {{ $videoFilters }}">
                                @if ($videoEmbed)
                                    <iframe src="{{ $videoEmbed }}" title="{{ $block['text'] ?? 'Video' }}" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                @else
                                    <video controls preload="metadata" title="{{ $block['text'] ?? 'Video' }}">
                                        <source src="{{ $videoUrl }}">
                                        Browser Anda tidak mendukung pemutar video.
                                    </video>
                                @endif
                            </div>
                            @if (!empty($block['text']))<figcaption>{{ $block['text'] }}</figcaption>@endif
                        </figure>
                    @endif
                @elseif ($block['type'] === 'button')
                    <div class="block button-wrap" {!! $responsiveAttributes !!}><a class="block button-link" href="{{ $block['url'] }}">{{ $block['text'] }}</a></div>
                @elseif ($block['type'] === 'divider')
                    <hr class="block divider" {!! $responsiveAttributes !!}>
                @elseif ($block['type'] === 'spacer')
                    <div class="block spacer" {!! $responsiveAttributes !!} aria-hidden="true" style="height: {{ max(16, min(400, (int) ($block['height'] ?? 48))) }}px"></div>
                @elseif ($block['type'] === 'map')
                    <a class="block map" {!! $responsiveAttributes !!} href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode($block['text']) }}" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 21s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12Z" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="9" r="2.2" stroke="currentColor" stroke-width="1.6"/></svg><span><strong>Buka lokasi di Google Maps</strong><small>{{ $block['text'] }}</small></span></a>
                @elseif ($block['type'] === 'menu')
                    @php
                        $menu = $navigationMenus->firstWhere('id', (int) ($blockSettings['menu_id'] ?? 0));
                        $menuStyles = [];
                        $menuTextShadows = ['none' => 'none', 'soft' => '1px 2px 5px #18204440', 'strong' => '2px 3px 8px #18204480'];
                        if (in_array($blockSettings['alignment'] ?? null, ['left', 'center', 'right', 'justify'], true)) $menuStyles[] = 'text-align: '.$blockSettings['alignment'];
                        if (isset($blockSettings['font_size'])) $menuStyles[] = 'font-size: '.max(12, min(72, (int) $blockSettings['font_size'])).'px';
                        if (in_array($blockSettings['font_weight'] ?? null, ['400', '500', '600', '700', '800'], true)) $menuStyles[] = 'font-weight: '.$blockSettings['font_weight'];
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $blockSettings['text_color'] ?? '')) $menuStyles[] = 'color: '.$blockSettings['text_color'];
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $blockSettings['hover_text_color'] ?? '')) $menuStyles[] = '--heading-hover-color: '.$blockSettings['hover_text_color'];
                        if (in_array($blockSettings['blend_mode'] ?? null, ['normal', 'multiply', 'screen', 'overlay', 'darken', 'lighten'], true)) $menuStyles[] = 'mix-blend-mode: '.$blockSettings['blend_mode'];
                        if ((int) ($blockSettings['text_stroke_width'] ?? 0) > 0 && preg_match('/^#[0-9a-fA-F]{6}$/', $blockSettings['text_stroke_color'] ?? '')) $menuStyles[] = '-webkit-text-stroke: '.max(0, min(3, (int) $blockSettings['text_stroke_width'])).'px '.$blockSettings['text_stroke_color'];
                        if (isset($menuTextShadows[$blockSettings['hover_text_shadow'] ?? ''])) $menuStyles[] = '--heading-hover-text-shadow: '.$menuTextShadows[$blockSettings['hover_text_shadow']];
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $blockSettings['background_color'] ?? '')) $menuStyles[] = '--heading-background: '.$blockSettings['background_color'];
                        if (($blockSettings['background_type'] ?? 'color') === 'image' && preg_match('#^/storage/heading-backgrounds/[A-Za-z0-9._-]+$#', $blockSettings['background_image'] ?? '')) $menuStyles[] = '--heading-background-image: url("'.$blockSettings['background_image'].'")';
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $blockSettings['hover_background_color'] ?? '')) $menuStyles[] = '--heading-hover-background: '.$blockSettings['hover_background_color'];
                        if (($blockSettings['hover_background_type'] ?? 'color') === 'image' && preg_match('#^/storage/heading-backgrounds/[A-Za-z0-9._-]+$#', $blockSettings['hover_background_image'] ?? '')) $menuStyles[] = '--heading-hover-background-image: url("'.$blockSettings['hover_background_image'].'")';
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $blockSettings['border_color'] ?? '')) $menuStyles[] = '--heading-border-color: '.$blockSettings['border_color'];
                        if (preg_match('/^#[0-9a-fA-F]{6}$/', $blockSettings['hover_border_color'] ?? '')) $menuStyles[] = '--heading-hover-border-color: '.$blockSettings['hover_border_color'];
                        if (in_array($blockSettings['border_type'] ?? null, ['solid', 'dashed', 'dotted'], true)) {
                            $menuStyles[] = 'border-style: '.$blockSettings['border_type'];
                            foreach (['top', 'right', 'bottom', 'left'] as $side) {
                                if (isset($blockSettings['border_width_'.$side])) $menuStyles[] = 'border-'.$side.'-width: '.max(0, min(20, (int) $blockSettings['border_width_'.$side])).'px';
                            }
                        }
                        if (in_array($blockSettings['hover_border_type'] ?? null, ['solid', 'dashed', 'dotted'], true)) {
                            $menuStyles[] = '--heading-hover-border: '.$blockSettings['hover_border_type'];
                            foreach (['top', 'right', 'bottom', 'left'] as $side) {
                                if (isset($blockSettings['hover_border_width_'.$side])) $menuStyles[] = '--heading-hover-border-'.$side.'-width: '.max(0, min(20, (int) $blockSettings['hover_border_width_'.$side])).'px';
                            }
                        }
                        foreach (['top', 'right', 'bottom', 'left'] as $side) {
                            foreach (['margin', 'padding'] as $property) {
                                $key = $property.'_'.$side;
                                if (isset($blockSettings[$key])) $menuStyles[] = $property.'-'.$side.': '.($property === 'margin'
                                    ? max(-500, min(500, (int) $blockSettings[$key]))
                                    : max(0, min(100, (int) $blockSettings[$key]))).'px';
                            }
                        }
                        $menuRadii = [];
                        foreach (['top', 'right', 'bottom', 'left'] as $side) {
                            $menuRadii[] = max(0, min(100, (int) ($blockSettings['border_radius_'.$side] ?? $blockSettings['border_radius'] ?? 0))).'px';
                        }
                        if (count(array_filter(['border_radius_top', 'border_radius_right', 'border_radius_bottom', 'border_radius_left', 'border_radius'], fn ($key) => isset($blockSettings[$key]))) > 0) $menuStyles[] = 'border-radius: '.implode(' ', $menuRadii);
                        $hoverRadii = [];
                        foreach (['top', 'right', 'bottom', 'left'] as $side) {
                            $hoverRadii[] = max(0, min(100, (int) ($blockSettings['hover_border_radius_'.$side] ?? $blockSettings['border_radius_'.$side] ?? $blockSettings['border_radius'] ?? 0))).'px';
                        }
                        if (count(array_filter(['hover_border_radius_top', 'hover_border_radius_right', 'hover_border_radius_bottom', 'hover_border_radius_left'], fn ($key) => isset($blockSettings[$key]))) > 0) $menuStyles[] = '--heading-hover-border-radius: '.implode(' ', $hoverRadii);
                        if (($blockSettings['text_shadow'] ?? null) && isset($menuTextShadows[$blockSettings['text_shadow']])) $menuStyles[] = 'text-shadow: '.$menuTextShadows[$blockSettings['text_shadow']];
                        $menuBoxShadows = ['none' => 'none', 'soft' => '0 2px 8px #18204426', 'strong' => '0 5px 15px #18204440'];
                        if (isset($menuBoxShadows[$blockSettings['box_shadow'] ?? ''])) $menuStyles[] = 'box-shadow: '.$menuBoxShadows[$blockSettings['box_shadow']];
                        if (isset($menuBoxShadows[$blockSettings['hover_box_shadow'] ?? ''])) $menuStyles[] = '--heading-hover-box-shadow: '.$menuBoxShadows[$blockSettings['hover_box_shadow']];
                        $menuStyleColors = [
                            'menu_item_color' => '--menu-item-color',
                            'menu_item_hover_color' => '--menu-item-hover-color',
                            'menu_item_active_color' => '--menu-item-active-color',
                            'menu_item_background' => '--menu-item-background',
                            'menu_item_hover_background' => '--menu-item-hover-background',
                            'menu_item_active_background' => '--menu-item-active-background',
                            'submenu_item_color' => '--submenu-item-color',
                            'submenu_item_hover_color' => '--submenu-item-hover-color',
                            'submenu_item_active_color' => '--submenu-item-active-color',
                            'submenu_background' => '--submenu-background',
                            'submenu_item_background' => '--submenu-item-background',
                            'submenu_item_hover_background' => '--submenu-item-hover-background',
                            'submenu_item_active_background' => '--submenu-item-active-background',
                            'submenu_border_color' => '--submenu-border-color',
                        ];
                        foreach ($menuStyleColors as $key => $variable) {
                            if (preg_match('/^#[0-9a-fA-F]{6}$/', $blockSettings[$key] ?? '')) $menuStyles[] = $variable.': '.$blockSettings[$key];
                        }
                        $menuStyleDimensions = [
                            'menu_item_font_size' => ['--menu-item-font-size', 8, 48],
                            'menu_item_padding_x' => ['--menu-item-padding-x', 0, 60],
                            'menu_item_padding_y' => ['--menu-item-padding-y', 0, 60],
                            'menu_item_gap' => ['--menu-item-gap', 0, 60],
                            'submenu_item_font_size' => ['--submenu-item-font-size', 8, 48],
                            'submenu_item_padding_x' => ['--submenu-item-padding-x', 0, 60],
                            'submenu_item_padding_y' => ['--submenu-item-padding-y', 0, 60],
                            'submenu_item_gap' => ['--submenu-item-gap', 0, 60],
                            'submenu_min_width' => ['--submenu-min-width', 80, 600],
                            'submenu_padding' => ['--submenu-padding', 0, 60],
                            'submenu_border_width' => ['--submenu-border-width', 0, 20],
                            'submenu_border_radius' => ['--submenu-border-radius', 0, 100],
                        ];
                        foreach ($menuStyleDimensions as $key => [$variable, $minimum, $maximum]) {
                            if (isset($blockSettings[$key])) $menuStyles[] = $variable.': '.max($minimum, min($maximum, (int) $blockSettings[$key])).'px';
                        }
                        foreach (['menu_item_font_weight' => '--menu-item-font-weight', 'submenu_item_font_weight' => '--submenu-item-font-weight'] as $key => $variable) {
                            if (in_array($blockSettings[$key] ?? null, ['400', '500', '600', '700', '800'], true)) $menuStyles[] = $variable.': '.$blockSettings[$key];
                        }
                        $submenuShadows = ['none' => 'none', 'soft' => '0 4px 12px #18204420', 'strong' => '0 8px 20px #18204435'];
                        if (isset($submenuShadows[$blockSettings['submenu_shadow'] ?? ''])) $menuStyles[] = '--submenu-shadow: '.$submenuShadows[$blockSettings['submenu_shadow']];
                        if (($blockSettings['width'] ?? null) === 'full' || ($blockSettings['align_self'] ?? null) === 'stretch') $menuStyles[] = 'width: 100%';
                        if (($blockSettings['width'] ?? null) === 'inline') $menuStyles[] = 'width: fit-content';
                        if (($blockSettings['align_self'] ?? null) === 'center') $menuStyles[] = 'margin-left: auto; margin-right: auto';
                        if (($blockSettings['align_self'] ?? null) === 'right') $menuStyles[] = 'margin-left: auto';
                    @endphp
                    @if ($menu)
                        <nav class="block menu" {!! $responsiveAttributes !!} aria-label="{{ $menu->name }}" style="{{ implode('; ', $menuStyles) }}">
                            @include('pages.partials.menu-items', ['items' => $menu->tree()])
                        </nav>
                    @endif
                @endif
                @if ($blockCustomCss !== '')
                    <style>{!! $blockCustomCss !!}</style>
                @endif
            @endforeach
        </article>
    </main>
    <footer class="footer">© {{ date('Y') }} <strong>Pemerintah Kabupaten {{ $siteContent['site_name'] }}</strong>. Hak cipta dilindungi.</footer>
</body>
</html>
