@php
    $livewire ??= null;
    $renderHookScopes = $livewire?->getRenderHookScopes() ?? [];
    $panelId = filament()->getId();
    $isSuperAdmin = $panelId === 'superadmin';
    $isDocente    = $panelId === 'docente';
@endphp

<x-filament-panels::layout.base :livewire="$livewire">

<style>
*, *::before, *::after { box-sizing: border-box; }
body { margin: 0; padding: 0; background: transparent !important; }

/* ══ Outer background con aurora radial ══ */
.sc-page {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 2rem;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    position: relative;
    isolation: isolate;
    background:
        radial-gradient(ellipse 110% 70% at 15% 12%, {{ $isSuperAdmin ? 'rgba(6,95,70,.12)' : 'rgba(34,197,94,.12)' }}, transparent 58%),
        radial-gradient(ellipse  90% 80% at 85% 88%, {{ $isSuperAdmin ? 'rgba(4,120,87,.09)' : 'rgba(16,185,129,.09)' }}, transparent 55%),
        {{ $isSuperAdmin ? '#e9f4ed' : '#ecfdf5' }};
}

/* Halo izquierdo — detrás del panel formulario */
.sc-page::before {
    content: '';
    position: absolute;
    inset: 0;
    z-index: -1;
    pointer-events: none;
    background: radial-gradient(ellipse 55% 70% at 22% 50%, {{ $isSuperAdmin ? 'rgba(6,95,70,.22)' : 'rgba(34,197,94,.20)' }}, transparent 68%);
    filter: blur(60px);
}

/* Halo derecho — detrás del panel de marca */
.sc-page::after {
    content: '';
    position: absolute;
    inset: 0;
    z-index: -1;
    pointer-events: none;
    background: radial-gradient(ellipse 50% 65% at 78% 50%, {{ $isSuperAdmin ? 'rgba(4,120,87,.18)' : 'rgba(16,185,129,.15)' }}, transparent 65%);
    filter: blur(60px);
}

/* ══ Card — más ancha, sombra con tinte verde ══ */
.sc-card {
    display: flex;
    align-items: stretch;
    width: 100%;
    max-width: 1100px;
    min-height: 620px;
    border-radius: 24px;
    overflow: hidden;
    position: relative;
    z-index: 1;
    box-shadow:
        0 32px 80px -20px rgba(6,78,59,.28),
        0 0 0 1px rgba(6,78,59,.06),
        0 4px 14px rgba(0,0,0,0.05);
}

