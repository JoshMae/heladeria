<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto Sarita</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>

    <!-- Barra Superior -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" style="max-height: 90px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mr-auto">
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
            <div class="navbar-nav">
                <div class="tooltip-container nav-item">
                    <a class="nav-link" href="{{ route('ubicacion') }}">
                        <img src="{{ asset('images/ubicacion.png') }}" alt="Ubicación" class="img-fluid" style="max-height: 30px;">
                        <span class="tooltip-text">Ubicación</span>
                    </a>
                </div>
                <div class="tooltip-container nav-item">
                    <a class="nav-link" href="{{ route('usuario') }}">
                        <img src="{{ asset('images/usuario.png') }}" alt="Usuario" class="img-fluid" style="max-height: 30px;">
                        <span class="tooltip-text">Usuario</span>
                    </a>
                </div>
                <div class="tooltip-container nav-item">
                    <a class="nav-link" href="{{ route('carrito') }}">
                        <img src="{{ asset('images/carrito.png') }}" alt="Carrito" class="img-fluid" style="max-height: 30px;">
                        <span class="tooltip-text">Tu Pedido</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Área de Contenido Dinámico -->
    <div class="content-area container-fluid">
        <div id="ajax-content">
            @yield('content')
        </div>
    </div>

    <!-- Barra Inferior -->
    @if(Request::is('/'))
    <footer class="footer-custom">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex flex-column flex-md-row justify-content-between">
                        <span>
                            <a href="https://wa.me/1234567890" target="_blank">
                                <img src="{{ asset('images/whatsapp.png') }}" alt="WhatsApp" class="img-fluid" style="max-height: 35px;">
                                +502 2020 0000
                            </a>
                        </span>
                        <span>
                            <a href="https://www.instagram.com/sarita" target="_blank">
                                <img src="{{ asset('images/instagram.png') }}" alt="Instagram" class="img-fluid" style="max-height: 35px;">
                                @heladossarita
                            </a>
                        </span>
                        <span>
                            <a href="https://www.facebook.com/sarita" target="_blank">
                                <img src="{{ asset('images/facebook.png') }}" alt="Facebook" class="img-fluid" style="max-height: 35px;">
                                Helados Sarita
                            </a>
                        </span>
                    </div>
                </div>
                <div class="col-md-6 text-md-right mt-3 mt-md-0">
                    <span class="h4 mr-2">¡ Creamos momentos de alegría !</span>
                    <img src="{{ asset('images/icono.png') }}" alt="IconoSarita" class="img-fluid" style="max-height: 45px;">
                </div>
            </div>
        </div>
    </footer>
    @endif
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    
    <script>
        $(document).ready(function() {
            let map = null;
            let routingControl = null;

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
                            setTimeout(function() {
                                initMap();
                                adjustLayout();
                            }, 100);
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

            function adjustLayout() {
                if ($(window).width() <= 768) {
                    $('.informacion').css('flex-direction', 'column');
                    $('.fotoTienda').css('margin-top', '20px');
                } else {
                    $('.informacion').css('flex-direction', 'row');
                    $('.fotoTienda').css('margin-top', '15px');
                }
            }

            $(window).resize(adjustLayout);

            function initMap() {
                if (!$('#map').length) {
                    return;
                }

                const destination = [14.703469, -90.576334];
                map = L.map('map').setView(destination, 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                L.marker(destination).addTo(map)
                    .bindPopup('Heladería Sarita')
                    .openPopup();

                $('#route-btn').on('click', function() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            const userLat = position.coords.latitude;
                            const userLng = position.coords.longitude;

                            if (routingControl) {
                                map.removeControl(routingControl);
                            }

                            routingControl = L.Routing.control({
                                waypoints: [
                                    L.latLng(userLat, userLng),
                                    L.latLng(destination[0], destination[1])
                                ],
                                routeWhileDragging: true
                            }).addTo(map);

                            map.fitBounds([
                                [userLat, userLng],
                                destination
                            ]);
                        }, function() {
                            alert('No se pudo obtener tu ubicación.');
                        });
                    } else {
                        alert('La geolocalización no está soportada por tu navegador.');
                    }
                });
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