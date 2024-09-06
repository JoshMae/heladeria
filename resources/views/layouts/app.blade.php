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
    <div class="content-area">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    
    <script>
        $(document).ready(function() {
            //let map = null;
    
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
                                // Asegurarse de que los estilos se apliquen correctamente
                                $('.informacion').css('display', 'flex');
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

            // Función para ajustar el diseño después de la carga
            function adjustLayout() {
                if ($(window).width() <= 768) {
                    $('.informacion').css('flex-direction', 'column');
                    $('.fotoTienda').css('margin-top', '20px');
                } else {
                    $('.informacion').css('flex-direction', 'row');
                    $('.fotoTienda').css('margin-top', '15px');
                }
            }

            // Llamar a adjustLayout cuando la ventana cambie de tamaño
            $(window).resize(adjustLayout);
            
                    let map = null;
                    let routingControl = null;

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
