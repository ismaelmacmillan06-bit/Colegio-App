<x-filament-panels::page>
<div style="max-width: 700px;">

    @if(session('mensaje'))
    <div style="background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #065f46; font-size: 0.875rem; display: flex; align-items: flex-start; gap: 10px;">
        <span style="font-size: 1.1rem;">✅</span>
        <span>{{ session('mensaje') }}</span>
    </div>
    @endif

    {{-- Paso 1 --}}
    <div style="background: var(--color-background-primary); border: 1px solid var(--color-border-tertiary); border-radius: 14px; padding: 1.5rem; margin-bottom: 1.25rem;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 1rem;">
            <div style="width: 32px; height: 32px; background: #4F46E5; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0;">1</div>
            <div>
                <h3 style="font-size: 0.95rem; font-weight: 600; margin: 0;">Descarga la plantilla</h3>
                <p style="font-size: 0.8rem; color: var(--color-text-secondary); margin: 0;">Ábrela en Excel, llena los datos y guárdala sin cambiar el formato.</p>
            </div>
        </div>

        {{-- Preview de columnas --}}
        <div style="border: 1px solid var(--color-border-tertiary); border-radius: 8px; overflow: hidden; margin-bottom: 1rem; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.78rem; min-width: 520px;">
                <thead>
                    <tr style="background: #4F46E5; color: white;">
                        <th style="padding: 8px 12px; text-align: left; font-weight: 600;">nombre</th>
                        <th style="padding: 8px 12px; text-align: left; font-weight: 600;">apellidos</th>
                        <th style="padding: 8px 12px; text-align: left; font-weight: 600;">tipo</th>
                        <th style="padding: 8px 12px; text-align: left; font-weight: 600;">telefono</th>
                        <th style="padding: 8px 12px; text-align: left; font-weight: 600;">materias</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="color: var(--color-text-secondary);">
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary);">María</td>
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary);">López Ruiz</td>
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary);">titular</td>
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary);">5512345678</td>
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary);">Español,Inglés</td>
                    </tr>
                    <tr style="color: var(--color-text-secondary); opacity: 0.55;">
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary);">Carlos</td>
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary);">Soto Vega</td>
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary);">especialista</td>
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary);">5598765432</td>
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary);">Matemáticas</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Tipos válidos --}}
        <div style="background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.78rem; color: #3730a3; margin-bottom: 1rem;">
            <strong>Tipos válidos:</strong> <code>titular</code> · <code>especialista</code> · <code>extracurricular</code> · <code>directivo</code><br>
            <strong>Materias:</strong> Separa varias con comas. Ej: <code>Español,Inglés,Artes</code>
        </div>

        <a href="{{ route('importar.plantilla', 'docentes') }}"
           style="display: inline-flex; align-items: center; gap: 8px; background: #4F46E5; color: white; padding: 10px 22px; border-radius: 8px; text-decoration: none; font-size: 0.85rem; font-weight: 600; cursor: pointer; border: none;">
            ⬇️ Descargar plantilla XLSX
        </a>
    </div>

    {{-- Paso 2 --}}
    <div style="background: var(--color-background-primary); border: 1px solid var(--color-border-tertiary); border-radius: 14px; padding: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 1rem;">
            <div style="width: 32px; height: 32px; background: #4F46E5; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0;">2</div>
            <div>
                <h3 style="font-size: 0.95rem; font-weight: 600; margin: 0;">Sube el archivo completado</h3>
                <p style="font-size: 0.8rem; color: var(--color-text-secondary); margin: 0;">Formato XLSX · Máximo 5 MB</p>
            </div>
        </div>

        <form action="{{ route('importar.csv', 'docentes') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Drop zone --}}
            <div id="dropzone-docentes"
                 onclick="document.getElementById('archivo-docentes').click()"
                 style="border: 2px dashed var(--color-border-tertiary); border-radius: 10px; padding: 2rem 1.5rem; text-align: center; margin-bottom: 1rem; background: var(--color-background-secondary); cursor: pointer; transition: border-color 0.15s;">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">📊</div>
                <p id="file-label-docentes" style="font-size: 0.85rem; color: var(--color-text-secondary); margin: 0 0 0.75rem;">Ningún archivo seleccionado</p>
                <label for="archivo-docentes"
                    style="display: inline-flex; align-items: center; gap: 6px; background: var(--color-background-primary); border: 1px solid var(--color-border-secondary); color: var(--color-text-primary); padding: 8px 18px; border-radius: 7px; font-size: 0.82rem; font-weight: 500; cursor: pointer;">
                    📁 Seleccionar archivo XLSX
                </label>
            </div>

            <input type="file" id="archivo-docentes" name="archivo" accept=".xlsx,.xls" style="display: none;"
                onchange="document.getElementById('file-label-docentes').textContent = this.files[0]?.name ?? 'Ningún archivo seleccionado'">

            <button type="submit"
                style="background: #16a34a; color: white; padding: 10px 26px; border-radius: 8px; border: none; font-size: 0.875rem; font-weight: 600; cursor: pointer;">
                ✓ Importar Docentes
            </button>
        </form>
    </div>

    {{-- Nota de orden --}}
    <div style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.8rem; color: #92400e; margin-top: 1rem;">
        ⚠️ <strong>Orden recomendado:</strong> 1° Crear Clases → 2° Importar Docentes → 3° Importar Alumnos.<br>
        Las clases se asignan manualmente desde el detalle de cada clase.
    </div>

</div>
</x-filament-panels::page>
