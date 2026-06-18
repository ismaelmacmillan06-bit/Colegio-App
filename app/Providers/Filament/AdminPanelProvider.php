<?php

namespace App\Providers\Filament;

use App\Http\Middleware\EnsureColegioAdmin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\Dashboard;
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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::hex('#4F46E5'),
            ])
            ->font('Inter', url: 'https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap')
            ->brandName('SchoolCore')
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            // Critical overrides via inline <style> — fires after @filamentStyles, never depends on Vite
            ->renderHook(
                'panels::styles.after',
                fn (): HtmlString => new HtmlString($this->getInlineCriticalCss())
            )
            // Vite assets (fonts, Tailwind utilities for custom pages)
            ->renderHook(
                'panels::head.end',
                fn (): HtmlString => new HtmlString(
                    Blade::render('@vite(["resources/css/app.css"])') .
                    '<link rel="manifest" href="/manifest.json">' .
                    '<meta name="theme-color" content="#1a1854">' .
                    '<meta name="mobile-web-app-capable" content="yes">' .
                    '<meta name="apple-mobile-web-app-capable" content="yes">'
                )
            )
            ->renderHook(
                'panels::body.end',
                fn (): HtmlString => new HtmlString(
                    '<script>if("serviceWorker"in navigator){navigator.serviceWorker.register("/sw.js").catch(function(){})}</script>'
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
                EnsureColegioAdmin::class,
            ]);
    }

    private function getInlineCriticalCss(): string
    {
        return <<<'CSS'
<style>
/* ═══════════════════════════════════
   SchoolCore — Critical Theme CSS
   Inline: no Vite dependency
   ═══════════════════════════════════ */

/* Inter font */
:root { --font-family: 'Inter', ui-sans-serif, system-ui, sans-serif !important; }

/* ── Sidebar ── */
.fi-sidebar {
    background: linear-gradient(160deg, #1a1854 0%, #0d0c35 100%) !important;
    border-right: none !important;
    box-shadow: 2px 0 20px rgba(0,0,0,0.3) !important;
}
.fi-sidebar-header {
    background: transparent !important;
    border-bottom: 1px solid rgba(255,255,255,0.07) !important;
}
.fi-sidebar-header .fi-logo,
.fi-sidebar-header .fi-logo *,
.fi-sidebar-header a,
.fi-sidebar-header a *,
.fi-sidebar-header span { color: white !important; fill: white !important; }

/* Nav scroll */
.fi-sidebar-nav { padding: 1.25rem 0.5rem !important; }
.fi-sidebar-nav::-webkit-scrollbar { width: 3px; }
.fi-sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 2px; }

/* ── Group labels ── */
.fi-sidebar-group-btn {
    padding: 0.875rem 0.75rem 0.25rem !important;
    display: flex !important;
    align-items: center !important;
}
.fi-sidebar-group-label {
    font-size: 10px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.12em !important;
    color: rgba(255,255,255,0.3) !important;
    flex: 1 !important;
}
.fi-sidebar-group-collapse-btn svg,
.fi-icon-btn svg { color: rgba(255,255,255,0.25) !important; width: 12px !important; height: 12px !important; }

/* ── Nav items — fi-sidebar-item-btn (NOT fi-sidebar-item-button) ── */
.fi-sidebar-item-btn {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 10px !important;
    padding: 10px 14px !important;
    margin: 1px 6px !important;
    border-radius: 8px !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    line-height: 1.4 !important;
    color: rgba(255,255,255,0.72) !important;
    text-decoration: none !important;
    transition: background-color 0.12s ease, color 0.12s ease !important;
    background-color: transparent !important;
}
.fi-sidebar-item-btn:hover {
    background-color: rgba(255,255,255,0.09) !important;
    color: rgba(255,255,255,0.95) !important;
}
/* Active: fi-active is on <li>, item-btn is child */
.fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
    background-color: rgba(255,255,255,0.13) !important;
    color: white !important;
    border-left: 3px solid rgba(255,255,255,0.65) !important;
    padding-left: 11px !important;
}

/* Icons inside nav items */
.fi-sidebar-item-btn > .fi-icon,
.fi-sidebar-item-btn .fi-sidebar-item-icon {
    width: 18px !important;
    height: 18px !important;
    flex-shrink: 0 !important;
    color: rgba(255,255,255,0.45) !important;
    opacity: 1 !important;
}
.fi-sidebar-item-btn:hover > .fi-icon,
.fi-sidebar-item-btn:hover .fi-sidebar-item-icon,
.fi-sidebar-item.fi-active > .fi-sidebar-item-btn > .fi-icon,
.fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon {
    color: rgba(255,255,255,0.9) !important;
}

/* Item label text */
.fi-sidebar-item-label {
    font-size: 14px !important;
    font-weight: 500 !important;
    color: inherit !important;
    flex: 1 !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
}

/* Remove tree lines — kill grouped border elements */
.fi-sidebar-item-grouped-border,
.fi-sidebar-item-grouped-border-part,
.fi-sidebar-item-grouped-border-part-not-first,
.fi-sidebar-item-grouped-border-part-not-last { display: none !important; }

/* Sub-items: indent */
.fi-sidebar-sub-group-items .fi-sidebar-item-btn { padding-left: 36px !important; }

/* ── Topbar ── */
.fi-topbar nav, .fi-topbar {
    background-color: #ffffff !important;
    border-bottom: 1px solid #e8edf4 !important;
    box-shadow: 0 1px 6px rgba(0,0,0,0.06) !important;
}

/* ── Main area ── */
.fi-main, .fi-body { background-color: #f0f4f9 !important; }

/* ── Page heading ── */
.fi-header-heading {
    font-weight: 700 !important;
    font-size: 1.4rem !important;
    color: #0f172a !important;
}

/* ── Cards ── */
.fi-section {
    border-radius: 1rem !important;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05), 0 2px 8px rgba(0,0,0,0.03) !important;
}

/* ── Login page ── */
.fi-simple-layout { background: #f0f4f9 !important; }

/* ── Sidebar toggle button ── */
.fi-layout-sidebar-toggle-btn { color: rgba(255,255,255,0.5) !important; }
</style>
CSS;
    }
}
