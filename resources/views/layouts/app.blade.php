<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto Sarita</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        .navbar-custom {
            background-color: #E90B0B;
            padding: 15px 20px; 
            height: 90px;
        }
        .footer-custom {
            background-color: #E90B0B;
            color: white;
            padding: 10px 20px;
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: left;
            height: 70px;
            font-size: 1.3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer-custom a {
            color: white; /* Cambia el color del texto de los enlaces a blanco */
            text-decoration: none; /* Elimina el subrayado de los enlaces */
            margin-left: 40px;
        }
        .footer-custom a:hover {
            text-decoration: underline; /* Añade un subrayado al pasar el mouse por encima */
        }
        .content-area {
            padding-top: 95px;
            padding-bottom: 90px;
            min-height: calc(100vh - 165px);
        }

        #ajax-content {
            overflow: auto;
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-size: 1.6rem; 
            margin: 0 60px; /* Añade espacio entre los enlaces */
            padding: 0; 
        }
        .navbar-nav .nav-link:hover {
            color: #ddd !important;
        }
        .nav-link.active {
            border-bottom: 3px solid white; /* Añade una línea blanca debajo del enlace activo */
            color: white !important; /* Asegura que el texto del enlace activo siga siendo blanco */
        }

        /* Estilos personalizados para los tooltips */
        .tooltip-container {
            position: relative;
            display: inline-block;
        }
        .tooltip-container .tooltip-text {
            visibility: hidden;
            width: 120px;
            background-color: rgba(255, 255, 255, 0.8); /* Fondo blanco semi-transparente */
            color: #333; /* Texto en gris oscuro */
            text-align: center;
            border-radius: 5px;
            padding: 5px 0;
            position: absolute;
            z-index: 1;
            bottom: 80%; /* Posición arriba del icono */
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .tooltip-container .tooltip-text::after {
            content: "";
            position: absolute;
            top: 100%; /* Flecha del tooltip */
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: rgba(255, 255, 255, 0.8) transparent transparent transparent; /* Flecha blanca */
        }
        .tooltip-container:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>
<body>

    <!-- Barra Superior -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 90px;">
        </a>
        <div class="collapse navbar-collapse justify-content-center">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('inicio') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('nosotros') ? 'active' : '' }}" href="{{ route('nosotros') }}">Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('catalogo') ? 'active' : '' }}" href="{{ route('catalogo') }}">Catálogo</a>
                </li>
            </ul>            
        </div>
        <div class="d-flex">
            <div class="tooltip-container">
                <a class="nav-link" href="{{ route('ubicacion') }}">
                    <img src="{{ asset('images/ubicacion.png') }}" alt="Ubicación" style="height: 30px;">
                    <span class="tooltip-text">Ubicación</span>
                </a>
            </div>
            <div class="tooltip-container">
                <a class="nav-link" href="{{ route('usuario') }}">
                    <img src="{{ asset('images/usuario.png') }}" alt="Usuario" style="height: 30px;">
                    <span class="tooltip-text">Usuario</span>
                </a>
            </div>
            <div class="tooltip-container">
                <a class="nav-link" href="{{ route('carrito') }}">
                    <img src="{{ asset('images/carrito.png') }}" alt="Carrito" style="height: 30px;">
                    <span class="tooltip-text">Tu Pedido</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Área de Contenido Dinámico -->
    <div class="container content-area">
        <div id="ajax-content">
            @yield('content')
        </div>
    </div>

    <!-- Barra Inferior -->
    <div class="footer-custom">
        <div>
            <span>
                <a href="https://wa.me/1234567890" target="_blank">
                    <img src="{{ asset('images/whatsapp.png') }}" alt="WhatsApp" style="height: 35px;">
                    +502 2020 0000
                </a>
            </span>
            &nbsp; | &nbsp;
            <span>
                <a href="https://www.instagram.com/sarita" target="_blank">
                    <img src="{{ asset('images/instagram.png') }}" alt="Instagram" style="height: 35px;">
                    @heladossarita
                </a>
            </span>
            &nbsp; | &nbsp;
            <span>
                <a href="https://www.facebook.com/sarita" target="_blank">
                    <img src="{{ asset('images/facebook.png') }}" alt="Facebook" style="height: 35px;">
                    Helados Sarita
                </a>
            </span>
        </div>
        <div>
            <span style="font-size: 30px; margin-right: 25px;">¡ Creamos momentos de alegría !</span>
            <img src="{{ asset('images/icono.png') }}" alt="IconoSarita" style="height: 45px;">
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Usar la versión completa de jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script defer src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        $(document).ready(function() {
            let map = null;
    
            function loadContent(url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(data) {
                        $('#ajax-content').html(data);
                        history.pushState(null, '', url);

                        if (url.includes('ubicacion')) {
                            setTimeout(initMap, 0);
                        } else if (map) {
                            map.remove();
                            map = null;
                        }
                    },
                    error: function(xhr) {
                        console.log('Error: ' + xhr.status + ' ' + xhr.statusText);
                    }
                });
            }
    
            function initMap() {
                if (!$('#ajax-content #map').length) {
                    $('#ajax-content').append('<div id="map" style="width: 400px; height: 200px; margin-bottom: 90px;"></div>');
                }

                if (map) {
                    map.remove();
                    map = null;
                }

                map = L.map('map').setView([14.703469, -90.576334], 15);

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                L.marker([14.703469, -90.576334]).addTo(map)
                    .bindPopup('Heladería Sarita')
                    .openPopup();
            }
    
          
            $('.navbar-nav .nav-link, .tooltip-container .nav-link').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                loadContent(url);
                
                $('.navbar-nav .nav-link').removeClass('active');
                $(this).addClass('active');
            });
    
            $(window).on('popstate', function() {
                loadContent(location.href);
            });
    
            if (window.location.href.includes('ubicacion')) {
                initMap();
            }
        
            
        });

        
    </script>    
    
    
</body>
</html>
