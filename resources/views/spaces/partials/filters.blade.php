<div class="flex justify-between items-center" x-data="{ openFilters: false }">

    <button
        @click="openFilters = true"
        class="flex items-center gap-2 px-6 py-2 bg-white border border-gray-300 rounded-full
               hover:border-emerald-500 hover:text-emerald-600 transition shadow-sm font-medium"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4
                     m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4
                     m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
        </svg>
        Filters
    </button>

    <div
        x-show="openFilters"
        style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm"
    >
        <div
            @click.away="openFilters = false"
            class="bg-white w-full max-w-4xl h-[90vh] rounded-xl shadow-2xl
                   flex flex-col animate-fade-in-up px-6 py-2"
        >

            <form action="{{ route('spaces.search') }}"
                  method="GET"
                  class="flex flex-col h-full"
                  id="filterForm" 
            >
                <input type="hidden" name="q" value="{{ request('q') }}">

                <div class="flex justify-between items-center p-6 border-b border-gray-200">
                    <h3 class="text-x4l font-bold text-gray-800">Filters</h3>
                    <button
                        type="button"
                        @click="openFilters = false"
                        class="text-gray-400 hover:text-gray-600"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-8">

                    {{-- Section 1: Sport Types --}}
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-gray-800" fill="none"
                                 stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M14.752 11.168l-3.197-2.132
                                         A1 1 0 0010 9.87v4.263
                                         a1 1 0 001.555.832l3.197-2.132
                                         a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h4 class="text-lg font-bold text-gray-900">Sport Types</h4>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            @foreach ($sports as $sport)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input
                                        type="checkbox"
                                        name="sport_type[]"
                                        value="{{ $sport->id }}"
                                        {{ (is_array(request('sport_type')) && in_array($sport->id, request('sport_type')))
                                            || request('sport_type') == $sport->id ? 'checked' : '' }}
                                        class="w-5 h-5 border-gray-300 rounded
                                               text-emerald-600 focus:ring-emerald-500 transition"
                                    >
                                    <span class="text-gray-600 group-hover:text-emerald-700 transition">
                                        {{ $sport->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- Section 2: Capacity --}}
                    <div
                        x-data="{
                            step: {{ is_numeric(request('capacity')) ? request('capacity') : 0 }},
                            get leftPosition() { return this.step * 10 },
                            get labelText() { return this.step + ' people' }
                        }"
                        class="pb-4 px-2"
                    >
                        <input type="hidden" name="capacity" :value="step == 0 ? '' : step">

                        <div class="flex items-center gap-2 mb-8">
                            <svg class="w-5 h-5 text-gray-800" fill="none"
                                 stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857
                                         M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                                         M7 20H2v-2a3 3 0 015.356-1.857
                                         M7 20v-2c0-.656.126-1.283.356-1.857
                                         m0 0a5.002 5.002 0 019.288 0" />
                            </svg>
                            <h4 class="text-lg font-bold text-gray-900">
                                Capacity (Minimum)
                            </h4>
                        </div>

                        <div class="w-full mt-10">
                            <div class="relative h-8 w-full">
                                <div
                                    class="absolute -top-10 transform -translate-x-1/2
                                           bg-emerald-500 text-white text-xs font-bold
                                           py-1.5 px-3 rounded shadow-lg"
                                    :style="`left: ${leftPosition}%`"
                                >
                                    <span x-text="labelText"></span>
                                    <div
                                        class="absolute top-full left-1/2 transform -translate-x-1/2
                                               border-4 border-transparent border-t-emerald-500"
                                    ></div>
                                </div>

                                <div class="absolute top-1/2 left-0 w-full h-1 bg-emerald-800
                                            rounded transform -translate-y-1/2"></div>

                                <div class="absolute top-1/2 left-0 w-full flex justify-between
                                            transform -translate-y-1/2 -z-10 px-[2px]">
                                    <template x-for="i in 11">
                                        <div class="w-2 h-2 rounded-full bg-emerald-800
                                                    border border-white"></div>
                                    </template>
                                </div>

                                <input
                                    type="range"
                                    min="0"
                                    max="10"
                                    step="1"
                                    x-model="step"
                                    class="w-full h-6 absolute top-1/2 left-0
                                           transform -translate-y-1/2 opacity-0 cursor-pointer z-20"
                                >

                                <div
                                    class="absolute top-1/2 w-6 h-6 bg-white
                                           border-2 border-emerald-800 rounded-full shadow-md
                                           transform -translate-y-1/2 -translate-x-1/2 z-10"
                                    :style="`left: ${leftPosition}%`"
                                ></div>
                            </div>

                            <div class="flex justify-between mt-2 text-xs text-gray-400 font-medium">
                                <span class="text-xl font-bold text-gray-300">0</span>
                                <span class="text-xl font-bold text-gray-300">10</span>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- Section 3: Date Range --}}
                    <div class="pt-4">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-gray-800" fill="none"
                                 stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10
                                         M5 21h14a2 2 0 002-2V7
                                         a2 2 0 00-2-2H5a2 2 0 00-2 2v12
                                         a2 2 0 002 2z" />
                            </svg>
                            <h4 class="text-lg font-bold text-gray-900">Availability</h4>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <label class="text-xs font-bold text-gray-500 mb-1 uppercase">
                                    From
                                </label>
                                <input type="date" name="date_from"
                                       value="{{ request('date_from') }}"
                                       class="w-full border border-gray-300 rounded-lg
                                              px-3 py-2 text-gray-700 focus:ring-emerald-500">
                            </div>

                            <div class="flex flex-col">
                                <label class="text-xs font-bold text-gray-500 mb-1 uppercase">
                                    To
                                </label>
                                <input type="date" name="date_to"
                                       value="{{ request('date_to') }}"
                                       class="w-full border border-gray-300 rounded-lg
                                              px-3 py-2 text-gray-700 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="flex flex-col">
                                <label class="text-xs font-bold text-gray-500 mb-1 uppercase">
                                    From Time
                                </label>
                                <input type="time" name="time_from"
                                    value="{{ request('time_from') }}"
                                    class="w-full border border-gray-300 rounded-lg
                                            px-3 py-2 text-gray-700 focus:ring-emerald-500">
                            </div>

                            <div class="flex flex-col">
                                <label class="text-xs font-bold text-gray-500 mb-1 uppercase">
                                    To Time
                                </label>
                                <input type="time" name="time_to"
                                    value="{{ request('time_to') }}"
                                    class="w-full border border-gray-300 rounded-lg
                                            px-3 py-2 text-gray-700 focus:ring-emerald-500">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="p-6 border-t border-gray-200 bg-gray-50 flex gap-4">
                    <button
                        type="button"
                        onclick="clearFilters()"
                        class="w-1/3 py-3 border border-gray-300 text-gray-700
                               font-bold rounded-lg hover:bg-gray-100 transition"
                    >
                        CLEAN
                    </button>

                    <button
                        type="submit"
                        class="w-full py-3 bg-emerald-800 text-white
                               font-bold rounded-lg hover:bg-emerald-700 transition shadow-lg"
                    >
                        VIEW RESULTS
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
