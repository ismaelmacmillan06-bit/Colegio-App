<x-filament-panels::page>
<div style="max-width: 700px;">

    <div style="background: linear-gradient(135deg, #1a1a2e, #0f4c96); border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; color: white;">
        <h2 style="font-size: 1.1rem; font-weight: 700; margin: 0 0 6px;">Lector NFC en espera</h2>
        <p style="font-size: 0.85rem; opacity: 0.8; margin: 0;">Selecciona una persona y acerca la credencial al lector</p>
    </div>

    {{-- SELECTOR --}}
    <div style="background: var(--color-background-primary); border: 1px solid #c3e3fd; border-radius: 14px; padding: 1.5rem; margin-bottom: 1rem;">
        <div style="margin-bottom: 1rem;">
            <label style="font-size: 0.82rem; font-weight: 600; color: var(--color-text-secondary); display: block; margin-bottom: 6px;">Tipo de persona</label>
            <div style="display: flex; gap: 0.75rem;">
                <button onclick="setTipo('alumno')" id="btn-alumno"
                    style="flex: 1; padding: 10px; border-radius: 10px; border: 2px solid #2196f3; background: #2196f3; color: white; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                    🎒 Alumno
                </button>
                <button onclick="setTipo('docente')" id="btn-docente"
                    style="flex: 1; padding: 10px; border-radius: 10px; border: 2px solid #c3e3fd; background: transparent; color: #2196f3; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                    👨‍🏫 Docente
                </button>
            </div>
        </div>

        <div style="margin-bottom: 1rem;">
            <label style="font-size: 0.82rem; font-weight: 600; color: var(--color-text-secondary); display: block; margin-bottom: 6px;">Buscar persona</label>
            <input type="text" id="buscador" placeholder="Escribe el nombre..."
                oninput="buscarPersona()"
                style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #c3e3fd; font-size: 0.9rem; box-sizing: border-box;">
        </div>

        <div id="resultados" style="display: none; max-height: 200px; overflow-y: auto; border: 1px solid #c3e3fd; border-radius: 10px; margin-bottom: 1rem;"></div>

        <div id="seleccionado" style="display: none; background: #e8f5ff; border: 2px solid #2196f3; border-radius: 10px; padding: 1rem; margin-bottom: 1rem;">
            <div style="font-size: 0.75rem; color: #6eb8f5; font-weight: 600; margin-bottom: 4px;">PERSONA SELECCIONADA</div>
            <div id="nombre-seleccionado" style="font-size: 1rem; font-weight: 700; color: #0f4c96;"></div>
            <div id="info-seleccionado" style="font-size: 0.8rem; color: #6eb8f5;"></div>
        </div>

        <button onclick="iniciarLectura()" id="btn-leer"
            style="width: 100%; padding: 14px; border-radius: 12px; border: none; background: linear-gradient(135deg, #1a1a2e, #0f4c96); color: white; font-size: 0.95rem; font-weight: 700; cursor: pointer; opacity: 0.5;" disabled>
            📡 Acercar credencial al lector
        </button>
    </div>

    {{-- ESTADO --}}
    <div id="estado" style="display: none; border-radius: 14px; padding: 1.25rem; text-align: center; margin-bottom: 1rem;">
        <div id="estado-icono" style="font-size: 2.5rem; margin-bottom: 8px;"></div>
        <div id="estado-titulo" style="font-size: 1rem; font-weight: 700; margin-bottom: 4px;"></div>
        <div id="estado-desc" style="font-size: 0.85rem; opacity: 0.8;"></div>
    </div>

</div>

<script>
let tipoSeleccionado = 'alumno';
let personaSeleccionada = null;
let leyendo = false;
let intervalLectura = null;

