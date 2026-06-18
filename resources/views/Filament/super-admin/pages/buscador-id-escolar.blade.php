<x-filament-panels::page>

<div class="bid-wrap">

    {{-- Header --}}
    <div class="bid-header">
        <div class="bid-header-icon">🔍</div>
        <div>
            <h2 class="bid-title">Buscador de ID Escolar</h2>
            <p class="bid-sub">Busca cualquier alumno por nombre, apellidos o código ID a través de todos los colegios</p>
        </div>
    </div>

    {{-- Search box --}}
    <div class="bid-search-box">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="bid-search-icon" width="18" height="18">
            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
        </svg>
        <input
            wire:model.live.debounce.300ms="query"
            type="text"
            placeholder="Buscar por nombre, apellidos o código como SC2026XXXXX…"
            class="bid-input"
            autocomplete="off"
        />
    </div>

    {{-- Results --}}
    @php $resultados = $this->resultados; @endphp

    @if (strlen(trim($query)) >= 2)
        @if ($resultados->isEmpty())
            <div class="bid-empty">No se encontraron alumnos para "{{ $query }}"</div>
        @else
            <div class="bid-results-meta">{{ $resultados->count() }} resultado{{ $resultados->count() !== 1 ? 's' : '' }} encontrado{{ $resultados->count() !== 1 ? 's' : '' }}</div>
            <div class="bid-table-wrap">
                <table class="bid-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>ID Escolar</th>
                            <th>Clase</th>
                            <th>Colegio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resultados as $alumno)
                            <tr>
                                <td class="bid-nombre">{{ $alumno->nombre }}</td>
                                <td>{{ $alumno->apellidos }}</td>
                                <td>
                                    <span class="bid-codigo">{{ $alumno->codigo_alumno ?? '—' }}</span>
                                </td>
                                <td class="bid-clase">{{ $alumno->clase?->nombre ?? '—' }}</td>
                                <td class="bid-colegio">{{ $alumno->clase?->colegio?->nombre ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @else
        <div class="bid-hint">Escribe al menos 2 caracteres para buscar.</div>
    @endif

</div>

<style>
.bid-wrap { padding: 0.5rem 0; }

.bid-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 1.5rem;
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
    border: 1px solid #ddd6fe;
    border-radius: 14px;
}
.bid-header-icon { font-size: 2rem; }
.bid-title { font-size: 1.1rem; font-weight: 700; color: #4c1d95; margin: 0 0 2px; }
.bid-sub { font-size: 0.82rem; color: #7c5cba; margin: 0; }

.bid-search-box {
    position: relative;
    margin-bottom: 1.25rem;
}
.bid-search-icon {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #a78bfa;
}
.bid-input {
    width: 100%;
    padding: 11px 14px 11px 42px;
    border: 1.5px solid #ddd6fe;
    border-radius: 10px;
    font-size: 0.9rem;
    color: #1e293b;
    background: #fff;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.bid-input:focus { border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124,58,237,0.1); }

.bid-hint, .bid-empty {
    text-align: center;
    color: #94a3b8;
    padding: 2.5rem;
    font-size: 0.875rem;
}
.bid-results-meta { font-size: 0.78rem; color: #64748b; margin-bottom: 0.6rem; }

.bid-table-wrap { overflow-x: auto; border-radius: 12px; border: 1px solid #e2e8f0; }
.bid-table { width: 100%; border-collapse: collapse; }
.bid-table thead tr { background: #4c1d95; color: white; }
.bid-table thead th { padding: 9px 14px; text-align: left; font-size: 11px; font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase; }
.bid-table tbody tr { border-bottom: 1px solid #f1f5f9; }
.bid-table tbody tr:hover { background: #faf5ff; }
.bid-table tbody td { padding: 10px 14px; font-size: 0.875rem; }
.bid-nombre { font-weight: 600; color: #1e293b; }
.bid-clase, .bid-colegio { color: #64748b; font-size: 0.82rem; }
.bid-codigo {
    font-family: monospace;
    font-size: 0.85rem;
    font-weight: 700;
    color: #6d28d9;
    background: #ede9fe;
    border: 1px solid #ddd6fe;
    border-radius: 5px;
    padding: 3px 8px;
}
</style>

</x-filament-panels::page>
