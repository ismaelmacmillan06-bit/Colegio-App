<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro Cultural y Pedagógico IMA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; color: #222; }

        /* NAVBAR */
        nav {
            position: fixed; top: 0; width: 100%; z-index: 1000;
            background: #00004E; padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between;
            height: 85px; box-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-logo img { height: 60px; }
        .nav-logo span { color: white; font-size: 0.9rem; font-weight: 500; line-height: 1.3; max-width: 180px; }
        .nav-links { display: flex; gap: 2rem; list-style: none; }
        .nav-links a {
            color: rgba(255,255,255,0.85); text-decoration: none;
            font-size: 0.92rem; font-weight: 500; transition: color 0.2s;
            display: flex; align-items: center; gap: 6px;
        }
        .nav-links a i { font-size: 1rem; }
        .nav-links a:hover { color: #6ab04c; }

        /* SLIDER */
        .slider { margin-top: 85px; position: relative; height: 63vh; overflow: hidden; background: #00004E; }
        .slide { position: absolute; inset: 0; opacity: 0; transition: opacity 0.8s ease; }
        .slide.active { opacity: 1; }
        .slide img { width: 100%; height: 100%; object-fit: cover; }
        .slide-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to right, rgba(0,0,78,0.85) 40%, rgba(0,0,78,0.2));
            display: flex; align-items: center; padding: 0 6rem;
        }
        .slide-text h1 { color: white; font-size: 3.5rem; font-weight: 800; line-height: 1.2; margin-bottom: 1rem; }
        .slide-text p { color: rgba(255,255,255,0.8); font-size: 1.1rem; margin-bottom: 2rem; }
        .btn-primary {
            background: #6ab04c; color: white; padding: 14px 32px;
            border-radius: 50px; text-decoration: none; font-weight: 600;
            font-size: 0.95rem; transition: all 0.3s; display: inline-block;
        }
        .btn-primary:hover { background: #5a9a3c; transform: translateY(-2px); }
        .slider-dots { position: absolute; bottom: 2rem; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; }
        .dot { width: 10px; height: 10px; border-radius: 50%; background: rgba(255,255,255,0.4); cursor: pointer; transition: background 0.3s; }
        .dot.active { background: #6ab04c; }

        /* SECCIONES */
        section { padding: 80px 6rem; }
        .section-title { text-align: center; margin-bottom: 3rem; }
        .section-title span { color: #6ab04c; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; }
        .section-title h2 { color: #00004E; font-size: 2.2rem; font-weight: 700; margin-top: 8px; }

        /* ACTIVIDADES */
        .actividades { background: #f8f9ff; }
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; }
        .card {
            background: white; border-radius: 16px; padding: 2rem 1.5rem;
            text-align: center; box-shadow: 0 4px 20px rgba(0,0,78,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0,0,78,0.15); }
        .card-icon { width: 70px; height: 70px; border-radius: 50%; background: #f0f0ff; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .card-icon i { font-size: 1.8rem; color: #00004E; }
        .card h3 { color: #00004E; font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem; }
        .card p { color: #666; font-size: 0.88rem; line-height: 1.6; }

        /* CIRCULARES */
        .circulares-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
        .circular-card {
    background: white; border-radius: 12px; padding: 1.5rem;
    border-left: 4px solid #6ab04c; box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    display: flex; flex-direction: column; gap: 0.75rem;
    transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
    cursor: pointer;
}
.circular-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(0,0,78,0.15);
    border-left-color: #00004E;
}
        
        .circular-fecha { color: #6ab04c; font-size: 0.8rem; font-weight: 600; }
        .circular-titulo { color: #00004E; font-size: 1rem; font-weight: 600; }
        .circular-desc { color: #666; font-size: 0.85rem; line-height: 1.6; flex: 1; }
        .circular-btn {
            display: inline-flex; align-items: center; gap: 6px;
            color: #00004E; font-size: 0.85rem; font-weight: 600;
            text-decoration: none; transition: color 0.2s;
        }
        .circular-btn:hover { color: #6ab04c; }

        /* CUADRO DE HONOR */
        .honor { background: #f8f9ff; }
        .honor-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem; }
        .honor-card { text-align: center; }
        .honor-foto {
            width: 120px; height: 120px; border-radius: 50%; object-fit: cover;
            border: 4px solid #6ab04c; margin: 0 auto 1rem; display: block;
        }
        .honor-foto-placeholder {
            width: 120px; height: 120px; border-radius: 50%;
            background: #e8e8f5; border: 4px solid #6ab04c;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
        }
        .honor-foto-placeholder i { font-size: 2.5rem; color: #00004E; }
        .honor-nombre { color: #00004E; font-weight: 600; font-size: 0.95rem; }
        .honor-grado { color: #6ab04c; font-size: 0.8rem; font-weight: 500; }
        .honor-motivo { color: #666; font-size: 0.8rem; margin-top: 4px; }

        /* DIVISOR */
        .divisor {
            background: linear-gradient(135deg, #00004E 0%, #0000a0 100%);
            padding: 80px 6rem; text-align: center; color: white;
        }
        .divisor h2 { font-size: 2rem; font-weight: 700; margin-bottom: 1rem; }
        .divisor p { font-size: 1.05rem; opacity: 0.85; max-width: 600px; margin: 0 auto 2.5rem; }
        .stats { display: flex; justify-content: center; gap: 4rem; flex-wrap: wrap; }
        .stat h3 { font-size: 2.5rem; font-weight: 800; color: #6ab04c; }
        .stat p { font-size: 0.9rem; opacity: 0.8; margin-top: 4px; }

        /* GALERÍA */
        .galeria-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem; }
        .galeria-item { border-radius: 12px; overflow: hidden; aspect-ratio: 4/3; }
        .galeria-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
        .galeria-item:hover img { transform: scale(1.05); }

        /* FOOTER */
        footer { background: #00004E; color: white; padding: 3rem 6rem; }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 3rem; margin-bottom: 2rem; }
        .footer-logo { display: flex; align-items: center; gap: 12px; margin-bottom: 1rem; }
        .footer-logo img { height: 60px; }
        .footer-desc { color: rgba(255,255,255,0.7); font-size: 0.88rem; line-height: 1.7; }
        .footer-title { color: #6ab04c; font-weight: 600; font-size: 0.9rem; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px; }
        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 0.5rem; }
        .footer-links a { color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.88rem; transition: color 0.2s; }
        .footer-links a:hover { color: #6ab04c; }
        .footer-info { color: rgba(255,255,255,0.7); font-size: 0.88rem; display: flex; flex-direction: column; gap: 0.5rem; }
        .footer-info i { color: #6ab04c; margin-right: 8px; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.5rem; text-align: center; color: rgba(255,255,255,0.5); font-size: 0.82rem; }

        @media (max-width: 768px) {
            section { padding: 60px 1.5rem; }
            .slide-text h1 { font-size: 2rem; }
            .slide-overlay { padding: 0 2rem; }
            .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
            .nav-links { display: none; }
            .stats { gap: 2rem; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav>
    <a href="/admin" class="nav-logo">
        <img src="{{ asset('images/logoimacf.png') }}" alt="IMA">
        <span>Centro Cultural y Pedagógico<br>Ignacio Manuel Altamirano</span>
    </a>
    <ul class="nav-links">
    <li><a href="{{ route('inicio') }}"><i class="fas fa-home"></i> Inicio</a></li>
    <li><a href="{{ route('circulares') }}"><i class="fas fa-file-alt"></i> Circulares</a></li>
    <li><a href="{{ route('galeria') }}"><i class="fas fa-images"></i> Galería</a></li>
    <li><a href="{{ route('menu') }}"><i class="fas fa-utensils"></i> Menú Cafetería</a></li>
    <li><a href="#contacto"><i class="fas fa-envelope"></i> Contacto</a></li>
</ul>
</nav>

<!-- SLIDER -->
<div class="slider" id="slider">
    @forelse($sliders as $i => $slider)
    <div class="slide {{ $i === 0 ? 'active' : '' }}">
        <img src="{{ asset('storage/' . $slider->imagen) }}" alt="{{ $slider->titulo }}">
        <div class="slide-overlay">
            <div class="slide-text">
                <h1>{{ $slider->titulo ?? 'Centro Cultural y Pedagógico IMA' }}</h1>
                <p>{{ $slider->subtitulo ?? 'Educación, Respeto y Calidad' }}</p>
                <a href="{{ route('circulares') }}" class="btn-primary">Ver Circulares</a>
            </div>
        </div>
    </div>
    @empty
    <div class="slide active">
        <div class="slide-overlay" style="background: linear-gradient(135deg, #00004E 0%, #0000a0 100%);">
            <div class="slide-text">
                <h1>Centro Cultural y Pedagógico IMA</h1>
                <p>Educación, Respeto y Calidad</p>
                <a href="{{ route('circulares') }}" class="btn-primary">Ver Circulares</a>
            </div>
        </div>
    </div>
    @endforelse
    <div class="slider-dots" id="dots"></div>
</div>

<!-- ACTIVIDADES EXTRACURRICULARES -->
<section class="actividades">
    <div class="section-title">
        <span>Formación Integral</span>
        <h2>Actividades Extracurriculares</h2>
    </div>
    <div class="cards-grid">
        <div class="card">
            <div class="card-icon"><i class="fas fa-futbol"></i></div>
            <h3>Deportes</h3>
            <p>Fútbol, basquetbol y atletismo para el desarrollo físico y trabajo en equipo.</p>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fas fa-palette"></i></div>
            <h3>Cluib de tareas</h3>
            <p>Espacio para que nuestros estudiantes realicen tareas acompañados de maestros.</p>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fas fa-music"></i></div>
            <h3>Música</h3>
            <p>Clases de guitarra, flauta y coro escolar para el desarrollo musical.</p>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fas fa-laptop-code"></i></div>
            <h3>Computación</h3>
            <p>Tecnología e informática para preparar a los alumnos del futuro.</p>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fas fa-language"></i></div>
            <h3>Inglés</h3>
            <p>Clases de inglés desde preescolar para un aprendizaje temprano del idioma.</p>
        </div>
    </div>
</section>

<!-- CIRCULARES -->
<section>
    <div class="section-title">
        <span>Comunicados</span>
        <h2>Circulares Recientes</h2>
    </div>
    <div class="circulares-grid">
        @forelse($circulares as $circular)
        <div class="circular-card">
            <div class="circular-fecha">{{ $circular->fecha->format('d/m/Y') }}</div>
            <div class="circular-titulo">{{ $circular->titulo }}</div>
            <div class="circular-desc">{{ Str::limit($circular->descripcion, 100) }}</div>
            @if($circular->archivo_pdf)
            <a href="{{ asset('storage/' . $circular->archivo_pdf) }}" target="_blank" class="circular-btn">
                <i class="fas fa-file-pdf"></i> Descargar PDF
            </a>
            @endif
        </div>
        @empty
        <p style="color:#999; grid-column:1/-1; text-align:center;">No hay circulares disponibles.</p>
        @endforelse
    </div>
</section>

<!-- CUADRO DE HONOR -->
<section class="honor">
    <div class="section-title">
        <span>Reconocimientos</span>
        <h2>Cuadro de Honor</h2>
    </div>
    <div class="honor-grid">
        @forelse($cuadroHonor as $alumno)
        <div class="honor-card">
            @if($alumno->foto)
            <img src="{{ asset('storage/' . $alumno->foto) }}" alt="{{ $alumno->nombre_alumno }}" class="honor-foto">
            @else
            <div class="honor-foto-placeholder"><i class="fas fa-user-graduate"></i></div>
            @endif
            <div class="honor-nombre">{{ $alumno->nombre_alumno }}</div>
            <div class="honor-grado">{{ $alumno->grado }} {{ $alumno->grupo }} — {{ $alumno->periodo }}</div>
            <div class="honor-motivo">{{ $alumno->motivo }}</div>
        </div>
        @empty
        <p style="color:#999; grid-column:1/-1; text-align:center;">No hay registros aún.</p>
        @endforelse
    </div>
</section>

<!-- DIVISOR -->
<div class="divisor">
    <h2>Formando el futuro de México</h2>
    <p>Más de 30 años educando con valores, respeto y excelencia académica en nuestra comunidad.</p>
    <div class="stats">
        <div class="stat"><h3>+500</h3><p>Alumnos</p></div>
        <div class="stat"><h3>30+</h3><p>Años de experiencia</p></div>
        <div class="stat"><h3>25+</h3><p>Maestros</p></div>
        <div class="stat"><h3>100%</h3><p>Compromiso</p></div>
    </div>
</div>

<!-- GALERÍA -->
<section>
    <div class="section-title">
        <span>Momentos</span>
        <h2>Galería de Fotos</h2>
    </div>
    <div class="galeria-grid">
        @forelse($galeria as $foto)
        <div class="galeria-item">
            <img src="{{ asset('storage/' . $foto->imagen) }}" alt="{{ $foto->titulo }}">
        </div>
        @empty
        <p style="color:#999; grid-column:1/-1; text-align:center;">No hay fotos disponibles.</p>
        @endforelse
    </div>
</section>

<!-- FOOTER -->
<footer id="contacto">
    <div class="footer-grid">
        <div>
            <div class="footer-logo">
                <img src="{{ asset('images/logoimacf.png') }}" alt="IMA">
            </div>
            <p class="footer-desc">Centro Cultural y Pedagógico Ignacio Manuel Altamirano. Educación, Respeto y Calidad desde hace más de 30 años.</p>
        </div>
        <div>
            <div class="footer-title">Navegación</div>
            <ul class="footer-links">
                <li><a href="{{ route('inicio') }}">Inicio</a></li>
                <li><a href="{{ route('circulares') }}">Circulares</a></li>
                <li><a href="{{ route('galeria') }}">Galería</a></li>
                <li><a href="{{ route('menu') }}">Menú Cafetería</a></li>
            </ul>
        </div>
        <div>
            <div class="footer-title">Contacto</div>
            <div class="footer-info">
                <span><i class="fas fa-map-marker-alt"></i>Dirección del colegio</span>
                <span><i class="fas fa-phone"></i>Teléfono del colegio</span>
                <span><i class="fas fa-envelope"></i>correo@colegio.com</span>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        © {{ date('Y') }} Centro Cultural y Pedagógico IMA. Todos los derechos reservados.
    </div>
</footer>

<script>
    const slides = document.querySelectorAll('.slide');
    const dotsContainer = document.getElementById('dots');
    let current = 0;

    slides.forEach((_, i) => {
        const dot = document.createElement('div');
        dot.className = 'dot' + (i === 0 ? ' active' : '');
        dot.onclick = () => goTo(i);
        dotsContainer.appendChild(dot);
    });

    function goTo(n) {
        slides[current].classList.remove('active');
        dotsContainer.children[current].classList.remove('active');
        current = n;
        slides[current].classList.add('active');
        dotsContainer.children[current].classList.add('active');
    }

    if (slides.length > 1) {
        setInterval(() => goTo((current + 1) % slides.length), 5000);
    }
</script>

</body>
</html>