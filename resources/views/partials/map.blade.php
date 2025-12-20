@props([
    'mapId' => 'map',
    'latitude' => 41.1579,
    'longitude' => -8.6291,
    'zoom' => 12,
    'height' => 'h-full',
    'markers' => [],
    'spaces' => null,
    'showPopupImage' => true,
    'popupImageHeight' => 'h-32',
    'fitBoundsPadding' => 15 // padding em pixels
])

@php
    // Se spaces foi fornecido, converte para markers automaticamente
    if ($spaces) {
        $markers = $spaces->map(function ($space) use ($showPopupImage, $popupImageHeight) {
            // Gera o HTML da imagem/SVG
            $imageHtml = '';
            if ($showPopupImage) {
                if ($space->media->isNotEmpty()) {
                    $imageUrl = e($space->media->first()->media_url);
                    $imageHtml = '
                        <div class="mb-2 ' . $popupImageHeight . ' w-full overflow-hidden">
                            <img src="' . $imageUrl . '"
                                 alt="' . e($space->title) . '"
                                 class="w-full h-full object-cover"
                                 onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">
                            <div class="w-full h-full hidden items-center justify-center bg-gray-100">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        </div>';
                } else {
                    $imageHtml = '
                        <div class="' . $popupImageHeight . ' w-full flex items-center justify-center bg-gray-100">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>';
                }
            }

            return [
                'lat' => $space->latitude ?? 41.1579,
                'lng' => $space->longitude ?? -8.6291,
                'popup' => '
                    <div style="text-align: center; min-width: 150px; width: 100%;">
                        ' . $imageHtml . '
                        <div style="font-weight: bold; font-size: 1rem; margin-bottom: 0.5rem; color: #1f2937;">
                            ' . e($space->title) . '
                        </div>
                        <a href="' . route('spaces.show', $space->id) . '"
                           style="display: inline-block; background-color: #065f46; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; transition: background-color 0.2s;"
                           onmouseover="this.style.backgroundColor=\'#d1fae5\'; this.style.color=\'black\'"
                           onmouseout="this.style.backgroundColor=\'#065f46\'; this.style.color=\'white\'">
                            View Details
                        </a>
                    </div>'
            ];
        })->toArray();
    }
@endphp

<div id="{{ $mapId }}" class="{{ $height }} w-full rounded-2xl"></div>

@push('scripts')
    <script>
        (function() {
            // Inicializa o mapa
            const map = L.map('{{ $mapId }}').setView([{{ $latitude }}, {{ $longitude }}], {{ $zoom }});

            // Adiciona tiles do OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Adiciona markers
            const markers = @json($markers);
            const bounds = []; // Array de coordenadas [lat, lng]

            markers.forEach(marker => {
                const leafletMarker = L.marker([marker.lat, marker.lng]).addTo(map);

                if (marker.popup) {
                    leafletMarker.bindPopup(marker.popup, {
                        maxWidth: 250,
                        minWidth: 150
                    });
                }

                // Adiciona coordenadas ao bounds
                bounds.push([marker.lat, marker.lng]);
            });

            // Ajusta o zoom para mostrar todos os markers se houver múltiplos
            if (bounds.length > 1) {
                map.fitBounds(bounds, {
                    padding: [{{ $fitBoundsPadding }}, {{ $fitBoundsPadding }}] // padding em pixels
                });
            }
        })();
    </script>
@endpush