function setTipo(tipo) {
    tipoSeleccionado = tipo;
    personaSeleccionada = null;
    document.getElementById('seleccionado').style.display = 'none';
    document.getElementById('buscador').value = '';
    document.getElementById('resultados').style.display = 'none';
    actualizarBotonLeer();

    if (tipo === 'alumno') {
        document.getElementById('btn-alumno').style.background = '#2196f3';
        document.getElementById('btn-alumno').style.color = 'white';
        document.getElementById('btn-alumno').style.borderColor = '#2196f3';
        document.getElementById('btn-docente').style.background = 'transparent';
        document.getElementById('btn-docente').style.color = '#2196f3';
        document.getElementById('btn-docente').style.borderColor = '#c3e3fd';
    } else {
        document.getElementById('btn-docente').style.background = '#2196f3';
        document.getElementById('btn-docente').style.color = 'white';
        document.getElementById('btn-docente').style.borderColor = '#2196f3';
        document.getElementById('btn-alumno').style.background = 'transparent';
        document.getElementById('btn-alumno').style.color = '#2196f3';
        document.getElementById('btn-alumno').style.borderColor = '#c3e3fd';
    }
}

function buscarPersona() {
    const q = document.getElementById('buscador').value;
    if (q.length < 2) {
        document.getElementById('resultados').style.display = 'none';
        return;
    }

    fetch(`/api/buscar-persona?tipo=${tipoSeleccionado}&q=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(data => {
            const div = document.getElementById('resultados');
            if (data.length === 0) {
                div.innerHTML = '<div style="padding: 12px 16px; font-size: 0.85rem; color: #9acef8;">Sin resultados</div>';
            } else {
                div.innerHTML = data.map(p => `
                    <div onclick="seleccionar(${p.id}, '${p.nombre}', '${p.info}')"
                        style="padding: 12px 16px; cursor: pointer; border-bottom: 0.5px solid #e8f5ff; font-size: 0.88rem; color: var(--color-text-primary);">
                        <strong>${p.nombre}</strong><br>
                        <span style="font-size: 0.78rem; color: #6eb8f5;">${p.info}</span>
                        ${p.tiene_uid ? '<span style="font-size: 0.7rem; background: #fef3c7; color: #92400e; padding: 1px 6px; border-radius: 99px; margin-left: 6px;">Ya tiene credencial</span>' : ''}
                    </div>
                `).join('');
            }
            div.style.display = 'block';
        });
}

function seleccionar(id, nombre, info) {
    personaSeleccionada = id;
    document.getElementById('nombre-seleccionado').textContent = nombre;
    document.getElementById('info-seleccionado').textContent = info;
    document.getElementById('seleccionado').style.display = 'block';
    document.getElementById('resultados').style.display = 'none';
    document.getElementById('buscador').value = nombre;
    actualizarBotonLeer();
}

function actualizarBotonLeer() {
    const btn = document.getElementById('btn-leer');
    if (personaSeleccionada) {
        btn.disabled = false;
        btn.style.opacity = '1';
    } else {
        btn.disabled = true;
        btn.style.opacity = '0.5';
    }
}

function iniciarLectura() {
    if (!personaSeleccionada) return;

    mostrarEstado('⏳', 'Esperando credencial...', 'Acerca la tarjeta NFC al lector', '#e8f5ff', '#0f4c96');

    fetch('/api/leer-nfc-y-asignar', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''},
        body: JSON.stringify({tipo: tipoSeleccionado, id: personaSeleccionada})
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            mostrarEstado('✅', '¡Credencial asignada!', `${data.nombre} — UID: ${data.uid}`, '#d1fae5', '#065f46');
            personaSeleccionada = null;
            document.getElementById('seleccionado').style.display = 'none';
            document.getElementById('buscador').value = '';
            actualizarBotonLeer();
        } else {
            mostrarEstado('❌', 'Error', data.mensaje, '#fee2e2', '#991b1b');
        }
    })
    .catch(() => {
        mostrarEstado('❌', 'Error de conexión', 'Verifica que el servidor esté corriendo', '#fee2e2', '#991b1b');
    });
}

function mostrarEstado(icono, titulo, desc, bg, color) {
    const div = document.getElementById('estado');
    div.style.display = 'block';
    div.style.background = bg;
    div.style.color = color;
    document.getElementById('estado-icono').textContent = icono;
    document.getElementById('estado-titulo').textContent = titulo;
    document.getElementById('estado-desc').textContent = desc;
}
</script>
</x-filament-panels::page>