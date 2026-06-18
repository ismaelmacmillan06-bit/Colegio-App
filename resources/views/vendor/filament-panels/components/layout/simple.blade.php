@php
    use Filament\Support\Enums\Width;

    $livewire ??= null;
    $renderHookScopes = $livewire?->getRenderHookScopes() ?? [];
    $panelId = filament()->getId();
    $isSuperAdmin = $panelId === 'superadmin';
@endphp

<x-filament-panels::layout.base :livewire="$livewire">

{{-- ═══════════════════════════════════════
     SchoolCore — Split Login Layout
     Left: form  |  Right: brand panel
     ═══════════════════════════════════════ --}}
<div class="sc-login-wrap">

    {{-- LEFT — form side --}}
    <div class="sc-login-form-panel">

        {{-- Logo + brand --}}
        <div class="sc-login-brand">
            <div class="sc-login-brand-icon">
                @if ($isSuperAdmin) 🏛️ @else 🎓 @endif
            </div>
            <span class="sc-login-brand-name">SchoolCore</span>
            @if ($isSuperAdmin)
                <span class="sc-login-brand-tag">Super Admin</span>
            @endif
        </div>

        {{-- Heading --}}
        <div class="sc-login-heading">
            <h1>Bienvenido a<br><strong>SchoolCoreApp</strong></h1>
            <p>Sistema de seguimiento escolar.<br>Ingresa tus credenciales de acceso.</p>
        </div>

        {{-- Form card --}}
        <div class="sc-login-card">
            {{ $slot }}
        </div>

        <p class="sc-login-footer">
            © {{ date('Y') }} SchoolCoreApp · Plataforma educativa
        </p>
    </div>

    {{-- RIGHT — decorative panel --}}
    <div class="sc-login-deco-panel">
        {{-- Decorative blobs --}}
        <div class="sc-blob sc-blob-1"></div>
        <div class="sc-blob sc-blob-2"></div>
        <div class="sc-blob sc-blob-3"></div>

        <div class="sc-deco-content">
            <div class="sc-deco-icon">📚</div>
            <h2>@if($isSuperAdmin) Panel Global @else Gestión Escolar @endif</h2>
            <p>@if($isSuperAdmin) Administra colegios, planes y usuarios del sistema. @else Pase de lista, comunicación con padres y seguimiento académico en un solo lugar. @endif</p>

            <div class="sc-deco-features">
                @if($isSuperAdmin)
                    <div class="sc-feat"><span>🏫</span> Gestión de colegios</div>
                    <div class="sc-feat"><span>💳</span> Planes y suscripciones</div>
                    <div class="sc-feat"><span>📊</span> Reportes globales</div>
                @else
                    <div class="sc-feat"><span>📡</span> Registro NFC de asistencia</div>
                    <div class="sc-feat"><span>📲</span> Avisos automáticos a padres</div>
                    <div class="sc-feat"><span>📋</span> Pase de lista SEP 2022</div>
                    <div class="sc-feat"><span>🎯</span> Seguimiento académico</div>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- Scoped CSS for login only --}}
<style>
*, *::before, *::after { box-sizing: border-box; }

.sc-login-wrap {
    display: flex;
    min-height: 100vh;
    font-family: 'Inter', system-ui, sans-serif;
    background: #f0f6ff;
}

/* ── Form panel (left) ── */
.sc-login-form-panel {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 2.5rem 3rem;
    background: #fff;
    max-width: 520px;
    min-height: 100vh;
}

.sc-login-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 2.25rem;
}
.sc-login-brand-icon {
    width: 40px; height: 40px;
    background: {{ $isSuperAdmin ? '#ede9fe' : '#e0eaff' }};
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
}
.sc-login-brand-name {
    font-size: 1.15rem;
    font-weight: 700;
    color: {{ $isSuperAdmin ? '#5b21b6' : '#2563eb' }};
}
.sc-login-brand-tag {
    background: #ede9fe; color: #5b21b6;
    font-size: 0.65rem; font-weight: 700;
    padding: 2px 8px; border-radius: 100px;
    text-transform: uppercase; letter-spacing: 0.06em;
}

