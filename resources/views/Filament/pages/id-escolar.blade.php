<x-filament-panels::page>

<div class="id-escolar-wrap">

    {{-- Header --}}
    <div class="ide-header">
        <div class="ide-header-icon">🎓</div>
        <div>
            <h2 class="ide-header-title">ID Escolar por Clase</h2>
            <p class="ide-header-sub">Genera el PDF con nombres e identificadores únicos por grupo</p>
        </div>
    </div>

    {{-- Class cards grid --}}
    @php $clases = $this->clases; @endphp

    @if ($clases->isEmpty())
        <div class="ide-empty">No hay clases activas registradas.</div>
    @else
        <div class="ide-grid">
            @foreach ($clases as $clase)
                <div class="ide-card">
                    <div class="ide-card-top">
                        <div class="ide-card-icon">📚</div>
                        <div class="ide-card-info">
                            <span class="ide-card-name">{{ $clase->nombre }}</span>
                            <span class="ide-card-count">{{ $clase->alumnos_count }} alumno{{ $clase->alumnos_count !== 1 ? 's' : '' }}</span>
                        </div>
                    </div>

                    <a
                        href="{{ route('id-escolar.pdf', $clase->id) }}"
                        target="_blank"
                        class="ide-btn"
                        @if($clase->alumnos_count === 0) style="pointer-events:none;opacity:0.45;" @endif
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="15" height="15"><path fill-rule="evenodd" d="M4.5 2A1.5 1.5 0 003 3.5v13A1.5 1.5 0 004.5 18h11a1.5 1.5 0 001.5-1.5V7.621a1.5 1.5 0 00-.44-1.06l-4.12-4.122A1.5 1.5 0 0011.378 2H4.5zm2.25 8.5a.75.75 0 000 1.5h6.5a.75.75 0 000-1.5h-6.5zm0 3a.75.75 0 000 1.5h6.5a.75.75 0 000-1.5h-6.5zm0-6a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd" /></svg>
                        Generar PDF
                    </a>
                </div>
            @endforeach
        </div>
    @endif

</div>

<style>
.id-escolar-wrap { padding: 0.5rem 0; }

.ide-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 1.75rem;
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, #e8f0fe 0%, #f0f4ff 100%);
    border: 1px solid #c7d7fc;
    border-radius: 14px;
}
.ide-header-icon { font-size: 2rem; }
.ide-header-title { font-size: 1.1rem; font-weight: 700; color: #1e3a8a; margin: 0 0 2px; }
.ide-header-sub { font-size: 0.82rem; color: #5b7db1; margin: 0; }

.ide-empty { text-align:center; color:#94a3b8; padding: 3rem; }

.ide-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1rem;
}

.ide-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1.1rem 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    transition: box-shadow 0.15s;
}
.ide-card:hover { box-shadow: 0 4px 14px rgba(37,99,235,0.1); border-color: #bfdbfe; }

.ide-card-top {
    display: flex;
    align-items: center;
    gap: 10px;
}
.ide-card-icon {
    width: 38px; height: 38px;
    background: #eff6ff;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
}
.ide-card-info { display: flex; flex-direction: column; min-width: 0; }
.ide-card-name { font-size: 0.9rem; font-weight: 700; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ide-card-count { font-size: 0.75rem; color: #64748b; margin-top: 1px; }

.ide-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: #2563eb;
    color: #fff;
    font-size: 0.82rem;
    font-weight: 600;
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.15s;
}
.ide-btn:hover { background: #1d4ed8; }
</style>

</x-filament-panels::page>
