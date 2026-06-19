<?php

namespace App\Providers\Filament;

use App\Filament\SuperAdmin\Widgets\ResumenWidget;
use App\Http\Middleware\EnsureSuperAdmin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class SuperAdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('superadmin')
            ->path('superadmin')
            ->login(\App\Filament\SuperAdmin\Pages\Auth\Login::class)
            ->colors(['primary' => Color::hex('#6d28d9')])
            ->font('Inter', url: 'https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap')
            ->brandName('SchoolCoreApp · Super Admin')
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(
                in: app_path('Filament/SuperAdmin/Resources'),
                for: 'App\Filament\SuperAdmin\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/SuperAdmin/Pages'),
                for: 'App\Filament\SuperAdmin\Pages'
            )
            ->pages([Dashboard::class])
            ->widgets([ResumenWidget::class])
            ->renderHook(
                'panels::styles.after',
                fn (): HtmlString => new HtmlString($this->getInlineCriticalCss())
            )
            ->renderHook(
                'panels::head.end',
                fn (): HtmlString => new HtmlString(
                    Blade::render('@vite(["resources/css/app.css"])') .
                    '<meta name="theme-color" content="#2e1065">'
                )
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureSuperAdmin::class,
            ]);
    }

    private function getInlineCriticalCss(): string
    {
        return <<<'CSS'
<style>
/* SchoolCore SuperAdmin — violet theme */
:root { --font-family: 'Inter', ui-sans-serif, system-ui, sans-serif !important; }

.fi-sidebar {
    background: linear-gradient(160deg, #2e1065 0%, #1a0940 100%) !important;
    border-right: none !important;
    box-shadow: 2px 0 20px rgba(0,0,0,0.3) !important;
}
.fi-sidebar-header { background: transparent !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.fi-sidebar-header .fi-logo, .fi-sidebar-header .fi-logo *,
.fi-sidebar-header a, .fi-sidebar-header a * { color: white !important; fill: white !important; }
.fi-sidebar-nav { padding: 1.25rem 0.5rem !important; }
.fi-sidebar-group-btn { padding: 0.875rem 0.75rem 0.25rem !important; display: flex !important; align-items: center !important; }
.fi-sidebar-group-label {
    font-size: 10px !important; font-weight: 700 !important; text-transform: uppercase !important;
    letter-spacing: 0.12em !important; color: rgba(255,255,255,0.3) !important; flex: 1 !important;
}
.fi-sidebar-item-btn {
    display: flex !important; align-items: center !important; justify-content: flex-start !important;
    gap: 10px !important; padding: 10px 14px !important; margin: 1px 6px !important;
    border-radius: 8px !important; font-size: 14px !important; font-weight: 500 !important;
    color: rgba(255,255,255,0.72) !important; text-decoration: none !important;
    background-color: transparent !important; transition: background-color 0.12s ease, color 0.12s ease !important;
}
.fi-sidebar-item-btn:hover { background-color: rgba(139,92,246,0.2) !important; color: rgba(255,255,255,0.95) !important; }
.fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
    background-color: rgba(139,92,246,0.25) !important; color: white !important;
    border-left: 3px solid #a78bfa !important; padding-left: 11px !important;
}
.fi-sidebar-item-btn > .fi-icon, .fi-sidebar-item-btn .fi-sidebar-item-icon { width: 18px !important; height: 18px !important; flex-shrink: 0 !important; color: rgba(255,255,255,0.45) !important; }
.fi-sidebar-item-btn:hover > .fi-icon,
.fi-sidebar-item.fi-active > .fi-sidebar-item-btn > .fi-icon { color: #c4b5fd !important; }
.fi-sidebar-item-label { font-size: 14px !important; font-weight: 500 !important; color: inherit !important; flex: 1 !important; overflow: hidden !important; text-overflow: ellipsis !important; white-space: nowrap !important; }
.fi-sidebar-item-grouped-border, .fi-sidebar-item-grouped-border-part,
.fi-sidebar-item-grouped-border-part-not-first, .fi-sidebar-item-grouped-border-part-not-last { display: none !important; }
.fi-sidebar-sub-group-items .fi-sidebar-item-btn { padding-left: 36px !important; }
.fi-topbar,
.fi-topbar > nav,
nav.fi-topbar { background: linear-gradient(90deg, #047857 0%, #059669 100%) !important; border-bottom: none !important; box-shadow: 0 2px 12px rgba(4,120,87,0.35) !important; }
.fi-topbar *:not(.fi-dropdown-panel):not(.fi-dropdown-panel *) { color: rgba(255,255,255,0.90) !important; }
.fi-topbar a:hover, .fi-topbar button:hover { color: #ffffff !important; }
.fi-topbar .fi-icon-btn:hover, .fi-topbar button:hover { background: rgba(255,255,255,0.12) !important; border-radius: 8px !important; }
.fi-topbar .fi-breadcrumbs li:not(:last-child) a,
.fi-topbar .fi-breadcrumbs li:not(:last-child) span { color: rgba(255,255,255,0.60) !important; }
.fi-topbar .fi-breadcrumbs li:last-child span,
.fi-topbar .fi-breadcrumbs li:last-child a { color: #ffffff !important; font-weight: 600 !important; }
.fi-topbar .fi-dropdown-panel,
.fi-topbar .fi-dropdown-panel * { color: #0f172a !important; background-color: revert !important; }
.fi-main, .fi-body { background-color: #f0f4f9 !important; }
.fi-header-heading { font-weight: 700 !important; font-size: 1.4rem !important; color: #0f172a !important; }
.fi-section { border-radius: 1rem !important; box-shadow: 0 1px 4px rgba(0,0,0,0.05) !important; }
.fi-layout-sidebar-toggle-btn { color: rgba(255,255,255,0.80) !important; }

/* ══════════════════════════════════════
   Login page — card flotante sobre fondo verde
   fi-simple-* son pass-throughs; sc-card-wrapper maneja el card
   ══════════════════════════════════════ */
.fi-simple-layout {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-height: 100vh !important;
    padding: 2rem !important;
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 55%, #6ee7b7 100%) !important;
}
.fi-simple-main-ctn {
    width: 100% !important;
    max-width: 1040px !important;
    min-height: 0 !important;
    background: transparent !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    padding: 0 !important;
    display: block !important;
}
.fi-simple-main {
    width: 100% !important;
    max-width: none !important;
    background: transparent !important;
    min-height: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
    box-shadow: none !important;
    display: block !important;
}
.fi-simple-page { width: 100% !important; padding: 0 !important; display: block !important; }
.fi-simple-page-content { width: 100% !important; display: block !important; padding: 0 !important; }
/* ── Card ── */
.sc-card-wrapper {
    display: flex !important;
    align-items: stretch !important;
    width: 100% !important;
    min-height: 620px !important;
    border-radius: 24px !important;
    overflow: hidden !important;
    box-shadow: 0 30px 70px -30px rgba(13,40,28,.32), 0 4px 12px rgba(0,0,0,0.06) !important;
}
/* ── Panel formulario (izquierda) ── */
.sc-form-side {
    flex-shrink: 0 !important;
    width: 440px !important;
    background: white !important;
    padding: 3.5rem 3rem !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: center !important;
}
.sc-form-side .fi-section,
.sc-form-side .fi-schema-section {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    border-radius: 0 !important;
}
.sc-form-side .fi-section-content,
.sc-form-side .fi-section-content-ctn { padding: 0 !important; }
.sc-form-side .fi-section-footer {
    padding: 0 !important; border-top: none !important; background: transparent !important;
}
/* ── Panel marca (derecha) — Super Admin verde oscuro ── */
.sc-brand-side {
    flex: 1 1 0 !important;
    background: linear-gradient(150deg, #065f46, #064e3b 55%, #022c22) !important;
    padding: 3rem 3.5rem !important;
    position: relative !important;
    overflow: hidden !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: center !important;
}
.sc-blob { position: absolute !important; border-radius: 50% !important; filter: blur(2px) !important; pointer-events: none !important; }
.sc-blob-1 { width:340px !important; height:340px !important; top:-90px !important; right:-70px !important; background:radial-gradient(circle,rgba(255,255,255,.18),transparent 70%) !important; }
.sc-blob-2 { width:260px !important; height:260px !important; bottom:-60px !important; left:-60px !important; background:radial-gradient(circle,rgba(255,255,255,.12),transparent 70%) !important; }
.sc-brand-lines { position:absolute !important; inset:0 !important; opacity:.05 !important; pointer-events:none !important; background-image:repeating-linear-gradient(180deg,#fff 0 1px,transparent 1px 34px) !important; }
.sc-brand-inner { position:relative !important; z-index:2 !important; }
.sc-sprout { width:56px !important; height:56px !important; border-radius:16px !important; display:grid !important; place-items:center !important; background:rgba(255,255,255,.12) !important; border:1px solid rgba(255,255,255,.18) !important; margin-bottom:24px !important; }
.sc-brand-headline { font-size:28px !important; font-weight:800 !important; letter-spacing:-.6px !important; line-height:1.15 !important; color:white !important; margin:0 0 12px !important; }
.sc-brand-lede { font-size:14.5px !important; line-height:1.65 !important; color:rgba(255,255,255,.75) !important; margin-bottom:28px !important; }
.sc-features { display:flex !important; flex-direction:column !important; gap:10px !important; }
.sc-feat { display:flex !important; align-items:center !important; gap:12px !important; padding:12px 14px !important; border-radius:12px !important; background:rgba(255,255,255,.09) !important; border:1px solid rgba(255,255,255,.13) !important; font-size:13.5px !important; font-weight:500 !important; color:white !important; }
.sc-feat-icon { width:32px !important; height:32px !important; border-radius:9px !important; flex:none !important; display:grid !important; place-items:center !important; background:rgba(255,255,255,.13) !important; }
/* ── Responsive ── */
@media (max-width: 860px) {
    .fi-simple-layout { padding: 0 !important; background: #f0fdf4 !important; align-items: flex-start !important; }
    .fi-simple-main-ctn { max-width: none !important; }
    .sc-card-wrapper { flex-direction: column !important; border-radius: 0 !important; min-height: 0 !important; }
    .sc-brand-side { padding: 2rem 1.5rem !important; min-height: 190px !important; }
    .sc-form-side { width: 100% !important; padding: 2rem 1.5rem !important; }
    .sc-brand-headline { font-size: 22px !important; }
    .sc-features { display: none !important; }
}
</style>
CSS;
    }
}