.sc-login-heading { margin-bottom: 1.75rem; }
.sc-login-heading h1 {
    font-size: 1.85rem;
    font-weight: 700;
    line-height: 1.25;
    color: #1e293b;
    margin: 0 0 0.6rem;
    letter-spacing: -0.02em;
}
.sc-login-heading h1 strong { color: {{ $isSuperAdmin ? '#6d28d9' : '#3b82f6' }}; }
.sc-login-heading p {
    font-size: 0.9rem;
    color: #64748b;
    line-height: 1.55;
    margin: 0;
}

.sc-login-card {
    background: #f8fbff;
    border: 1px solid #dbeafe;
    border-radius: 16px;
    padding: 1.75rem 1.5rem;
    margin-bottom: 1.5rem;
}

.sc-login-footer {
    font-size: 0.72rem;
    color: #94a3b8;
    text-align: center;
    margin: 0;
}

/* ── Deco panel (right) ── */
.sc-login-deco-panel {
    display: none;
    flex: 1;
    position: relative;
    overflow: hidden;
    {{ $isSuperAdmin
        ? 'background: linear-gradient(135deg, #4c1d95 0%, #6d28d9 40%, #a78bfa 100%);'
        : 'background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 45%, #93c5fd 100%);' }}
    align-items: center;
    justify-content: center;
}

/* Decorative blobs */
.sc-blob {
    position: absolute;
    border-radius: 50%;
    opacity: 0.18;
    filter: blur(2px);
}
.sc-blob-1 {
    width: 380px; height: 380px;
    background: rgba(255,255,255,0.6);
    top: -80px; right: -80px;
}
.sc-blob-2 {
    width: 260px; height: 260px;
    background: rgba(255,255,255,0.5);
    bottom: -60px; left: -60px;
}
.sc-blob-3 {
    width: 180px; height: 180px;
    background: rgba(255,255,255,0.4);
    bottom: 30%; right: 15%;
}

.sc-deco-content {
    position: relative; z-index: 1;
    text-align: center;
    padding: 2.5rem;
    color: white;
}
.sc-deco-icon {
    font-size: 3.5rem;
    margin-bottom: 1rem;
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
}
.sc-deco-content h2 {
    font-size: 1.75rem;
    font-weight: 800;
    margin: 0 0 0.75rem;
    letter-spacing: -0.02em;
}
.sc-deco-content p {
    font-size: 0.95rem;
    color: rgba(255,255,255,0.8);
    line-height: 1.6;
    margin: 0 0 2rem;
}
.sc-deco-features {
    display: flex; flex-direction: column; gap: 10px;
    text-align: left; max-width: 280px; margin: 0 auto;
}
.sc-feat {
    display: flex; align-items: center; gap: 10px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.875rem;
    color: rgba(255,255,255,0.92);
    font-weight: 500;
}
.sc-feat span { font-size: 1.1rem; }

/* ── Filament form style overrides inside login card ── */
.sc-login-card .fi-simple-page { background: transparent !important; box-shadow: none !important; padding: 0 !important; }
.sc-login-card .fi-simple-page-content { padding: 0 !important; }
.sc-login-card .fi-header-simple { margin-bottom: 1.25rem; }
.sc-login-card .fi-btn[type="submit"],
.sc-login-card .fi-btn-color-primary {
    width: 100% !important;
    background: {{ $isSuperAdmin ? '#6d28d9' : '#2563eb' }} !important;
    border-radius: 10px !important;
    padding: 11px !important;
    font-size: 0.95rem !important;
    font-weight: 600 !important;
    letter-spacing: 0.01em !important;
}
.sc-login-card .fi-input {
    border-radius: 8px !important;
    border-color: #bfdbfe !important;
    background: #fff !important;
}
.sc-login-card .fi-input:focus { border-color: #3b82f6 !important; box-shadow: 0 0 0 3px rgba(59,130,246,0.12) !important; }

/* ── Responsive ── */
@media (min-width: 860px) {
    .sc-login-deco-panel { display: flex !important; }
    .sc-login-form-panel { max-width: 480px; padding: 3rem 3.5rem; }
}
@media (max-width: 859px) {
    .sc-login-wrap { justify-content: center; background: #fff; }
    .sc-login-form-panel { padding: 2rem 1.5rem; max-width: 100%; }
}
</style>

{{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $renderHookScopes) }}

</x-filament-panels::layout.base>