/* ══ Form side — LEFT ══ */
.sc-form {
    flex-shrink: 0;
    width: 460px;
    background: #ffffff;
    padding: 4rem 3.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Strip Filament page wrappers inside form */
.sc-form .fi-simple-page { width: 100% !important; padding: 0 !important; display: block !important; }
.sc-form .fi-simple-page-content { width: 100% !important; padding: 0 !important; display: block !important; }
.sc-form .fi-section,
.sc-form .fi-schema-section { background: transparent !important; border: none !important; box-shadow: none !important; border-radius: 0 !important; }
.sc-form .fi-section-content,
.sc-form .fi-section-content-ctn { padding: 0 !important; }
.sc-form .fi-section-footer { padding: 0 !important; border-top: none !important; background: transparent !important; }

/* ── Inputs: borde definido, fondo blanco, focus ring verde ── */
.sc-form .fi-input-wrp {
    background: #ffffff !important;
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 12px !important;
    box-shadow: none !important;
    transition: border-color .2s, box-shadow .2s !important;
}
.sc-form .fi-input-wrp:focus-within {
    border-color: {{ $isSuperAdmin ? '#059669' : '#22c55e' }} !important;
    box-shadow: 0 0 0 4px {{ $isSuperAdmin ? 'rgba(6,95,70,.14)' : 'rgba(34,197,94,.18)' }} !important;
}
.sc-form .fi-input {
    background: transparent !important;
    font-size: 14.5px !important;
    color: #0f1f17 !important;
}

/* ── Botón submit: más alto, bold, glow verde (admin Y super admin) ── */
.sc-form button[type="submit"],
.sc-form .fi-btn[type="submit"] {
    width: 100% !important;
    padding: 14px !important;
    font-size: 15px !important;
    font-weight: 700 !important;
    letter-spacing: -.1px !important;
    border-radius: 12px !important;
    background: {{ $isSuperAdmin
        ? 'linear-gradient(135deg, #059669, #064e3b)'
        : 'linear-gradient(135deg, #22c55e, #15803d)' }} !important;
    color: #ffffff !important;
    border: none !important;
    box-shadow: {{ $isSuperAdmin
        ? '0 14px 26px -12px rgba(6,78,59,.55)'
        : '0 14px 26px -12px rgba(21,128,61,.50)' }} !important;
    transition: transform .15s ease, box-shadow .25s !important;
    cursor: pointer !important;
}
.sc-form button[type="submit"]:hover,
.sc-form .fi-btn[type="submit"]:hover {
    transform: translateY(-2px) !important;
    box-shadow: {{ $isSuperAdmin
        ? '0 20px 34px -14px rgba(6,78,59,.60)'
        : '0 20px 34px -14px rgba(21,128,61,.55)' }} !important;
}
.sc-form button[type="submit"]:active,
.sc-form .fi-btn[type="submit"]:active { transform: translateY(0) !important; }

/* ══ Brand side — RIGHT ══ */
.sc-brand {
    flex: 1 1 0;
    background: {{ $isSuperAdmin
        ? 'linear-gradient(150deg, #065f46, #064e3b 55%, #022c22)'
        : 'linear-gradient(150deg, #16a34a, #15803d 55%, #166534)' }};
    padding: 3rem 3.5rem;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.sc-b { position: absolute; border-radius: 50%; filter: blur(2px); pointer-events: none; }
.sc-b1 {
    width: 340px; height: 340px; top: -90px; right: -70px;
    background: radial-gradient(circle, rgba(255,255,255,{{ $isSuperAdmin ? '.18' : '.22' }}), transparent 70%);
}
.sc-b2 {
    width: 260px; height: 260px; bottom: -60px; left: -60px;
    background: radial-gradient(circle, rgba(255,255,255,{{ $isSuperAdmin ? '.12' : '.16' }}), transparent 70%);
}

.sc-brand-inner { position: relative; z-index: 2; }

/* Ícono más grande: 64×64 ── */
.sc-ico {
    width: 64px; height: 64px; border-radius: 18px;
    display: grid; place-items: center;
    background: rgba(255,255,255,{{ $isSuperAdmin ? '.12' : '.14' }});
    border: 1px solid rgba(255,255,255,{{ $isSuperAdmin ? '.18' : '.22' }});
    margin-bottom: 28px;
}
.sc-h2 {
    font-size: 28px; font-weight: 800; letter-spacing: -.6px;
    line-height: 1.15; color: white; margin: 0 0 12px;
}
.sc-lede {
    font-size: 14.5px; line-height: 1.65;
    color: rgba(255,255,255,{{ $isSuperAdmin ? '.75' : '.78' }});
    margin-bottom: 28px;
}
/* Pills con más separación ── */
.sc-feats { display: flex; flex-direction: column; gap: 14px; }
.sc-feat {
    display: flex; align-items: center; gap: 12px;
    padding: 13px 15px; border-radius: 13px;
    background: rgba(255,255,255,{{ $isSuperAdmin ? '.09' : '.11' }});
    border: 1px solid rgba(255,255,255,{{ $isSuperAdmin ? '.13' : '.16' }});
    font-size: 13.5px; font-weight: 500; color: white;
}
.sc-fi {
    width: 34px; height: 34px; border-radius: 10px;
    flex: none; display: grid; place-items: center;
    background: rgba(255,255,255,{{ $isSuperAdmin ? '.13' : '.15' }});
}

/* ══ Responsive ══ */
@media (max-width: 900px) {
    .sc-page {
        padding: 0;
        align-items: stretch;
        min-height: 100dvh;
        background: {{ $isSuperAdmin ? '#e9f4ed' : '#f0fdf4' }};
    }
    .sc-page::before, .sc-page::after { display: none; }

    .sc-card {
        flex-direction: column;
        border-radius: 0;
        min-height: 100dvh;
        box-shadow: none;
        width: 100%;
    }

    /* Marca va ARRIBA en mobile — strip compacto */
    .sc-brand {
        order: -1;
        flex: none;
        padding: 2rem 1.75rem 1.5rem;
        min-height: auto;
    }
    .sc-ico { width: 44px; height: 44px; border-radius: 14px; margin-bottom: 12px; }
    .sc-h2 { font-size: 19px; margin-bottom: 0; }
    .sc-lede { display: none; }
    .sc-feats { display: none; }

    /* Formulario crece para llenar el resto */
    .sc-form {
        flex: 1;
        width: 100%;
        padding: 2rem 1.5rem 2.5rem;
        justify-content: flex-start;
    }

    /* Ocultar logo duplicado en formulario (ya aparece en el strip de arriba) */
    .sc-form-logo { display: none !important; }
}

@media (prefers-reduced-motion: reduce) {
    * { animation: none !important; transition: none !important; }
    .sc-page::before, .sc-page::after { filter: none !important; }
}
</style>

<div class="sc-page">
<div class="sc-card">

    {{-- ══ Panel formulario (IZQUIERDA) ══ --}}
    <div class="sc-form">

        {{-- Logo --}}
        <div class="sc-form-logo" style="display:flex;align-items:center;gap:10px;margin-bottom:32px;">
            @if($isSuperAdmin)
            <div style="width:36px;height:36px;border-radius:10px;display:grid;place-items:center;flex-shrink:0;background:linear-gradient(135deg,#059669,#064e3b);box-shadow:0 6px 16px -6px rgba(6,78,59,.45);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M4 20V9l8-5 8 5v11" stroke="white" stroke-width="1.8" stroke-linejoin="round"/>
                    <path d="M9 20v-4h6v4" stroke="white" stroke-width="1.8"/>
                </svg>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <span style="font-weight:800;font-size:17px;letter-spacing:-.3px;color:#0f172a;">SchoolCoreApp</span>
                <span style="font-size:10px;font-weight:700;letter-spacing:.7px;text-transform:uppercase;padding:3px 7px;border-radius:6px;background:rgba(6,78,59,.1);color:#065f46;">Super Admin</span>
            </div>
            @elseif($isDocente)
            <div style="width:36px;height:36px;border-radius:10px;display:grid;place-items:center;flex-shrink:0;background:linear-gradient(135deg,#22c55e,#15803d);box-shadow:0 6px 16px -6px rgba(21,128,61,.45);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <span style="font-weight:800;font-size:17px;letter-spacing:-.3px;color:#0f172a;">SchoolCoreApp</span>
                <span style="font-size:10px;font-weight:700;letter-spacing:.7px;text-transform:uppercase;padding:3px 7px;border-radius:6px;background:rgba(21,128,61,.1);color:#15803d;">Docente</span>
            </div>
            @else
            <div style="width:36px;height:36px;border-radius:10px;display:grid;place-items:center;flex-shrink:0;background:linear-gradient(135deg,#22c55e,#15803d);box-shadow:0 6px 16px -6px rgba(21,128,61,.45);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M3 9l9-5 9 5-9 5-9-5z" fill="white"/>
                    <path d="M7 11.5V16c0 1 2.2 2.5 5 2.5s5-1.5 5-2.5v-4.5" stroke="white" stroke-width="1.7" stroke-linecap="round"/>
                </svg>
            </div>
            <span style="font-weight:800;font-size:17px;letter-spacing:-.3px;color:#0f172a;">SchoolCoreApp</span>
            @endif
        </div>

        {{-- Heading --}}
        <div style="margin-bottom:24px;">
            @if($isSuperAdmin)
            <h1 style="font-size:26px;font-weight:800;letter-spacing:-.5px;line-height:1.1;color:#0f1f17;margin:0 0 7px;">Panel Global</h1>
            <p style="color:#5b6b62;font-size:14px;margin:0;line-height:1.5;">Acceso exclusivo para super administradores.</p>
            @elseif($isDocente)
            <h1 style="font-size:26px;font-weight:800;letter-spacing:-.5px;line-height:1.1;color:#0f1f17;margin:0 0 7px;">Portal Docente</h1>
            <p style="color:#5b6b62;font-size:14px;margin:0;line-height:1.5;">Ingresa para acceder a tus clases y actividades.</p>
            @else
            <h1 style="font-size:26px;font-weight:800;letter-spacing:-.5px;line-height:1.1;color:#0f1f17;margin:0 0 7px;">Iniciar sesión</h1>
            <p style="color:#5b6b62;font-size:14px;margin:0;line-height:1.5;">Ingresa tus credenciales para entrar a tu colegio.</p>
            @endif
        </div>

        {{-- Formulario de Filament --}}
        {{ $slot }}

        <div style="margin-top:1.75rem;font-size:11.5px;color:#a0adb5;">
            &copy; {{ date('Y') }} SchoolCoreApp &middot; Plataforma educativa
        </div>

    </div>

    {{-- ══ Panel marca (DERECHA) ══ --}}
    <div class="sc-brand">
        <div class="sc-b sc-b1"></div>
        <div class="sc-b sc-b2"></div>
        <div class="sc-brand-inner">

            <div class="sc-ico">
                @if($isSuperAdmin)
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                    <path d="M4 20V9l8-5 8 5v11" stroke="#fff" stroke-width="1.7" stroke-linejoin="round"/>
                    <path d="M9 20v-5h6v5" stroke="#fff" stroke-width="1.7"/>
                </svg>
                @elseif($isDocente)
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                    <path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0" stroke="#fff" stroke-width="1.7" stroke-linecap="round"/>
                </svg>
                @else
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                    <path d="M12 21v-8" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M12 13c0-3 2.4-5 6-5 0 3.2-2.6 5-6 5z" fill="rgba(255,255,255,.9)"/>
                    <path d="M12 14c0-2.6-2.2-4.4-5.4-4.4C6.6 12.3 9 14 12 14z" fill="rgba(255,255,255,.65)"/>
                </svg>
                @endif
            </div>

            @if($isSuperAdmin)
            <h2 class="sc-h2">Administra todos los<br>colegios desde aquí.</h2>
            <p class="sc-lede">Colegios, planes, suscripciones y usuarios del sistema, todo bajo un mismo control.</p>
            @elseif($isDocente)
            <h2 class="sc-h2">Tu espacio<br>de trabajo docente.</h2>
            <p class="sc-lede">Gestiona tus clases, registra actividades y marca tu asistencia desde un solo lugar.</p>
            @else
            <h2 class="sc-h2">Toda la gestión escolar<br>en un solo lugar.</h2>
            <p class="sc-lede">Pase de lista, colegiaturas, credenciales y comunicados desde un solo panel profesional.</p>
            @endif

            <div class="sc-feats">
                @if($isSuperAdmin)
                <div class="sc-feat">
                    <span class="sc-fi"><svg width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="M4 20V9l8-5 8 5v11" stroke="#fff" stroke-width="1.8" stroke-linejoin="round"/><path d="M9 20v-4h6v4" stroke="#fff" stroke-width="1.8"/></svg></span>
                    Gestión de colegios
                </div>
                <div class="sc-feat">
                    <span class="sc-fi"><svg width="17" height="17" viewBox="0 0 24 24" fill="none"><rect x="3" y="6" width="18" height="12" rx="2.5" stroke="#fff" stroke-width="1.7"/><path d="M3 10h18" stroke="#fff" stroke-width="1.7"/></svg></span>
                    Planes y suscripciones
                </div>
                <div class="sc-feat">
                    <span class="sc-fi"><svg width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="M4 19V5M4 19h16" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/><path d="M8 15v-4M12 15V8M16 15v-6" stroke="#fff" stroke-width="1.9" stroke-linecap="round"/></svg></span>
                    Reportes globales
                </div>
                <div class="sc-feat">
                    <span class="sc-fi"><svg width="17" height="17" viewBox="0 0 24 24" fill="none"><circle cx="9" cy="8" r="3" stroke="#fff" stroke-width="1.7"/><path d="M3 20c0-3.2 2.7-5 6-5s6 1.8 6 5" stroke="#fff" stroke-width="1.7" stroke-linecap="round"/><path d="M16 6.5a3 3 0 0 1 0 5.5M18 20c0-2.4-1-3.8-2.4-4.6" stroke="#fff" stroke-width="1.7" stroke-linecap="round"/></svg></span>
                    Usuarios del sistema
                </div>
                @elseif($isDocente)
                <div class="sc-feat">
                    <span class="sc-fi"><svg width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                    Marca tu entrada con un clic
                </div>
                <div class="sc-feat">
                    <span class="sc-fi"><svg width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.63 48.63 0 0112 20.904a48.63 48.63 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342" stroke="#fff" stroke-width="1.7" stroke-linecap="round"/></svg></span>
                    Solo tus clases asignadas
                </div>
                <div class="sc-feat">
                    <span class="sc-fi"><svg width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/></svg></span>
                    Tareas, exámenes y proyectos
                </div>
                @else
                <div class="sc-feat">
                    <span class="sc-fi"><svg width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="M5 12l4 4 10-10" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    Pase de lista diario
                </div>
                <div class="sc-feat">
                    <span class="sc-fi"><svg width="17" height="17" viewBox="0 0 24 24" fill="none"><rect x="3" y="6" width="18" height="12" rx="2.5" stroke="#fff" stroke-width="1.7"/><path d="M3 10h18" stroke="#fff" stroke-width="1.7"/></svg></span>
                    Colegiaturas y becas
                </div>
                <div class="sc-feat">
                    <span class="sc-fi"><svg width="17" height="17" viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="14" rx="2.5" stroke="#fff" stroke-width="1.7"/><path d="M7 9h2M13 9h2M7 13h6" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/></svg></span>
                    Credenciales digitales
                </div>
                <div class="sc-feat">
                    <span class="sc-fi"><svg width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="M4 19V5M4 19h16" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/><path d="M8 15v-4M12 15V8M16 15v-6" stroke="#fff" stroke-width="1.9" stroke-linecap="round"/></svg></span>
                    Dashboard en tiempo real
                </div>
                @endif
            </div>

        </div>
    </div>

</div>
</div>

{{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $renderHookScopes) }}

</x-filament-panels::layout.base>
