@props([
    'mapId' => 'map',
    'latitude' => 41.1579,  // Porto centro por defeito
    'longitude' => -8.6291,
    'zoom' => 13,
    'height' => 'h-96',
    'markers' => []  // Array de markers: [['lat' => 41.15, 'lng' => -8.62, 'popup' => 'Texto']]
])

<div id="{{ $mapId }}" class="{{ $height }} w-full rounded-lg shadow-lg z-0"></div>

@push('scripts')
    <script>
        (function() {
            // Inicializar mapa
            const map{{ Str::studly($mapId) }} = L.map('{{ $mapId }}').setView([{{ $latitude }}, {{ $longitude }}], {{ $zoom }});

            // Adicionar tile layer do OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19,
            }).addTo(map{{ Str::studly($mapId) }});

            @if(!empty($markers))
            // Adicionar markers
            const markers{{ Str::studly($mapId) }} = @json($markers);
            const bounds{{ Str::studly($mapId) }} = [];

            markers{{ Str::studly($mapId) }}.forEach(marker => {
                const leafletMarker = L.marker([marker.lat, marker.lng])
                    .addTo(map{{ Str::studly($mapId) }});

                if (marker.popup) {
                    leafletMarker.bindPopup(marker.popup);
                }

                bounds{{ Str::studly($mapId) }}.push([marker.lat, marker.lng]);
            });

            // Ajustar zoom para mostrar todos os markers
            if (bounds{{ Str::studly($mapId) }}.length > 1) {
                map{{ Str::studly($mapId) }}.fitBounds(bounds{{ Str::studly($mapId) }}, {
                    padding: [30, 30]
                });
            }
            @else
            // Sem markers, adicionar um marker na posição central
            L.marker([{{ $latitude }}, {{ $longitude }}])
                .addTo(map{{ Str::studly($mapId) }})
                .bindPopup('📍 Localização')
                .openPopup();
            @endif
        })();
    </script>
@endpush
