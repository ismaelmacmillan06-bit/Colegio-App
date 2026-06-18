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
                <p style="font-size: 0.8rem; color: var(--color-text-secondary); margin: 0;">Ábrela en Excel, llena los datos y guárdala. Las clases deben existir antes de importar alumnos.</p>
            </div>
        </div>

        {{-- Preview de columnas --}}
        <div style="border: 1px solid var(--color-border-tertiary); border-radius: 8px; overflow: hidden; margin-bottom: 1rem; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.78rem; min-width: 600px;">
                <thead>
                    <tr style="background: #4F46E5; color: white;">
                        <th style="padding: 8px 12px; text-align: left; font-weight: 600;">nombre</th>
                        <th style="padding: 8px 12px; text-align: left; font-weight: 600;">apellidos</th>
                        <th style="padding: 8px 12px; text-align: left; font-weight: 600;">clase</th>
                        <th style="padding: 8px 12px; text-align: left; font-weight: 600;">tel_padre</th>
                        <th style="padding: 8px 12px; text-align: left; font-weight: 600;">correo_padre</th>
                        <th style="padding: 8px 12px; text-align: left; font-weight: 600;">nombre_padre</th>
                        <th style="padding: 8px 12px; text-align: left; font-weight: 600; color: rgba(255,255,255,0.6);">+ 6 más...</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="color: var(--color-text-secondary);">
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary);">Juan</td>
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary);">Pérez García</td>
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary);">1°A Primaria</td>
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary);">5512345678</td>
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary);">papa@correo.com</td>
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary);">Carlos Pérez</td>
                        <td style="padding: 7px 12px; border-top: 1px solid var(--color-border-tertiary); color: var(--color-text-tertiary);">madre, tutor...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.8rem; color: #92400e; margin-bottom: 1rem;">
            ⚠️ <strong>El nombre de la clase debe coincidir exactamente</strong> con el registrado en el sistema. Ej: <em>1°A Primaria</em>
        </div>

        <a href="{{ route('importar.plantilla', 'alumnos') }}"
           style="display: inline-flex; align-items: center; gap: 8px; background: #4F46E5; color: white; padding: 10px 22px; border-radius: 8px; text-decoration: none; font-size: 0.85rem; font-weight: 600;">
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

        <form action="{{ route('importar.csv', 'alumnos') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Drop zone --}}
            <div id="dropzone-alumnos"
                 onclick="document.getElementById('archivo-alumnos').click()"
                 style="border: 2px dashed var(--color-border-tertiary); border-radius: 10px; padding: 2rem 1.5rem; text-align: center; margin-bottom: 1rem; background: var(--color-background-secondary); cursor: pointer;">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">📊</div>
                <p id="file-label-alumnos" style="font-size: 0.85rem; color: var(--color-text-secondary); margin: 0 0 0.75rem;">Ningún archivo seleccionado</p>
                <label for="archivo-alumnos"
                    style="display: inline-flex; align-items: center; gap: 6px; background: var(--color-background-primary); border: 1px solid var(--color-border-secondary); color: var(--color-text-primary); padding: 8px 18px; border-radius: 7px; font-size: 0.82rem; font-weight: 500; cursor: pointer;">
                    📁 Seleccionar archivo XLSX
                </label>
            </div>

            <input type="file" id="archivo-alumnos" name="archivo" accept=".xlsx,.xls" style="display: none;"
                onchange="document.getElementById('file-label-alumnos').textContent = this.files[0]?.name ?? 'Ningún archivo seleccionado'">

            <button type="submit"
                style="background: #16a34a; color: white; padding: 10px 26px; border-radius: 8px; border: none; font-size: 0.875rem; font-weight: 600; cursor: pointer;">
                ✓ Importar Alumnos
            </button>
        </form>
    </div>

</div>
</x-filament-panels::page>
