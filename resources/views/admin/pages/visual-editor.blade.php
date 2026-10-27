<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#252f60">
    <title>VEditor · {{ $page->title }}</title>
    <style>
        :root { color-scheme: light; --navy: #252f60; --navy-dark: #1b244e; --gold: #eeae43; --ink: #222b45; --muted: #727b90; --line: #e1e4ea; --panel: #fff; --stage: #f0f1f4; font-family: "Segoe UI", Arial, sans-serif; }
        * { box-sizing: border-box; }
        body { margin: 0; overflow: hidden; background: var(--stage); color: var(--ink); }
        button, input, textarea, select { font: inherit; }
        button, a { -webkit-tap-highlight-color: transparent; }
        button { cursor: pointer; }
        a { color: inherit; text-decoration: none; }
        .editor-shell { display: flex; width: 100%; height: 100dvh; flex-direction: column; }
        .editor-topbar { z-index: 5; display: flex; min-height: 58px; align-items: center; justify-content: space-between; gap: 15px; padding: 0 16px; border-bottom: 1px solid var(--line); background: #fff; }
        .top-left, .top-center, .top-right { display: flex; align-items: center; }
        .top-left { min-width: 0; gap: 13px; }
        .top-center { gap: 3px; }
        .top-right { gap: 9px; }
        .back-link { display: inline-flex; align-items: center; gap: 7px; color: #606a80; font-size: 12px; font-weight: 600; white-space: nowrap; }
        .back-link:hover { color: var(--navy); }
        .back-link svg { width: 16px; height: 16px; }
        .brand-mark { display: grid; width: 30px; height: 30px; flex: 0 0 auto; place-items: center; border-radius: 6px; background: var(--navy); color: var(--gold); }
        .brand-mark svg { width: 18px; height: 18px; }
        .document-name { overflow: hidden; color: #343d58; font-size: 12px; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
        .document-status { margin-left: 7px; color: #8c93a3; font-size: 10px; white-space: nowrap; }
        .tool-button { display: grid; width: 34px; height: 34px; place-items: center; border: 1px solid transparent; border-radius: 5px; background: transparent; color: #667087; }
        .tool-button:hover, .tool-button.active { border-color: #e6e8ee; background: #f4f5f8; color: var(--navy); }
        .tool-button svg { width: 17px; height: 17px; }
        .tool-button[aria-pressed="true"] { border-color: #d7dbea; background: #f0f2f8; color: var(--navy); }
        .preview-button { display: inline-flex; height: 34px; align-items: center; gap: 7px; padding: 0 11px; border: 1px solid #dfe2e9; border-radius: 4px; background: white; color: #49536c; font-size: 10px; font-weight: 700; }
        .preview-button:hover { border-color: var(--navy); }
        .preview-button svg { width: 15px; height: 15px; }
        .publish-button { display: inline-flex; height: 34px; align-items: center; gap: 7px; padding: 0 13px; border: 0; border-radius: 4px; background: var(--gold); color: #282c3f; font-size: 10px; font-weight: 800; }
        .publish-button:hover { background: #f2bb5d; }
        .publish-button svg { width: 15px; height: 15px; }
        .structure-toggle { display: grid; width: 34px; height: 34px; place-items: center; border: 0; border-radius: 4px; background: #171717; color: #fff; }
        .structure-toggle:hover, .structure-toggle[aria-pressed="true"] { background: #303030; }
        .structure-toggle svg { width: 20px; height: 20px; }
        .structure-window { position: fixed; z-index: 20; top: 78px; right: 24px; display: flex; width: min(360px, calc(100vw - 32px)); max-height: min(540px, calc(100dvh - 100px)); flex-direction: column; overflow: hidden; border: 1px solid #dfe2e9; border-radius: 8px; background: #fff; box-shadow: 0 8px 32px #18204430; }
        .structure-window[hidden] { display: none; }
        .structure-window-head { display: flex; min-height: 48px; flex: 0 0 auto; align-items: center; justify-content: space-between; gap: 10px; padding: 0 14px; border-bottom: 1px solid var(--line); cursor: move; touch-action: none; user-select: none; }
        .structure-window-title { display: flex; align-items: center; gap: 9px; margin: 0; color: #303954; font-size: 13px; font-weight: 700; }
        .structure-window-title svg { width: 18px; height: 18px; color: var(--navy); }
        .structure-window-close { display: grid; width: 30px; height: 30px; place-items: center; border: 0; border-radius: 4px; background: transparent; color: #687184; }
        .structure-window-close:hover { background: #f1f2f5; color: #252f60; }
        .structure-window-close svg { width: 17px; height: 17px; }
        .structure-tree { min-height: 80px; overflow: auto; padding: 7px; }
        .structure-tree-empty { padding: 20px 12px; color: #858d9e; font-size: 12px; text-align: center; }
        .structure-tree-row { display: flex; min-height: 39px; align-items: center; gap: 7px; padding: 0 7px; border-radius: 4px; color: #525d72; }
        .structure-tree-row:hover { background: #f4f5f8; }
        .structure-tree-row.selected { background: #e8eaf0; }
        .structure-tree-row[draggable="true"] { cursor: grab; }
        .structure-tree-row.dragging { opacity: .4; }
        .structure-tree-row.drop-before { box-shadow: inset 0 2px var(--gold); }
        .structure-tree-row.drop-after { box-shadow: inset 0 -2px var(--gold); }
        .structure-tree-row.drop-inside { outline: 2px solid var(--gold); background: #fff9ed; }
        .structure-tree.is-drop-root { background: #fff9ed; box-shadow: inset 0 0 0 2px var(--gold); }
        .structure-tree-item { display: flex; min-width: 0; flex: 1; align-items: center; gap: 8px; border: 0; background: transparent; color: inherit; text-align: left; }
        .structure-tree-item span { overflow: hidden; font-size: 12px; text-overflow: ellipsis; white-space: nowrap; }
        .structure-tree-icon { width: 19px; height: 19px; flex: 0 0 auto; color: #687184; stroke: currentColor; stroke-width: 1.5; stroke-linecap: round; stroke-linejoin: round; fill: none; }
        .structure-tree-actions { display: flex; flex: 0 0 auto; gap: 2px; opacity: 0; transition: opacity .12s; }
        .structure-tree-row:hover .structure-tree-actions, .structure-tree-row:focus-within .structure-tree-actions { opacity: 1; }
        .structure-tree-action { display: grid; width: 25px; height: 25px; place-items: center; border: 0; border-radius: 4px; background: transparent; color: #687184; }
        .structure-tree-action:hover { background: #e4e7ed; color: var(--navy); }
        .structure-tree-action.delete:hover { background: #fff0ee; color: #a64b43; }
        .structure-tree-action svg { width: 14px; height: 14px; }
        .structure-tree-rename { min-width: 0; height: 28px; flex: 1; padding: 3px 6px; border: 1px solid #aeb6ca; border-radius: 3px; outline: 0; color: #303954; font-size: 12px; }
        .structure-tree-toggle { display: grid; width: 22px; height: 22px; flex: 0 0 auto; place-items: center; border: 0; border-radius: 3px; background: transparent; color: #687184; }
        .structure-tree-toggle:hover { background: #e4e7ed; }
        .structure-tree-toggle svg { width: 13px; height: 13px; transition: transform .15s; }
        .structure-tree-toggle[aria-expanded="false"] svg { transform: rotate(-90deg); }
        .structure-tree-spacer { width: 22px; flex: 0 0 auto; }
        .editor-body { display: grid; min-height: 0; flex: 1; grid-template-columns: 320px 32px minmax(0, 1fr); transition: grid-template-columns .2s ease; }
        .component-panel, .settings-panel { min-width: 0; min-height: 0; overflow-y: auto; background: var(--panel); }
        .component-panel, .settings-panel { grid-column: 1; grid-row: 1; border-right: 1px solid var(--line); }
        .panel-toggle-rail { grid-column: 2; grid-row: 1; }
        .canvas-area { grid-column: 3; grid-row: 1; }
        .settings-panel { display: none; }
        .editor-body.show-settings .component-panel { display: none; }
        .editor-body.show-settings .settings-panel { display: block; }
        .editor-body.panels-collapsed { grid-template-columns: 0 32px minmax(0, 1fr); }
        .editor-body.panels-collapsed .component-panel, .editor-body.panels-collapsed .settings-panel { visibility: hidden; overflow: hidden; }
        .panel-toggle-rail { display: flex; align-items: center; justify-content: center; border-right: 1px solid #dfe2e9; background: #f8f8fa; }
        .panel-toggle { display: grid; width: 26px; min-height: 68px; place-items: center; border: 1px solid #d8dce5; border-radius: 4px; background: #fff; color: #657087; }
        .panel-toggle:hover, .panel-toggle:focus-visible { border-color: #aeb6ca; color: var(--navy); outline: 0; }
        .panel-toggle svg { width: 16px; height: 16px; transition: transform .2s; }
        .editor-body.panels-collapsed .panel-toggle svg { transform: rotate(180deg); }
        .panel-head { display: flex; min-height: 54px; align-items: center; justify-content: space-between; padding: 0 16px; border-bottom: 1px solid #eceef2; }
        .panel-head h2 { margin: 0; color: #303954; font-size: 12px; font-weight: 800; }
        .panel-head span { color: #9299a8; font-size: 10px; }
        .component-group { padding: 12px 10px 17px; }
        .group-title { display: flex; align-items: center; gap: 8px; margin: 0 3px 12px; color: #30384c; font-size: 13px; font-weight: 700; }
        .group-title svg { width: 13px; height: 13px; }
        .component-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 9px; }
        .component-tile { display: flex; min-height: 91px; flex-direction: column; align-items: center; justify-content: center; gap: 8px; padding: 8px 5px; border: 1px solid #d7dbe3; border-radius: 5px; background: #fff; color: #515b70; cursor: grab; transition: border-color .15s, background .15s, color .15s; }
        .component-tile:active { cursor: grabbing; }
        .component-tile.dragging { opacity: .5; }
        .component-tile:hover, .component-tile:focus-visible { border-color: #9ba5c2; background: #f8f9fc; color: var(--navy); outline: 0; }
        .component-tile svg { width: 31px; height: 31px; stroke: currentColor; stroke-width: 1.5; stroke-linecap: round; stroke-linejoin: round; fill: none; }
        .component-tile span { font-size: 11px; font-weight: 500; }
        .component-hint { margin: 13px 3px 0; color: #9096a4; font-size: 10px; line-height: 1.55; }

        .canvas-area { display: flex; min-width: 0; min-height: 0; flex-direction: column; }
        .canvas-toolbar { display: flex; min-height: 43px; align-items: center; justify-content: space-between; gap: 10px; padding: 0 13px; border-bottom: 1px solid #dfe1e7; background: #f8f8fa; }
        .canvas-url { overflow: hidden; color: #7b8394; font-size: 10px; text-overflow: ellipsis; white-space: nowrap; }
        .canvas-width { color: #9299a8; font-size: 10px; white-space: nowrap; }
        .canvas-scroll { overflow: auto; min-height: 0; flex: 1; padding: 25px; }
        .page-canvas { width: 1280px; min-height: 600px; margin: 0 auto; background: #fff; box-shadow: 0 3px 16px #18204418; container-type: inline-size; }
        .page-canvas.tablet { width: 768px; }
        .page-canvas.mobile { width: 390px; }
        .page-canvas.mobile .preview-site-header { min-height: 72px; padding: 9px 15px; }
        .page-canvas.mobile .preview-site-name { font-size: 14px; }
        .page-canvas.mobile .preview-site-nav { display: none; }
        .page-canvas.mobile .preview-hero { padding: 38px 18px 32px; }
        .page-canvas.mobile .preview-hero h1 { font-size: 30px; }
        .page-canvas.mobile .preview-hero p { font-size: 11px; }
        .page-canvas.mobile .preview-content { padding: 26px 18px 42px; }
        .preview-site-header { display: flex; min-height: 85px; align-items: center; justify-content: space-between; gap: 15px; padding: 12px max(20px, calc((100% - 1120px) / 2)); border-bottom: 1px solid #e7e9ef; }
        .preview-site-name { color: var(--navy); font-family: Georgia, "Times New Roman", serif; font-size: 17px; font-weight: 700; }
        .preview-site-name small { display: block; margin-bottom: 4px; color: #9a783d; font-family: "Segoe UI", Arial, sans-serif; font-size: 8px; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; }
        .preview-site-nav { color: #6b7388; font-size: 9px; }
        .preview-hero { padding: 53px max(20px, calc((100% - 900px) / 2)) 43px; background: var(--navy); color: #fff; }
        .preview-hero h1 { margin: 0; font-family: Georgia, "Times New Roman", serif; font-size: clamp(30px, 5cqw, 46px); line-height: 1.18; }
        .preview-hero p { max-width: 700px; margin: 15px 0 0; color: #d7dbea; font-size: 12px; line-height: 1.8; }
        .preview-content { width: min(100%, 760px); min-height: 320px; margin: 0 auto; padding: 34px 20px 58px; }
        .preview-block { position: relative; margin: 0 0 19px; padding: 0; border: 0; border-radius: 0; cursor: pointer; transition: outline-color .12s, background-color .12s; }
        .preview-block[draggable="true"] { cursor: grab; }
        .preview-block.dragging { opacity: .4; }
        .preview-block:hover { outline: 1px solid #cbd2e1; outline-offset: 1px; }
        .preview-block.selected { outline: 2px solid var(--navy); outline-offset: 1px; background-color: #252f6005; box-shadow: inset 3px 0 var(--gold); }
        .preview-block.drop-before { box-shadow: inset 0 3px var(--gold), inset 3px 0 var(--gold); }
        .preview-block.drop-after { box-shadow: inset 0 -3px var(--gold), inset 3px 0 var(--gold); }
        .preview-block.selected.drop-before { box-shadow: inset 0 3px var(--gold), inset 3px 0 var(--gold); }
        .preview-block.selected.drop-after { box-shadow: inset 0 -3px var(--gold), inset 3px 0 var(--gold); }
        .preview-content.is-drop-target { outline: 2px dashed #eeae43; outline-offset: -2px; }
        .container-structure { display: grid; gap: 12px; margin: 28px 0; padding: 16px; border: 1px dashed #cbd1dc; background: #fff; }
        .container-structure.selected { outline: 2px solid var(--navy); outline-offset: 2px; }
        .container-cell { display: flex; min-width: 0; min-height: 90px; flex-direction: column; gap: 8px; padding: 10px; border: 1px dashed #c6ccd7; background: #f8f9fb; transition: background .15s, border-color .15s; }
        .container-cell.is-drop-target { border-color: var(--gold); background: #fff9ed; }
        .container-cell-empty { display: grid; min-height: 56px; flex: 1; place-items: center; color: #9299a8; font-size: 10px; text-align: center; }
        .preview-block.menu { min-width: 0; background-color: var(--heading-background, transparent); background-image: var(--heading-background-image, none); background-size: cover; background-position: center; background-repeat: no-repeat; border-color: var(--heading-border-color, transparent); border-top-width: var(--heading-border-top-width, 1px); border-right-width: var(--heading-border-right-width, 1px); border-bottom-width: var(--heading-border-bottom-width, 1px); border-left-width: var(--heading-border-left-width, 1px); }
        .preview-menu-list, .preview-menu-list ul { display: flex; flex-wrap: wrap; align-items: center; gap: var(--menu-item-gap, 4px); margin: 0; padding: 0; list-style: none; }
        .preview-menu-list li { position: relative; }
        .preview-menu-list > li > a, .preview-menu-list > li > span { display: block; padding: var(--menu-item-padding-y, 9px) var(--menu-item-padding-x, 12px); color: var(--menu-item-color, inherit); font-size: var(--menu-item-font-size, inherit); font-weight: var(--menu-item-font-weight, inherit); background: var(--menu-item-background, transparent); text-decoration: none; }
        .preview-menu-list > li > a:hover { color: var(--menu-item-hover-color, var(--menu-item-color, inherit)); background: var(--menu-item-hover-background, transparent); }
        .preview-menu-list > li > a[aria-current="page"] { color: var(--menu-item-active-color, var(--menu-item-color, inherit)); background: var(--menu-item-active-background, transparent); }
        .preview-menu-list ul { position: absolute; top: 100%; left: 0; z-index: 2; display: none; min-width: var(--submenu-min-width, 170px); flex-direction: column; align-items: stretch; gap: var(--submenu-item-gap, 0); padding: var(--submenu-padding, 5px); border: var(--submenu-border-width, 1px) solid var(--submenu-border-color, #e1e4ea); border-radius: var(--submenu-border-radius, 0); background: var(--submenu-background, #fff); box-shadow: var(--submenu-shadow, 0 4px 12px #18204420); }
        .preview-menu-list ul li > a, .preview-menu-list ul li > span { display: block; padding: var(--submenu-item-padding-y, 9px) var(--submenu-item-padding-x, 12px); color: var(--submenu-item-color, #303954); font-size: var(--submenu-item-font-size, inherit); font-weight: var(--submenu-item-font-weight, inherit); background: var(--submenu-item-background, transparent); text-decoration: none; white-space: nowrap; }
        .preview-menu-list ul li > a:hover { color: var(--submenu-item-hover-color, var(--submenu-item-color, #303954)); background: var(--submenu-item-hover-background, #f2f3f7); }
        .preview-menu-list ul li > a[aria-current="page"] { color: var(--submenu-item-active-color, var(--submenu-item-color, #303954)); background: var(--submenu-item-active-background, #f2f3f7); }
        .preview-menu-list li:hover > ul, .preview-menu-list li:focus-within > ul { display: block; }
        .preview-menu-list ul li ul { top: 0; left: 100%; }
        .preview-block.menu:hover { color: var(--heading-hover-color, inherit); background-color: var(--heading-hover-background, var(--heading-background, transparent)); background-image: var(--heading-hover-background-image, var(--heading-background-image, none)); border-color: var(--heading-hover-border-color, var(--heading-border-color, transparent)); border-style: var(--heading-hover-border, inherit); border-radius: var(--heading-hover-border-radius, 0); box-shadow: var(--heading-hover-box-shadow, none); text-shadow: var(--heading-hover-text-shadow, none); }
        .structure-builder { width: 100%; margin: 22px auto 0; padding: 17px; border: 1px dashed #cbd1dc; background: #fff; }
        .structure-builder.is-drop-target { border-color: var(--gold); background: #fff9ed; }
        .structure-builder-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; color: #49516a; font-size: 11px; font-weight: 700; }
        .structure-builder-head button { min-height: 32px; padding: 0 10px; border: 1px solid #d5dae4; border-radius: 4px; background: #fff; color: var(--navy); font-size: 10px; font-weight: 700; }
        .structure-builder-hint { margin: 8px 0 0; color: #858d9e; font-size: 10px; line-height: 1.5; }
        .structure-picker { margin-top: 14px; padding: 14px; border: 1px solid #e1e4ea; background: #fff; }
        .structure-picker[hidden] { display: none; }
        .structure-picker-title { margin: 0 0 12px; color: #49516a; font-size: 12px; font-weight: 700; text-align: center; }
        .structure-picker-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(102px, 1fr)); gap: 9px; }
        .structure-option { display: grid; min-height: 74px; justify-items: center; gap: 6px; padding: 7px; border: 1px solid transparent; background: #fff; color: #566176; font-size: 9px; }
        .structure-option:hover, .structure-option:focus-visible { border-color: #b8c0d0; background: #f8f9fb; color: var(--navy); outline: 0; }
        .structure-option-preview { display: grid; width: 100%; height: 43px; gap: 2px; }
        .structure-option-preview span { background: #d6d9de; }
        .structure-option-preview .structure-flow-arrow { position: relative; z-index: 1; display: grid; place-items: center; background: transparent; color: #fff; font-size: 19px; font-weight: 700; }
        .preview-block.selected:before { position: absolute; top: -8px; left: 0; padding: 3px 6px; background: var(--navy); color: #fff; content: attr(data-label); font-size: 8px; font-weight: 700; }
        .preview-block.heading { margin-top: 33px; color: var(--navy); background-color: var(--heading-background, transparent); background-image: var(--heading-background-image, none); background-size: cover; background-position: center; background-repeat: no-repeat; border-color: var(--heading-border-color, transparent); border-top-width: var(--heading-border-top-width, 1px); border-right-width: var(--heading-border-right-width, 1px); border-bottom-width: var(--heading-border-bottom-width, 1px); border-left-width: var(--heading-border-left-width, 1px); font-family: Georgia, "Times New Roman", serif; font-size: 23px; font-weight: 700; line-height: 1.4; }
        .preview-block.heading:hover { color: var(--heading-hover-color, inherit); background-color: var(--heading-hover-background, var(--heading-background, transparent)); background-image: var(--heading-hover-background-image, var(--heading-background-image, none)); background-size: cover; background-position: center; border-color: var(--heading-hover-border-color, var(--heading-border-color, transparent)); border-style: var(--heading-hover-border, inherit); border-radius: var(--heading-hover-border-radius, var(--heading-border-radius, 0)); box-shadow: var(--heading-hover-box-shadow, none); text-shadow: var(--heading-hover-text-shadow, none); }
        .preview-block.heading:hover { border-top-width: var(--heading-hover-border-top-width, var(--heading-border-top-width, 1px)); border-right-width: var(--heading-hover-border-right-width, var(--heading-border-right-width, 1px)); border-bottom-width: var(--heading-hover-border-bottom-width, var(--heading-border-bottom-width, 1px)); border-left-width: var(--heading-hover-border-left-width, var(--heading-border-left-width, 1px)); }
        .preview-heading-link { color: inherit; text-decoration: none; }
        .preview-block.paragraph, .preview-block.text { color: #48516a; font-size: 13px; line-height: 1.9; white-space: pre-line; }
        .preview-block.text-editor { white-space: normal; background-color: var(--heading-background, transparent); background-image: var(--heading-background-image, none); background-size: cover; background-position: center; background-repeat: no-repeat; border-color: var(--heading-border-color, transparent); border-top-width: var(--heading-border-top-width, 1px); border-right-width: var(--heading-border-right-width, 1px); border-bottom-width: var(--heading-border-bottom-width, 1px); border-left-width: var(--heading-border-left-width, 1px); }
        .preview-block.text-editor p { margin: 0 0 10px; }
        .preview-block.text-editor p:last-child { margin-bottom: 0; }
        .preview-block.text-editor[data-drop-cap="true"] > p:first-child:first-letter { float: left; margin: .08em .12em 0 0; color: var(--navy); font-size: 3em; font-weight: 700; line-height: .8; }
        .preview-block.text-editor[data-columns="2"] { column-count: 2; }
        .preview-block.text-editor[data-columns="3"] { column-count: 3; }
        .preview-block.text-editor[data-columns="4"] { column-count: 4; }
        .preview-block.text-editor a { color: var(--navy); text-decoration: underline; }
        .preview-block.text-editor img { max-width: 100%; height: auto; }
        .preview-block.text-editor:hover { color: var(--heading-hover-color, inherit); background-color: var(--heading-hover-background, var(--heading-background, transparent)); background-image: var(--heading-hover-background-image, var(--heading-background-image, none)); border-color: var(--heading-hover-border-color, var(--heading-border-color, transparent)); border-style: var(--heading-hover-border, inherit); border-top-width: var(--heading-hover-border-top-width, var(--heading-border-top-width, 1px)); border-right-width: var(--heading-hover-border-right-width, var(--heading-border-right-width, 1px)); border-bottom-width: var(--heading-hover-border-bottom-width, var(--heading-border-bottom-width, 1px)); border-left-width: var(--heading-hover-border-left-width, var(--heading-border-left-width, 1px)); border-radius: var(--heading-hover-border-radius, var(--heading-border-radius, 0)); box-shadow: var(--heading-hover-box-shadow, none); text-shadow: var(--heading-hover-text-shadow, none); }
        .preview-block.quote { margin: 25px 0; padding: 16px 20px; border-left: 4px solid var(--gold); background: #fbf8f0; color: #535d76; font-family: Georgia, "Times New Roman", serif; font-size: 16px; font-style: italic; line-height: 1.7; white-space: pre-line; }
        .preview-block.list { padding-left: 22px; color: #48516a; font-size: 12px; line-height: 1.9; }
        .preview-block.image img { display: block; width: auto; max-width: 100%; max-height: 650px; margin: 0 auto; border-radius: 3px; object-fit: contain; }
        .image-caption { margin-top: 7px; color: #7b8393; font-size: 10px; }
        .preview-block.image[data-align="left"] { text-align: left; }
        .preview-block.image[data-align="center"] { text-align: center; }
        .preview-block.image[data-align="right"] { text-align: right; }
        .preview-block.image[data-align="left"] img { margin-left: 0; }
        .preview-block.image[data-align="right"] img { margin-right: 0; }
        .preview-block.image[data-align="center"] img { margin-right: auto; margin-left: auto; }
        .preview-block.image[data-opacity] img { opacity: var(--image-opacity); }
        .preview-block.image[data-hover-opacity]:hover img { opacity: var(--image-hover-opacity); }
        .preview-block.image a { display: inline-block; max-width: 100%; }
        .preview-block.video .video-frame { width: 100%; overflow: hidden; background: #11172e; }
        .preview-block.video video, .preview-block.video iframe { display: block; width: 100%; height: 100%; border: 0; background: #11172e; }
        .video-upload-preview { width: 100%; height: 125px; background-color: #f7f8fa; background-image: linear-gradient(45deg, #d9dde3 25%, transparent 25%), linear-gradient(-45deg, #d9dde3 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #d9dde3 75%), linear-gradient(-45deg, transparent 75%, #d9dde3 75%); background-size: 20px 20px; background-position: 0 0, 0 10px, 10px -10px, -10px 0; }
        .video-upload > .setting-label { margin-bottom: 7px; color: #49516a; font-size: 10px; font-weight: 700; }
        .video-upload-preview video { display: block; width: 100%; height: 100%; object-fit: contain; }
        .video-upload-actions { display: flex; gap: 7px; margin-top: 7px; }
        .video-upload-actions button { min-height: 34px; padding: 0 10px; border: 1px solid #d5dae4; border-radius: 4px; background: #fff; color: #34405f; font-size: 10px; font-weight: 600; }
        .video-upload-actions button:disabled { opacity: .6; }
        .video-filter-control { display: grid; grid-template-columns: 1fr 58px; align-items: center; gap: 7px; margin: 0 0 12px; }
        .video-filter-control label { color: #49516a; font-size: 10px; font-weight: 600; }
        .video-filter-control input { min-width: 0; }
        .video-filter-control input[type="number"] { height: 32px; padding: 4px; border: 1px solid #dfe2e9; border-radius: 4px; color: var(--ink); font-size: 10px; }
        .video-caption { margin-top: 7px; color: #7b8393; font-size: 10px; }
        .preview-block.button { padding: 8px 0; text-align: left; }
        .preview-block.button span { display: inline-flex; min-height: 40px; align-items: center; padding: 0 16px; border-radius: 3px; background: var(--gold); color: #292d3d; font-size: 11px; font-weight: 700; }
        .preview-block.divider { margin: 22px 0; padding: 0; border: 0; }
        .preview-block.divider hr { margin: 0; border: 0; border-top: 1px solid #e7e9ef; }
        .preview-block.spacer { display: flex; align-items: center; justify-content: center; margin: 0; padding: 0; outline: 1px dashed #c7ccda; outline-offset: -1px; color: #8a92a4; font-size: 9px; }
        .preview-block.map { display: flex; min-height: 105px; align-items: center; justify-content: center; gap: 12px; background: #eef0ed; color: #58634f; }
        .preview-block.map svg { width: 27px; height: 27px; flex: 0 0 auto; }
        .map-preview strong, .map-preview small { display: block; }
        .map-preview strong { font-size: 11px; }
        .map-preview small { margin-top: 4px; color: #7d8578; font-size: 9px; }
        .empty-canvas { display: flex; min-height: 235px; flex-direction: column; align-items: center; justify-content: center; border: 1px dashed #d5d9e3; color: #7f8798; text-align: center; }
        .empty-canvas svg { width: 27px; height: 27px; margin-bottom: 10px; color: #929aab; }
        .empty-canvas strong { color: #525d75; font-size: 11px; }
        .empty-canvas span { margin-top: 5px; font-size: 9px; }

        .settings-empty { padding: 26px 18px; color: #838b9c; text-align: center; }
        .settings-empty svg { width: 27px; height: 27px; margin-bottom: 10px; color: #9ba2b1; }
        .settings-empty strong { display: block; color: #4d5770; font-size: 11px; }
        .settings-empty p { margin: 6px auto 0; max-width: 205px; font-size: 9px; line-height: 1.5; }
        .settings-form { padding: 15px; }
        .heading-tabs { display: grid; grid-template-columns: repeat(3, 1fr); margin: -15px -15px 0; border-bottom: 1px solid #e1e4ea; }
        .heading-tab { min-height: 48px; border: 0; border-bottom: 3px solid transparent; background: #fff; color: #697284; font-size: 10px; font-weight: 600; }
        .heading-tab[aria-selected="true"] { border-bottom-color: var(--navy); color: var(--navy); }
        .heading-tab:hover { background: #f8f9fc; }
        .heading-tab-content { padding-top: 16px; }
        .heading-section { margin: 0 0 17px; }
        .heading-section-title { margin: 0 0 14px; color: #303954; font-size: 12px; font-weight: 800; }
        .heading-setting-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 10px; }
        .heading-setting-grid .setting-field { min-width: 0; }
        .spacing-setting { margin: 0 0 17px; }
        .spacing-setting-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
        .spacing-setting-head strong { color: #49516a; font-size: 11px; font-weight: 600; }
        .spacing-setting-head span { color: #7d8595; font-size: 10px; }
        .spacing-setting-controls { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)) 36px; }
        .spacing-setting-controls input { width: 100%; min-width: 0; height: 36px; padding: 4px; border: 1px solid #d6dae2; border-right: 0; border-radius: 0; outline: 0; color: var(--ink); text-align: center; font-size: 11px; }
        .spacing-setting-controls input:first-child { border-radius: 4px 0 0 4px; }
        .spacing-setting-controls input:focus { position: relative; z-index: 1; border-color: #8993b4; box-shadow: 0 0 0 2px #252f600d; }
        .spacing-link { display: grid; width: 36px; height: 36px; place-items: center; border: 1px solid #d6dae2; border-radius: 0 4px 4px 0; background: #fff; color: #60697a; }
        .spacing-link svg { width: 15px; height: 15px; }
        .spacing-link[aria-pressed="true"] { border-color: #c9ced8; background: #d7d9dd; color: #252b38; }
        .spacing-setting-labels { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)) 36px; margin-top: 4px; color: #858d9e; text-align: center; font-size: 9px; }
        .setting-field input[type="color"] { height: 36px; padding: 3px; }
        .heading-state-toggle { display: grid; grid-template-columns: 1fr 1fr; margin: 12px 0; border: 1px solid #dfe2e9; border-radius: 4px; overflow: hidden; }
        .heading-state-toggle button { min-height: 32px; border: 0; background: #fff; color: #5b6476; font-size: 10px; }
        .heading-state-toggle button[aria-pressed="true"] { background: #e7e9ee; color: #242a38; font-weight: 700; }
        .background-control { margin-top: 3px; }
        .background-image-picker { display: grid; grid-template-columns: 1fr auto; align-items: center; gap: 9px; padding: 10px; border: 1px dashed #cfd4df; border-radius: 4px; background: #fafbfc; }
        .background-image-button, .background-image-remove { min-height: 32px; padding: 0 10px; border: 1px solid #d5dae4; border-radius: 4px; background: #fff; color: #34405f; font-size: 10px; font-weight: 600; }
        .background-image-button:disabled { opacity: .6; }
        .background-image-picker .setting-help { grid-column: 1 / -1; margin: 0; }
        .background-image-preview { grid-column: 1 / -1; width: 100%; max-height: 110px; border-radius: 3px; object-fit: cover; }
        .background-image-remove { display: inline-flex; align-items: center; justify-self: end; gap: 5px; color: #9b3c3c; }
        .background-image-remove svg { width: 14px; height: 14px; }
        .image-picker { display: grid; gap: 10px; margin-bottom: 16px; }
        .image-picker-heading { display: flex; align-items: center; justify-content: space-between; color: #555e6d; font-size: 12px; }
        .image-picker-sparkle { width: 18px; height: 18px; color: #e700c7; }
        .image-picker-card { overflow: hidden; border: 1px solid #e1e3e7; background: #a0a7ad; }
        .image-picker-preview-frame { position: relative; display: grid; height: 113px; place-items: center; overflow: hidden; background: #a0a7ad; }
        .image-picker-placeholder { width: 100%; height: 100%; }
        .image-picker-preview { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        .image-picker-remove { position: absolute; top: 9px; right: 9px; display: grid; width: 30px; height: 30px; place-items: center; border: 0; border-radius: 2px; background: #43474a; color: #fff; box-shadow: 0 2px 5px #0003; }
        .image-picker-remove:hover:not(:disabled) { background: #292c2e; }
        .image-picker-remove:disabled { cursor: default; opacity: .65; }
        .image-picker-remove svg { width: 17px; height: 17px; }
        .image-picker-actions { display: grid; height: 41px; grid-template-columns: minmax(0, 1fr) 40px; background: #111; }
        .image-picker-actions button { min-width: 0; border: 0; background: #111; color: #fff; font-size: 12px; font-weight: 700; }
        .image-picker-actions button:hover { background: #292929; }
        .image-picker-actions .image-picker-library { display: grid; place-items: center; border-left: 1px solid #b8b8b8; }
        .image-picker-actions svg { width: 17px; height: 17px; }
        .image-picker-url .setting-field { margin: 0; }
        .image-picker-url .setting-field input { min-width: 0; }
        .image-control-row { display: grid; grid-template-columns: 1fr 1fr; align-items: center; gap: 9px; margin-bottom: 14px; }
        .image-control-row label { color: #49516a; font-size: 10px; font-weight: 700; }
        .image-control-row select, .image-control-row input { width: 100%; min-width: 0; min-height: 35px; padding: 7px 8px; border: 1px solid #dfe2e9; border-radius: 4px; background: #fff; color: var(--ink); font-size: 11px; }
        .image-control-slider { display: grid; grid-template-columns: minmax(0, 1fr) 70px 55px; align-items: center; gap: 6px; margin-bottom: 14px; }
        .image-control-slider label { grid-column: 1 / -1; color: #49516a; font-size: 10px; font-weight: 700; }
        .image-control-slider input[type="range"] { min-width: 0; accent-color: var(--navy); }
        .image-control-slider input[type="number"], .image-control-slider select { width: 100%; min-width: 0; min-height: 34px; padding: 6px; border: 1px solid #dfe2e9; border-radius: 4px; background: #fff; color: var(--ink); font-size: 11px; }
        .image-alignment { display: flex; gap: 4px; }
        .image-alignment button { display: grid; width: 34px; height: 34px; place-items: center; border: 1px solid #dfe2e9; background: #fff; color: #566176; }
        .image-alignment button:first-child { border-radius: 4px 0 0 4px; }
        .image-alignment button:last-child { border-radius: 0 4px 4px 0; }
        .image-alignment button[aria-pressed="true"] { background: #e8eaf0; color: var(--navy); }
        .image-alignment svg { width: 16px; height: 16px; }
        .heading-section + .heading-section { padding-top: 15px; border-top: 1px solid #e8eaf0; }
        .advanced-option { margin: 16px 0; border-top: 1px solid #e8eaf0; }
        .advanced-option summary { padding: 15px 0 10px; color: #303954; cursor: pointer; font-size: 12px; font-weight: 800; }
        .advanced-option-content { padding: 2px 0 8px; }
        .responsive-note { margin: 0 0 12px; color: #858d9e; font-size: 11px; font-style: italic; line-height: 1.5; }
        .responsive-toggle-row { display: flex; min-height: 42px; align-items: center; justify-content: space-between; gap: 10px; color: #515b70; font-size: 12px; }
        .responsive-toggle { display: inline-flex; min-width: 78px; align-items: center; justify-content: space-between; gap: 7px; padding: 3px 8px 3px 3px; border: 0; border-radius: 20px; background: #d7dadd; color: #687184; font-size: 10px; }
        .responsive-toggle:before { width: 24px; height: 24px; border-radius: 50%; background: white; content: ""; }
        .responsive-toggle[aria-pressed="true"] { flex-direction: row-reverse; background: var(--navy); color: white; }
        .custom-css-editor { display: grid; min-height: 160px; grid-template-columns: 40px minmax(0, 1fr); overflow: hidden; border: 1px solid #d5d9e0; border-radius: 4px; background: #fff; }
        .custom-css-lines { overflow: hidden; padding: 7px 8px; background: #f0f0f0; color: #555; text-align: right; white-space: pre; font: 12px/20px Consolas, "Courier New", monospace; user-select: none; }
        .custom-css-input { width: 100%; min-width: 0; min-height: 158px; resize: vertical; padding: 7px 8px; border: 0; outline: 0; background: #fff; color: #222; tab-size: 2; white-space: pre; overflow: auto; font: 12px/20px Consolas, "Courier New", monospace; }
        .selected-type { display: flex; align-items: center; margin: 0 0 17px; padding-bottom: 13px; border-bottom: 1px solid #eceef2; }
        .selected-type strong { display: block; color: #343d58; font-size: 11px; }
        .component-order { display: flex; gap: 7px; margin: -5px 0 15px; }
        .component-order button { min-height: 30px; flex: 1; border: 1px solid #dfe2e9; border-radius: 4px; background: #fff; color: #49536c; font-size: 10px; font-weight: 600; }
        .component-order button:hover:not(:disabled) { border-color: #aeb6ca; color: var(--navy); }
        .component-order button:disabled { cursor: not-allowed; opacity: .45; }
        .setting-field { margin-bottom: 14px; }
        .setting-field label { display: block; margin-bottom: 6px; color: #49516a; font-size: 10px; font-weight: 700; }
        .setting-field input, .setting-field textarea, .setting-field select { width: 100%; min-height: 35px; padding: 8px 9px; border: 1px solid #dfe2e9; border-radius: 4px; outline: 0; background: #fff; color: var(--ink); font-size: 11px; }
        .setting-field textarea { min-height: 88px; resize: vertical; line-height: 1.55; }
        .setting-field input:focus, .setting-field textarea:focus, .setting-field select:focus { border-color: #8993b4; box-shadow: 0 0 0 3px #252f600d; }
        .setting-help { margin-top: 5px; color: #9298a6; font-size: 9px; line-height: 1.45; }
        .text-editor-content { border: 1px solid #dfe2e9; border-radius: 4px; overflow: hidden; background: #fff; }
        .text-editor-toolbar { display: flex; flex-wrap: wrap; gap: 4px; padding: 7px; border-bottom: 1px solid #e3e5eb; background: #f8f9fb; }
        .text-editor-toolbar button, .text-editor-toolbar select { min-height: 30px; padding: 0 8px; border: 1px solid #dfe2e9; border-radius: 3px; background: #fff; color: #4d566b; font-size: 11px; }
        .text-editor-toolbar button { min-width: 30px; font-weight: 700; }
        .text-editor-toolbar button[aria-pressed="true"] { background: #e8eaf0; color: var(--navy); }
        .text-editor-workspace { min-height: 170px; max-height: 360px; overflow: auto; padding: 12px; color: #48516a; font: 13px/1.8 "Segoe UI", Arial, sans-serif; outline: 0; }
        .text-editor-workspace:empty:before { color: #9a9faf; content: "Ketik konten teks di sini..."; pointer-events: none; }
        .text-editor-workspace p { margin: 0 0 10px; }
        .text-editor-code { width: 100%; min-height: 170px; resize: vertical; padding: 12px; border: 0; outline: 0; color: #30384d; font: 12px/1.6 Consolas, "Courier New", monospace; }
        .text-editor-footer { display: flex; min-height: 28px; align-items: center; justify-content: space-between; padding: 0 9px; border-top: 1px solid #e3e5eb; color: #737d8e; font-size: 10px; }
        .text-editor-options { margin: 12px 0 0; padding-top: 5px; border-top: 1px solid #e5e7ed; }
        .text-editor-options .setting-field { margin-top: 12px; }
        .text-editor-gap { display: grid; grid-template-columns: minmax(0, 1fr) 70px; align-items: center; gap: 9px; }
        .text-editor-gap input[type="range"] { min-width: 0; padding: 0; accent-color: var(--navy); }
        .delete-selected { width: 100%; min-height: 34px; margin-top: 5px; border: 1px solid #efd8d5; border-radius: 4px; background: #fff; color: #a64b43; font-size: 10px; font-weight: 700; }
        .delete-selected:hover { background: #fff5f4; }
        .editor-toast { position: fixed; bottom: 17px; left: 50%; z-index: 30; padding: 10px 14px; transform: translate(-50%, 10px); border-radius: 4px; background: var(--navy-dark); box-shadow: 0 5px 15px #1b244e30; color: white; font-size: 10px; opacity: 0; pointer-events: none; transition: opacity .15s, transform .15s; }
        .editor-toast.show { transform: translate(-50%, 0); opacity: 1; }
        .validation-message { margin: 12px 15px 0; padding: 9px 10px; border: 1px solid #f0d6d1; border-radius: 4px; background: #fff6f5; color: #a64b43; font-size: 10px; line-height: 1.5; }
        .sr-only { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0, 0, 0, 0); clip-path: inset(50%); white-space: nowrap; }
        @media (max-width: 1000px) {
            .editor-body { grid-template-columns: 300px 32px minmax(0, 1fr); }
            .canvas-scroll { padding: 17px; }
            .component-tile { min-height: 80px; }
        }
        @media (max-width: 760px) {
            body { overflow: auto; }
            .editor-shell { min-height: 100dvh; height: auto; }
            .editor-topbar { position: sticky; top: 0; min-height: 54px; padding: 0 9px; }
            .top-left { gap: 7px; }
            .back-link { width: 30px; overflow: hidden; font-size: 0; }
            .back-link svg { flex: 0 0 auto; }
            .document-name { max-width: 115px; font-size: 10px; }
            .document-status { display: none; }
            .top-center { gap: 0; }
            .tool-button { width: 28px; height: 30px; }
            .tool-button svg { width: 15px; height: 15px; }
            .preview-button { width: 31px; justify-content: center; padding: 0; font-size: 0; }
            .publish-button { height: 31px; padding: 0 9px; font-size: 9px; }
            .publish-button svg { width: 13px; height: 13px; }
            .editor-body { display: flex; min-height: 0; flex-direction: column; }
            .component-panel, .settings-panel, .panel-toggle-rail, .canvas-area { grid-column: auto; grid-row: auto; }
            .component-panel { order: 3; overflow: visible; border-top: 1px solid var(--line); border-right: 0; }
            .canvas-area { order: 1; min-height: 430px; }
            .settings-panel { order: 3; overflow: visible; border-top: 1px solid var(--line); border-right: 0; }
            .panel-toggle-rail { order: 2; min-height: 34px; border-right: 0; border-bottom: 1px solid #dfe2e9; }
            .panel-toggle { width: 70px; min-height: 26px; }
            .panel-toggle svg { transform: rotate(90deg); }
            .editor-body.panels-collapsed .panel-toggle svg { transform: rotate(-90deg); }
            .editor-body.panels-collapsed .component-panel, .editor-body.panels-collapsed .settings-panel { display: none; }
            .component-panel .panel-head, .settings-panel .panel-head { min-height: 42px; }
            .component-group { padding: 9px 11px 12px; }
            .component-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 6px; }
            .component-tile { min-height: 67px; gap: 4px; padding: 5px 2px; }
            .component-tile svg { width: 23px; height: 23px; }
            .component-tile span { font-size: 9px; }
            .group-title { margin-bottom: 8px; font-size: 11px; }
            .component-hint { display: none; }
            .canvas-scroll { padding: 12px; }
            .page-canvas { min-height: 350px; }
            .page-canvas.tablet { width: 768px; }
            .page-canvas.mobile { width: 390px; }
            .preview-site-header { min-height: 54px; padding: 9px 13px; }
            .preview-site-nav { display: none; }
            .preview-hero { padding: 20px 15px; }
            .preview-hero h1 { font-size: 30px; }
            .preview-content { min-height: 250px; padding: 15px 10px; }
            .settings-form { padding: 11px; }
            .structure-builder { margin-top: 14px; }
            .structure-picker-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .container-cell { min-height: 72px; padding: 6px; }
            .structure-window { top: 68px; right: 8px; width: min(340px, calc(100vw - 16px)); }
        }
        @media (prefers-reduced-motion: reduce) { *, *:before, *:after { transition-duration: .01ms !important; } }
    </style>
</head>
<body>
    <div class="editor-shell">
        <header class="editor-topbar">
            <div class="top-left">
                <a class="back-link" href="{{ route('admin.pages.index') }}" aria-label="Kembali ke daftar halaman"><svg viewBox="0 0 24 24" fill="none"><path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>Keluar editor</a>
                <span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M3 20h18M5 17V8l7-4 7 4v9M8 17v-5h8v5M3 8l9-5 9 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                <span class="document-name">{{ $page->title }}</span><span class="document-status">· {{ $page->status === 'published' ? 'Terbit' : 'Draf' }}</span>
            </div>
            <div class="top-center" role="group" aria-label="Ukuran pratinjau">
                <button class="tool-button active" type="button" data-viewport="desktop" aria-label="Pratinjau desktop" aria-pressed="true"><svg viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="13" rx="1.5" stroke="currentColor" stroke-width="1.6"/><path d="M8 21h8m-4-4v4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></button>
                <button class="tool-button" type="button" data-viewport="tablet" aria-label="Pratinjau tablet" aria-pressed="false"><svg viewBox="0 0 24 24" fill="none"><rect x="5" y="2.5" width="14" height="19" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M11 18.5h2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></button>
                <button class="tool-button" type="button" data-viewport="mobile" aria-label="Pratinjau mobile" aria-pressed="false"><svg viewBox="0 0 24 24" fill="none"><rect x="7" y="2.5" width="10" height="19" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M11 18.5h2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></button>
            </div>
            <div class="top-right">
                <button class="structure-toggle" id="structureToggle" type="button" aria-label="Buka struktur halaman" aria-controls="structureWindow" aria-pressed="false" title="Struktur"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m12 3 9 5-9 5-9-5 9-5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m3 12 9 5 9-5M3 16l9 5 9-5" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg></button>
                @if ($page->status === 'published')<a class="preview-button" href="{{ route('pages.show', $page->slug) }}" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="none"><path d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="2.5" stroke="currentColor" stroke-width="1.6"/></svg>Lihat</a>@endif
                <button class="publish-button" id="saveButton" type="button"><svg viewBox="0 0 24 24" fill="none"><path d="M5 4h12l3 3v13H4V4h1Zm3 0v6h8V4M8 20v-7h8v7" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>Simpan</button>
            </div>
        </header>
        <div class="editor-body" id="editorBody">
            <aside class="component-panel" id="componentPanel" aria-label="Komponen halaman">
                <div class="panel-head"><h2>Komponen</h2><span>Basic</span></div>
                @if ($errors->any())
                    <div class="validation-message" role="alert">@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
                @endif
                @if (session('status'))<div class="validation-message" style="border-color:#cfe8da;background:#f2fbf6;color:#286e50" role="status">{{ session('status') }}</div>@endif
                <section class="component-group global-settings-group">
                    <h3 class="group-title"><svg viewBox="0 0 16 16" fill="none"><path d="M2 4h12M2 8h12M2 12h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="5" cy="4" r="1.5" fill="white" stroke="currentColor"/><circle cx="10" cy="8" r="1.5" fill="white" stroke="currentColor"/><circle cx="7" cy="12" r="1.5" fill="white" stroke="currentColor"/></svg>Global Setting</h3>
                    <div id="pageLayoutSettings"></div>
                </section>
                <section class="component-group">
                    <h3 class="group-title"><svg viewBox="0 0 16 16" fill="none"><path d="m3 6 5 5 5-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>Basic</h3>
                    <div class="component-grid">
                        <button class="component-tile" type="button" draggable="true" data-add-component="heading"><svg viewBox="0 0 36 36"><path d="M6 8h24v4M18 8v22m-5 0h10"/></svg><span>Heading</span></button>
                        <button class="component-tile" type="button" draggable="true" data-add-component="image"><svg viewBox="0 0 36 36"><rect x="4" y="7" width="28" height="22"/><circle cx="12" cy="14" r="2.5"/><path d="m6 27 9-9 6 6 4-4 7 8"/></svg><span>Image</span></button>
                        <button class="component-tile" type="button" draggable="true" data-add-component="text"><svg viewBox="0 0 36 36"><path d="M5 9h26M5 14h26M5 19h21M5 24h26M5 29h18"/></svg><span>Text Editor</span></button>
                        <button class="component-tile" type="button" draggable="true" data-add-component="video"><svg viewBox="0 0 36 36"><rect x="4" y="8" width="28" height="20" rx="5"/><path d="m15 13 8 5-8 5V13Z"/></svg><span>Video</span></button>
                        <button class="component-tile" type="button" draggable="true" data-add-component="menu"><svg viewBox="0 0 36 36"><path d="M5 9h26M5 18h11m5 0h10M5 27h26"/><circle cx="18" cy="18" r="2"/></svg><span>Menu</span></button>
                        <button class="component-tile" type="button" draggable="true" data-add-component="container"><svg viewBox="0 0 36 36"><rect x="4" y="6" width="28" height="24" rx="2"/><path d="M18 6v24M4 18h14m0-12v12m0 0h14"/></svg><span>Container</span></button>
                        <button class="component-tile" type="button" draggable="true" data-add-component="button"><svg viewBox="0 0 36 36"><rect x="5" y="9" width="26" height="18" rx="3"/><path d="M15 14 24 18l-9 4v-8Zm1 8 4 7"/></svg><span>Button</span></button>
                        <button class="component-tile" type="button" draggable="true" data-add-component="divider"><svg viewBox="0 0 36 36"><path d="M5 18h26M14 10l4-5 4 5m-8 16 4 5 4-5"/></svg><span>Divider</span></button>
                        <button class="component-tile" type="button" draggable="true" data-add-component="spacer"><svg viewBox="0 0 36 36"><path d="M5 9h26M5 27h26M18 12v12m0-12-4 5m4-5 4 5m-4 7-4-5m4 5 4-5"/></svg><span>Spacer</span></button>
                        <button class="component-tile" type="button" draggable="true" data-add-component="map"><svg viewBox="0 0 36 36"><path d="m5 11 8-5 10 4 8-5v24l-8 5-10-4-8 5V11Z"/><path d="m13 6v24m10-20v24"/><path d="M23 12c0-3 2-5 5-5s5 2 5 5c0 4-5 9-5 9s-5-5-5-9Z"/><circle cx="28" cy="12" r="1.5"/></svg><span>Google Maps</span></button>
                    </div>
                    <p class="component-hint">Seret komponen ke ruang kerja atau ke dalam container. Container dapat disusun bertingkat, dan widget dapat dipindahkan antar-container.</p>
                </section>
            </aside>

            <aside class="settings-panel" id="settingsPanel" aria-label="Pengaturan komponen">
                <div class="panel-head">
                    <button class="back-link" type="button" id="backToWidgets" aria-label="Kembali ke daftar widget">
                        <svg viewBox="0 0 24 24" fill="none"><path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Widget
                    </button>
                    <h2 id="settingsPanelTitle">Pengaturan</h2>
                    <span id="selectionLabel">Heading</span>
                </div>
                <div id="settingsContent"></div>
            </aside>

            <div class="panel-toggle-rail">
                <button class="panel-toggle" type="button" id="panelToggle" aria-label="Sembunyikan panel widget" aria-expanded="true" aria-controls="componentPanel settingsPanel">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <main class="canvas-area">
                <div class="canvas-toolbar"><span class="canvas-url">{{ url('/halaman/'.$page->slug) }}</span><span class="canvas-width" id="canvasWidth">Desktop · 100%</span></div>
                <div class="canvas-scroll">
                    <div class="page-canvas" id="pageCanvas">
                        <header class="preview-site-header"><span class="preview-site-name"><small>Situs Resmi Pemerintah</small>Kabupaten {{ $siteContent['site_name'] }}</span><span class="preview-site-nav">Beranda　 Profil Daerah　 Layanan Publik</span></header>
                        <section class="preview-hero"><h1 id="previewTitle">{{ $page->title }}</h1><p id="previewSummary">{{ $page->summary }}</p></section>
                        <div class="preview-content" id="previewContent"></div>
                    </div>
                    <section class="structure-builder" id="structureBuilder" aria-label="Tambah widget atau container">
                        <div class="structure-builder-head">
                            <span>Tambah widget atau container</span>
                            <button type="button" id="toggleStructurePicker" aria-expanded="false">Buat Container</button>
                        </div>
                        <p class="structure-builder-hint">Lepaskan widget di sini untuk menambahkannya di bagian paling bawah, atau pilih struktur kolom/baris.</p>
                        <div class="structure-picker" id="structurePicker" hidden>
                            <h3 class="structure-picker-title">Pilih struktur container</h3>
                            <div class="structure-picker-grid" id="structurePickerGrid"></div>
                        </div>
                    </section>
                </div>
            </main>

        </div>
        <form id="saveForm" method="POST" action="{{ route('admin.pages.visual-editor.update', $page) }}" class="sr-only">
            @csrf
            @method('PUT')
            <input type="hidden" name="content_width_mode" id="pageWidthModeInput">
            <input type="hidden" name="content_width" id="pageWidthInput">
            <div id="blockInputs"></div>
        </form>
    </div>
    <section class="structure-window" id="structureWindow" aria-label="Struktur halaman" aria-hidden="true" hidden>
        <header class="structure-window-head" id="structureWindowHandle">
            <h2 class="structure-window-title"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m12 3 9 5-9 5-9-5 9-5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m3 12 9 5 9-5M3 16l9 5 9-5" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>Struktur</h2>
            <button class="structure-window-close" id="structureWindowClose" type="button" aria-label="Tutup struktur"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        </header>
        <div class="structure-tree" id="structureTree" role="tree" aria-label="Urutan container dan widget"></div>
    </section>
    <div class="editor-toast" id="editorToast" role="status" aria-live="polite"></div>
    <script>
        const componentNames = {
            heading: 'Heading',
            image: 'Image',
            text: 'Text Editor',
            paragraph: 'Text Editor',
            quote: 'Kutipan',
            list: 'Daftar',
            video: 'Video',
            menu: 'Menu',
            button: 'Button',
            divider: 'Divider',
            spacer: 'Spacer',
            map: 'Google Maps',
            container: 'Container',
        };
        const menuOptions = {!! Illuminate\Support\Js::from($menus)->toHtml() !!};
        const componentDefaults = {
            heading: { type: 'heading', text: 'Judul baru', level: 2 },
            image: { type: 'image', text: '', url: '', settings: { image_resolution: 'large', caption_type: 'none', link_type: 'none', image_alignment: 'center' } },
            text: { type: 'text', text: 'Tulis konten teks di sini.' },
            video: { type: 'video', text: '', url: '', settings: { video_source: 'self_hosted', video_aspect_ratio: '16:9' } },
            button: { type: 'button', text: 'Pelajari selengkapnya', url: '' },
            divider: { type: 'divider', text: '' },
            spacer: { type: 'spacer', text: '', height: 48 },
            map: { type: 'map', text: 'Alamat lokasi' },
            menu: { type: 'menu', text: '', settings: { menu_id: menuOptions[0]?.id ? String(menuOptions[0].id) : '' } },
            paragraph: { type: 'paragraph', text: 'Tulis konten teks di sini.' },
            quote: { type: 'quote', text: 'Tulis kutipan di sini.' },
            list: { type: 'list', text: 'Butir pertama\nButir kedua' },
        };
        const containerPresets = [
            { key: 'column', label: 'Vertikal', columns: 1, tracks: '1fr', areas: ['a'], icon: '↓' },
            { key: 'row', label: 'Horizontal', columns: 1, tracks: '1fr', areas: ['a'], icon: '→' },
            { key: 'columns-2', label: '2 kolom', columns: 2, tracks: '1fr 1fr', areas: ['a b'] },
            { key: 'columns-wide-left', label: 'Kolom kiri lebar', columns: 2, tracks: '2fr 1fr', areas: ['a b'] },
            { key: 'columns-wide-right', label: 'Kolom kanan lebar', columns: 2, tracks: '1fr 2fr', areas: ['a b'] },
            { key: 'columns-4', label: '4 kolom', columns: 4, tracks: '1fr 1fr 1fr 1fr', areas: ['a b c d'] },
            { key: 'columns-3-center', label: '3 kolom tengah lebar', columns: 3, tracks: '1fr 2fr 1fr', areas: ['a b c'] },
            { key: 'rows-2', label: '2 baris', columns: 1, tracks: '1fr', areas: ['a', 'b'] },
            { key: 'top-wide', label: 'Atas lebar', columns: 2, tracks: '1fr 1fr', areas: ['a a', 'b c'] },
            { key: 'left-tall', label: 'Kiri tinggi', columns: 2, tracks: '1fr 1fr', areas: ['a b', 'a c'] },
            { key: 'grid-3x2', label: '3 kolom × 2 baris', columns: 3, tracks: '1fr 1fr 1fr', areas: ['a b c', 'd e f'] },
            { key: 'top-3-bottom-2', label: '3 atas, 2 bawah', columns: 6, tracks: '1fr 1fr 1fr 1fr 1fr 1fr', areas: ['a a b b c c', 'd d d e e e'] },
            { key: 'top-2-bottom-3', label: '2 atas, 3 bawah', columns: 6, tracks: '1fr 1fr 1fr 1fr 1fr 1fr', areas: ['a a a b b b', 'c c d d e e'] },
        ];
        function createContainerBlock(layout = 'columns-2') {
            const preset = containerPresets.find((item) => item.key === layout) || containerPresets[2];
            const id = globalThis.crypto?.randomUUID?.() || `container-${Date.now()}-${Math.random().toString(36).slice(2, 10)}`;
            return {
                type: 'container',
                text: '',
                settings: {
                    container_id: id,
                    container_layout: preset.key,
                    container_slots: getContainerSlots(preset).length,
                    container_gap: 12,
                },
            };
        }
        function createComponentBlock(type) {
            return type === 'container' ? createContainerBlock() : structuredClone(componentDefaults[type]);
        }
        function getWidgetCount() {
            return blocks.filter((block) => block.type !== 'container').length;
        }
        const maximumPageBlocks = 60;
        const maximumWidgets = 30;
        function createRootComponentEntries(type, block = createComponentBlock(type)) {
            if (block.type === 'container') return [block];

            const container = createContainerBlock('column');
            block.settings ||= {};
            block.settings.container_id = container.settings.container_id;
            block.settings.container_column = 0;
            return [container, block];
        }
        const pageBlocks = {!! Illuminate\Support\Js::from(old('blocks', $page->blocks ?? []))->toHtml() !!};
        const backgroundUploadUrl = {!! Illuminate\Support\Js::from(route('admin.pages.visual-editor.background-image', $page))->toHtml() !!};
        const imageUploadUrl = {!! Illuminate\Support\Js::from(route('admin.pages.visual-editor.image', $page))->toHtml() !!};
        const videoUploadUrl = {!! Illuminate\Support\Js::from(route('admin.pages.visual-editor.video', $page))->toHtml() !!};
        const csrfToken = {!! Illuminate\Support\Js::from(csrf_token())->toHtml() !!};
        const pageWidthSettings = {
            mode: {!! Illuminate\Support\Js::from(old('content_width_mode', $page->content_width_mode ?? 'custom'))->toHtml() !!},
            width: Number({!! Illuminate\Support\Js::from(old('content_width', $page->content_width ?? 760))->toHtml() !!}),
        };
        const existingContainerIds = new Set(pageBlocks
            .filter((block) => block.type === 'container')
            .map((block) => block.settings?.container_id));
        const blocks = [];
        pageBlocks.forEach((sourceBlock) => {
            const block = { ...sourceBlock, settings: { ...(sourceBlock.settings || {}) } };
            const isContainer = block.type === 'container';
            const parentId = isContainer ? block.settings.parent_container_id : block.settings.container_id;
            if (parentId && existingContainerIds.has(parentId)) {
                blocks.push(block);
                return;
            }
            if (isContainer) {
                delete block.settings.parent_container_id;
                delete block.settings.container_column;
                blocks.push(block);
                return;
            }
            delete block.settings.container_id;
            delete block.settings.container_column;
            const [container, child] = createRootComponentEntries(block.type, block);
            blocks.push(container, child);
        });
        const previewContent = document.getElementById('previewContent');
        const pageCanvas = document.getElementById('pageCanvas');
        const canvasScroll = document.querySelector('.canvas-scroll');
        const settingsContent = document.getElementById('settingsContent');
        const pageLayoutSettings = document.getElementById('pageLayoutSettings');
        const selectedPageTitle = document.getElementById('previewTitle');
        const selectedPageSummary = document.getElementById('previewSummary');
        const toast = document.getElementById('editorToast');
        const structureToggle = document.getElementById('structureToggle');
        const structureWindow = document.getElementById('structureWindow');
        const structureWindowHandle = document.getElementById('structureWindowHandle');
        const structureWindowClose = document.getElementById('structureWindowClose');
        const structureTree = document.getElementById('structureTree');
        const expandedStructureContainers = new Set();
        let structureTreeInitialized = false;
        let selectedIndex = -1;
        let toastTimer;
        let draggedBlockIndex = null;
        let structureDraggedBlock = null;
        let activeComponentTab = 'content';
        let activeTextEditorMode = 'visual';
        let headingAdvancedState = 'normal';
        let backgroundUploadInProgress = false;
        const advancedPanelOpenStates = {};
        const linkedSpacing = { margin: false, padding: false };
        const editorBody = document.getElementById('editorBody');
        const panelToggle = document.getElementById('panelToggle');
        const backToWidgets = document.getElementById('backToWidgets');

        function textNode(tag, text, className) {
            const element = document.createElement(tag);
            if (className) element.className = className;
            element.textContent = text || '';
            return element;
        }

        function applyBlockStyles(element, block) {
            const settings = block.settings || {};
            const safeNumber = (value, min, max) => Math.max(min, Math.min(max, Number.parseInt(value, 10) || 0));
            const textShadow = { none: 'none', soft: '1px 2px 5px #18204440', strong: '2px 3px 8px #18204480' };
            const boxShadow = { none: 'none', soft: '0 2px 8px #18204426', strong: '0 5px 15px #18204440' };
            const color = /^#[0-9a-f]{6}$/i;

            ['margin', 'padding'].forEach((property) => {
                ['top', 'right', 'bottom', 'left'].forEach((side) => {
                    const value = settings[`${property}_${side}`];
                    if (value !== undefined && value !== '') {
                        const minimum = property === 'margin' ? -500 : 0;
                        const maximum = property === 'margin' ? 500 : 100;
                        element.style[`${property}${side[0].toUpperCase()}${side.slice(1)}`] = `${safeNumber(value, minimum, maximum)}px`;
                    }
                });
            });
            if (settings.alignment) element.style.textAlign = settings.alignment;
            if (settings.font_size) element.style.fontSize = `${safeNumber(settings.font_size, 12, 72)}px`;
            if (settings.font_weight) element.style.fontWeight = settings.font_weight;
            if (color.test(settings.text_color || '')) element.style.color = settings.text_color;
            if (color.test(settings.hover_text_color || '')) element.style.setProperty('--heading-hover-color', settings.hover_text_color);
            if (settings.blend_mode) element.style.mixBlendMode = settings.blend_mode;
            if (Number(settings.text_stroke_width) > 0 && color.test(settings.text_stroke_color || '')) {
                element.style.webkitTextStroke = `${safeNumber(settings.text_stroke_width, 0, 3)}px ${settings.text_stroke_color}`;
            }
            if (textShadow[settings.text_shadow]) element.style.textShadow = textShadow[settings.text_shadow];
            if (textShadow[settings.hover_text_shadow]) element.style.setProperty('--heading-hover-text-shadow', textShadow[settings.hover_text_shadow]);
            if (settings.width === 'full') element.style.width = '100%';
            if (settings.width === 'inline') element.style.width = 'fit-content';
            if (settings.align_self === 'center') element.style.marginInline = 'auto';
            if (settings.align_self === 'right') element.style.marginLeft = 'auto';
            if (settings.align_self === 'stretch') element.style.width = '100%';
            const zIndex = Number(settings.z_index);
            if (settings.z_index !== undefined && settings.z_index !== '' && Number.isInteger(zIndex) && zIndex >= -9999 && zIndex <= 9999) {
                element.style.position = 'relative';
                element.style.zIndex = String(zIndex);
            }
            if (color.test(settings.background_color || '')) element.style.setProperty('--heading-background', settings.background_color);
            if (settings.background_type === 'image' && /^\/storage\/heading-backgrounds\/[A-Za-z0-9._-]+$/.test(settings.background_image || '')) {
                element.style.setProperty('--heading-background-image', `url("${settings.background_image}")`);
            }
            if (color.test(settings.hover_background_color || '')) element.style.setProperty('--heading-hover-background', settings.hover_background_color);
            if (settings.hover_background_type === 'image' && /^\/storage\/heading-backgrounds\/[A-Za-z0-9._-]+$/.test(settings.hover_background_image || '')) {
                element.style.setProperty('--heading-hover-background-image', `url("${settings.hover_background_image}")`);
            }
            if (color.test(settings.border_color || '')) element.style.setProperty('--heading-border-color', settings.border_color);
            if (color.test(settings.hover_border_color || '')) element.style.setProperty('--heading-hover-border-color', settings.hover_border_color);
            if (block.type === 'menu') {
                const menuColors = {
                    menu_item_color: '--menu-item-color',
                    menu_item_hover_color: '--menu-item-hover-color',
                    menu_item_active_color: '--menu-item-active-color',
                    menu_item_background: '--menu-item-background',
                    menu_item_hover_background: '--menu-item-hover-background',
                    menu_item_active_background: '--menu-item-active-background',
                    submenu_item_color: '--submenu-item-color',
                    submenu_item_hover_color: '--submenu-item-hover-color',
                    submenu_item_active_color: '--submenu-item-active-color',
                    submenu_background: '--submenu-background',
                    submenu_item_background: '--submenu-item-background',
                    submenu_item_hover_background: '--submenu-item-hover-background',
                    submenu_item_active_background: '--submenu-item-active-background',
                    submenu_border_color: '--submenu-border-color',
                };
                Object.entries(menuColors).forEach(([key, variable]) => {
                    if (color.test(settings[key] || '')) element.style.setProperty(variable, settings[key]);
                });
                const menuDimensions = {
                    menu_item_font_size: ['--menu-item-font-size', 8, 48],
                    menu_item_padding_x: ['--menu-item-padding-x', 0, 60],
                    menu_item_padding_y: ['--menu-item-padding-y', 0, 60],
                    menu_item_gap: ['--menu-item-gap', 0, 60],
                    submenu_item_font_size: ['--submenu-item-font-size', 8, 48],
                    submenu_item_padding_x: ['--submenu-item-padding-x', 0, 60],
                    submenu_item_padding_y: ['--submenu-item-padding-y', 0, 60],
                    submenu_item_gap: ['--submenu-item-gap', 0, 60],
                    submenu_min_width: ['--submenu-min-width', 80, 600],
                    submenu_padding: ['--submenu-padding', 0, 60],
                    submenu_border_width: ['--submenu-border-width', 0, 20],
                    submenu_border_radius: ['--submenu-border-radius', 0, 100],
                };
                Object.entries(menuDimensions).forEach(([key, [variable, min, max]]) => {
                    if (settings[key] !== undefined && settings[key] !== '') {
                        element.style.setProperty(variable, `${safeNumber(settings[key], min, max)}px`);
                    }
                });
                if (['400', '500', '600', '700', '800'].includes(settings.menu_item_font_weight)) {
                    element.style.setProperty('--menu-item-font-weight', settings.menu_item_font_weight);
                }
                if (['400', '500', '600', '700', '800'].includes(settings.submenu_item_font_weight)) {
                    element.style.setProperty('--submenu-item-font-weight', settings.submenu_item_font_weight);
                }
                const submenuShadows = { none: 'none', soft: '0 4px 12px #18204420', strong: '0 8px 20px #18204435' };
                if (submenuShadows[settings.submenu_shadow]) {
                    element.style.setProperty('--submenu-shadow', submenuShadows[settings.submenu_shadow]);
                }
            }
            const columns = Number(settings.columns ?? 2);
            if (columns >= 1 && columns <= 4) {
                element.dataset.columns = String(columns);
                element.style.columnGap = `${safeNumber(settings.columns_gap ?? 0, 0, 100)}px`;
            }
            if (settings.border_type && settings.border_type !== 'none') {
                element.style.borderStyle = settings.border_type;
                ['top', 'right', 'bottom', 'left'].forEach((side) => {
                    const value = settings[`border_width_${side}`];
                    element.style.setProperty(`--heading-border-${side}-width`, value === undefined || value === '' ? '1px' : `${safeNumber(value, 0, 20)}px`);
                });
            }
            if (settings.hover_border_type && settings.hover_border_type !== 'none') {
                element.style.setProperty('--heading-hover-border', settings.hover_border_type);
                ['top', 'right', 'bottom', 'left'].forEach((side) => {
                    const value = settings[`hover_border_width_${side}`];
                    if (value !== undefined && value !== '') {
                        element.style.setProperty(`--heading-hover-border-${side}-width`, `${safeNumber(value, 0, 20)}px`);
                    }
                });
            }
            const radiusSides = ['top', 'right', 'bottom', 'left'];
            const normalRadii = radiusSides.map((side) => {
                const value = settings[`border_radius_${side}`] ?? settings.border_radius ?? '';
                return value === '' ? '0px' : `${safeNumber(value, 0, 100)}px`;
            });
            if (normalRadii.some((value) => value !== '0px')) {
                element.style.borderRadius = normalRadii.join(' ');
            }
            element.style.setProperty('--heading-border-radius', normalRadii.join(' '));
            const hoverRadii = radiusSides.map((side) => settings[`hover_border_radius_${side}`]);
            if (hoverRadii.some((value) => value !== undefined && value !== '')) {
                element.style.setProperty('--heading-hover-border-radius', radiusSides.map((side) => {
                    const value = settings[`hover_border_radius_${side}`];
                    return value === undefined || value === '' ? `${safeNumber(settings[`border_radius_${side}`] ?? settings.border_radius ?? 0, 0, 100)}px` : `${safeNumber(value, 0, 100)}px`;
                }).join(' '));
            }
            if (boxShadow[settings.box_shadow]) element.style.boxShadow = boxShadow[settings.box_shadow];
            if (boxShadow[settings.hover_box_shadow]) element.style.setProperty('--heading-hover-box-shadow', boxShadow[settings.hover_box_shadow]);
        }

        function sanitizeTextEditorHtml(html) {
            const parsed = new DOMParser().parseFromString(html, 'text/html');
            const allowedTags = new Set(['A', 'B', 'BLOCKQUOTE', 'BR', 'CODE', 'EM', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'I', 'IMG', 'LI', 'OL', 'P', 'PRE', 'S', 'STRONG', 'U', 'UL']);
            const cleanNode = (node) => {
                if (node.nodeType === Node.TEXT_NODE) return document.createTextNode(node.textContent || '');
                if (!(node instanceof Element)) return document.createDocumentFragment();
                const tag = node.tagName;
                if (['SCRIPT', 'STYLE', 'IFRAME', 'OBJECT', 'EMBED', 'SVG', 'MATH'].includes(tag)) return document.createDocumentFragment();
                const children = Array.from(node.childNodes).map(cleanNode);
                if (!allowedTags.has(tag)) {
                    const fragment = document.createDocumentFragment();
                    children.forEach((child) => fragment.append(child));
                    return fragment;
                }
                const clean = document.createElement(tag.toLowerCase());
                if (tag === 'A' && /^(https?:\/\/|mailto:)/i.test(node.getAttribute('href') || '')) clean.href = node.getAttribute('href');
                if (tag === 'IMG' && /^https:\/\//i.test(node.getAttribute('src') || '')) {
                    clean.src = node.getAttribute('src');
                    clean.alt = node.getAttribute('alt') || '';
                }
                if (['A', 'IMG'].includes(tag) && node.getAttribute('title')) clean.title = node.getAttribute('title');
                children.forEach((child) => clean.append(child));
                return clean;
            };
            const output = document.createElement('div');
            Array.from(parsed.body.childNodes).map(cleanNode).forEach((node) => output.append(node));
            return output.innerHTML;
        }

        function normalizeTextEditorHtml(html) {
            const sanitized = sanitizeTextEditorHtml(html);
            return sanitized && !/<[a-z][\s\S]*>/i.test(sanitized) ? `<p>${sanitized.replace(/\r?\n/g, '<br>')}</p>` : sanitized;
        }

        function renderMenuItems(items) {
            const list = document.createElement('ul');
            list.className = 'preview-menu-list';
            items.forEach((item) => {
                const entry = document.createElement('li');
                const label = item.url ? document.createElement('a') : document.createElement('span');
                label.textContent = item.label;
                if (item.url) label.href = item.url;
                entry.append(label);
                if (item.children?.length) entry.append(renderMenuItems(item.children));
                list.append(entry);
            });
            return list;
        }

        function renderBlock(block, index) {
            let element;
            let renderedElement;
            const content = block.text || '';
            const label = componentNames[block.type] || 'Komponen';

            if (block.type === 'heading') {
                const fallbackTag = `h${[2, 3, 4].includes(Number(block.level)) ? Number(block.level) : 2}`;
                const tag = /^h[1-6]$|^(p|div|span)$/.test(block.settings?.html_tag || '') ? block.settings.html_tag : fallbackTag;
                element = textNode(tag, content, 'preview-block heading');
                applyBlockStyles(element, block);
            } else if (block.type === 'paragraph' || block.type === 'text') {
                if (block.type === 'text') {
                    element = document.createElement('div');
                    element.className = 'preview-block text-editor';
                    element.innerHTML = normalizeTextEditorHtml(content);
                    if (block.settings?.drop_cap) element.dataset.dropCap = 'true';
                    applyBlockStyles(element, block);
                } else {
                    element = textNode('p', content, `preview-block ${block.type}`);
                }
            } else if (block.type === 'quote') {
                element = textNode('blockquote', content, 'preview-block quote');
            } else if (block.type === 'list') {
                element = document.createElement('ul');
                element.className = 'preview-block list';
                content.split(/\r\n|\r|\n/).filter((line) => line.trim()).forEach((line) => {
                    element.append(textNode('li', line));
                });
            } else if (block.type === 'image') {
                element = document.createElement('div');
                element.className = 'preview-block image';
                if (block.url) {
                    const image = document.createElement('img');
                    image.src = block.url;
                    image.alt = block.settings?.alt_text ?? content;
                    image.loading = 'lazy';
                    const settings = block.settings || {};
                    const resolutionMax = { thumbnail: 150, medium: 300, large: 1024 }[settings.image_resolution];
                    const maxWidthValue = Number(settings.image_max_width);
                    const maxWidthUnit = ['px', '%', 'vw'].includes(settings.image_max_width_unit) ? settings.image_max_width_unit : '%';
                    const widthValue = Number(settings.image_width);
                    const widthUnit = ['px', '%', 'vw'].includes(settings.image_width_unit) ? settings.image_width_unit : 'px';
                    const heightValue = Number(settings.image_height);
                    const heightUnit = ['px', '%', 'vh'].includes(settings.image_height_unit) ? settings.image_height_unit : 'px';
                    image.style.width = Number.isFinite(widthValue) && widthValue > 0 ? `${widthValue}${widthUnit}` : 'auto';
                    image.style.maxWidth = Number.isFinite(maxWidthValue) && maxWidthValue > 0
                        ? `${maxWidthValue}${maxWidthUnit}`
                        : resolutionMax ? `${resolutionMax}px` : '100%';
                    image.style.height = Number.isFinite(heightValue) && heightValue > 0 ? `${heightValue}${heightUnit}` : 'auto';
                    if (settings.image_border_radius !== undefined && settings.image_border_radius !== '') {
                        image.style.borderRadius = `${Math.max(0, Math.min(100, Number(settings.image_border_radius) || 0))}px`;
                    }
                    const opacity = Number(settings.opacity);
                    if (settings.opacity !== undefined && settings.opacity !== '' && opacity >= 0 && opacity <= 100) {
                        element.dataset.opacity = 'true';
                        element.style.setProperty('--image-opacity', String(opacity / 100));
                    }
                    const hoverOpacity = Number(settings.hover_opacity);
                    if (settings.hover_opacity !== undefined && settings.hover_opacity !== '' && hoverOpacity >= 0 && hoverOpacity <= 100) {
                        element.dataset.hoverOpacity = 'true';
                        element.style.setProperty('--image-hover-opacity', String(hoverOpacity / 100));
                    }
                    const alignment = ['left', 'center', 'right'].includes(settings.image_alignment) ? settings.image_alignment : 'center';
                    element.dataset.align = alignment;
                    image.addEventListener('error', () => {
                        image.replaceWith(textNode('div', 'Gambar tidak dapat dimuat dari URL ini.', 'setting-help'));
                    }, { once: true });
                    const captionType = settings.caption_type ?? (content ? 'legacy' : 'none');
                    if (settings.link_type === 'custom' && /^https?:\/\/\S+$/i.test(settings.link_url || '')) {
                        const link = document.createElement('a');
                        link.href = settings.link_url;
                        link.append(image);
                        element.append(link);
                    } else if (settings.link_type === 'media') {
                        const link = document.createElement('a');
                        link.href = block.url;
                        link.append(image);
                        element.append(link);
                    } else {
                        element.append(image);
                    }
                    const caption = settings.caption ?? (captionType === 'legacy' ? content : '');
                    if (captionType !== 'none' && caption) element.append(textNode('div', caption, 'image-caption'));
                } else {
                    element.append(textNode('div', 'Pratinjau gambar akan muncul setelah URL diisi.', 'empty-canvas'));
                }
                applyBlockStyles(element, block);
            } else if (block.type === 'video') {
                element = document.createElement('div');
                element.className = 'preview-block video';
                const settings = block.settings || {};
                const aspectRatio = ['1:1', '3:2', '4:3', '16:9', '21:9', '9:16'].includes(settings.video_aspect_ratio) ? settings.video_aspect_ratio : '16:9';
                const frame = document.createElement('div');
                frame.className = 'video-frame';
                frame.style.aspectRatio = aspectRatio.replace(':', ' / ');
                const filters = [
                    `brightness(${Number(settings.video_filter_brightness ?? 100)}%)`,
                    `contrast(${Number(settings.video_filter_contrast ?? 100)}%)`,
                    `saturate(${Number(settings.video_filter_saturation ?? 100)}%)`,
                    `hue-rotate(${Number(settings.video_filter_hue ?? 0)}deg)`,
                    `blur(${Number(settings.video_filter_blur ?? 0)}px)`,
                ].join(' ');
                frame.style.filter = filters;
                const isLocalVideo = /^\/storage\/page-videos\/[A-Za-z0-9._-]+\.(mp4|webm|ogg)$/i.test(block.url || '');
                const source = settings.video_source || 'self_hosted';
                if (block.url && (source === 'self_hosted' || isLocalVideo)) {
                    const video = document.createElement('video');
                    video.controls = true;
                    video.preload = 'metadata';
                    video.src = block.url;
                    frame.append(video);
                } else if (block.url && source !== 'self_hosted') {
                    const embedUrl = videoEmbedUrl(source, block.url);
                    if (embedUrl) {
                        const iframe = document.createElement('iframe');
                        iframe.src = embedUrl;
                        iframe.title = content || 'Video';
                        iframe.loading = 'lazy';
                        iframe.allowFullscreen = true;
                        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
                        frame.append(iframe);
                    } else {
                        frame.append(textNode('div', 'URL tidak cocok dengan sumber video yang dipilih.', 'setting-help'));
                    }
                } else {
                    frame.append(textNode('div', 'Tambahkan URL atau unggah video untuk pratinjau.', 'empty-canvas'));
                }
                element.append(frame);
                if (content) element.append(textNode('div', content, 'video-caption'));
                applyBlockStyles(element, block);
            } else if (block.type === 'menu') {
                element = document.createElement('nav');
                element.className = 'preview-block menu';
                element.setAttribute('aria-label', 'Menu navigasi');
                const menu = menuOptions.find((option) => String(option.id) === String(block.settings?.menu_id));
                if (menu) {
                    element.append(renderMenuItems(menu.items));
                } else {
                    element.append(textNode('div', menuOptions.length ? 'Pilih menu di pengaturan widget.' : 'Buat menu terlebih dahulu di dashboard admin.', 'setting-help'));
                }
                applyBlockStyles(element, block);
            } else if (block.type === 'button') {
                element = document.createElement('div');
                element.className = 'preview-block button';
                element.append(textNode('span', content || 'Tombol'));
            } else if (block.type === 'divider') {
                element = document.createElement('div');
                element.className = 'preview-block divider';
                element.append(document.createElement('hr'));
            } else if (block.type === 'spacer') {
                element = document.createElement('div');
                element.className = 'preview-block spacer';
                const height = Math.max(16, Math.min(400, Number.parseInt(block.height, 10) || 48));
                element.style.height = `${height}px`;
                element.textContent = `Spacer · ${height}px`;
            } else if (block.type === 'map') {
                element = document.createElement('div');
                element.className = 'preview-block map';
                element.innerHTML = '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 21s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12Z" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="9" r="2.2" stroke="currentColor" stroke-width="1.6"/></svg>';
                const mapCopy = document.createElement('span');
                mapCopy.className = 'map-preview';
                mapCopy.append(textNode('strong', 'Google Maps'));
                mapCopy.append(textNode('small', content || 'Tambahkan alamat atau nama lokasi.'));
                element.append(mapCopy);
            } else {
                element = textNode('p', content, 'preview-block text');
            }

            element.dataset.index = String(index);
            element.dataset.label = label;
            element.id = `veditor-block-${index}`;
            element.classList.toggle('selected', index === selectedIndex);
            element.draggable = true;
            element.addEventListener('click', (event) => {
                event.stopPropagation();
                selectBlock(index);
            });
            element.addEventListener('dragstart', (event) => {
                event.stopPropagation();
                draggedBlockIndex = index;
                structureDraggedBlock = block;
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/plain', `block:${index}`);
                element.classList.add('dragging');
            });
            element.addEventListener('dragend', (event) => {
                event.stopPropagation();
                draggedBlockIndex = null;
                structureDraggedBlock = null;
                element.classList.remove('dragging');
                clearDropIndicator();
            });
            renderedElement = element;
            if (block.type === 'heading' && block.settings?.link && /^https?:\/\/\S+$/i.test(block.settings.link)) {
                renderedElement = document.createElement('a');
                renderedElement.className = 'preview-heading-link';
                renderedElement.href = block.settings.link;
                renderedElement.append(element);
            }
            return renderedElement;
        }

        function videoEmbedUrl(source, value) {
            try {
                const url = new URL(value);
                if (!['http:', 'https:'].includes(url.protocol)) return null;
                const host = url.hostname.toLowerCase();
                const path = url.pathname.replace(/^\/+|\/+$/g, '');
                if (source === 'youtube' && ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com', 'youtu.be'].includes(host)) {
                    const id = host === 'youtu.be' ? path.split('/')[0] : path === 'watch' ? url.searchParams.get('v') : path.match(/^(?:embed|shorts)\/([A-Za-z0-9_-]+)$/)?.[1];
                    return id && /^[A-Za-z0-9_-]{6,20}$/.test(id) ? `https://www.youtube-nocookie.com/embed/${id}` : null;
                }
                if (source === 'vimeo' && ['vimeo.com', 'www.vimeo.com', 'player.vimeo.com'].includes(host)) {
                    const id = path.match(/^(?:video\/)?(\d+)(?:\/.*)?$/)?.[1];
                    return id ? `https://player.vimeo.com/video/${id}` : null;
                }
                if (source === 'dailymotion' && ['dailymotion.com', 'www.dailymotion.com', 'dai.ly', 'www.dai.ly'].includes(host)) {
                    const id = path.match(/^(?:video\/)?([A-Za-z0-9]+)(?:_[^/]*)?$/)?.[1];
                    return id ? `https://www.dailymotion.com/embed/video/${id}` : null;
                }
                if (source === 'videopress' && ['videopress.com', 'www.videopress.com', 'videos.files.wordpress.com'].includes(host)) {
                    const id = path.match(/^(?:v\/)?([A-Za-z0-9]+)(?:\/.*)?$/)?.[1];
                    return id ? `https://videopress.com/embed/${id}` : null;
                }
            } catch (error) {
                return null;
            }
            return null;
        }

        function getContainerSlots(preset) {
            return [...new Set(preset.areas.join(' ').split(/\s+/))];
        }

        function renderContainer(block, index, ancestors = []) {
            const settings = block.settings || {};
            const containerId = settings.container_id;
            if (!containerId || ancestors.includes(containerId)) return textNode('div', 'Struktur container tidak valid.', 'container-cell-empty');
            const nestedAncestors = [...ancestors, containerId];
            const preset = containerPresets.find((item) => item.key === settings.container_layout) || containerPresets[1];
            const slotKeys = getContainerSlots(preset);
            const container = document.createElement('div');
            container.className = 'container-structure';
            container.style.gridTemplateColumns = preset.tracks;
            container.style.gridTemplateRows = `repeat(${preset.areas.length}, minmax(90px, auto))`;
            container.style.gridTemplateAreas = preset.areas.map((area) => `"${area}"`).join(' ');
            container.style.gap = `${Math.max(0, Math.min(60, Number(settings.container_gap ?? 12) || 0))}px`;
            container.classList.toggle('selected', index === selectedIndex);
            container.dataset.containerId = settings.container_id;
            container.dataset.index = String(index);
            container.dataset.label = componentNames.container;
            container.id = `veditor-block-${index}`;
            container.draggable = true;
            container.addEventListener('click', (event) => {
                event.stopPropagation();
                if (event.target !== container && !event.target.closest('.container-cell-empty')) return;
                selectBlock(index);
            });
            container.addEventListener('dragstart', (event) => {
                event.stopPropagation();
                draggedBlockIndex = index;
                structureDraggedBlock = block;
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/plain', `block:${index}`);
                container.classList.add('dragging');
            });
            container.addEventListener('dragend', (event) => {
                event.stopPropagation();
                draggedBlockIndex = null;
                structureDraggedBlock = null;
                container.classList.remove('dragging');
                clearDropIndicator();
            });
            slotKeys.forEach((slotKey, columnIndex) => {
                const cell = document.createElement('div');
                cell.className = 'container-cell';
                cell.style.gridArea = slotKey;
                cell.dataset.containerId = settings.container_id;
                cell.dataset.containerColumn = String(columnIndex);
                const children = blocks
                    .map((child, childIndex) => ({ child, childIndex }))
                    .filter(({ child, childIndex }) => childIndex !== index
                        && (child.type === 'container'
                            ? child.settings?.parent_container_id === containerId
                            : child.settings?.container_id === containerId)
                        && Number(child.settings?.container_column) === columnIndex);
                if (!children.length) cell.append(textNode('div', 'Lepaskan widget di sini', 'container-cell-empty'));
                children.forEach(({ child, childIndex }) => {
                    cell.append(child.type === 'container'
                        ? renderContainer(child, childIndex, nestedAncestors)
                        : renderBlock(child, childIndex));
                });
                container.append(cell);
            });
            applyBlockStyles(container, block);
            return container;
        }

        function renderPreview() {
            previewContent.replaceChildren();
            previewContent.style.width = pageWidthSettings.mode === 'full'
                ? '100%'
                : `min(100%, ${Math.max(320, Math.min(1920, pageWidthSettings.width || 760))}px)`;
            const customStyles = blocks.map((block, index) => {
                const css = block.settings?.custom_css || '';
                return css ? css.replace(/\bselector\b/gi, `#veditor-block-${index}`) : '';
            }).filter(Boolean).join('\n');
            let customStyleElement = document.getElementById('veditorCustomCssPreview');
            if (customStyles) {
                if (!customStyleElement) {
                    customStyleElement = document.createElement('style');
                    customStyleElement.id = 'veditorCustomCssPreview';
                    document.head.append(customStyleElement);
                }
                customStyleElement.textContent = customStyles;
            } else {
                customStyleElement?.remove();
            }
            if (!blocks.length) {
                const empty = document.createElement('div');
                empty.className = 'empty-canvas';
                empty.innerHTML = '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="4" y="3" width="16" height="18" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M8 8h8m-8 4h8m-8 4h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>';
                empty.append(textNode('strong', 'Halaman ini belum memiliki komponen'));
                empty.append(textNode('span', 'Pilih komponen Basic di panel kiri untuk memulai.'));
                previewContent.append(empty);
                return;
            }
            const containerIds = new Set(blocks.filter((block) => block.type === 'container').map((block) => block.settings?.container_id));
            blocks.forEach((block, index) => {
                if (block.type === 'container') {
                    if (!containerIds.has(block.settings?.parent_container_id)) {
                        previewContent.append(renderContainer(block, index));
                    }
                } else if (!containerIds.has(block.settings?.container_id)) {
                    previewContent.append(renderBlock(block, index));
                }
            });
        }

        function resizePageCanvas() {
            const viewportMode = document.querySelector('[data-viewport].active')?.dataset.viewport || 'desktop';
            const viewportWidth = viewportMode === 'mobile' ? 390 : viewportMode === 'tablet' ? 768 : window.innerWidth;
            const styles = window.getComputedStyle(canvasScroll);
            const availableWidth = canvasScroll.clientWidth - Number.parseFloat(styles.paddingLeft) - Number.parseFloat(styles.paddingRight);
            pageCanvas.style.width = `${viewportWidth}px`;
            pageCanvas.style.zoom = String(Math.min(1, availableWidth / viewportWidth));
            structureBuilder.style.width = `${pageCanvas.getBoundingClientRect().width}px`;
            document.getElementById('canvasWidth').textContent = `${viewportMode[0].toUpperCase()}${viewportMode.slice(1)} · ${viewportWidth}px`;
        }

        panelToggle.addEventListener('click', () => {
            const collapsed = editorBody.classList.toggle('panels-collapsed');
            panelToggle.setAttribute('aria-expanded', String(!collapsed));
            panelToggle.setAttribute('aria-label', collapsed ? 'Tampilkan panel widget' : 'Sembunyikan panel widget');
            panelToggle.title = collapsed ? 'Tampilkan panel widget' : 'Sembunyikan panel widget';
            requestAnimationFrame(resizePageCanvas);
        });

        function settingField(label, key, options = {}) {
            const wrapper = document.createElement('div');
            wrapper.className = 'setting-field';
            const id = `setting-${options.settings ? 'heading-' : ''}${key}`;
            const labelElement = document.createElement('label');
            labelElement.htmlFor = id;
            labelElement.textContent = label;
            wrapper.append(labelElement);

            let input;
            if (options.select) {
                input = document.createElement('select');
                options.select.forEach(([value, name]) => {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = name;
                    input.append(option);
                });
            } else if (options.textarea !== false) {
                input = document.createElement('textarea');
            } else {
                input = document.createElement('input');
                input.type = options.type || 'text';
            }
            input.id = id;
            if (input instanceof HTMLInputElement) {
                if (options.min !== undefined) input.min = String(options.min);
                if (options.max !== undefined) input.max = String(options.max);
                if (options.step !== undefined) input.step = String(options.step);
            }
            const value = options.settings
                ? blocks[selectedIndex].settings?.[key] ?? options.defaultValue ?? ''
                : blocks[selectedIndex][key] ?? '';
            input.value = value;
            input.addEventListener('input', () => {
                if (options.settings) {
                    blocks[selectedIndex].settings ||= {};
                    blocks[selectedIndex].settings[key] = input.value;
                } else {
                    blocks[selectedIndex][key] = input.value;
                }
                renderPreview();
            });
            wrapper.append(input);
            if (options.help) wrapper.append(textNode('div', options.help, 'setting-help'));
            return wrapper;
        }

        function headingSetting(label, key, options = {}) {
            return settingField(label, key, { ...options, settings: true });
        }

        function headingBackgroundControl(state) {
            const settings = blocks[selectedIndex].settings || {};
            const prefix = state === 'normal' ? '' : 'hover_';
            const typeKey = `${prefix}background_type`;
            const colorKey = `${prefix}background_color`;
            const imageKey = `${prefix}background_image`;
            const wrapper = document.createElement('div');
            wrapper.className = 'background-control';

            const typeToggle = document.createElement('div');
            typeToggle.className = 'heading-state-toggle';
            ['color', 'image'].forEach((type) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = type === 'color' ? 'Color' : 'Image';
                button.setAttribute('aria-pressed', String((settings[typeKey] || 'color') === type));
                button.addEventListener('click', () => {
                    blocks[selectedIndex].settings ||= {};
                    blocks[selectedIndex].settings[typeKey] = type;
                    showSettings();
                    renderPreview();
                });
                typeToggle.append(button);
            });
            wrapper.append(typeToggle);

            if ((settings[typeKey] || 'color') === 'color') {
                wrapper.append(headingSetting('Background Color', colorKey, { textarea: false, type: 'color', defaultValue: '#ffffff' }));
            } else {
                const imagePicker = document.createElement('div');
                imagePicker.className = 'background-image-picker';
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.accept = 'image/jpeg,image/png,image/webp';
                fileInput.hidden = true;
                const chooseButton = document.createElement('button');
                chooseButton.type = 'button';
                chooseButton.className = 'background-image-button';
                chooseButton.textContent = settings[imageKey] ? 'Ganti Gambar' : 'Pilih Gambar';
                chooseButton.addEventListener('click', () => fileInput.click());
                fileInput.addEventListener('change', () => {
                    if (fileInput.files?.[0]) uploadHeadingBackground(fileInput.files[0], state, chooseButton);
                });
                imagePicker.append(fileInput, chooseButton);
                if (settings[imageKey]) {
                    const preview = document.createElement('img');
                    preview.className = 'background-image-preview';
                    preview.src = settings[imageKey];
                    preview.alt = 'Pratinjau gambar background';
                    imagePicker.append(preview);
                    const removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.className = 'background-image-remove';
                    removeButton.setAttribute('aria-label', 'Hapus gambar background');
                    removeButton.title = 'Hapus gambar background';
                    removeButton.innerHTML = '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16m-10 4v6m4-6v6M6 7l1 14h10l1-14M9 7V4h6v3" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                    removeButton.addEventListener('click', () => {
                        blocks[selectedIndex].settings[imageKey] = '';
                        showSettings();
                        renderPreview();
                    });
                    imagePicker.append(removeButton);
                } else {
                    imagePicker.append(textNode('p', 'Pilih gambar JPG, PNG, atau WebP (maksimal 5 MB).', 'setting-help'));
                }
                wrapper.append(imagePicker);
            }
            return wrapper;
        }

        async function uploadHeadingBackground(file, state, button) {
            const targetBlock = blocks[selectedIndex];
            const buttonLabel = button.textContent;
            const maxBytes = 5 * 1024 * 1024;
            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > maxBytes) {
                showToast(file.size > maxBytes ? 'Ukuran gambar maksimal 5 MB.' : 'Format gambar yang didukung: JPG, PNG, atau WebP.');
                return;
            }
            backgroundUploadInProgress = true;
            button.disabled = true;
            button.textContent = 'Mengunggah...';
            const formData = new FormData();
            formData.append('image', file);
            try {
                const response = await fetch(backgroundUploadUrl, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: formData,
                });
                const result = await response.json();
                if (!response.ok) {
                    const validationMessage = result.errors?.image?.[0];
                    throw new Error(validationMessage || result.message || 'Gambar gagal diunggah.');
                }
                if (!blocks.includes(targetBlock)) {
                    showToast('Gambar sudah diunggah, tetapi komponen terkait tidak lagi tersedia.');
                    return;
                }
                const imageKey = state === 'normal' ? 'background_image' : 'hover_background_image';
                const typeKey = state === 'normal' ? 'background_type' : 'hover_background_type';
                targetBlock.settings ||= {};
                targetBlock.settings[imageKey] = result.url;
                targetBlock.settings[typeKey] = 'image';
                showToast('Gambar background berhasil diunggah.');
                if (blocks[selectedIndex] === targetBlock) {
                    showSettings();
                    renderPreview();
                }
            } catch (error) {
                showToast(error instanceof Error ? error.message : 'Gambar gagal diunggah. Periksa koneksi lalu coba lagi.');
            } finally {
                backgroundUploadInProgress = false;
                button.disabled = false;
                button.textContent = buttonLabel;
            }
        }

        function headingSection(title, fields) {
            const section = document.createElement('section');
            section.className = 'heading-section';
            section.append(textNode('h3', title, 'heading-section-title'));
            if (Array.isArray(fields)) {
                const grid = document.createElement('div');
                grid.className = 'heading-setting-grid';
                fields.forEach((field) => grid.append(field));
                section.append(grid);
            } else {
                section.append(fields);
            }
            return section;
        }

        function advancedPanel(title, content) {
            const panel = document.createElement('details');
            panel.className = 'advanced-option';
            const stateKey = `${selectedIndex}:${title}`;
            panel.open = advancedPanelOpenStates[stateKey] ?? title === 'Layout';
            panel.addEventListener('toggle', () => {
                advancedPanelOpenStates[stateKey] = panel.open;
            });
            const summary = document.createElement('summary');
            summary.textContent = title;
            const panelContent = document.createElement('div');
            panelContent.className = 'advanced-option-content';
            panelContent.append(content);
            panel.append(summary, panelContent);
            return panel;
        }

        function renderAdvancedOptions() {
            const settings = blocks[selectedIndex].settings || {};
            const layoutContent = document.createElement('div');
            layoutContent.append(headingSpacingControl('margin'));
            layoutContent.append(headingSpacingControl('padding'));
            layoutContent.append(headingSetting('Width', 'width', {
                textarea: false,
                select: [['default', 'Default'], ['full', 'Full Width'], ['inline', 'Inline']],
                defaultValue: 'default',
            }));
            layoutContent.append(headingSetting('Align Self', 'align_self', {
                textarea: false,
                select: [['left', 'Left'], ['center', 'Center'], ['right', 'Right'], ['stretch', 'Stretch']],
            }));
            layoutContent.append(headingSetting('Z-Index', 'z_index', {
                textarea: false,
                type: 'number',
                min: -9999,
                max: 9999,
                step: 1,
                help: 'Atur urutan tumpukan widget (-9999 sampai 9999).',
            }));
            const responsiveContent = document.createElement('div');
            responsiveContent.append(textNode('p', 'Pengaturan visibilitas hanya berlaku pada halaman pratinjau atau halaman publik, bukan saat mengedit.', 'responsive-note'));
            [
                ['hide_desktop', 'Hide On Desktop'],
                ['hide_tablet', 'Hide On Tablet Portrait'],
                ['hide_mobile', 'Hide On Mobile Portrait'],
            ].forEach(([key, label]) => {
                const row = document.createElement('div');
                row.className = 'responsive-toggle-row';
                row.append(textNode('span', label));
                const toggle = document.createElement('button');
                toggle.type = 'button';
                toggle.className = 'responsive-toggle';
                toggle.setAttribute('role', 'switch');
                toggle.setAttribute('aria-label', label);
                toggle.setAttribute('aria-pressed', String(Boolean(settings[key])));
                toggle.setAttribute('aria-checked', String(Boolean(settings[key])));
                toggle.append(textNode('span', settings[key] ? 'Hide' : 'Show'));
                toggle.addEventListener('click', () => {
                    blocks[selectedIndex].settings ||= {};
                    blocks[selectedIndex].settings[key] = !blocks[selectedIndex].settings[key];
                    showSettings();
                    renderPreview();
                });
                row.append(toggle);
                responsiveContent.append(row);
            });

            const customCssContent = document.createElement('div');
            customCssContent.append(textNode('p', 'Gunakan selector untuk menargetkan komponen ini. Contoh: selector { color: #252f60; }', 'setting-help'));
            const editor = document.createElement('div');
            editor.className = 'custom-css-editor';
            const lineNumbers = textNode('div', '1', 'custom-css-lines');
            lineNumbers.setAttribute('aria-hidden', 'true');
            const textarea = document.createElement('textarea');
            textarea.className = 'custom-css-input';
            textarea.setAttribute('aria-label', 'Custom CSS');
            textarea.spellcheck = false;
            textarea.value = settings.custom_css || '';
            const updateLineNumbers = () => {
                const lineCount = textarea.value.split('\n').length;
                lineNumbers.textContent = Array.from({ length: lineCount }, (_, index) => String(index + 1)).join('\n');
            };
            textarea.addEventListener('input', () => {
                blocks[selectedIndex].settings ||= {};
                blocks[selectedIndex].settings.custom_css = textarea.value;
                updateLineNumbers();
                renderPreview();
            });
            textarea.addEventListener('scroll', () => {
                lineNumbers.scrollTop = textarea.scrollTop;
            });
            textarea.addEventListener('keydown', (event) => {
                if (event.key !== 'Tab') return;
                event.preventDefault();
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                textarea.setRangeText('  ', start, end, 'end');
                textarea.dispatchEvent(new Event('input', { bubbles: true }));
            });
            editor.append(lineNumbers, textarea);
            customCssContent.append(editor);
            return [
                advancedPanel('Layout', layoutContent),
                advancedPanel('Responsive', responsiveContent),
                advancedPanel('Custom CSS', customCssContent),
            ];
        }

        function renderPageLayoutSettings() {
            const content = document.createElement('div');
            const widthMode = document.createElement('div');
            widthMode.className = 'setting-field';
            const modeLabel = document.createElement('label');
            modeLabel.htmlFor = 'page-content-width-mode';
            modeLabel.textContent = 'Lebar halaman';
            const modeSelect = document.createElement('select');
            modeSelect.id = 'page-content-width-mode';
            [
                ['custom', 'Lebar manual'],
                ['full', 'Stretch full lebar'],
            ].forEach(([value, label]) => {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = label;
                modeSelect.append(option);
            });
            modeSelect.value = pageWidthSettings.mode === 'full' ? 'full' : 'custom';
            modeSelect.addEventListener('change', () => {
                pageWidthSettings.mode = modeSelect.value;
                showSettings();
                renderPreview();
            });
            widthMode.append(modeLabel, modeSelect);
            content.append(widthMode);

            if (pageWidthSettings.mode === 'custom') {
                const customWidth = document.createElement('div');
                customWidth.className = 'setting-field';
                const widthLabel = document.createElement('label');
                widthLabel.htmlFor = 'page-content-width';
                widthLabel.textContent = 'Lebar maksimum (px)';
                const widthInput = document.createElement('input');
                widthInput.id = 'page-content-width';
                widthInput.type = 'number';
                widthInput.min = '320';
                widthInput.max = '1920';
                widthInput.step = '10';
                widthInput.value = String(Math.max(320, Math.min(1920, pageWidthSettings.width || 760)));
                widthInput.addEventListener('input', () => {
                    pageWidthSettings.width = Number(widthInput.value);
                    renderPreview();
                });
                customWidth.append(widthLabel, widthInput);
                content.append(customWidth);
            } else {
                content.append(textNode('p', 'Konten halaman akan menggunakan seluruh lebar yang tersedia.', 'setting-help'));
            }

            return content;
        }

        function headingSpacingControl(property, title = property === 'margin' ? 'Margin' : property === 'padding' ? 'Padding' : 'Border Width') {
            const sides = ['top', 'right', 'bottom', 'left'];
            const settings = blocks[selectedIndex].settings || {};
            const section = document.createElement('div');
            section.className = 'spacing-setting';
            const header = document.createElement('div');
            header.className = 'spacing-setting-head';
            header.append(textNode('strong', title));
            header.append(textNode('span', 'px'));
            section.append(header);

            const controls = document.createElement('div');
            controls.className = 'spacing-setting-controls';
            sides.forEach((side) => {
                const input = document.createElement('input');
                input.type = 'number';
                input.min = property === 'margin' ? '-500' : '0';
                input.max = property.endsWith('border_width') ? '20' : property === 'margin' ? '500' : '100';
                input.name = `${property}_${side}`;
                input.setAttribute('aria-label', `${title} ${side}`);
                input.title = `${side[0].toUpperCase()}${side.slice(1)}`;
                input.value = settings[`${property}_${side}`] ?? '';
                input.addEventListener('input', () => {
                    blocks[selectedIndex].settings ||= {};
                    const max = property.endsWith('border_width') ? 20 : property === 'margin' ? 500 : 100;
                    const min = property === 'margin' ? -500 : 0;
                    const value = input.value === '' ? '' : String(Math.max(min, Math.min(max, Number.parseInt(input.value, 10) || 0)));
                    blocks[selectedIndex].settings[`${property}_${side}`] = value;
                    if (linkedSpacing[property]) {
                        sides.forEach((linkedSide) => {
                            blocks[selectedIndex].settings[`${property}_${linkedSide}`] = value;
                        });
                        controls.querySelectorAll('input').forEach((linkedInput) => {
                            linkedInput.value = value;
                        });
                    }
                    renderPreview();
                });
                controls.append(input);
            });

            const linkButton = document.createElement('button');
            linkButton.type = 'button';
            linkButton.className = 'spacing-link';
            linkButton.setAttribute('aria-label', `Samakan semua sisi ${title.toLowerCase()}`);
            linkButton.setAttribute('aria-pressed', String(Boolean(linkedSpacing[property])));
            linkButton.title = linkedSpacing[property] ? 'Nilai semua sisi tersambung' : 'Sambungkan agar semua sisi memakai nilai yang sama';
            linkButton.innerHTML = '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.07 0l3-3A5 5 0 0 0 13 2.93l-1.72 1.71m2.72 6.36a5 5 0 0 0-7.07 0l-3 3A5 5 0 0 0 11 21.07l1.71-1.71" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            linkButton.addEventListener('click', () => {
                linkedSpacing[property] = !linkedSpacing[property];
                if (linkedSpacing[property]) {
                    const value = controls.querySelector('input').value;
                    sides.forEach((side) => {
                        blocks[selectedIndex].settings ||= {};
                        blocks[selectedIndex].settings[`${property}_${side}`] = value;
                    });
                }
                showSettings();
                renderPreview();
            });
            controls.append(linkButton);
            section.append(controls);

            const labels = document.createElement('div');
            labels.className = 'spacing-setting-labels';
            sides.forEach((side) => labels.append(textNode('span', side[0].toUpperCase() + side.slice(1))));
            labels.append(document.createElement('span'));
            section.append(labels);
            return section;
        }

        function headingStateToggle(state, onChange) {
            const toggle = document.createElement('div');
            toggle.className = 'heading-state-toggle';
            ['normal', 'hover'].forEach((value) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = value === 'normal' ? 'Normal' : 'Hover';
                button.setAttribute('aria-pressed', String(state === value));
                button.addEventListener('click', () => onChange(value));
                toggle.append(button);
            });
            return toggle;
        }

        function renderTextEditorContent(tabContent) {
            const block = blocks[selectedIndex];
            const settings = block.settings || {};
            const editor = document.createElement('div');
            editor.className = 'text-editor-content';
            const toolbar = document.createElement('div');
            toolbar.className = 'text-editor-toolbar';
            toolbar.setAttribute('role', 'toolbar');
            toolbar.setAttribute('aria-label', 'Pemformatan teks');
            const workspace = document.createElement('div');
            workspace.className = 'text-editor-workspace';
            workspace.contentEditable = 'true';
            workspace.setAttribute('role', 'textbox');
            workspace.setAttribute('aria-label', 'Konten Text Editor');
            workspace.setAttribute('aria-multiline', 'true');
            workspace.innerHTML = normalizeTextEditorHtml(block.text || '');
            const codeArea = document.createElement('textarea');
            codeArea.className = 'text-editor-code';
            codeArea.setAttribute('aria-label', 'Kode HTML konten');
            codeArea.hidden = activeTextEditorMode !== 'code';
            codeArea.value = block.text || '';
            workspace.hidden = activeTextEditorMode === 'code';

            const status = textNode('span', 'P');
            const updateContent = (value) => {
                blocks[selectedIndex].text = value;
                status.textContent = `${value.length} karakter`;
                renderPreview();
            };
            const command = (name, value = null) => {
                workspace.focus();
                document.execCommand(name, false, value);
                updateContent(workspace.innerHTML);
            };
            const addToolbarButton = (label, title, action, options = {}) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = label;
                button.title = title;
                button.setAttribute('aria-label', title);
                if (options.command) button.dataset.command = options.command;
                button.addEventListener('mousedown', (event) => event.preventDefault());
                button.addEventListener('click', action);
                toolbar.append(button);
                return button;
            };
            const format = document.createElement('select');
            format.setAttribute('aria-label', 'Format paragraf');
            [
                ['P', 'Paragraph'],
                ['H2', 'Heading 2'],
                ['H3', 'Heading 3'],
                ['H4', 'Heading 4'],
                ['BLOCKQUOTE', 'Blockquote'],
                ['PRE', 'Preformatted'],
            ].forEach(([tag, label]) => {
                const option = document.createElement('option');
                option.value = tag;
                option.textContent = label;
                format.append(option);
            });
            format.addEventListener('change', () => command('formatBlock', `<${format.value.toLowerCase()}>`));
            toolbar.append(format);
            addToolbarButton('B', 'Tebal', () => command('bold'), { command: 'bold' });
            addToolbarButton('I', 'Miring', () => command('italic'), { command: 'italic' });
            addToolbarButton('U', 'Garis bawah', () => command('underline'), { command: 'underline' });
            addToolbarButton('•', 'Daftar berpoin', () => command('insertUnorderedList'), { command: 'insertUnorderedList' });
            addToolbarButton('1.', 'Daftar bernomor', () => command('insertOrderedList'), { command: 'insertOrderedList' });
            addToolbarButton('Link', 'Sisipkan tautan', () => {
                const url = window.prompt('Masukkan URL tautan (http://, https://, atau mailto:).');
                if (url && /^(https?:\/\/|mailto:)\S+$/i.test(url)) command('createLink', url);
                else if (url) showToast('URL tautan tidak valid.');
            });
            addToolbarButton('Img', 'Sisipkan gambar dari URL', () => {
                const url = window.prompt('Masukkan URL gambar HTTPS.');
                if (url && /^https:\/\/\S+$/i.test(url)) command('insertImage', url);
                else if (url) showToast('URL gambar harus menggunakan HTTPS.');
            });
            const modeButton = addToolbarButton(activeTextEditorMode === 'visual' ? 'Code' : 'Visual', 'Ubah mode Visual / Code', () => {
                if (activeTextEditorMode === 'visual') {
                    updateContent(workspace.innerHTML);
                    codeArea.value = block.text || '';
                    activeTextEditorMode = 'code';
                    workspace.hidden = true;
                    codeArea.hidden = false;
                    modeButton.textContent = 'Visual';
                } else {
                    activeTextEditorMode = 'visual';
                    workspace.innerHTML = normalizeTextEditorHtml(codeArea.value);
                    workspace.hidden = false;
                    codeArea.hidden = true;
                    updateContent(workspace.innerHTML);
                    modeButton.textContent = 'Code';
                }
            });
            addToolbarButton('⛶', 'Perbesar editor', () => editor.classList.toggle('is-expanded'));
            const mediaButton = addToolbarButton('Media', 'Tambahkan gambar dari URL', () => {
                const url = window.prompt('Masukkan URL gambar HTTPS.');
                if (url && /^https:\/\/\S+$/i.test(url)) command('insertImage', url);
                else if (url) showToast('URL gambar harus menggunakan HTTPS.');
            });
            mediaButton.classList.add('media-button');
            workspace.addEventListener('input', () => updateContent(workspace.innerHTML));
            codeArea.addEventListener('input', () => updateContent(codeArea.value));
            const footer = document.createElement('div');
            footer.className = 'text-editor-footer';
            footer.append(status, textNode('span', 'Editor teks'));
            editor.append(toolbar, workspace, codeArea, footer);
            tabContent.append(editor);

            const options = document.createElement('div');
            options.className = 'text-editor-options';
            const dropCap = document.createElement('div');
            dropCap.className = 'responsive-toggle-row';
            dropCap.append(textNode('span', 'Drop Cap'));
            const dropCapToggle = document.createElement('button');
            dropCapToggle.type = 'button';
            dropCapToggle.className = 'responsive-toggle';
            dropCapToggle.setAttribute('role', 'switch');
            dropCapToggle.setAttribute('aria-label', 'Drop Cap');
            dropCapToggle.setAttribute('aria-pressed', String(Boolean(settings.drop_cap)));
            dropCapToggle.setAttribute('aria-checked', String(Boolean(settings.drop_cap)));
            dropCapToggle.append(textNode('span', settings.drop_cap ? 'On' : 'Off'));
            dropCapToggle.addEventListener('click', () => {
                block.settings ||= {};
                block.settings.drop_cap = !block.settings.drop_cap;
                showSettings();
                renderPreview();
            });
            dropCap.append(dropCapToggle);
            options.append(dropCap);

            const columns = headingSetting('Columns', 'columns', {
                textarea: false,
                select: [['1', '1'], ['2', '2'], ['3', '3'], ['4', '4']],
                defaultValue: '2',
            });
            columns.querySelector('select').addEventListener('change', (event) => {
                block.settings ||= {};
                block.settings.columns = event.target.value;
                renderPreview();
            });
            options.append(columns);
            const gap = document.createElement('div');
            gap.className = 'setting-field';
            const gapLabel = document.createElement('label');
            gapLabel.textContent = 'Columns Gap (px)';
            const gapControls = document.createElement('div');
            gapControls.className = 'text-editor-gap';
            const gapRange = document.createElement('input');
            gapRange.type = 'range';
            gapRange.min = '0';
            gapRange.max = '100';
            gapRange.value = settings.columns_gap ?? 0;
            const gapInput = document.createElement('input');
            gapInput.type = 'number';
            gapInput.min = '0';
            gapInput.max = '100';
            gapInput.value = settings.columns_gap ?? 0;
            gapInput.setAttribute('aria-label', 'Jarak antar kolom (px)');
            gapInput.addEventListener('input', () => {
                block.settings ||= {};
                block.settings.columns_gap = gapInput.value === '' ? '' : String(Math.max(0, Math.min(100, Number.parseInt(gapInput.value, 10) || 0)));
                gapRange.value = block.settings.columns_gap || '0';
                renderPreview();
            });
            gapRange.addEventListener('input', () => {
                block.settings ||= {};
                block.settings.columns_gap = gapRange.value;
                gapInput.value = gapRange.value;
                renderPreview();
            });
            gapControls.append(gapRange, gapInput);
            gap.append(gapLabel, gapControls);
            options.append(gap);
            tabContent.append(options);
        }

        function renderHeadingAdvancedTab(tabContent) {
            const advancedOptions = renderAdvancedOptions();
            tabContent.append(advancedOptions[0]);
            const backgroundState = headingAdvancedState;
            const background = document.createElement('div');
            background.append(headingStateToggle(backgroundState, (value) => {
                headingAdvancedState = value;
                showSettings();
            }));
            background.append(headingBackgroundControl(backgroundState));
            tabContent.append(advancedPanel('Background', background));

            const borderKey = backgroundState === 'normal' ? 'border_type' : 'hover_border_type';
            const shadowKey = backgroundState === 'normal' ? 'box_shadow' : 'hover_box_shadow';
            const border = document.createElement('div');
            border.append(headingStateToggle(backgroundState, (value) => {
                headingAdvancedState = value;
                showSettings();
            }));
            border.append(headingSetting('Border Type', borderKey, {
                textarea: false,
                select: [['none', 'Default / None'], ['solid', 'Solid'], ['dashed', 'Dashed'], ['dotted', 'Dotted']],
                defaultValue: 'none',
            }));
            border.append(headingSetting('Border Color', backgroundState === 'normal' ? 'border_color' : 'hover_border_color', {
                textarea: false,
                type: 'color',
                defaultValue: '#c5cad5',
            }));
            border.append(headingSpacingControl(backgroundState === 'normal' ? 'border_width' : 'hover_border_width', 'Border Width'));
            border.append(headingSpacingControl(backgroundState === 'normal' ? 'border_radius' : 'hover_border_radius', 'Border Radius'));
            border.append(headingSetting('Box Shadow', shadowKey, {
                textarea: false,
                select: [['none', 'None'], ['soft', 'Soft'], ['strong', 'Strong']],
                defaultValue: 'none',
            }));
            tabContent.append(advancedPanel('Border', border));
            advancedOptions.slice(1).forEach((section) => tabContent.append(section));
        }

        function renderMenuStyle(tabContent) {
            const mainItems = document.createElement('div');
            mainItems.append(
                headingSetting('Text Color', 'menu_item_color', { textarea: false, type: 'color', defaultValue: '#252f60' }),
                headingSetting('Hover Text Color', 'menu_item_hover_color', { textarea: false, type: 'color', defaultValue: '#252f60' }),
                headingSetting('Active Text Color', 'menu_item_active_color', { textarea: false, type: 'color', defaultValue: '#252f60' }),
                headingSetting('Background Color', 'menu_item_background', { textarea: false, type: 'color', defaultValue: '#ffffff' }),
                headingSetting('Hover Background Color', 'menu_item_hover_background', { textarea: false, type: 'color', defaultValue: '#f2f3f7' }),
                headingSetting('Active Background Color', 'menu_item_active_background', { textarea: false, type: 'color', defaultValue: '#f2f3f7' }),
                headingSetting('Font Size (px)', 'menu_item_font_size', { textarea: false, type: 'number', min: 8, max: 48, defaultValue: 14 }),
                headingSetting('Font Weight', 'menu_item_font_weight', {
                    textarea: false,
                    select: [['400', 'Normal'], ['500', 'Medium'], ['600', 'Semi Bold'], ['700', 'Bold'], ['800', 'Extra Bold']],
                    defaultValue: '600',
                }),
                headingSetting('Horizontal Padding (px)', 'menu_item_padding_x', { textarea: false, type: 'number', min: 0, max: 60, defaultValue: 12 }),
                headingSetting('Vertical Padding (px)', 'menu_item_padding_y', { textarea: false, type: 'number', min: 0, max: 60, defaultValue: 9 }),
                headingSetting('Item Gap (px)', 'menu_item_gap', { textarea: false, type: 'number', min: 0, max: 60, defaultValue: 4 }),
            );
            tabContent.append(headingSection('Main Menu Items', mainItems));

            const submenuItems = document.createElement('div');
            submenuItems.append(
                headingSetting('Text Color', 'submenu_item_color', { textarea: false, type: 'color', defaultValue: '#303954' }),
                headingSetting('Hover Text Color', 'submenu_item_hover_color', { textarea: false, type: 'color', defaultValue: '#252f60' }),
                headingSetting('Active Text Color', 'submenu_item_active_color', { textarea: false, type: 'color', defaultValue: '#252f60' }),
                headingSetting('Background Color', 'submenu_item_background', { textarea: false, type: 'color', defaultValue: '#ffffff' }),
                headingSetting('Hover Background Color', 'submenu_item_hover_background', { textarea: false, type: 'color', defaultValue: '#f2f3f7' }),
                headingSetting('Active Background Color', 'submenu_item_active_background', { textarea: false, type: 'color', defaultValue: '#f2f3f7' }),
                headingSetting('Font Size (px)', 'submenu_item_font_size', { textarea: false, type: 'number', min: 8, max: 48, defaultValue: 13 }),
                headingSetting('Font Weight', 'submenu_item_font_weight', {
                    textarea: false,
                    select: [['400', 'Normal'], ['500', 'Medium'], ['600', 'Semi Bold'], ['700', 'Bold'], ['800', 'Extra Bold']],
                    defaultValue: '400',
                }),
                headingSetting('Horizontal Padding (px)', 'submenu_item_padding_x', { textarea: false, type: 'number', min: 0, max: 60, defaultValue: 12 }),
                headingSetting('Vertical Padding (px)', 'submenu_item_padding_y', { textarea: false, type: 'number', min: 0, max: 60, defaultValue: 9 }),
                headingSetting('Item Gap (px)', 'submenu_item_gap', { textarea: false, type: 'number', min: 0, max: 60, defaultValue: 0 }),
            );
            tabContent.append(headingSection('Dropdown Items', submenuItems));

            const dropdown = document.createElement('div');
            dropdown.append(
                headingSetting('Dropdown Background', 'submenu_background', { textarea: false, type: 'color', defaultValue: '#ffffff' }),
                headingSetting('Dropdown Minimum Width (px)', 'submenu_min_width', { textarea: false, type: 'number', min: 80, max: 600, defaultValue: 170 }),
                headingSetting('Dropdown Padding (px)', 'submenu_padding', { textarea: false, type: 'number', min: 0, max: 60, defaultValue: 5 }),
                headingSetting('Border Color', 'submenu_border_color', { textarea: false, type: 'color', defaultValue: '#e1e4ea' }),
                headingSetting('Border Width (px)', 'submenu_border_width', { textarea: false, type: 'number', min: 0, max: 20, defaultValue: 1 }),
                headingSetting('Border Radius (px)', 'submenu_border_radius', { textarea: false, type: 'number', min: 0, max: 100, defaultValue: 0 }),
                headingSetting('Box Shadow', 'submenu_shadow', {
                    textarea: false,
                    select: [['none', 'None'], ['soft', 'Soft'], ['strong', 'Strong']],
                    defaultValue: 'soft',
                }),
            );
            tabContent.append(headingSection('Dropdown Panel', dropdown));
        }

        async function uploadImage(file, targetBlock, uploadButton) {
            if (!['image/jpeg', 'image/png', 'image/webp', 'image/gif'].includes(file.type) || file.size > 5 * 1024 * 1024) {
                showToast(file.size > 5 * 1024 * 1024 ? 'Ukuran gambar maksimal 5 MB.' : 'Format gambar yang didukung: JPG, PNG, WebP, atau GIF.');
                return;
            }
            uploadButton.disabled = true;
            uploadButton.textContent = 'Mengunggah...';
            const formData = new FormData();
            formData.append('image', file);
            try {
                const response = await fetch(imageUploadUrl, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: formData,
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.errors?.image?.[0] || result.message || 'Gambar gagal diunggah.');
                if (!blocks.includes(targetBlock)) {
                    showToast('Gambar sudah diunggah, tetapi komponen terkait tidak lagi tersedia.');
                    return;
                }
                targetBlock.url = result.url;
                showToast('Gambar berhasil diunggah.');
                if (blocks[selectedIndex] === targetBlock) showSettings();
                renderPreview();
            } catch (error) {
                showToast(error instanceof Error ? error.message : 'Gambar gagal diunggah. Periksa koneksi lalu coba lagi.');
            } finally {
                uploadButton.disabled = false;
                uploadButton.textContent = 'Choose Image';
            }
        }

        function renderImageContent(tabContent) {
            const block = blocks[selectedIndex];
            block.settings ||= {};
            const content = document.createElement('div');
            const imagePicker = document.createElement('div');
            imagePicker.className = 'image-picker';
            const pickerHeading = document.createElement('div');
            pickerHeading.className = 'image-picker-heading';
            pickerHeading.append(textNode('span', 'Choose Image'));
            const sparkle = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            sparkle.setAttribute('viewBox', '0 0 24 24');
            sparkle.setAttribute('fill', 'none');
            sparkle.setAttribute('stroke', 'currentColor');
            sparkle.setAttribute('stroke-width', '1.8');
            sparkle.setAttribute('stroke-linecap', 'round');
            sparkle.setAttribute('stroke-linejoin', 'round');
            sparkle.classList.add('image-picker-sparkle');
            sparkle.setAttribute('aria-hidden', 'true');
            sparkle.innerHTML = '<path d="m12 3 1.7 5.3L19 10l-5.3 1.7L12 17l-1.7-5.3L5 10l5.3-1.7L12 3Z"/><path d="m19 15 .9 2.1L22 18l-2.1.9L19 21l-.9-2.1L16 18l2.1-.9L19 15Z"/>';
            pickerHeading.append(sparkle);

            const pickerCard = document.createElement('div');
            pickerCard.className = 'image-picker-card';
            const previewFrame = document.createElement('div');
            previewFrame.className = 'image-picker-preview-frame';
            const placeholder = document.createElement('svg');
            placeholder.classList.add('image-picker-placeholder');
            placeholder.setAttribute('viewBox', '0 0 360 113');
            placeholder.setAttribute('preserveAspectRatio', 'xMidYMid slice');
            placeholder.setAttribute('aria-hidden', 'true');
            placeholder.innerHTML = '<rect width="360" height="113" fill="#a0a7ad"/><circle cx="105" cy="64" r="15" fill="#c6c8ca"/><path d="M0 113 0 99Q28 66 58 96l18 17Zm132 0 54-74q30-40 66-8t108 82Z" fill="#c6c8ca"/>';
            previewFrame.append(placeholder);
            if (block.url) {
                const preview = document.createElement('img');
                preview.className = 'image-picker-preview';
                preview.src = block.url;
                preview.alt = block.settings.alt_text || block.text || 'Pratinjau gambar';
                preview.addEventListener('error', () => {
                    preview.remove();
                    placeholder.hidden = false;
                }, { once: true });
                placeholder.hidden = true;
                previewFrame.append(preview);
            }

            const actions = document.createElement('div');
            actions.className = 'image-picker-actions';
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = 'image/jpeg,image/png,image/webp,image/gif';
            fileInput.hidden = true;
            const chooseButton = document.createElement('button');
            chooseButton.type = 'button';
            chooseButton.textContent = 'Choose Image';
            chooseButton.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', () => {
                if (fileInput.files?.[0]) uploadImage(fileInput.files[0], block, chooseButton);
            });
            const libraryButton = document.createElement('button');
            libraryButton.type = 'button';
            libraryButton.className = 'image-picker-library';
            libraryButton.setAttribute('aria-label', 'Pilih gambar dari perangkat');
            libraryButton.title = 'Pilih gambar dari perangkat';
            libraryButton.innerHTML = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 8v3c0 1.7 3.6 3 8 3s8-1.3 8-3V8c-1.6 1.4-4.8 2-8 2S5.6 9.4 4 8Zm0 6v3c0 1.7 3.6 3 8 3s8-1.3 8-3v-3c-1.6 1.4-4.8 2-8 2s-6.4-.6-8-2Z"/></svg>';
            libraryButton.addEventListener('click', () => fileInput.click());
            actions.append(chooseButton, libraryButton, fileInput);
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'image-picker-remove';
            removeButton.disabled = !block.url;
            removeButton.setAttribute('aria-label', 'Hapus gambar');
            removeButton.title = 'Hapus gambar';
            removeButton.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M10 11v6m4-6v6M5 7l1 14h12l1-14M9 7V4h6v3"/></svg>';
            removeButton.addEventListener('click', () => {
                block.url = '';
                showSettings();
                renderPreview();
            });
            previewFrame.append(removeButton);
            pickerCard.append(previewFrame, actions);
            imagePicker.append(pickerHeading, pickerCard);

            const imageUrl = settingField('Image URL', 'url', {
                textarea: false,
                type: 'url',
                help: 'Opsional; gunakan URL http:// atau https:// jika gambar tidak diunggah dari perangkat.',
            });
            imageUrl.classList.add('image-picker-url');
            imageUrl.querySelector('input').addEventListener('change', showSettings);
            imagePicker.append(imageUrl);
            content.append(imagePicker);

            content.append(headingSetting('Image Resolution', 'image_resolution', {
                textarea: false,
                select: [['large', 'Large - 1024 px'], ['medium', 'Medium - 300 px'], ['thumbnail', 'Thumbnail - 150 px'], ['full', 'Full']],
                defaultValue: 'large',
            }));
            const resolution = content.querySelector('select');
            resolution.addEventListener('change', () => {
                showSettings();
                renderPreview();
            });

            const caption = headingSetting('Caption', 'caption_type', {
                textarea: false,
                select: [['none', 'None'], ['custom', 'Custom']],
                defaultValue: 'none',
            });
            caption.querySelector('select').addEventListener('change', showSettings);
            content.append(caption);
            if (block.settings.caption_type === 'custom') {
                content.append(headingSetting('Caption Text', 'caption', { help: 'Teks yang tampil di bawah gambar.' }));
            }

            const link = headingSetting('Link', 'link_type', {
                textarea: false,
                select: [['none', 'None'], ['custom', 'Custom URL'], ['media', 'Media File']],
                defaultValue: 'none',
            });
            link.querySelector('select').addEventListener('change', showSettings);
            content.append(link);
            if (block.settings.link_type === 'custom') {
                content.append(headingSetting('Link URL', 'link_url', { textarea: false, type: 'url' }));
            }
            content.append(headingSetting('Alt Text', 'alt_text', { help: 'Deskripsikan gambar untuk aksesibilitas.' }));
            tabContent.append(headingSection('Image', content));
        }

        function imageSlider(label, key, options = {}) {
            const wrapper = document.createElement('div');
            wrapper.className = 'image-control-slider';
            const labelElement = document.createElement('label');
            labelElement.textContent = label;
            const range = document.createElement('input');
            range.type = 'range';
            range.min = String(options.min ?? 0);
            range.max = String(options.max ?? 100);
            const number = document.createElement('input');
            number.type = 'number';
            number.min = range.min;
            number.max = range.max;
            const settings = blocks[selectedIndex].settings || {};
            const value = settings[key] ?? options.defaultValue ?? '';
            range.value = value === '' ? String(options.min ?? 0) : String(value);
            number.value = value;
            const update = (nextValue) => {
                blocks[selectedIndex].settings ||= {};
                blocks[selectedIndex].settings[key] = String(Math.max(Number(range.min), Math.min(Number(range.max), Number.parseInt(nextValue, 10) || 0)));
                number.value = blocks[selectedIndex].settings[key];
                range.value = blocks[selectedIndex].settings[key];
                renderPreview();
            };
            range.addEventListener('input', () => update(range.value));
            number.addEventListener('input', () => update(number.value));
            wrapper.append(labelElement, range, number);
            return wrapper;
        }

        async function uploadVideo(file, targetBlock, uploadButton) {
            const maxBytes = 50 * 1024 * 1024;
            if (!['video/mp4', 'video/webm', 'video/ogg'].includes(file.type) || file.size > maxBytes) {
                showToast(file.size > maxBytes ? 'Ukuran video maksimal 50 MB.' : 'Format video yang didukung: MP4, WebM, atau OGG.');
                return;
            }
            backgroundUploadInProgress = true;
            uploadButton.disabled = true;
            uploadButton.textContent = 'Mengunggah...';
            const formData = new FormData();
            formData.append('video', file);
            try {
                const response = await fetch(videoUploadUrl, {
                    method: 'POST',
                    headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: formData,
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.errors?.video?.[0] || result.message || 'Video gagal diunggah.');
                if (!blocks.includes(targetBlock)) {
                    showToast('Video sudah diunggah, tetapi komponen terkait tidak lagi tersedia.');
                    return;
                }
                targetBlock.url = result.url;
                targetBlock.settings ||= {};
                targetBlock.settings.video_source = 'self_hosted';
                showToast('Video berhasil diunggah.');
                if (blocks[selectedIndex] === targetBlock) showSettings();
                renderPreview();
            } catch (error) {
                showToast(error instanceof Error ? error.message : 'Video gagal diunggah. Periksa koneksi lalu coba lagi.');
            } finally {
                backgroundUploadInProgress = false;
                uploadButton.disabled = false;
                uploadButton.textContent = targetBlock.url ? 'Ganti Video' : 'Pilih Video';
            }
        }

        function renderVideoContent(tabContent) {
            const block = blocks[selectedIndex];
            block.settings ||= {};
            const sourceField = headingSetting('Source', 'video_source', {
                textarea: false,
                select: [
                    ['youtube', 'YouTube'],
                    ['vimeo', 'Vimeo'],
                    ['dailymotion', 'Dailymotion'],
                    ['videopress', 'VideoPress'],
                    ['self_hosted', 'Self Hosted'],
                ],
                defaultValue: 'self_hosted',
            });
            sourceField.querySelector('select').addEventListener('change', () => {
                showSettings();
                renderPreview();
            });
            const content = document.createElement('div');
            content.append(sourceField);
            const source = block.settings.video_source || 'self_hosted';
            const urlLabel = source === 'self_hosted' ? 'External URL' : `${componentNames[source] || source} URL`;
            const urlField = settingField(urlLabel, 'url', {
                textarea: false,
                type: 'url',
                help: source === 'self_hosted'
                    ? 'Opsional. Masukkan URL langsung MP4, WebM, atau OGG; atau unggah video dari perangkat.'
                    : `Masukkan URL video ${componentNames[source] || source}.`,
            });
            urlField.querySelector('input').addEventListener('change', () => {
                showSettings();
                renderPreview();
            });
            content.append(urlField);

            if (source === 'self_hosted') {
                const upload = document.createElement('div');
                upload.className = 'video-upload';
                upload.append(textNode('div', 'Choose Video File', 'setting-label'));
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.accept = 'video/mp4,video/webm,video/ogg';
                fileInput.hidden = true;
                const preview = document.createElement('div');
                preview.className = 'video-upload-preview';
                if (block.url && /^\/storage\/page-videos\/[A-Za-z0-9._-]+\.(mp4|webm|ogg)$/i.test(block.url)) {
                    const video = document.createElement('video');
                    video.src = block.url;
                    video.preload = 'metadata';
                    video.controls = true;
                    preview.append(video);
                }
                const actions = document.createElement('div');
                actions.className = 'video-upload-actions';
                const choose = document.createElement('button');
                choose.type = 'button';
                choose.textContent = block.url ? 'Ganti Video' : 'Pilih Video';
                choose.addEventListener('click', () => fileInput.click());
                fileInput.addEventListener('change', () => {
                    if (fileInput.files?.[0]) uploadVideo(fileInput.files[0], block, choose);
                });
                actions.append(choose);
                if (block.url && /^\/storage\/page-videos\//i.test(block.url)) {
                    const remove = document.createElement('button');
                    remove.type = 'button';
                    remove.textContent = 'Hapus Video';
                    remove.addEventListener('click', () => {
                        block.url = '';
                        showSettings();
                        renderPreview();
                    });
                    actions.append(remove);
                }
                upload.append(fileInput, preview, actions);
                content.append(upload);
            }

            content.append(settingField('Video Title', 'text', {
                textarea: false,
                help: 'Opsional; digunakan sebagai judul aksesibilitas pada video.',
            }));
            tabContent.append(headingSection('Video', content));
        }

        function imageDimension(label, key, unitKey, units, defaultUnit) {
            const wrapper = document.createElement('div');
            wrapper.className = 'image-control-slider';
            const labelElement = document.createElement('label');
            labelElement.textContent = label;
            const range = document.createElement('input');
            range.type = 'range';
            range.min = '0';
            range.max = '1920';
            const number = document.createElement('input');
            number.type = 'number';
            number.min = '0';
            number.max = '1920';
            const unit = document.createElement('select');
            unit.setAttribute('aria-label', `${label} unit`);
            units.forEach((value) => {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = value;
                unit.append(option);
            });
            const settings = blocks[selectedIndex].settings || {};
            number.value = settings[key] ?? '';
            range.value = settings[key] || '0';
            unit.value = settings[unitKey] || defaultUnit;
            const save = () => {
                blocks[selectedIndex].settings ||= {};
                blocks[selectedIndex].settings[key] = number.value;
                blocks[selectedIndex].settings[unitKey] = unit.value;
                renderPreview();
            };
            range.addEventListener('input', () => {
                number.value = range.value;
                save();
            });
            number.addEventListener('input', () => {
                range.value = number.value || '0';
                save();
            });
            unit.addEventListener('change', save);
            wrapper.append(labelElement, range, number, unit);
            return wrapper;
        }

        function renderImageStyle(tabContent) {
            const settings = blocks[selectedIndex].settings || {};
            const align = document.createElement('div');
            align.className = 'image-control-row';
            const label = document.createElement('label');
            label.textContent = 'Alignment';
            const buttons = document.createElement('div');
            buttons.className = 'image-alignment';
            [
                ['left', '<path d="M4 6h16M4 10h10M4 14h16M4 18h10" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>'],
                ['center', '<path d="M4 6h16M7 10h10M4 14h16M7 18h10" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>'],
                ['right', '<path d="M4 6h16M10 10h10M4 14h16M10 18h10" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>'],
            ].forEach(([value, path]) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.setAttribute('aria-label', `Rata ${value}`);
                button.setAttribute('aria-pressed', String((settings.image_alignment || 'center') === value));
                button.innerHTML = `<svg viewBox="0 0 24 24" fill="none" aria-hidden="true">${path}</svg>`;
                button.addEventListener('click', () => {
                    blocks[selectedIndex].settings ||= {};
                    blocks[selectedIndex].settings.image_alignment = value;
                    showSettings();
                    renderPreview();
                });
                buttons.append(button);
            });
            align.append(label, buttons);
            tabContent.append(headingSection('Image', align));
            tabContent.append(imageDimension('Width', 'image_width', 'image_width_unit', ['px', '%', 'vw'], 'px'));
            tabContent.append(imageDimension('Max Width', 'image_max_width', 'image_max_width_unit', ['px', '%', 'vw'], '%'));
            tabContent.append(imageDimension('Height', 'image_height', 'image_height_unit', ['px', '%', 'vh'], 'px'));
            const state = headingAdvancedState;
            tabContent.append(headingStateToggle(state, (value) => {
                headingAdvancedState = value;
                showSettings();
            }));
            tabContent.append(imageSlider('Opacity', state === 'normal' ? 'opacity' : 'hover_opacity', { min: 0, max: 100, defaultValue: 100 }));
            tabContent.append(imageSlider('Border Radius', 'image_border_radius', { min: 0, max: 100, defaultValue: 0 }));
        }

        function videoFilterControl(label, key, min, max, defaultValue) {
            const wrapper = document.createElement('div');
            wrapper.className = 'video-filter-control';
            const id = `video-filter-${key}`;
            const labelElement = document.createElement('label');
            labelElement.htmlFor = id;
            labelElement.textContent = label;
            const range = document.createElement('input');
            range.type = 'range';
            range.min = String(min);
            range.max = String(max);
            const number = document.createElement('input');
            number.type = 'number';
            number.id = id;
            number.min = String(min);
            number.max = String(max);
            const settings = blocks[selectedIndex].settings || {};
            const initial = Number(settings[key] ?? defaultValue);
            range.value = String(initial);
            number.value = String(initial);
            const update = (value) => {
                const numericValue = Math.max(min, Math.min(max, Number.parseInt(value, 10) || 0));
                blocks[selectedIndex].settings ||= {};
                blocks[selectedIndex].settings[key] = String(numericValue);
                range.value = String(numericValue);
                number.value = String(numericValue);
                renderPreview();
            };
            range.addEventListener('input', () => update(range.value));
            number.addEventListener('input', () => update(number.value));
            wrapper.append(labelElement, range, number);
            return wrapper;
        }

        function renderVideoStyle(tabContent) {
            const fields = document.createElement('div');
            fields.append(headingSetting('Aspect Ratio', 'video_aspect_ratio', {
                textarea: false,
                select: [['1:1', '1:1'], ['3:2', '3:2'], ['4:3', '4:3'], ['16:9', '16:9'], ['21:9', '21:9'], ['9:16', '9:16']],
                defaultValue: '16:9',
            }));
            fields.querySelector('select').addEventListener('change', renderPreview);
            tabContent.append(headingSection('Video', fields));
            const filters = document.createElement('div');
            filters.append(
                videoFilterControl('Brightness (%)', 'video_filter_brightness', 0, 200, 100),
                videoFilterControl('Contrast (%)', 'video_filter_contrast', 0, 200, 100),
                videoFilterControl('Saturation (%)', 'video_filter_saturation', 0, 200, 100),
                videoFilterControl('Hue (degrees)', 'video_filter_hue', 0, 360, 0),
                videoFilterControl('Blur (px)', 'video_filter_blur', 0, 20, 0),
            );
            tabContent.append(headingSection('CSS Filters', filters));
        }

        function renderContainerSettings(form, block) {
            const fields = document.createElement('div');
            fields.append(headingSetting('Struktur', 'container_layout', {
                textarea: false,
                select: containerPresets.map(({ key, label }) => [key, label]),
                defaultValue: 'row',
            }));
            fields.querySelector('select').addEventListener('change', () => {
                const selectedPreset = containerPresets.find((preset) => preset.key === block.settings.container_layout) || containerPresets[1];
                const slots = getContainerSlots(selectedPreset).length;
                block.settings.container_slots = slots;
                blocks.forEach((child) => {
                    if (child.settings?.container_id === block.settings.container_id) {
                        child.settings.container_column = String(Math.min(slots - 1, Number(child.settings.container_column) || 0));
                    }
                });
                renderAll();
            });
            fields.append(headingSetting('Jarak antar kolom/baris (px)', 'container_gap', {
                textarea: false,
                type: 'number',
                min: 0,
                max: 60,
                defaultValue: 12,
            }));
            fields.querySelector('input[type="number"]').addEventListener('input', renderPreview);
            form.append(headingSection('Container', fields));
        }

        function renderHeadingSettings(form, componentType = 'heading') {
            const tabs = document.createElement('div');
            tabs.className = 'heading-tabs';
            tabs.setAttribute('role', 'tablist');
            const tabContent = document.createElement('div');
            tabContent.className = 'heading-tab-content';
            tabContent.setAttribute('role', 'tabpanel');
            const tabOptions = [
                ['content', 'Content'],
                ['style', 'Style'],
                ['advanced', 'Advanced'],
            ];
            tabOptions.forEach(([tab, label]) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'heading-tab';
                button.textContent = label;
                button.setAttribute('role', 'tab');
                button.setAttribute('aria-selected', String(activeComponentTab === tab));
                button.addEventListener('click', () => {
                    activeComponentTab = tab;
                    showSettings();
                });
                tabs.append(button);
            });
            form.append(tabs);

            if (activeComponentTab === 'content') {
                if (componentType === 'text') {
                    renderTextEditorContent(tabContent);
                } else if (componentType === 'image') {
                    renderImageContent(tabContent);
                } else if (componentType === 'video') {
                    renderVideoContent(tabContent);
                } else if (componentType === 'menu') {
                    const menuFields = document.createElement('div');
                    const menuSelect = headingSetting('Pilih Menu', 'menu_id', {
                        textarea: false,
                        select: menuOptions.map((menu) => [String(menu.id), menu.name]),
                        defaultValue: menuOptions[0] ? String(menuOptions[0].id) : '',
                        help: menuOptions.length ? 'Pilih menu yang telah dibuat di dashboard admin.' : 'Belum ada menu. Buat menu terlebih dahulu di dashboard admin.',
                    });
                    menuSelect.querySelector('select').addEventListener('change', renderPreview);
                    menuFields.append(menuSelect);
                    tabContent.append(headingSection('Menu', menuFields));
                } else {
                const contentFields = document.createElement('div');
                contentFields.append(settingField('Title', 'text', { help: 'Gunakan judul yang ringkas dan mudah dipahami.' }));
                contentFields.append(headingSetting('Link', 'link', { textarea: false, type: 'url', help: 'Opsional. Masukkan URL lengkap yang diawali http:// atau https://.' }));
                contentFields.append(headingSetting('HTML Tag', 'html_tag', {
                    textarea: false,
                    select: [['h1', 'H1'], ['h2', 'H2'], ['h3', 'H3'], ['h4', 'H4'], ['h5', 'H5'], ['h6', 'H6'], ['p', 'P'], ['div', 'DIV'], ['span', 'SPAN']],
                    defaultValue: `h${[2, 3, 4].includes(Number(blocks[selectedIndex].level)) ? Number(blocks[selectedIndex].level) : 2}`,
                }));
                tabContent.append(headingSection('Heading', contentFields));
                }
            } else if (activeComponentTab === 'style') {
                if (componentType === 'image') {
                    renderImageStyle(tabContent);
                } else if (componentType === 'video') {
                    renderVideoStyle(tabContent);
                } else if (componentType === 'menu') {
                    renderMenuStyle(tabContent);
                } else {
                    const styleFields = document.createElement('div');
                    styleFields.append(headingSetting('Alignment', 'alignment', {
                        textarea: false,
                        select: [['left', 'Left'], ['center', 'Center'], ['right', 'Right'], ['justify', 'Justify']],
                        defaultValue: 'left',
                    }));
                    styleFields.append(headingSetting('Font Size (px)', 'font_size', { textarea: false, type: 'number', help: '12–72 px.' }));
                    styleFields.append(headingSetting('Font Weight', 'font_weight', {
                        textarea: false,
                        select: [['400', 'Normal'], ['500', 'Medium'], ['600', 'Semi Bold'], ['700', 'Bold'], ['800', 'Extra Bold']],
                        defaultValue: '700',
                    }));
                    styleFields.append(headingSetting('Text Color', 'text_color', { textarea: false, type: 'color', defaultValue: '#252f60' }));
                    styleFields.append(headingSetting('Text Stroke (px)', 'text_stroke_width', { textarea: false, type: 'number', help: '0–3 px.' }));
                    styleFields.append(headingSetting('Stroke Color', 'text_stroke_color', { textarea: false, type: 'color', defaultValue: '#252f60' }));
                    styleFields.append(headingSetting('Text Shadow', 'text_shadow', {
                        textarea: false,
                        select: [['none', 'None'], ['soft', 'Soft'], ['strong', 'Strong']],
                        defaultValue: 'none',
                    }));
                    tabContent.append(headingSection(componentType === 'menu' ? 'Menu' : 'Heading', styleFields));
                }
            } else {
                renderHeadingAdvancedTab(tabContent);
            }
            form.append(tabContent);
        }

        function showSettings() {
            const hasSelectedBlock = selectedIndex >= 0 && Boolean(blocks[selectedIndex]);
            editorBody.classList.toggle('show-settings', hasSelectedBlock);
            if (selectedIndex < 0 || !blocks[selectedIndex]) {
                pageLayoutSettings.replaceChildren(renderPageLayoutSettings());
                return;
            }

            settingsContent.replaceChildren();
            const block = blocks[selectedIndex];
            const name = componentNames[block.type] || 'Komponen';
            document.getElementById('settingsPanelTitle').textContent = `Pengaturan ${name}`;
            document.getElementById('selectionLabel').textContent = name;
            const form = document.createElement('div');
            form.className = 'settings-form';
            const selectedType = document.createElement('div');
            selectedType.className = 'selected-type';
            const selectedCopy = document.createElement('span');
            selectedCopy.append(textNode('strong', name));
            selectedType.append(selectedCopy);
            form.append(selectedType);

            const orderControls = document.createElement('div');
            orderControls.className = 'component-order';
            [
                { label: 'Naikkan', offset: -1 },
                { label: 'Turunkan', offset: 1 },
            ].forEach(({ label, offset }) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = label;
                button.disabled = selectedIndex + offset < 0 || selectedIndex + offset >= blocks.length;
                button.addEventListener('click', () => {
                    const targetIndex = selectedIndex + offset;
                    [blocks[selectedIndex], blocks[targetIndex]] = [blocks[targetIndex], blocks[selectedIndex]];
                    selectedIndex = targetIndex;
                    renderAll();
                });
                orderControls.append(button);
            });
            form.append(orderControls);

            if (block.type === 'heading') {
                renderHeadingSettings(form);
            } else if (block.type === 'text') {
                renderHeadingSettings(form, 'text');
            } else if (block.type === 'image') {
                renderHeadingSettings(form, 'image');
            } else if (block.type === 'video') {
                renderHeadingSettings(form, 'video');
            } else if (block.type === 'menu') {
                renderHeadingSettings(form, 'menu');
            } else if (block.type === 'container') {
                renderContainerSettings(form, block);
            } else if (block.type === 'button') {
                form.append(settingField('Teks tombol', 'text', { textarea: false }));
                form.append(settingField('URL tujuan', 'url', { textarea: false, type: 'url', help: 'Gunakan URL lengkap, misalnya https://layanan.go.id.' }));
            } else if (block.type === 'spacer') {
                form.append(settingField('Tinggi spacer (px)', 'height', { textarea: false, type: 'number', help: 'Masukkan nilai antara 16 dan 400 px.' }));
            } else if (block.type === 'map') {
                form.append(settingField('Alamat atau lokasi', 'text', { help: 'Lokasi ditautkan ke pencarian Google Maps.' }));
            } else if (block.type !== 'divider') {
                form.append(settingField(block.type === 'list' ? 'Daftar (satu butir per baris)' : 'Konten teks', 'text', {
                    help: block.type === 'list' ? 'Setiap baris akan ditampilkan sebagai satu butir.' : 'Konten teks biasa; tag HTML tidak diproses.',
                }));
            }

            if (block.type !== 'heading' && block.type !== 'text' && block.type !== 'image' && block.type !== 'video' && block.type !== 'menu') {
                renderAdvancedOptions().forEach((section) => form.append(section));
            }

            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'delete-selected';
            remove.textContent = 'Hapus komponen';
            remove.addEventListener('click', () => {
                if (block.type === 'container') {
                    const containerId = block.settings?.container_id;
                    for (let index = blocks.length - 1; index >= 0; index -= 1) {
                        if (blocks[index] === block || blocks[index].settings?.container_id === containerId) blocks.splice(index, 1);
                    }
                } else {
                    blocks.splice(selectedIndex, 1);
                }
                selectedIndex = blocks.length ? Math.min(selectedIndex, blocks.length - 1) : -1;
                renderAll();
            });
            form.append(remove);
            settingsContent.append(form);
        }

        function renderAll() {
            renderPreview();
            showSettings();
            renderStructureTree();
        }

        function getStructureSubtree(block) {
            if (block?.type !== 'container') return [block];
            const ids = new Set([block.settings?.container_id]);
            let changed = true;
            while (changed) {
                changed = false;
                blocks.forEach((candidate) => {
                    if (candidate.type !== 'container'
                        || !ids.has(candidate.settings?.parent_container_id)
                        || ids.has(candidate.settings?.container_id)) return;
                    ids.add(candidate.settings.container_id);
                    changed = true;
                });
            }
            return blocks.filter((candidate) => candidate === block
                || (candidate.type === 'container'
                    ? ids.has(candidate.settings?.container_id)
                    : ids.has(candidate.settings?.container_id)));
        }

        function setStructureParent(block, parentId, column) {
            block.settings ||= {};
            if (block.type === 'container') {
                if (parentId) block.settings.parent_container_id = parentId;
                else delete block.settings.parent_container_id;
            } else if (parentId) {
                block.settings.container_id = parentId;
            } else {
                delete block.settings.container_id;
            }
            if (parentId) block.settings.container_column = String(column ?? 0);
            else delete block.settings.container_column;
        }

        function moveStructureBlock(block, targetBlock = null, placement = 'root') {
            if (!blocks.includes(block)) return false;
            const movingBlocks = getStructureSubtree(block);
            const movingSet = new Set(movingBlocks);
            const movingContainerIds = new Set(movingBlocks
                .filter((item) => item.type === 'container')
                .map((item) => item.settings?.container_id));

            if (targetBlock && movingSet.has(targetBlock)) return false;
            if (placement === 'inside'
                && (targetBlock?.type !== 'container'
                    || movingContainerIds.has(targetBlock.settings?.container_id))) return false;

            let parentId = null;
            let column = 0;
            if (placement === 'inside') {
                parentId = targetBlock.settings.container_id;
            } else if (targetBlock) {
                parentId = targetBlock.type === 'container'
                    ? targetBlock.settings?.parent_container_id
                    : targetBlock.settings?.container_id;
                column = targetBlock.settings?.container_column ?? 0;
            }

            const remaining = blocks.filter((item) => !movingSet.has(item));
            let insertionIndex = remaining.length;
            if (targetBlock && placement !== 'inside') {
                const targetSubtree = getStructureSubtree(targetBlock).filter((item) => !movingSet.has(item));
                const targetEnd = Math.max(...targetSubtree.map((item) => remaining.indexOf(item)));
                insertionIndex = placement === 'after' ? targetEnd + 1 : remaining.indexOf(targetBlock);
            }

            if (placement === 'inside') {
                insertionIndex = remaining.length;
            }
            if (!parentId && block.type !== 'container' && remaining.length + 2 > maximumPageBlocks) {
                return false;
            }
            blocks.splice(0, blocks.length, ...remaining);
            if (!parentId && block.type !== 'container') {
                const wrapper = createContainerBlock('column');
                setStructureParent(block, wrapper.settings.container_id, 0);
                blocks.splice(insertionIndex, 0, wrapper, block);
            } else {
                setStructureParent(block, parentId, column);
                blocks.splice(insertionIndex, 0, ...movingBlocks);
            }
            selectedIndex = blocks.indexOf(block);
            if (parentId) {
                expandedStructureContainers.add(parentId);
            }
            return true;
        }

        function renderStructureTree() {
            structureTree.replaceChildren();
            if (!blocks.length) {
                structureTree.append(textNode('div', 'Belum ada container atau widget.', 'structure-tree-empty'));
                return;
            }

            const containerById = new Map();
            const childrenByContainer = new Map();
            blocks.forEach((block, index) => {
                if (block.type === 'container') {
                    containerById.set(block.settings?.container_id, { block, index });
                }
            });
            if (!structureTreeInitialized) {
                containerById.forEach((_, containerId) => expandedStructureContainers.add(containerId));
                structureTreeInitialized = true;
            }
            blocks.forEach((block, index) => {
                const containerId = block.type === 'container'
                    ? block.settings?.parent_container_id
                    : block.settings?.container_id;
                if (!containerById.has(containerId)) return;
                const children = childrenByContainer.get(containerId) || [];
                children.push({ block, index });
                childrenByContainer.set(containerId, children);
            });

            const rendered = new Set();
            const appendNode = (block, index, depth) => {
                if (rendered.has(index)) return;
                rendered.add(index);
                const isContainer = block.type === 'container';
                const children = isContainer ? (childrenByContainer.get(block.settings?.container_id) || []) : [];
                const row = document.createElement('div');
                row.className = `structure-tree-row${selectedIndex === index ? ' selected' : ''}`;
                row.setAttribute('role', 'treeitem');
                row.setAttribute('aria-level', String(depth + 1));
                row.dataset.blockIndex = String(index);
                row.draggable = true;
                row.style.paddingLeft = `${7 + depth * 22}px`;
                row.addEventListener('dragstart', (event) => {
                    structureDraggedBlock = block;
                    draggedBlockIndex = index;
                    event.dataTransfer.effectAllowed = 'move';
                    event.dataTransfer.setData('text/plain', `block:${index}`);
                    row.classList.add('dragging');
                });
                row.addEventListener('dragend', () => {
                    structureDraggedBlock = null;
                    draggedBlockIndex = null;
                    row.classList.remove('dragging');
                    clearStructureDropIndicator();
                    clearDropIndicator();
                });

                if (children.length) {
                    const toggle = document.createElement('button');
                    toggle.type = 'button';
                    toggle.className = 'structure-tree-toggle';
                    const containerId = block.settings?.container_id;
                    toggle.setAttribute('aria-label', `${expandedStructureContainers.has(containerId) ? 'Ciutkan' : 'Buka'} ${componentNames.container}`);
                    toggle.setAttribute('aria-expanded', String(expandedStructureContainers.has(containerId)));
                    toggle.innerHTML = '<svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="m4 6 4 4 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                    toggle.addEventListener('click', () => {
                        if (expandedStructureContainers.has(containerId)) expandedStructureContainers.delete(containerId);
                        else expandedStructureContainers.add(containerId);
                        renderStructureTree();
                    });
                    row.append(toggle);
                } else {
                    const spacer = document.createElement('span');
                    spacer.className = 'structure-tree-spacer';
                    row.append(spacer);
                }

                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'structure-tree-item';
                item.setAttribute('role', 'treeitem');
                item.setAttribute('aria-selected', String(selectedIndex === index));
                const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                icon.setAttribute('viewBox', '0 0 36 36');
                icon.setAttribute('fill', 'none');
                icon.classList.add('structure-tree-icon');
                icon.innerHTML = structureIconMarkup(block.type);
                const label = textNode('span', block.settings?.structure_label || componentNames[block.type] || 'Widget');
                item.append(icon, label);
                item.addEventListener('click', () => selectBlock(index));
                row.append(item);

                const actions = document.createElement('span');
                actions.className = 'structure-tree-actions';
                actions.addEventListener('click', (event) => event.stopPropagation());

                const rename = document.createElement('button');
                rename.type = 'button';
                rename.className = 'structure-tree-action';
                rename.setAttribute('aria-label', `Ubah nama ${label.textContent}`);
                rename.title = 'Ubah nama';
                rename.innerHTML = '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m14 5 5 5M4 20l4.5-1 10-10a2.12 2.12 0 0 0-3-3l-10 10L4 20Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                rename.addEventListener('click', () => beginStructureRename(block, row, label));
                actions.append(rename);

                const remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'structure-tree-action delete';
                remove.setAttribute('aria-label', `Hapus ${label.textContent}`);
                remove.title = 'Hapus';
                remove.innerHTML = '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M10 11v6m4-6v6M6 7l1 13h10l1-13M9 7V4h6v3" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                remove.addEventListener('click', () => deleteStructureBlock(block));
                actions.append(remove);
                row.append(actions);
                structureTree.append(row);

                if (isContainer && expandedStructureContainers.has(block.settings?.container_id)) {
                    children.forEach(({ block: child, index: childIndex }) => appendNode(child, childIndex, depth + 1));
                }
            };

            blocks.forEach((block, index) => {
                if (block.type === 'container') {
                    if (!containerById.has(block.settings?.parent_container_id)) appendNode(block, index, 0);
                } else if (!containerById.has(block.settings?.container_id)) {
                    appendNode(block, index, 0);
                }
            });
        }

        function structureIconMarkup(type) {
            const icons = {
                container: '<rect x="4" y="6" width="28" height="24" rx="2"/><path d="M18 6v24M4 18h14m0-12v12m0 0h14"/>',
                heading: '<path d="M6 8h24v4M18 8v22m-5 0h10"/>',
                image: '<rect x="4" y="7" width="28" height="22"/><circle cx="12" cy="14" r="2.5"/><path d="m6 27 9-9 6 6 4-4 7 8"/>',
                text: '<path d="M5 9h26M5 14h26M5 19h21M5 24h26M5 29h18"/>',
                paragraph: '<path d="M5 9h26M5 14h26M5 19h21M5 24h26M5 29h18"/>',
                video: '<rect x="4" y="8" width="28" height="20" rx="5"/><path d="m15 13 8 5-8 5V13Z"/>',
                menu: '<path d="M5 9h26M5 18h11m5 0h10M5 27h26"/><circle cx="18" cy="18" r="2"/>',
                button: '<rect x="5" y="9" width="26" height="18" rx="3"/><path d="M15 14 24 18l-9 4v-8Zm1 8 4 7"/>',
                divider: '<path d="M5 18h26M14 10l4-5 4 5m-8 16 4 5 4-5"/>',
                spacer: '<path d="M5 9h26M5 27h26M18 12v12m0-12-4 5m4-5 4 5m-4 7-4-5m4 5 4-5"/>',
                map: '<path d="m5 11 8-5 10 4 8-5v24l-8 5-10-4-8 5V11Z"/><path d="m13 6v24m10-20v24"/><path d="M23 12c0-3 2-5 5-5s5 2 5 5c0 4-5 9-5 9s-5-5-5-9Z"/><circle cx="28" cy="12" r="1.5"/>',
                quote: '<path d="M7 8h8v8H9l-2 4v-4a8 8 0 0 1 0-8Zm12 0h8v8h-6l-2 4v-4a8 8 0 0 1 0-8Z"/>',
                list: '<path d="M12 9h19M12 18h19M12 27h19"/><circle cx="6" cy="9" r="1.5"/><circle cx="6" cy="18" r="1.5"/><circle cx="6" cy="27" r="1.5"/>',
            };
            return icons[type] || icons.text;
        }

        function beginStructureRename(block, row, label) {
            const input = document.createElement('input');
            input.className = 'structure-tree-rename';
            input.type = 'text';
            input.maxLength = 80;
            input.value = block.settings?.structure_label || componentNames[block.type] || 'Widget';
            input.setAttribute('aria-label', 'Nama widget atau container');
            label.replaceWith(input);
            input.focus();
            input.select();

            const finish = (save) => {
                if (!input.isConnected) return;
                const value = input.value.trim();
                if (save && value) {
                    block.settings ||= {};
                    block.settings.structure_label = value;
                }
                renderStructureTree();
            };
            input.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    finish(true);
                } else if (event.key === 'Escape') {
                    finish(false);
                }
            });
            input.addEventListener('blur', () => finish(true), { once: true });
        }

        function deleteStructureBlock(block) {
            const descendants = getStructureSubtree(block);
            const label = block.settings?.structure_label || componentNames[block.type] || 'widget';
            if (!window.confirm(`Hapus ${label}${descendants.length > 1 ? ' beserta seluruh isi di dalamnya' : ''}?`)) return;
            const removedIndices = descendants.map((item) => blocks.indexOf(item)).filter((index) => index >= 0);
            const firstRemoved = Math.min(...removedIndices);
            descendants.forEach((item) => {
                const index = blocks.indexOf(item);
                if (index >= 0) blocks.splice(index, 1);
            });
            selectedIndex = blocks.length ? Math.min(firstRemoved, blocks.length - 1) : -1;
            renderAll();
        }

        function clearStructureDropIndicator() {
            structureTree.classList.remove('is-drop-root');
            structureTree.querySelectorAll('.drop-before, .drop-after, .drop-inside').forEach((row) => {
                row.classList.remove('drop-before', 'drop-after', 'drop-inside');
            });
        }

        structureTree.addEventListener('dragover', (event) => {
            if (!structureDraggedBlock) return;
            event.preventDefault();
            clearStructureDropIndicator();
            const row = event.target.closest('.structure-tree-row');
            if (!row) {
                structureTree.classList.add('is-drop-root');
                event.dataTransfer.dropEffect = 'move';
                return;
            }
            const targetIndex = Number(row.dataset.blockIndex);
            const targetBlock = blocks[targetIndex];
            const bounds = row.getBoundingClientRect();
            const offset = event.clientY - bounds.top;
            const placement = targetBlock.type === 'container' && offset > bounds.height * 0.25 && offset < bounds.height * 0.75
                ? 'inside'
                : (offset < bounds.height / 2 ? 'before' : 'after');
            if (placement === 'inside'
                && wouldCreateContainerCycle(structureDraggedBlock, targetBlock.settings?.container_id)) {
                event.dataTransfer.dropEffect = 'none';
                return;
            }
            row.classList.add(placement === 'inside' ? 'drop-inside' : `drop-${placement}`);
            event.dataTransfer.dropEffect = 'move';
        });

        structureTree.addEventListener('dragleave', (event) => {
            if (!structureTree.contains(event.relatedTarget)) clearStructureDropIndicator();
        });

        structureTree.addEventListener('drop', (event) => {
            if (!structureDraggedBlock) return;
            event.preventDefault();
            const draggedBlock = structureDraggedBlock;
            const row = event.target.closest('.structure-tree-row');
            const targetBlock = row ? blocks[Number(row.dataset.blockIndex)] : null;
            let placement = 'root';
            if (row && targetBlock) {
                const bounds = row.getBoundingClientRect();
                const offset = event.clientY - bounds.top;
                placement = targetBlock.type === 'container' && offset > bounds.height * 0.25 && offset < bounds.height * 0.75
                    ? 'inside'
                    : (offset < bounds.height / 2 ? 'before' : 'after');
            }
            if (!moveStructureBlock(draggedBlock, targetBlock, placement)) {
                showToast('Posisi tujuan tidak valid atau akan membuat struktur container bersiklus.');
            } else {
                renderAll();
            }
            structureDraggedBlock = null;
            draggedBlockIndex = null;
            clearStructureDropIndicator();
        });

        function setStructureWindowOpen(isOpen) {
            structureWindow.hidden = !isOpen;
            structureWindow.setAttribute('aria-hidden', String(!isOpen));
            structureToggle.setAttribute('aria-pressed', String(isOpen));
        }

        structureToggle.addEventListener('click', () => setStructureWindowOpen(structureWindow.hidden));
        structureWindowClose.addEventListener('click', () => setStructureWindowOpen(false));
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !structureWindow.hidden) setStructureWindowOpen(false);
        });
        structureWindowHandle.addEventListener('pointerdown', (event) => {
            if (event.target.closest('button')) return;
            event.preventDefault();
            const rect = structureWindow.getBoundingClientRect();
            const offsetX = event.clientX - rect.left;
            const offsetY = event.clientY - rect.top;
            structureWindow.style.left = `${rect.left}px`;
            structureWindow.style.top = `${rect.top}px`;
            structureWindow.style.right = 'auto';
            structureWindowHandle.setPointerCapture(event.pointerId);
            const moveWindow = (moveEvent) => {
                if (moveEvent.pointerId !== event.pointerId) return;
                const left = Math.max(0, Math.min(window.innerWidth - structureWindow.offsetWidth, moveEvent.clientX - offsetX));
                const top = Math.max(0, Math.min(window.innerHeight - structureWindow.offsetHeight, moveEvent.clientY - offsetY));
                structureWindow.style.left = `${left}px`;
                structureWindow.style.top = `${top}px`;
            };
            const stopMoving = (upEvent) => {
                if (upEvent.pointerId !== event.pointerId) return;
                structureWindowHandle.removeEventListener('pointermove', moveWindow);
                structureWindowHandle.removeEventListener('pointerup', stopMoving);
                structureWindowHandle.removeEventListener('pointercancel', stopMoving);
                if (structureWindowHandle.hasPointerCapture(event.pointerId)) {
                    structureWindowHandle.releasePointerCapture(event.pointerId);
                }
            };
            structureWindowHandle.addEventListener('pointermove', moveWindow);
            structureWindowHandle.addEventListener('pointerup', stopMoving);
            structureWindowHandle.addEventListener('pointercancel', stopMoving);
        });

        function selectBlock(index) {
            selectedIndex = index;
            renderAll();
        }

        backToWidgets.addEventListener('click', () => {
            selectedIndex = -1;
            renderAll();
        });

        function clearDropIndicator() {
            previewContent.classList.remove('is-drop-target');
            delete previewContent.dataset.dropIndex;
            delete previewContent.dataset.containerId;
            delete previewContent.dataset.containerColumn;
            previewContent.querySelectorAll('.drop-before, .drop-after').forEach((block) => {
                block.classList.remove('drop-before', 'drop-after');
            });
            previewContent.querySelectorAll('.container-cell.is-drop-target').forEach((cell) => cell.classList.remove('is-drop-target'));
        }

        function getDropIndex(event) {
            const target = event.target.closest('.preview-block, .container-structure');
            if (!target || !previewContent.contains(target)) return blocks.length;

            const targetIndex = Number(target.dataset.index);
            const dropAfter = event.clientY >= target.getBoundingClientRect().top + target.getBoundingClientRect().height / 2;
            target.classList.toggle('drop-before', !dropAfter);
            target.classList.toggle('drop-after', dropAfter);

            return targetIndex + (dropAfter ? 1 : 0);
        }

        function wouldCreateContainerCycle(block, targetContainerId) {
            if (block.type !== 'container') return false;
            const movingId = block.settings?.container_id;
            let ancestorId = targetContainerId;
            while (ancestorId) {
                if (ancestorId === movingId) return true;
                const ancestor = blocks.find((candidate) =>
                    candidate.type === 'container' && candidate.settings?.container_id === ancestorId);
                ancestorId = ancestor?.settings?.parent_container_id;
            }
            return false;
        }

        previewContent.addEventListener('dragover', (event) => {
            if (!event.dataTransfer.types.includes('text/plain')) return;
            event.preventDefault();
            event.dataTransfer.dropEffect = draggedBlockIndex === null ? 'copy' : 'move';
            clearDropIndicator();
            const cell = event.target.closest('.container-cell');
            if (cell) {
                const draggedBlock = draggedBlockIndex === null ? null : blocks[draggedBlockIndex];
                if (draggedBlock && wouldCreateContainerCycle(draggedBlock, cell.dataset.containerId)) return;
                cell.classList.add('is-drop-target');
                previewContent.dataset.containerId = cell.dataset.containerId;
                previewContent.dataset.containerColumn = cell.dataset.containerColumn;
            } else {
                previewContent.classList.add('is-drop-target');
                previewContent.dataset.dropIndex = String(getDropIndex(event));
            }
        });

        previewContent.addEventListener('dragleave', (event) => {
            if (!previewContent.contains(event.relatedTarget)) clearDropIndicator();
        });

        previewContent.addEventListener('drop', (event) => {
            if (!event.dataTransfer.types.includes('text/plain')) return;
            event.preventDefault();
            const cell = event.target.closest('.container-cell');
            if (cell) {
                const containerId = cell.dataset.containerId;
                const column = cell.dataset.containerColumn;
                if (draggedBlockIndex !== null) {
                    const block = blocks[draggedBlockIndex];
                    if (wouldCreateContainerCycle(block, containerId)) {
                        showToast('Container tidak dapat dipindahkan ke dalam dirinya sendiri atau turunannya.');
                        clearDropIndicator();
                        return;
                    }
                    blocks.splice(draggedBlockIndex, 1);
                    block.settings ||= {};
                    if (block.type === 'container') {
                        block.settings.parent_container_id = containerId;
                    } else {
                        block.settings.container_id = containerId;
                    }
                    block.settings.container_column = column;
                    blocks.push(block);
                    selectedIndex = blocks.indexOf(block);
                } else {
                    const componentType = event.dataTransfer.getData('text/plain').replace(/^component:/, '');
                    if ((!componentDefaults[componentType] && componentType !== 'container')
                        || blocks.length >= maximumPageBlocks
                        || (componentType !== 'container' && getWidgetCount() >= maximumWidgets)) {
                        showToast('Maksimal 30 komponen dalam satu halaman.');
                        clearDropIndicator();
                        return;
                    }
                    const block = createComponentBlock(componentType);
                    block.settings ||= {};
                    if (block.type === 'container') {
                        block.settings.parent_container_id = containerId;
                    } else {
                        block.settings.container_id = containerId;
                    }
                    block.settings.container_column = column;
                    blocks.push(block);
                    selectedIndex = blocks.length - 1;
                }
                draggedBlockIndex = null;
                structureDraggedBlock = null;
                clearDropIndicator();
                renderAll();
                return;
            }
            const insertionIndex = Number(previewContent.dataset.dropIndex ?? blocks.length);

            if (draggedBlockIndex !== null) {
                const [block] = blocks.splice(draggedBlockIndex, 1);
                const adjustedIndex = insertionIndex > draggedBlockIndex ? insertionIndex - 1 : insertionIndex;
                if (block.type === 'container') {
                    if (block.settings) {
                        delete block.settings.parent_container_id;
                        delete block.settings.container_column;
                    }
                    blocks.splice(adjustedIndex, 0, block);
                    selectedIndex = adjustedIndex;
                } else {
                    if (blocks.length + 1 > maximumPageBlocks) {
                        blocks.splice(draggedBlockIndex, 0, block);
                        showToast('Maksimal 30 komponen dalam satu halaman.');
                        clearDropIndicator();
                        return;
                    }
                    if (block.settings) {
                    delete block.settings.container_id;
                    delete block.settings.container_column;
                    }
                    const [container, child] = createRootComponentEntries(block.type, block);
                    blocks.splice(adjustedIndex, 0, container, child);
                    selectedIndex = adjustedIndex + 1;
                }
            } else {
                const componentType = event.dataTransfer.getData('text/plain').replace(/^component:/, '');
                if (!componentDefaults[componentType] && componentType !== 'container') return;
                const entries = createRootComponentEntries(componentType);
                if (blocks.length + entries.length > maximumPageBlocks
                    || (componentType !== 'container' && getWidgetCount() >= maximumWidgets)) {
                    showToast('Maksimal 30 komponen dalam satu halaman.');
                    return;
                }
                blocks.splice(insertionIndex, 0, ...entries);
                selectedIndex = insertionIndex + entries.length - 1;
            }

            draggedBlockIndex = null;
            structureDraggedBlock = null;
            clearDropIndicator();
            renderAll();
        });

        document.querySelectorAll('[data-add-component]').forEach((button) => {
            button.addEventListener('dragstart', (event) => {
                const componentType = button.dataset.addComponent;
                event.dataTransfer.effectAllowed = 'copy';
                event.dataTransfer.setData('text/plain', `component:${componentType}`);
                button.classList.add('dragging');
            });
            button.addEventListener('dragend', () => {
                button.classList.remove('dragging');
                clearDropIndicator();
            });
            button.addEventListener('click', () => {
                const entries = createRootComponentEntries(button.dataset.addComponent);
                if (blocks.length + entries.length > maximumPageBlocks
                    || (button.dataset.addComponent !== 'container' && getWidgetCount() >= maximumWidgets)) {
                    showToast('Maksimal 30 komponen dalam satu halaman.');
                    return;
                }
                blocks.push(...entries);
                selectedIndex = blocks.length - 1;
                renderAll();
                settingsContent.querySelector('textarea, input')?.focus();
            });
        });

        const structureBuilder = document.getElementById('structureBuilder');
        const structurePicker = document.getElementById('structurePicker');
        const structurePickerGrid = document.getElementById('structurePickerGrid');
        const toggleStructurePicker = document.getElementById('toggleStructurePicker');
        containerPresets.forEach((preset) => {
            const option = document.createElement('button');
            option.type = 'button';
            option.className = 'structure-option';
            const preview = document.createElement('span');
            preview.className = 'structure-option-preview';
            preview.style.gridTemplateColumns = preset.tracks;
            preview.style.gridTemplateRows = `repeat(${preset.areas.length}, 1fr)`;
            preview.style.gridTemplateAreas = preset.areas.map((area) => `"${area}"`).join(' ');
            getContainerSlots(preset).forEach((slot) => {
                const cell = document.createElement('span');
                cell.style.gridArea = slot;
                preview.append(cell);
            });
            if (preset.icon) {
                const arrow = textNode('span', preset.icon, 'structure-flow-arrow');
                arrow.style.gridArea = '1 / 1';
                preview.append(arrow);
            }
            option.append(preview, textNode('span', preset.label));
            option.addEventListener('click', () => {
                if (blocks.length >= maximumPageBlocks) {
                    showToast('Maksimal 30 komponen dalam satu halaman.');
                    return;
                }
                blocks.push(createContainerBlock(preset.key));
                selectedIndex = blocks.length - 1;
                structurePicker.hidden = true;
                toggleStructurePicker.setAttribute('aria-expanded', 'false');
                renderAll();
            });
            structurePickerGrid.append(option);
        });
        toggleStructurePicker.addEventListener('click', () => {
            structurePicker.hidden = !structurePicker.hidden;
            toggleStructurePicker.setAttribute('aria-expanded', String(!structurePicker.hidden));
        });
        structureBuilder.addEventListener('dragover', (event) => {
            if (!event.dataTransfer.types.includes('text/plain')) return;
            event.preventDefault();
            event.dataTransfer.dropEffect = draggedBlockIndex === null ? 'copy' : 'move';
            structureBuilder.classList.add('is-drop-target');
        });
        structureBuilder.addEventListener('dragleave', (event) => {
            if (!structureBuilder.contains(event.relatedTarget)) structureBuilder.classList.remove('is-drop-target');
        });
        structureBuilder.addEventListener('drop', (event) => {
            if (!event.dataTransfer.types.includes('text/plain')) return;
            event.preventDefault();
            structureBuilder.classList.remove('is-drop-target');
            if (draggedBlockIndex !== null) {
                const [block] = blocks.splice(draggedBlockIndex, 1);
                if (block.type === 'container') {
                    if (block.settings) {
                        delete block.settings.parent_container_id;
                        delete block.settings.container_column;
                    }
                    blocks.push(block);
                    selectedIndex = blocks.length - 1;
                } else {
                    if (blocks.length + 1 > maximumPageBlocks) {
                        blocks.splice(draggedBlockIndex, 0, block);
                        showToast('Maksimal 30 komponen dalam satu halaman.');
                        return;
                    }
                    if (block.settings) {
                        delete block.settings.container_id;
                        delete block.settings.container_column;
                    }
                    const [container, child] = createRootComponentEntries(block.type, block);
                    blocks.push(container, child);
                    selectedIndex = blocks.length - 1;
                }
            } else {
                const componentType = event.dataTransfer.getData('text/plain').replace(/^component:/, '');
                if (!componentDefaults[componentType] && componentType !== 'container') return;
                const entries = createRootComponentEntries(componentType);
                if (blocks.length + entries.length > maximumPageBlocks
                    || (componentType !== 'container' && getWidgetCount() >= maximumWidgets)) {
                    showToast('Maksimal 30 komponen dalam satu halaman.');
                    return;
                }
                blocks.push(...entries);
                selectedIndex = blocks.length - 1;
            }
            draggedBlockIndex = null;
            structureDraggedBlock = null;
            renderAll();
        });

        document.querySelectorAll('[data-viewport]').forEach((button) => {
            button.addEventListener('click', () => {
                document.querySelectorAll('[data-viewport]').forEach((item) => {
                    item.classList.toggle('active', item === button);
                    item.setAttribute('aria-pressed', String(item === button));
                });
                const viewport = button.dataset.viewport;
                const canvas = document.getElementById('pageCanvas');
                canvas.classList.remove('tablet', 'mobile');
                if (viewport !== 'desktop') canvas.classList.add(viewport);
                resizePageCanvas();
            });
        });
        window.addEventListener('resize', resizePageCanvas);
        if ('ResizeObserver' in window) {
            new ResizeObserver(resizePageCanvas).observe(canvasScroll);
        }

        document.getElementById('saveButton').addEventListener('click', () => {
            if (backgroundUploadInProgress) {
                showToast('Tunggu hingga unggahan gambar selesai sebelum menyimpan.');
                return;
            }
            if (!blocks.length) {
                showToast('Tambahkan minimal satu komponen sebelum menyimpan.');
                return;
            }

            const hiddenInputs = document.getElementById('blockInputs');
            hiddenInputs.replaceChildren();
            const normalizedPageWidth = Math.max(320, Math.min(1920, Number.parseInt(pageWidthSettings.width, 10) || 760));
            document.getElementById('pageWidthModeInput').value = pageWidthSettings.mode;
            document.getElementById('pageWidthInput').value = String(normalizedPageWidth);
            const requiredFields = {
                heading: ['text'],
                paragraph: ['text'],
                text: ['text'],
                quote: ['text'],
                list: ['text'],
                image: [],
                video: [],
                button: ['text', 'url'],
                divider: [],
                spacer: ['height'],
                map: ['text'],
            };
            let invalidMessage = '';
            blocks.forEach((block, index) => {
                const fields = new Set(['type', 'text', 'url', 'level', 'height']);
                fields.forEach((key) => {
                    if (block[key] === undefined || block[key] === null || block[key] === '') return;
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `blocks[${index}][${key}]`;
                    input.value = String(block[key]);
                    hiddenInputs.append(input);
                });
                Object.entries(block.settings || {}).forEach(([key, value]) => {
                    if (value === undefined || value === null || value === '') return;
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `blocks[${index}][settings][${key}]`;
                    input.value = typeof value === 'boolean' ? (value ? '1' : '0') : String(value);
                    hiddenInputs.append(input);
                });
                (requiredFields[block.type] || []).forEach((key) => {
                    if (block[key] === undefined || String(block[key]).trim() === '') {
                        invalidMessage = `Lengkapi ${componentNames[block.type]} sebelum menyimpan.`;
                    }
                });
                const isUploadedPageImage = block.type === 'image'
                    && /^\/storage\/page-images\/[A-Za-z0-9._-]+\.(jpg|jpeg|png|webp|gif)$/i.test(block.url || '');
                const isUploadedPageVideo = block.type === 'video'
                    && (block.settings?.video_source || 'self_hosted') === 'self_hosted'
                    && /^\/storage\/page-videos\/[A-Za-z0-9._-]+\.(mp4|webm|ogg)$/i.test(block.url || '');
                if (block.url && !isUploadedPageImage && !isUploadedPageVideo && !/^https?:\/\/\S+$/i.test(block.url)) {
                    invalidMessage = 'URL komponen harus berupa alamat web http:// atau https://.';
                }
                if (block.type === 'video' && block.url && !isUploadedPageVideo
                    && (block.settings?.video_source || 'self_hosted') !== 'self_hosted'
                    && !videoEmbedUrl(block.settings.video_source, block.url)) {
                    invalidMessage = 'URL video tidak sesuai dengan sumber yang dipilih.';
                }
                if (block.settings?.link && !/^https?:\/\/\S+$/i.test(block.settings.link)) {
                    invalidMessage = 'Link heading harus berupa alamat web http:// atau https://.';
                }
                if (block.type === 'spacer' && !/^\d+$/.test(String(block.height))) {
                    invalidMessage = 'Tinggi spacer harus berupa angka.';
                }
                if (block.type === 'menu' && !menuOptions.some((menu) => String(menu.id) === String(block.settings?.menu_id))) {
                    invalidMessage = 'Pilih menu yang sudah dibuat di dashboard admin.';
                }
            });
            if (invalidMessage) {
                showToast(invalidMessage);
                return;
            }
            document.getElementById('saveForm').requestSubmit();
        });

        function showToast(message) {
            toast.textContent = message;
            toast.classList.add('show');
            window.clearTimeout(toastTimer);
            toastTimer = window.setTimeout(() => toast.classList.remove('show'), 2800);
        }

        document.querySelectorAll('.setting-field').forEach((field) => field.remove());
        const initialTitle = {!! Illuminate\Support\Js::from($page->title)->toHtml() !!};
        const initialSummary = {!! Illuminate\Support\Js::from($page->summary ?? '')->toHtml() !!};
        selectedPageTitle.textContent = initialTitle;
        selectedPageSummary.textContent = initialSummary;
        resizePageCanvas();
        renderAll();
    </script>
</body>
</html>
