{{-- 2. BARRA DE FILTROS E PESQUISA --}}
<div class="mb-8 flex justify-between items-center" x-data="{ openFilters: false }">

    {{-- BOTÃO PARA ABRIR O MODAL --}}
    <button 
        @click="openFilters = true"
        class="flex items-center gap-2 px-6 py-2 bg-white border border-gray-300 rounded-full hover:border-emerald-500 hover:text-emerald-600 transition shadow-sm font-medium"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
        </svg>
        Filters
    </button>

    {{-- MODAL DE FILTROS (Início) --}}
    <div 
        x-show="openFilters" 
        style="display: none;" 
        class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm "
    >
        {{-- Container do Modal --}}
        <div 
            @click.away="openFilters = false"
            class="bg-white w-full max-w-4xl h-[90vh] rounded-xl shadow-2xl flex flex-col animate-fade-in-up px-6 py-2 " 
        >
            
            {{-- FORMULÁRIO GERAL --}}
            <form action="{{ route('spaces.search') }}" method="GET" class="flex flex-col h-full">
                
                {{-- Input Hidden para pesquisa de texto --}}
                <input type="hidden" name="q" value="{{ request('q') }}">

                {{-- CABEÇALHO DO MODAL --}}
                <div class="flex justify-between items-center p-6 border-b border-gray-200">
                    <h3 class="text-x4l font-bold text-gray-800">Filters</h3>
                    <button type="button" @click="openFilters = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- CORPO DO MODAL (Scrollable) --}}
                <div class="flex-1 overflow-y-auto p-6 space-y-8">

                    {{-- SECÇÃO 1: TIPOS DE DESPORTO --}}
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            {{-- Ícone (Ex: Bola) --}}
                            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h4 class="text-lg font-bold text-gray-900">Sport Types</h4>
                        </div>
                        
                        {{-- Grid de Checkboxes (2 Colunas como na imagem) --}}
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($sports as $sport)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input 
                                        type="checkbox" 
                                        name="sport_type[]" 
                                        value="{{ $sport->id }}"
                                        {{ (is_array(request('sport_type')) && in_array($sport->id, request('sport_type'))) || request('sport_type') == $sport->id ? 'checked' : '' }}
                                        class="w-5 h-5 border-gray-300 rounded text-emerald-600 focus:ring-emerald-500 transition"
                                    >
                                    <span class="text-gray-600 group-hover:text-emerald-700 transition">{{ $sport->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- SECÇÃO 2: CAPACIDADE (Slider Estilo 'TheFork') --}}
                    {{-- SECÇÃO 2: CAPACIDADE (Slider 0 a 10) --}}
                    <div x-data="{
                        step: {{ is_numeric(request('capacity')) ? request('capacity') : 0 }},
                        
                        // Calcula a posição em % (step * 10 porque 100% / 10 degraus = 10%)
                        get leftPosition() {
                            return this.step * 10;
                        },
                        
                        // Texto que aparece na bolha preta
                        get labelText() {
                            return this.step + ' pessoas';
                        }
                    }" class="pb-4 px-2">

                        {{-- Input Hidden: Se for 0 envia vazio, se não envia o número --}}
                        <input type="hidden" name="capacity" :value="step == 0 ? '' : step">

                        <div class="flex items-center gap-2 mb-8">
                            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <h4 class="text-lg font-bold text-gray-900">Capacidade (Mínima)</h4>
                        </div>

                        {{-- Container do Slider --}}
                        <div class="relative w-full mt-10"> 
                            
                            {{-- A Bolha Preta (Tooltip) --}}
                            <div 
                                class="absolute -top-10 transform -translate-x-1/2 bg-emerald-500 text-white text-xs font-bold py-1.5 px-3 rounded shadow-lg transition-all duration-75 pointer-events-none whitespace-nowrap z-10"
                                :style="`left: ${leftPosition}%`"
                            >
                                <span x-text="labelText"></span>
                                {{-- Setinha da bolha --}}
                            </div>

                            {{-- A Linha de Fundo (Track) --}}
                            <div class="absolute top-1/2 left-0 w-full h-1 bg-emerald-800 rounded transform -translate-y-1/2 "></div>
                            
                            {{-- Os Pontos Visuais (0 a 10 = 11 pontos) --}}
                            <div class="absolute top-1/2 left-0 w-full flex justify-between transform -translate-y-1/2 -z-10 px-[2px]">
                                <template x-for="i in 11">
                                    <div class="w-2 h-2 rounded-full bg-emerald-800 border border-white box-content"></div>
                                </template>
                            </div>

                            {{-- O Input Range Invisível --}}
                            <input 
                                type="range" 
                                min="0" 
                                max="10" 
                                step="1" 
                                x-model="step"
                                class="w-full h-6 absolute top-1/2 left-0 transform -translate-y-1/2 opacity-0 cursor-pointer z-20 margin-0"
                            >

                            {{-- O "Polegar" (Thumb) Falso --}}
                            <div 
                                class="absolute top-1/2 w-6 h-6 bg-white border-2 border-emerald-800 rounded-full shadow-md transform -translate-y-1/2 -translate-x-1/2 pointer-events-none transition-all duration-75 z-10"
                                :style="`left: ${leftPosition}%`"
                            ></div>

                            {{-- Etiquetas numéricas abaixo da linha --}}
                            <div class="flex justify-between mt-4 text-xs text-gray-400 font-medium select-none">
                                <span class= "text-2xl">0</span>
                                <span class= "text-2xl">10</span>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                </div>

                {{-- RODAPÉ DO MODAL (Botão de Ação) --}}
                <div class="p-6 border-t border-gray-200 bg-gray-50">
                    <button type="submit" class="w-full py-3 bg-emerald-800 text-white font-bold rounded-lg hover:bg-emerald-700 transition shadow-lg">
                        VIEW RESULTS
                    </button>
                </div>

            </form>
        </div>
    </div>
{{-- FIM DO MODAL --}}
</div>
