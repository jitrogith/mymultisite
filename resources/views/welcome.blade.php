<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#252f60">
    <meta name="description" content="Situs resmi Pemerintah Kabupaten Nusantara. Informasi pemerintahan, layanan publik, dan kabar terbaru untuk masyarakat.">
    <title>Beranda | Pemerintah Kabupaten {{ $content['site_name'] }}</title>
    <style>
        :root {
            color-scheme: light;
            --navy: #252f60;
            --navy-dark: #1b244e;
            --gold: #eeae43;
            --gold-light: #fff4df;
            --ink: #222b45;
            --muted: #69728a;
            --line: #e7e9ef;
            --paper: #fff;
            --canvas: #f7f7f5;
            font-family: "Segoe UI", Arial, sans-serif;
            font-synthesis: none;
            text-rendering: optimizeLegibility;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; background: var(--paper); color: var(--ink); }
        a { color: inherit; text-decoration: none; }
        button, input { font: inherit; }
        button { cursor: pointer; }
        .container { width: min(1160px, calc(100% - 48px)); margin: 0 auto; }
        .utility-bar { min-height: 36px; background: var(--navy-dark); color: #e6e8f1; }
        .utility-inner { display: flex; min-height: 36px; align-items: center; justify-content: space-between; gap: 18px; font-size: 10px; }
        .utility-note, .utility-links { display: flex; align-items: center; gap: 17px; }
        .utility-note { color: #d6d9e6; }
        .utility-note svg { width: 12px; height: 12px; color: var(--gold); }
        .utility-links a { color: #e4e6ef; transition: color .15s; }
        .utility-links a:hover { color: var(--gold); }
        .utility-links .language { display: inline-flex; align-items: center; gap: 5px; color: #f1ca82; font-weight: 700; }
        .language svg { width: 12px; height: 12px; }

        .site-header { position: sticky; top: 0; z-index: 20; border-bottom: 1px solid var(--line); background: #fff; }
        .header-main { position: relative; display: flex; min-height: 91px; align-items: center; justify-content: space-between; gap: 30px; }
        .identity { display: flex; align-items: center; gap: 14px; flex: 0 0 auto; }
        .crest { display: grid; width: 54px; height: 60px; place-items: center; color: var(--navy); }
        .crest svg { width: 52px; height: 58px; }
        .identity-text { max-width: 330px; }
        .identity-kicker { margin-bottom: 4px; color: #8a7146; font-size: 9px; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; }
        .identity h1 { margin: 0; color: var(--navy); font-family: Georgia, "Times New Roman", serif; font-size: 17px; font-weight: 700; letter-spacing: .015em; line-height: 1.25; }
        .identity p { margin: 4px 0 0; color: #70788c; font-size: 9px; letter-spacing: .07em; text-transform: uppercase; }
        .header-actions { display: flex; align-items: center; gap: 15px; }
        .search-box { display: flex; width: 218px; height: 36px; align-items: center; gap: 8px; padding: 0 10px; border: 1px solid #e5e7ed; border-radius: 5px; background: #fff; }
        .search-box:focus-within { border-color: #b4bacb; box-shadow: 0 0 0 3px #252f600e; }
        .search-box svg { width: 15px; height: 15px; flex: 0 0 auto; color: #858ca0; }
        .search-box input { width: 100%; min-width: 0; border: 0; outline: 0; color: var(--ink); font-size: 10px; }
        .search-box input::placeholder { color: #9a9eac; }
        .search-box button { display: grid; width: 23px; height: 23px; flex: 0 0 auto; place-items: center; border: 0; background: transparent; color: #747c91; }
        .search-box button svg { width: 14px; height: 14px; }
        .header-contact { display: flex; align-items: center; gap: 9px; color: var(--navy); }
        .contact-icon { display: grid; width: 34px; height: 34px; place-items: center; border-radius: 50%; background: var(--gold-light); color: #9f6a13; }
        .contact-icon svg { width: 16px; height: 16px; }
        .contact-copy small, .contact-copy strong { display: block; }
        .contact-copy small { margin-bottom: 3px; color: #848a9a; font-size: 8px; }
        .contact-copy strong { font-size: 10px; }
        .menu-toggle { display: none; width: 38px; height: 38px; place-items: center; border: 1px solid var(--line); border-radius: 5px; background: #fff; color: var(--navy); }
        .menu-toggle svg { width: 19px; height: 19px; }

        .main-nav { border-top: 1px solid #f0f1f4; }
        .nav-inner { display: flex; min-height: 47px; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 4px 12px; padding-top: 4px; padding-bottom: 4px; }
        .nav-links { display: flex; flex-wrap: wrap; align-items: stretch; gap: 0 20px; }
        .nav-links a { position: relative; display: inline-flex; align-items: center; color: #48516b; font-size: 10px; font-weight: 600; transition: color .15s; }
        .nav-links a:hover, .nav-links a[aria-current="page"] { color: var(--navy); }
        .nav-links a[aria-current="page"]:after { position: absolute; right: 0; bottom: -1px; left: 0; height: 3px; background: var(--gold); content: ""; }
        .nav-links a svg { width: 11px; height: 11px; margin-left: 5px; color: #8b91a1; }
        .nav-ppid { display: inline-flex; align-items: center; gap: 7px; color: var(--navy); font-size: 9px; font-weight: 700; }
        .nav-ppid svg { width: 14px; height: 14px; color: #a37631; }

        .announcement { background: var(--gold-light); border-bottom: 1px solid #f2e4c8; }
        .announcement-inner { display: flex; min-height: 39px; align-items: center; gap: 12px; }
        .announcement-label { display: inline-flex; flex: 0 0 auto; align-items: center; gap: 6px; color: #805710; font-size: 9px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
        .announcement-label svg { width: 14px; height: 14px; }
        .announcement-divider { width: 1px; height: 17px; background: #e7d5b1; }
        .announcement-text { overflow: hidden; color: #5e584b; font-size: 10px; text-overflow: ellipsis; white-space: nowrap; }
        .announcement-text strong { color: #342f27; font-weight: 600; }
        .announcement-link { margin-left: auto; flex: 0 0 auto; color: #79561e; font-size: 9px; font-weight: 700; }
        .announcement-link:hover { text-decoration: underline; }

        .hero { position: relative; overflow: hidden; background: var(--navy); color: #fff; }
        .hero:before { position: absolute; inset: 0; background: radial-gradient(ellipse at 80% 45%, #455287 0, transparent 46%), linear-gradient(112deg, #202a58 0%, #252f60 53%, #303b70 100%); content: ""; }
        .hero-inner { position: relative; display: grid; min-height: 432px; grid-template-columns: 1.02fr .98fr; align-items: center; gap: 30px; padding-top: 32px; padding-bottom: 32px; }
        .hero-copy { position: relative; z-index: 2; max-width: 530px; padding: 16px 0 26px; }
        .hero-eyebrow { display: inline-flex; align-items: center; gap: 9px; margin: 0 0 17px; color: #f1cb84; font-size: 9px; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; }
        .hero-eyebrow:before { width: 23px; height: 1px; background: var(--gold); content: ""; }
        .hero-copy h2 { max-width: 510px; margin: 0; font-family: Georgia, "Times New Roman", serif; font-size: clamp(32px, 3.45vw, 46px); font-weight: 600; letter-spacing: -.025em; line-height: 1.13; }
        .hero-copy h2 span { color: #f2c773; }
        .hero-copy p { max-width: 460px; margin: 18px 0 23px; color: #d7dbea; font-size: 12px; line-height: 1.8; }
        .hero-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 11px; }
        .button-primary, .button-ghost { display: inline-flex; min-height: 42px; align-items: center; justify-content: center; gap: 9px; padding: 0 16px; border-radius: 4px; font-size: 10px; font-weight: 700; transition: transform .15s, background .15s; }
        .button-primary { border: 1px solid var(--gold); background: var(--gold); color: #252a42; }
        .button-primary:hover { transform: translateY(-1px); background: #f4bd5e; }
        .button-ghost { border: 1px solid #ffffff6b; background: #ffffff08; color: #fff; }
        .button-ghost:hover { background: #ffffff16; }
        .button-primary svg, .button-ghost svg { width: 14px; height: 14px; }
        .hero-visual { position: relative; min-height: 355px; align-self: end; }
        .hero-visual:before { position: absolute; right: 3%; bottom: 3%; width: 88%; height: 80%; border: 1px solid #ffffff1e; border-radius: 48% 48% 0 0; content: ""; }
        .hero-visual:after { position: absolute; right: 8%; bottom: 5%; width: 77%; height: 74%; border: 1px solid #ffffff14; border-radius: 48% 48% 0 0; content: ""; }
        .hero-art { position: absolute; right: 0; bottom: 0; width: 100%; height: 100%; }
        .hero-art svg { display: block; width: 100%; height: 100%; }
        .hero-caption { position: absolute; right: 0; bottom: 11px; display: flex; align-items: center; gap: 8px; color: #c9cde0; font-size: 8px; letter-spacing: .03em; }
        .hero-caption:before { width: 17px; height: 1px; background: var(--gold); content: ""; }
        .hero-dots { position: absolute; right: 0; bottom: -7px; left: 0; display: flex; justify-content: center; gap: 6px; }
        .hero-dots span { width: 5px; height: 5px; border-radius: 5px; background: #ffffff55; }
        .hero-dots span:first-child { width: 20px; background: var(--gold); }

        .quick-section { position: relative; z-index: 3; margin-top: -1px; border-bottom: 1px solid var(--line); background: #fff; }
        .quick-grid { display: grid; grid-template-columns: repeat(4, 1fr); }
        .quick-card { display: flex; min-height: 109px; align-items: center; gap: 14px; padding: 20px 22px; border-right: 1px solid var(--line); transition: background .15s; }
        .quick-card:first-child { border-left: 1px solid var(--line); }
        .quick-card:hover { background: #faf9f5; }
        .quick-icon { display: grid; width: 43px; height: 43px; flex: 0 0 auto; place-items: center; border: 1px solid #f2e5cb; border-radius: 50%; background: var(--gold-light); color: #9b6d20; }
        .quick-icon svg { width: 20px; height: 20px; }
        .quick-copy { min-width: 0; }
        .quick-copy strong { display: block; color: var(--navy); font-family: Georgia, "Times New Roman", serif; font-size: 12px; }
        .quick-copy span { display: block; margin-top: 5px; color: #81879a; font-size: 9px; line-height: 1.45; }
        .quick-arrow { width: 14px; height: 14px; flex: 0 0 auto; margin-left: auto; color: #a1a5b0; }

        .section { padding-top: 51px; padding-bottom: 51px; }
        .section-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 18px; margin-bottom: 22px; }
        .section-kicker { margin: 0 0 7px; color: #a07939; font-size: 9px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
        .section-heading h2 { margin: 0; color: var(--navy); font-family: Georgia, "Times New Roman", serif; font-size: 25px; font-weight: 700; letter-spacing: -.015em; }
        .section-heading p { margin: 7px 0 0; color: var(--muted); font-size: 10px; line-height: 1.6; }
        .text-link { display: inline-flex; align-items: center; gap: 7px; flex: 0 0 auto; color: var(--navy); font-size: 10px; font-weight: 700; }
        .text-link:hover { color: #a37631; }
        .text-link svg { width: 14px; height: 14px; }
        .service-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 13px; }
        .service-card { min-height: 153px; padding: 19px 18px 16px; border: 1px solid var(--line); border-radius: 5px; background: #fff; transition: border-color .18s, box-shadow .18s, transform .18s; }
        .service-card:hover { transform: translateY(-2px); border-color: #d6c08f; box-shadow: 0 8px 22px #1d264f0b; }
        .service-icon { display: grid; width: 38px; height: 38px; place-items: center; border-radius: 5px; background: #f3f4f8; color: var(--navy); }
        .service-card:nth-child(2n) .service-icon { background: var(--gold-light); color: #996b22; }
        .service-icon svg { width: 19px; height: 19px; }
        .service-card h3 { margin: 13px 0 5px; color: #313a58; font-size: 11px; font-weight: 700; }
        .service-card p { margin: 0; color: #848a9b; font-size: 9px; line-height: 1.55; }
        .service-more { display: flex; justify-content: center; margin-top: 20px; }
        .button-outline { display: inline-flex; min-height: 36px; align-items: center; gap: 8px; padding: 0 14px; border: 1px solid #d9dce4; border-radius: 4px; background: #fff; color: var(--navy); font-size: 9px; font-weight: 700; }
        .button-outline:hover { border-color: var(--navy); }
        .button-outline svg { width: 13px; height: 13px; }

        .news-section { background: var(--canvas); }
        .news-grid { display: grid; grid-template-columns: 1.35fr 1fr 1fr; gap: 15px; }
        .news-card { overflow: hidden; border: 1px solid #e9e9e5; border-radius: 5px; background: #fff; transition: box-shadow .18s, transform .18s; }
        .news-card:hover { transform: translateY(-2px); box-shadow: 0 10px 24px #1d264f0c; }
        .news-image { position: relative; display: grid; height: 157px; overflow: hidden; place-items: center; background: linear-gradient(145deg, #dbd6c8, #adb8b0); }
        .news-card:first-child .news-image { height: 188px; background: linear-gradient(145deg, #d9d0b9, #8fa19a); }
        .news-image:after { position: absolute; inset: 45% 0 0; background: linear-gradient(0deg, #1b244e80, transparent); content: ""; }
        .news-image svg { width: 100%; height: 100%; }
        .news-category { position: absolute; z-index: 1; top: 12px; left: 12px; padding: 6px 8px; border-radius: 2px; background: var(--gold); color: #302b20; font-size: 8px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
        .news-content { padding: 14px 15px 16px; }
        .news-meta { display: flex; align-items: center; gap: 8px; color: #9297a4; font-size: 8px; }
        .news-meta span + span:before { display: inline-block; width: 3px; height: 3px; margin: 0 8px 2px 0; border-radius: 50%; background: var(--gold); content: ""; }
        .news-content h3 { margin: 9px 0 7px; color: #2c3553; font-family: Georgia, "Times New Roman", serif; font-size: 14px; line-height: 1.4; }
        .news-card:first-child .news-content h3 { font-size: 17px; }
        .news-content p { margin: 0; color: #747c8f; font-size: 9px; line-height: 1.65; }
        .news-read { display: inline-flex; align-items: center; gap: 6px; margin-top: 12px; color: #846024; font-size: 9px; font-weight: 700; }
        .news-read svg { width: 12px; height: 12px; }

        .info-section { display: grid; grid-template-columns: 1fr 1fr; gap: 34px; align-items: start; }
        .agenda-list { border-top: 1px solid var(--line); }
        .agenda-item { display: grid; grid-template-columns: 48px 1fr auto; align-items: center; gap: 13px; padding: 15px 0; border-bottom: 1px solid var(--line); }
        .agenda-date { display: grid; width: 43px; height: 45px; place-content: center; border: 1px solid #efe3ca; border-radius: 3px; background: var(--gold-light); color: var(--navy); text-align: center; }
        .agenda-date strong { font-family: Georgia, "Times New Roman", serif; font-size: 17px; line-height: 1; }
        .agenda-date span { margin-top: 4px; color: #94713a; font-size: 7px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
        .agenda-item h3 { margin: 0; color: #333c58; font-size: 10px; font-weight: 700; line-height: 1.45; }
        .agenda-item p { margin: 5px 0 0; color: #858b9b; font-size: 8px; }
        .agenda-time { color: #727b91; font-size: 8px; white-space: nowrap; }
        .transparency-card { position: relative; overflow: hidden; min-height: 234px; padding: 25px; border-radius: 5px; background: var(--navy); color: #fff; }
        .transparency-card:after { position: absolute; right: -68px; bottom: -118px; width: 270px; height: 270px; border: 1px solid #ffffff21; border-radius: 50%; box-shadow: 0 0 0 18px #ffffff08, 0 0 0 38px #ffffff06; content: ""; }
        .transparency-card .section-kicker { color: #f0c77b; }
        .transparency-card h2 { position: relative; z-index: 1; max-width: 340px; margin: 0; font-family: Georgia, "Times New Roman", serif; font-size: 23px; line-height: 1.3; }
        .transparency-card p { position: relative; z-index: 1; max-width: 380px; margin: 11px 0 18px; color: #d4d8e6; font-size: 9px; line-height: 1.7; }
        .transparency-card .button-primary { position: relative; z-index: 1; min-height: 36px; font-size: 9px; }
        .transparency-links { display: grid; grid-template-columns: 1fr 1fr; gap: 9px; margin-top: 13px; }
        .transparency-links a { display: flex; min-height: 41px; align-items: center; gap: 8px; padding: 8px; border: 1px solid #e8e9ed; border-radius: 3px; color: #4b536b; font-size: 8px; font-weight: 600; }
        .transparency-links a:hover { border-color: #d6c08f; }
        .transparency-links svg { width: 15px; height: 15px; flex: 0 0 auto; color: #9d742d; }

        .service-band { background: #f3f4f7; }
        .service-band-inner { display: flex; align-items: center; justify-content: space-between; gap: 20px; padding-top: 20px; padding-bottom: 20px; }
        .service-band-copy { display: flex; align-items: center; gap: 13px; }
        .service-band-copy .contact-icon { width: 41px; height: 41px; background: var(--navy); color: var(--gold); }
        .service-band-copy strong { display: block; color: var(--navy); font-family: Georgia, "Times New Roman", serif; font-size: 13px; }
        .service-band-copy span { display: block; margin-top: 4px; color: #737b8e; font-size: 9px; }
        .service-band-actions { display: flex; align-items: center; gap: 22px; }
        .service-band-actions a { color: var(--navy); font-size: 10px; font-weight: 700; }
        .service-band-actions a:hover { text-decoration: underline; }

        .site-footer { background: var(--navy-dark); color: #d7dbea; }
        .footer-main { display: grid; grid-template-columns: 1.45fr 1fr 1fr 1fr; gap: 36px; padding-top: 37px; padding-bottom: 31px; }
        .footer-identity { display: flex; align-items: center; gap: 11px; margin-bottom: 12px; }
        .footer-identity .crest { width: 40px; height: 45px; color: var(--gold); }
        .footer-identity .crest svg { width: 39px; height: 44px; }
        .footer-identity strong { display: block; color: white; font-family: Georgia, "Times New Roman", serif; font-size: 12px; line-height: 1.4; }
        .footer-about p { max-width: 290px; margin: 0; color: #b7bdd2; font-size: 9px; line-height: 1.75; }
        .footer-column h2 { margin: 3px 0 13px; color: #f1c879; font-size: 9px; font-weight: 800; letter-spacing: .09em; text-transform: uppercase; }
        .footer-column a, .footer-column address, .footer-column p { display: block; margin: 0 0 9px; color: #c4c9d9; font-size: 9px; font-style: normal; line-height: 1.65; }
        .footer-column a:hover { color: var(--gold); }
        .footer-bottom { border-top: 1px solid #ffffff20; }
        .footer-bottom-inner { display: flex; min-height: 43px; align-items: center; justify-content: space-between; gap: 15px; color: #aeb5ca; font-size: 8px; }
        .footer-bottom strong { color: #dce0ec; font-weight: 600; }
        .toast { position: fixed; right: 20px; bottom: 20px; z-index: 50; max-width: calc(100vw - 40px); padding: 12px 15px; transform: translateY(10px); border-left: 3px solid var(--gold); border-radius: 3px; background: var(--navy-dark); box-shadow: 0 8px 24px #11172e30; color: #fff; font-size: 10px; opacity: 0; pointer-events: none; transition: opacity .2s, transform .2s; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .sr-only { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; clip-path: inset(50%); }

        @media (min-width: 1400px) {
            .hero-inner { min-height: 470px; }
            .hero-visual { min-height: 397px; }
        }
        @media (max-width: 980px) {
            .container { width: min(100% - 38px, 800px); }
            .header-main { min-height: 80px; gap: 14px; }
            .header-contact { display: none; }
            .nav-links { gap: 19px; }
            .hero-inner { min-height: 390px; grid-template-columns: 1.1fr .9fr; gap: 12px; }
            .hero-visual { min-height: 320px; }
            .quick-card { gap: 10px; padding: 15px 13px; }
            .quick-icon { width: 38px; height: 38px; }
            .footer-main { gap: 22px; }
        }
        @media (max-width: 720px) {
            .container { width: calc(100% - 34px); }
            .utility-inner { justify-content: center; }
            .utility-links { display: none; }
            .header-main { min-height: 73px; }
            .crest { width: 44px; height: 50px; }
            .crest svg { width: 43px; height: 48px; }
            .identity { gap: 9px; }
            .identity h1 { font-size: 14px; }
            .identity-kicker { font-size: 7px; }
            .identity p { font-size: 7px; }
            .search-box { width: 36px; height: 36px; justify-content: center; padding: 0; border: 0; }
            .search-box input, .search-box button { display: none; }
            .search-box svg { width: 17px; height: 17px; }
            .search-box.search-open { position: absolute; top: 50%; right: 43px; z-index: 2; width: min(205px, calc(100vw - 112px)); height: 36px; justify-content: flex-start; padding: 0 8px; transform: translateY(-50%); border: 1px solid #e5e7ed; box-shadow: 0 4px 12px #1d264f16; }
            .search-box.search-open input { display: block; }
            .search-box.search-open button { display: grid; }
            .search-box.search-open > svg { width: 15px; height: 15px; }
            .header-actions { gap: 8px; }
            .menu-toggle { display: grid; }
            .main-nav { display: none; border-top: 1px solid var(--line); }
            .main-nav.open { display: block; }
            .nav-inner { display: block; padding-top: 7px; padding-bottom: 11px; }
            .nav-links { display: grid; gap: 0; }
            .nav-links a { min-height: 40px; border-bottom: 1px solid #f1f2f5; font-size: 11px; }
            .nav-links a[aria-current="page"]:after { right: auto; width: 3px; height: 20px; }
            .nav-links a svg { margin-left: auto; }
            .nav-ppid { min-height: 38px; margin-top: 7px; }
            .announcement-inner { gap: 8px; }
            .announcement-label { font-size: 8px; }
            .announcement-text { font-size: 9px; }
            .announcement-link { font-size: 8px; }
            .announcement-divider { display: none; }
            .hero-inner { min-height: 0; grid-template-columns: 1fr; padding-top: 43px; padding-bottom: 32px; }
            .hero-copy { max-width: 560px; padding: 0; }
            .hero-copy h2 { max-width: 490px; font-size: clamp(31px, 7.5vw, 42px); }
            .hero-copy p { max-width: 440px; font-size: 11px; }
            .hero-visual { position: absolute; right: -67px; bottom: -36px; width: 53%; min-height: 230px; opacity: .2; }
            .hero-caption, .hero-dots { display: none; }
            .hero-copy { z-index: 2; }
            .quick-grid { grid-template-columns: repeat(2, 1fr); }
            .quick-card { min-height: 90px; border-bottom: 1px solid var(--line); }
            .quick-card:nth-child(odd) { border-left: 1px solid var(--line); }
            .quick-card:nth-child(even) { border-right: 1px solid var(--line); }
            .quick-card:nth-child(n+3) { border-bottom: 0; }
            .section { padding-top: 39px; padding-bottom: 39px; }
            .section-heading h2 { font-size: 22px; }
            .service-grid { grid-template-columns: repeat(2, 1fr); }
            .service-card { min-height: 143px; padding: 15px; }
            .news-grid { grid-template-columns: repeat(2, 1fr); }
            .news-card:first-child { grid-column: 1 / -1; }
            .news-card:first-child .news-image { height: 205px; }
            .info-section { grid-template-columns: 1fr; gap: 33px; }
            .footer-main { grid-template-columns: 1fr 1fr; row-gap: 26px; }
            .footer-about { grid-column: 1 / -1; }
            .footer-bottom-inner { min-height: 54px; flex-direction: column; justify-content: center; gap: 4px; text-align: center; }
        }
        @media (max-width: 460px) {
            .container { width: calc(100% - 28px); }
            .utility-inner { min-height: 32px; font-size: 9px; }
            .header-main { min-height: 67px; }
            .identity-text { max-width: 212px; }
            .identity h1 { font-size: 12px; }
            .identity p { max-width: 195px; font-size: 6px; }
            .identity-kicker { font-size: 6px; }
            .crest { width: 38px; height: 45px; }
            .crest svg { width: 37px; height: 43px; }
            .header-actions { gap: 5px; }
            .menu-toggle { width: 34px; height: 34px; }
            .search-box { width: 30px; height: 34px; }
            .announcement-inner { min-height: 36px; }
            .announcement-label { gap: 4px; font-size: 7px; }
            .announcement-label svg { width: 12px; height: 12px; }
            .announcement-text { font-size: 8px; }
            .announcement-link { display: none; }
            .hero-inner { padding-top: 36px; padding-bottom: 29px; }
            .hero-eyebrow { margin-bottom: 13px; font-size: 8px; }
            .hero-copy h2 { font-size: 31px; }
            .hero-copy p { margin: 13px 0 18px; font-size: 10px; }
            .button-primary, .button-ghost { min-height: 38px; padding: 0 11px; font-size: 9px; }
            .hero-visual { right: -90px; width: 65%; }
            .quick-card { min-height: 83px; gap: 8px; padding: 12px 9px; }
            .quick-icon { width: 33px; height: 33px; }
            .quick-icon svg { width: 17px; height: 17px; }
            .quick-copy strong { font-size: 10px; }
            .quick-copy span { font-size: 8px; }
            .quick-arrow { width: 11px; height: 11px; }
            .section-heading { align-items: flex-start; }
            .section-heading h2 { font-size: 20px; }
            .section-heading p { max-width: 240px; }
            .section-heading > .text-link { margin-top: 4px; font-size: 8px; }
            .service-grid { gap: 9px; }
            .service-card { min-height: 140px; padding: 12px; }
            .service-card h3 { font-size: 10px; }
            .service-card p { font-size: 8px; }
            .news-grid { gap: 9px; }
            .news-image { height: 115px; }
            .news-card:first-child .news-image { height: 165px; }
            .news-content { padding: 11px; }
            .news-content h3 { font-size: 12px; }
            .news-card:first-child .news-content h3 { font-size: 15px; }
            .news-content p { font-size: 8px; }
            .agenda-item { grid-template-columns: 43px 1fr; gap: 10px; }
            .agenda-time { grid-column: 2; margin-top: -5px; }
            .transparency-card { padding: 20px; }
            .transparency-card h2 { font-size: 20px; }
            .transparency-links { gap: 7px; }
            .transparency-links a { font-size: 7px; }
            .service-band-inner { align-items: flex-start; flex-direction: column; }
            .service-band-actions { width: 100%; justify-content: space-between; padding-left: 54px; }
            .footer-main { gap: 18px 13px; }
            .footer-column h2 { font-size: 8px; }
            .footer-column a, .footer-column address, .footer-column p { font-size: 8px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *:before, *:after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <div class="utility-bar">
        <div class="container utility-inner">
            <div class="utility-note">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 21s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12Z" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="9" r="2.2" stroke="currentColor" stroke-width="1.6"/></svg>
                <span>{{ $content['location'] }}</span>
            </div>
            <div class="utility-links">
                <a href="#agenda">Agenda</a>
                <a href="#berita">Pengumuman</a>
                <a href="#kontak">Kontak</a>
                <a class="language" href="#bahasa" aria-label="Bahasa Indonesia">ID <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="m4 6 4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
            </div>
        </div>
    </div>

    <header class="site-header">
        <div class="container header-main">
            <a class="identity" href="#beranda" aria-label="Beranda Pemerintah Kabupaten {{ $content['site_name'] }}">
                <span class="crest" aria-hidden="true">
                    <svg viewBox="0 0 60 68" fill="none"><path d="M30 3 54 12v18c0 16-10.2 27.9-24 34C16.2 57.9 6 46 6 30V12L30 3Z" fill="#fff" stroke="currentColor" stroke-width="2"/><path d="M30 8 49 15v15c0 12.8-7.8 22.6-19 28.3C18.8 52.6 11 42.8 11 30V15L30 8Z" fill="#fff8e9" stroke="#eeae43" stroke-width="1.5"/><path d="M18 42h24M20 39h20M22 37V25l8-5 8 5v12m-12 0v-8h8v8m-4-17v-4m-12 3h24" stroke="#252f60" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M24 47h12" stroke="#eeae43" stroke-width="2" stroke-linecap="round"/></svg>
                </span>
                <span class="identity-text">
                    <span class="identity-kicker">Situs Resmi Pemerintah</span>
                    <h1>Kabupaten {{ $content['site_name'] }}</h1>
                    <p>{{ $content['province'] }}</p>
                </span>
            </a>
            <div class="header-actions">
                <form class="search-box" id="searchForm" role="search">
                    <label class="sr-only" for="siteSearch">Cari informasi di situs</label>
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8" stroke="currentColor" stroke-width="1.7"/><path d="m16 16 4.5 4.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                    <input id="siteSearch" name="q" type="search" placeholder="Cari informasi..." autocomplete="off">
                    <button type="submit" aria-label="Cari"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                </form>
                <a class="header-contact" href="tel:{{ preg_replace('/[^0-9+]/', '', $content['phone']) }}">
                    <span class="contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M7 3.5h3l1.5 4-2 1.5a15 15 0 0 0 5.5 5.5l1.5-2 4 1.5v3c0 1.1-.9 2-2 2C10.5 19 5 13.5 5 7.5c0-2.2.8-4 2-4Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    <span class="contact-copy"><small>Hubungi kami</small><strong>{{ $content['phone'] }}</strong></span>
                </a>
                <button class="menu-toggle" id="menuToggle" type="button" aria-label="Buka menu navigasi" aria-expanded="false">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </button>
            </div>
        </div>
        <nav class="main-nav" id="mainNav" aria-label="Navigasi utama">
            <div class="container nav-inner">
                <div class="nav-links">
                    <a href="#beranda" aria-current="page">Beranda</a>
                    <a href="#profil">Profil Daerah <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="m4 6 4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></a>
                    <a href="#layanan">Layanan Publik <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="m4 6 4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></a>
                    <a href="#berita">Berita</a>
                    <a href="#agenda">Agenda</a>
                    <a href="#transparansi">Transparansi</a>
                    @foreach ($pages as $page)
                        <a href="{{ route('pages.show', $page->slug) }}">{{ $page->title }}</a>
                    @endforeach
                </div>
                <a class="nav-ppid" href="#transparansi"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 4h14v16H5zM8 8h8m-8 4h8m-8 4h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>PPID</a>
            </div>
        </nav>
    </header>

    <main id="beranda">
        <div class="announcement" aria-label="Pengumuman terbaru">
            <div class="container announcement-inner">
                <span class="announcement-label"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 14V8a2 2 0 0 1 2-2h3l8-3v16l-8-3H6a2 2 0 0 1-2-2Zm5 2 2 5h3l-2.5-5M19 8a4 4 0 0 1 0 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>{{ $content['announcement_label'] }}</span>
                <span class="announcement-divider" aria-hidden="true"></span>
                <span class="announcement-text"><strong>{{ $content['announcement_title'] }}</strong> {{ $content['announcement_body'] }}</span>
                <a class="announcement-link" href="#berita">Baca selengkapnya&nbsp; →</a>
            </div>
        </div>

        <section class="hero" aria-labelledby="hero-title">
            <div class="container hero-inner">
                <div class="hero-copy">
                    <p class="hero-eyebrow">{{ $content['hero_eyebrow'] }}</p>
                    <h2 id="hero-title">{{ $content['hero_title'] }} <span>{{ $content['hero_highlight'] }}</span></h2>
                    <p>{{ $content['hero_description'] }}</p>
                    <div class="hero-actions">
                        <a class="button-primary" href="#layanan">Jelajahi layanan publik <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                        <a class="button-ghost" href="#profil"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6"/><path d="M12 11v5m0-8h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>Tentang daerah</a>
                    </div>
                </div>
                <div class="hero-visual" aria-hidden="true">
                    <div class="hero-art">
                        <svg viewBox="0 0 560 390" fill="none" preserveAspectRatio="xMidYMax meet">
                            <defs><linearGradient id="building" x1="280" y1="65" x2="280" y2="342" gradientUnits="userSpaceOnUse"><stop stop-color="#f6f0df"/><stop offset="1" stop-color="#c5c1af"/></linearGradient><linearGradient id="hill" x1="280" y1="300" x2="280" y2="390" gradientUnits="userSpaceOnUse"><stop stop-color="#566746"/><stop offset="1" stop-color="#334b43"/></linearGradient></defs>
                            <circle cx="393" cy="75" r="34" fill="#EEAE43" fill-opacity=".75"/>
                            <path d="M0 306c62-40 112-48 167-23 55 24 90 19 137-4 49-25 106-31 256 28v83H0v-84Z" fill="#465d53" fill-opacity=".85"/>
                            <path d="M0 338c74-44 124-38 184-14 62 25 104 28 164 3 67-28 126-21 212 19v44H0v-52Z" fill="url(#hill)"/>
                            <path d="M165 146 280 82l115 64H165Z" fill="#eeae43"/>
                            <path d="M184 147h192v18H184v-18Z" fill="#e2e0d3"/>
                            <path d="M199 165h162v130H199V165Z" fill="url(#building)"/>
                            <path d="M188 295h184v17H188v-17Z" fill="#b9b8ac"/>
                            <path d="M176 312h208v13H176v-13Z" fill="#f2ead7"/>
                            <path d="M230 177h15v102h-15V177Zm42 0h15v102h-15V177Zm42 0h15v102h-15V177Z" fill="#f6f2e8"/>
                            <path d="M224 279v-47a15 15 0 0 1 30 0v47h-30Zm42 0v-47a15 15 0 0 1 30 0v47h-30Zm42 0v-47a15 15 0 0 1 30 0v47h-30Z" fill="#777f7c"/>
                            <path d="M265 297v-43a15 15 0 0 1 30 0v43h-30Z" fill="#7f857c"/>
                            <path d="M180 165h201m-191 7h181" stroke="#fffdf5" stroke-width="3"/>
                            <path d="M151 326h260m-232 8h204" stroke="#d4ceb9" stroke-width="2"/>
                            <path d="M148 326c7-38 4-61-12-83-19 11-31 28-34 53-3 22 13 31 46 30Zm264 1c-7-39-4-62 13-84 19 11 30 28 33 54 2 21-14 30-46 30Z" fill="#608064"/>
                            <path d="M135 278v68m267-66v66" stroke="#b9a77c" stroke-width="4"/>
                            <path d="M118 346h35m231 0h35" stroke="#e2c878" stroke-width="3" stroke-linecap="round"/>
                            <path d="m280 102 14 6v10c0 9-6 15-14 19-8-4-14-10-14-19v-10l14-6Z" fill="#252f60"/>
                            <path d="M274 119h12m-10-3v-5l4-2 4 2v5m-6 0v-3h4v3" stroke="#f2c773" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M45 370h470" stroke="#e8d9b3" stroke-opacity=".6" stroke-width="2"/>
                            <path d="M51 348v22m9-22v22m-18-22v22m436-22v22m9-22v22m-18-22v22" stroke="#bdc8a6" stroke-width="2"/>
                        </svg>
                    </div>
                    <div class="hero-caption">Pusat Pemerintahan Kabupaten Nusantara</div>
                    <div class="hero-dots"><span></span><span></span><span></span></div>
                </div>
            </div>
        </section>

        <section class="quick-section" aria-label="Akses cepat">
            <div class="container quick-grid">
                <a class="quick-card" href="#layanan">
                    <span class="quick-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 4h14v16H5zM8 8h8m-8 4h8m-8 4h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    <span class="quick-copy"><strong>Layanan Publik</strong><span>Akses layanan administrasi</span></span>
                    <svg class="quick-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <a class="quick-card" href="#transparansi">
                    <span class="quick-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 19V5m0 14h16M8 15v-4m4 4V7m4 8v-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    <span class="quick-copy"><strong>Data &amp; Statistik</strong><span>Data terbuka Kabupaten</span></span>
                    <svg class="quick-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <a class="quick-card" href="#pengaduan">
                    <span class="quick-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3h11A2.5 2.5 0 0 1 20 5.5v8a2.5 2.5 0 0 1-2.5 2.5H10l-5 4v-4.8a2.5 2.5 0 0 1-1-2V5.5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M8 8h8m-8 4h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></span>
                    <span class="quick-copy"><strong>Aspirasi Warga</strong><span>Sampaikan saran dan aduan</span></span>
                    <svg class="quick-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <a class="quick-card" href="#agenda">
                    <span class="quick-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3.5" y="5" width="17" height="15.5" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M7.5 3v4m9-4v4M4 9h16m-12 4h2m3 0h2m-7 3h2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></span>
                    <span class="quick-copy"><strong>Agenda Daerah</strong><span>Jadwal kegiatan pemerintah</span></span>
                    <svg class="quick-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </section>

        <section class="section" id="layanan" aria-labelledby="services-title">
            <div class="container">
                <div class="section-heading">
                    <div><p class="section-kicker">Mudah, dekat, dan transparan</p><h2 id="services-title">Layanan untuk masyarakat</h2><p>Temukan layanan pemerintah yang Anda butuhkan.</p></div>
                    <a class="text-link" href="#layanan">Semua layanan <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                </div>
                <div class="service-grid">
                    @foreach ($content['services'] as $service)
                        <a class="service-card" href="#layanan">
                            <span class="service-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 20h16M6 20V8l6-4 6 4v12M9 20v-6h6v6M3 8l9-5 9 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                            <h3>{{ $service['title'] }}</h3><p>{{ $service['description'] }}</p>
                        </a>
                    @endforeach
                </div>
                <div class="service-more"><a class="button-outline" href="#layanan">Lihat seluruh layanan <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></a></div>
            </div>
        </section>

        <section class="news-section section" id="berita" aria-labelledby="news-title">
            <div class="container">
                <div class="section-heading">
                    <div><p class="section-kicker">Kabar terkini</p><h2 id="news-title">Berita &amp; informasi</h2><p>Ikuti perkembangan terbaru dari Kabupaten Nusantara.</p></div>
                    <a class="text-link" href="#berita">Arsip berita <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                </div>
                <div class="news-grid">
                    @foreach ($content['news'] as $article)
                        <article class="news-card">
                            <a href="#berita" aria-label="Baca berita {{ $article['title'] }}">
                                <div class="news-image">
                                    <svg viewBox="0 0 640 300" fill="none" preserveAspectRatio="xMidYMid slice" aria-hidden="true"><rect width="640" height="300" fill="#d6d0bc"/><path d="M0 198c92-39 149-29 230 8 71 32 129 22 196-14 80-43 140-37 214-4v112H0V198Z" fill="#829181"/><path d="M0 233c94-33 164-19 246 15 72 30 147 22 219-5 71-26 124-25 175-7v64H0v-67Z" fill="#627665"/><path d="M202 117 320 54l118 63H202Z" fill="#eeae43"/><path d="M221 119h198v16H221z" fill="#ede8d9"/><path d="M237 135h166v105H237z" fill="#ddd9cc"/><path d="M225 240h190v14H225z" fill="#b6b6a8"/><path d="M250 148h15v83h-15zm44 0h15v83h-15zm44 0h15v83h-15z" fill="#f5f0e4"/><path d="M244 233v-42a14 14 0 0 1 28 0v42m30 0v-42a14 14 0 0 1 28 0v42m30 0v-42a14 14 0 0 1 28 0v42" fill="#79817b"/><path d="M299 240v-38a14 14 0 0 1 28 0v38" fill="#737a73"/><circle cx="507" cy="66" r="26" fill="#f5d48e"/><path d="M60 250v-52m15 52v-63m-29 63v-44m489 44v-53m15 53v-64m-29 64v-43" stroke="#bbc29e" stroke-width="4"/></svg>
                                    <span class="news-category">{{ $article['category'] }}</span>
                                </div>
                                <div class="news-content"><div class="news-meta"><span>{{ $article['date'] }}</span><span>{{ $article['author'] }}</span></div><h3>{{ $article['title'] }}</h3><p>{{ $article['excerpt'] }}</p><span class="news-read">Baca selengkapnya <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></span></div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container info-section">
                <section id="agenda" aria-labelledby="agenda-title">
                    <div class="section-heading"><div><p class="section-kicker">Catat tanggalnya</p><h2 id="agenda-title">Agenda daerah</h2><p>Kegiatan Pemerintah Kabupaten Nusantara.</p></div><a class="text-link" href="#agenda">Semua agenda <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></a></div>
                    <div class="agenda-list">
                        @foreach ($content['agendas'] as $agenda)
                            <article class="agenda-item"><div class="agenda-date"><strong>{{ $agenda['day'] }}</strong><span>{{ $agenda['month'] }}</span></div><div><h3>{{ $agenda['title'] }}</h3><p>{{ $agenda['location'] }}</p></div><span class="agenda-time">{{ $agenda['time'] }}</span></article>
                        @endforeach
                    </div>
                </section>
                <section id="transparansi" aria-labelledby="transparency-title">
                    <div class="transparency-card">
                        <p class="section-kicker">Pemerintahan terbuka</p>
                        <h2 id="transparency-title">{{ $content['transparency_title'] }}</h2>
                        <p>{{ $content['transparency_description'] }}</p>
                        <a class="button-primary" href="#transparansi">Kunjungi layanan PPID <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    </div>
                    <div class="transparency-links">
                        <a href="#transparansi"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 3.5h9l5 5v12H5v-17Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M14 3.5v5h5m-10 4h6m-6 4h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>Dokumen perencanaan daerah</a>
                        <a href="#transparansi"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 19V5m0 14h16M8 15v-4m4 4V7m4 8v-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>Informasi anggaran</a>
                        <a href="#transparansi"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 4h14v16H5zM8 8h8m-8 4h8m-8 4h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>Laporan kinerja instansi</a>
                        <a href="#transparansi"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3 4 7v5c0 5 3.5 8 8 9 4.5-1 8-4 8-9V7l-8-4Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>Standar pelayanan publik</a>
                    </div>
                </section>
            </div>
        </section>
    </main>

    <section class="service-band" id="pengaduan">
        <div class="container service-band-inner">
            <div class="service-band-copy">
                <span class="contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3h11A2.5 2.5 0 0 1 20 5.5v8a2.5 2.5 0 0 1-2.5 2.5H10l-5 4v-4.8a2.5 2.5 0 0 1-1-2V5.5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M8 8h8m-8 4h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></span>
                <span><strong>Ada saran atau pengaduan?</strong><span>Suara Anda membantu kami memberikan pelayanan yang lebih baik.</span></span>
            </div>
            <div class="service-band-actions"><a href="#pengaduan">Sampaikan aspirasi&nbsp; →</a><a href="tel:112">Hubungi 112</a></div>
        </div>
    </section>

    <footer class="site-footer" id="kontak">
        <div class="container footer-main">
            <div class="footer-about" id="profil">
                <div class="footer-identity">
                    <span class="crest" aria-hidden="true"><svg viewBox="0 0 60 68" fill="none"><path d="M30 3 54 12v18c0 16-10.2 27.9-24 34C16.2 57.9 6 46 6 30V12L30 3Z" stroke="currentColor" stroke-width="2"/><path d="M18 42h24M20 39h20M22 37V25l8-5 8 5v12m-12 0v-8h8v8m-4-17v-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    <strong>Pemerintah Kabupaten<br>{{ $content['site_name'] }}</strong>
                </div>
                <p>{{ $content['footer_description'] }}</p>
            </div>
            <div class="footer-column">
                <h2>Jelajahi</h2>
                <a href="#profil">Profil Daerah</a><a href="#layanan">Layanan Publik</a><a href="#berita">Berita &amp; Pengumuman</a><a href="#agenda">Agenda Kegiatan</a>
            </div>
            <div class="footer-column">
                <h2>Informasi publik</h2>
                <a href="#transparansi">PPID</a><a href="#transparansi">Dokumen Perencanaan</a><a href="#transparansi">Informasi Anggaran</a><a href="#transparansi">Laporan Kinerja</a>
            </div>
            <div class="footer-column">
                <h2>Hubungi kami</h2>
                <address>{{ $content['address'] }}</address>
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $content['phone']) }}">{{ $content['phone'] }}</a><a href="mailto:{{ $content['email'] }}">{{ $content['email'] }}</a>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container footer-bottom-inner"><span>© {{ date('Y') }} <strong>Pemerintah Kabupaten {{ $content['site_name'] }}</strong>. Hak cipta dilindungi.</span><span>Melayani dengan integritas, membangun untuk semua.</span></div>
        </div>
    </footer>
    <div class="toast" id="toast" role="status" aria-live="polite"></div>
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const mainNav = document.getElementById('mainNav');
        const toast = document.getElementById('toast');
        let toastTimer;

        function showToast(message) {
            toast.textContent = message;
            toast.classList.add('show');
            window.clearTimeout(toastTimer);
            toastTimer = window.setTimeout(() => toast.classList.remove('show'), 3000);
        }

        menuToggle.addEventListener('click', () => {
            const isOpen = mainNav.classList.toggle('open');
            menuToggle.setAttribute('aria-expanded', String(isOpen));
            menuToggle.setAttribute('aria-label', isOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi');
        });

        mainNav.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                mainNav.classList.remove('open');
                menuToggle.setAttribute('aria-expanded', 'false');
                menuToggle.setAttribute('aria-label', 'Buka menu navigasi');
            });
        });

        const searchForm = document.getElementById('searchForm');
        const searchInput = document.getElementById('siteSearch');

        searchForm.addEventListener('click', () => {
            if (window.matchMedia('(max-width: 720px)').matches && !searchForm.classList.contains('search-open')) {
                searchForm.classList.add('search-open');
                searchInput.focus();
            }
        });

        searchInput.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                searchForm.classList.remove('search-open');
                searchInput.blur();
            }
        });

        searchForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const query = searchInput.value.trim();
            if (query) {
                showToast(`Pencarian "${query}" akan tersedia setelah konten situs terhubung.`);
            } else {
                searchInput.focus();
                showToast('Masukkan kata kunci yang ingin dicari.');
            }
        });
    </script>
</body>
</html>
