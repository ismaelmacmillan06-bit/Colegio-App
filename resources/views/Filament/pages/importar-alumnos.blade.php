<x-filament-panels::page>
    <div style="max-width: 600px;">

        @if(session('mensaje'))
        <div style="background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; color: #065f46; font-size: 0.9rem;">
            {{ session('mensaje') }}
        </div>
        @endif

        <div style="background: var(--color-background-primary); border: 0.5px solid var(--color-border-tertiary); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">Paso 1 — Descarga la plantilla</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-secondary); margin-bottom: 1rem;">
                Descarga el archivo CSV, ábrelo en Excel, llena los datos y guárdalo como CSV.
            </p>
            <a href="{{ route('importar.plantilla', 'alumnos') }}"
               style="display: inline-block; background: #00004E; color: white; padding: 8px 20px; border-radius: 8px; text-decoration: none; font-size: 0.85rem; font-weight: 500;">
                Descargar plantilla CSV
            </a>
        </div>

        <div style="background: var(--color-background-primary); border: 0.5px solid var(--color-border-tertiary); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">Paso 2 — Sube el archivo lleno</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-secondary); margin-bottom: 1rem;">
                Asegúrate que los grados y grupos ya existen en el sistema antes de importar alumnos.
            </p>
            <form action="{{ route('importar.csv', 'alumnos') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="archivo" accept=".csv,.txt"
                    style="display: block; margin-bottom: 1rem; font-size: 0.85rem;">
                <button type="submit"
                    style="background: #6ab04c; color: white; padding: 8px 20px; border-radius: 8px; border: none; font-size: 0.85rem; font-weight: 500; cursor: pointer;">
                    Importar Alumnos
                </button>
            </form>
        </div>

        <div style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 1rem; font-size: 0.82rem; color: #92400e;">
            <strong>Columnas del CSV:</strong> nombre, apellidos, grado, grupo, telefono_padre, telefono_madre, nombre_padre, nombre_madre
        </div>
    </div>
</x-filament-panels::page>