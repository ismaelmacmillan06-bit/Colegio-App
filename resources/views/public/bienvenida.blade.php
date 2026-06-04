<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencia — Centro IMA</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: linear-gradient(135deg, #1a2a6c 0%, #0f4c96 60%, #1a3a8a 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: white;
            overflow: hidden;
        }

        .header {
            position: fixed;
            top: 0; left: 0; right: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            z-index: 10;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo-area img {
            height: 60px;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.3));
        }

        .logo-text {
            line-height: 1.3;
        }

        .logo-text .nombre {
            font-size: 1rem;
            font-weight: 700;
            color: white;
        }

        .logo-text .subtitulo {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.7);
        }

        .reloj {
            font-size: 2rem;
            font-weight: 300;
            letter-spacing: 2px;
            color: white;
            font-variant-numeric: tabular-nums;
        }

        .centro {
            position: fixed;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 24px;
            padding: 50px 60px;
            text-align: center;
            min-width: 420px;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
            transition: all 0.5s ease;
        }

        .card::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #00d2ff, #3a7bd5);
        }

        /* PANTALLA ESPERA */
        .nfc-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2196f3, #6eb8f5);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(33,150,243,0.4); }
            50% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(33,150,243,0); }
        }

        .espera-titulo {
            font-size: 1.3rem;
            font-weight: 700;
            color: white;
            margin-bottom: 8px;
        }

        .espera-sub {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.6);
        }

        /* PANTALLA BIENVENIDA */
        .foto-container {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto 20px;
        }

        .foto-alumno {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255,255,255,0.8);
            box-shadow: 0 0 30px rgba(0,0,0,0.3);
        }

        .foto-placeholder {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2196f3, #6eb8f5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 700;
            color: white;
            border: 4px solid rgba(255,255,255,0.8);
        }

        .badge-tipo {
            position: absolute;
            bottom: 0; right: 0;
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            border: 3px solid rgba(255,255,255,0.9);
        }

        .badge-entrada { background: #16a34a; }
        .badge-salida { background: #2563eb; }

        .bienvenida-nombre {
            font-size: 1.6rem;
            font-weight: 800;
            color: white;
            margin-bottom: 6px;
        }

        .bienvenida-clase {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.7);
            margin-bottom: 16px;
        }

        .bienvenida-hora {
            font-size: 1rem;
            color: #6eb8f5;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .progreso-bar {
            width: 100%;
            height: 4px;
            background: rgba(255,255,255,0.2);
            border-radius: 99px;
            overflow: hidden;
        }

        .progreso-fill {
            height: 100%;
            background: linear-gradient(90deg, #00d2ff, #3a7bd5);
            border-radius: 99px;
            width: 100%;
            animation: progreso 4s linear forwards;
        }

        @keyframes progreso {
            from { width: 100%; }
            to { width: 0%; }
        }

        .card-bienvenida {
            background: rgba(22, 163, 74, 0.15);
            border-color: rgba(22, 163, 74, 0.4);
        }

        .card-salida {
            background: rgba(37, 99, 235, 0.15);
            border-color: rgba(37, 99, 235, 0.4);
        }

        /* ANIMACIONES */
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .footer {
            position: fixed;
            bottom: 20px;
            left: 0; right: 0;
            text-align: center;
            font-size: 0.75rem;
            color: rgba(255,255,255,0.4);
        }

        /* Partículas de fondo */
        .particulas {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .particula {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            animation: flotar linear infinite;
        }

        @keyframes flotar {
            from { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            to { transform: translateY(-100px) rotate(720deg); opacity: 0; }
        }
    </style>
</head>
<body>

<div class="particulas" id="particulas"></div>

<div class="header">
    <div class="logo-area">
        <img src="{{ asset('images/logoimacf.png') }}" alt="IMA">
        <div class="logo-text">
            <div class="nombre">Centro Cultural y Pedagógico</div>
            <div class="subtitulo">Ignacio Manuel Altamirano • Centro IMA</div>
        </div>
    </div>
    <div class="reloj" id="reloj">00:00:00</div>
</div>

<div class="centro">
    <div class="card" id="card">
        <div class="nfc-icon">📡</div>
        <div class="espera-titulo">Acerca tu credencial NFC</div>
        <div class="espera-sub">Registra tu asistencia en segundos</div>
    </div>
</div>

<div class="footer">Sistema de Asistencia Escolar • RFID / NFC</div>

<script>
    // Reloj
    function actualizarReloj() {
        const ahora = new Date();
        const h = String(ahora.getHours()).padStart(2, '0');
        const m = String(ahora.getMinutes()).padStart(2, '0');
        const s = String(ahora.getSeconds()).padStart(2, '0');
        document.getElementById('reloj').textContent = `${h}:${m}:${s}`;
    }
    setInterval(actualizarReloj, 1000);
    actualizarReloj();

    // Partículas
    function crearParticulas() {
        const contenedor = document.getElementById('particulas');
        for (let i = 0; i < 15; i++) {
            const p = document.createElement('div');
            p.className = 'particula';
            const size = Math.random() * 30 + 10;
            p.style.cssText = `
                width: ${size}px;
                height: ${size}px;
                left: ${Math.random() * 100}%;
                bottom: -${size}px;
                animation-duration: ${Math.random() * 10 + 7}s;
                animation-delay: ${Math.random() * 5}s;
            `;
            contenedor.appendChild(p);
        }
    }
    crearParticulas();

    let mostrando = false;
    let timer = null;
    let ultimoTipo = null;
    let ultimoNombre = null;

    function mostrarEspera() {
        mostrando = false;
        ultimoTipo = null;
        ultimoNombre = null;
        const card = document.getElementById('card');
        card.className = 'card fade-in';
        card.innerHTML = `
            <div class="nfc-icon">📡</div>
            <div class="espera-titulo">Acerca tu credencial NFC</div>
            <div class="espera-sub">Registra tu asistencia en segundos</div>
        `;
    }

    function mostrarBienvenida(data) {
        if (mostrando && ultimoTipo === data.tipo && ultimoNombre === data.nombre) return;

        mostrando = true;
        ultimoTipo = data.tipo;
        ultimoNombre = data.nombre;

        const card = document.getElementById('card');

        if (data.tipo === 'muy_pronto') {
            card.className = 'card fade-in card-salida';
            const fotoHTML = data.foto
                ? `<img src="${data.foto}" class="foto-alumno" alt="${data.nombre}">`
                : `<div class="foto-placeholder">${data.nombre.charAt(0)}</div>`;

            card.innerHTML = `
                <div class="foto-container">
                    ${fotoHTML}
                    <div class="badge-tipo" style="background:#f59e0b;">⏱️</div>
                </div>
                <div class="bienvenida-nombre">⏱️ ${data.nombre.split(' ')[0]}</div>
                <div class="bienvenida-clase" style="color: #fcd34d; font-size: 1rem;">Demasiado pronto para registrar salida</div>
                <div class="bienvenida-clase">Mínimo 30 minutos entre entrada y salida</div>
                <div class="bienvenida-hora">⏰ ${data.hora}</div>
                <div class="progreso-bar">
                    <div class="progreso-fill" style="background: linear-gradient(90deg, #f59e0b, #fcd34d);"></div>
                </div>
            `;
        } else {
            const esEntrada = data.tipo === 'entrada';
            const saludo = esEntrada ? '¡Bienvenido!' : '¡Hasta mañana!';

            card.className = `card fade-in ${esEntrada ? 'card-bienvenida' : 'card-salida'}`;

            const fotoHTML = data.foto
                ? `<img src="${data.foto}" class="foto-alumno" alt="${data.nombre}">`
                : `<div class="foto-placeholder">${data.nombre.charAt(0)}</div>`;

            card.innerHTML = `
                <div class="foto-container">
                    ${fotoHTML}
                    <div class="badge-tipo ${esEntrada ? 'badge-entrada' : 'badge-salida'}">
                        ${esEntrada ? '✅' : '🏠'}
                    </div>
                </div>
                <div class="bienvenida-nombre">${saludo} ${data.nombre.split(' ')[0]}</div>
                <div class="bienvenida-clase">${data.clase}</div>
                <div class="bienvenida-hora">⏰ ${data.hora}</div>
                <div class="progreso-bar">
                    <div class="progreso-fill"></div>
                </div>
            `;
        }

        if (timer) clearTimeout(timer);
        timer = setTimeout(mostrarEspera, 1000);
    }

    function verificarRegistro() {
        fetch('/api/ultimo-registro')
            .then(r => r.json())
            .then(data => {
                if (data.hay_registro) {
                    mostrarBienvenida(data);
                }
            })
            .catch(() => {});
    }

    setInterval(verificarRegistro, 1000);
</script>

</body>
</html>