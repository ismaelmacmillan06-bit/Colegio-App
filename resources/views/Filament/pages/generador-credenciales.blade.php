<x-filament-panels::page>

    {{-- Especificaciones del template --}}
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <x-heroicon-o-information-circle class="w-5 h-5 text-blue-500" />
                Especificaciones del template
            </h3>
            <p class="text-sm text-gray-400 mt-0.5">Diseña tu credencial en Canva, Word o Photoshop respetando estas medidas y zonas.</p>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- FRENTE --}}
            <div>
                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">📋 FRENTE</h4>
                <div class="relative bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden" style="width: 172px; height: 296px; margin: 0 auto;">

                    {{-- Vista previa del template actual --}}
                    @if($frente_actual)
                        <img src="{{ asset('storage/' . $frente_actual) }}"
                             class="absolute inset-0 w-full h-full object-cover" />
                    @endif

                    {{-- Zona foto --}}
                    <div class="absolute border-2 border-dashed border-blue-500 bg-blue-500/10 rounded flex items-center justify-center"
                         style="left: 35px; top: 86px; width: 102px; height: 102px;">
                        <span class="text-blue-600 text-xs font-bold text-center leading-tight px-1">FOTO<br>ALUMNO<br>26×26 mm</span>
                    </div>

                    {{-- Zona nombre --}}
                    <div class="absolute border-2 border-dashed border-green-500 bg-green-500/10 flex items-center justify-center"
                         style="left: 6px; top: 200px; width: 160px; height: 22px;">
                        <span class="text-green-700 text-xs font-bold">NOMBRE ALUMNO</span>
                    </div>

                    {{-- Zona clase --}}
                    <div class="absolute border-2 border-dashed border-amber-500 bg-amber-500/10 flex items-center justify-center"
                         style="left: 6px; top: 226px; width: 160px; height: 16px;">
                        <span class="text-amber-700 text-xs font-bold">CLASE</span>
                    </div>

                    {{-- Zona fechas --}}
                    <div class="absolute border-2 border-dashed border-purple-500 bg-purple-500/10 flex items-center justify-center"
                         style="left: 6px; top: 268px; width: 160px; height: 16px;">
                        <span class="text-purple-700 text-xs font-bold">FECHAS</span>
                    </div>
                </div>

                <div class="mt-3 space-y-1 text-xs text-gray-500">
                    <p>📐 Tamaño: <strong>86 × 148 mm</strong> (o 1016 × 1748 px a 300 dpi)</p>
                    <p>🔵 Foto alumno: centrada, a <strong>40 mm</strong> del tope</p>
                    <p>🟢 Nombre: centrado, a <strong>104 mm</strong> del tope</p>
                    <p>🟡 Clase: centrada, a <strong>115 mm</strong> del tope</p>
                    <p>🟣 Fechas: fondo, a <strong>138 mm</strong> del tope</p>
                    <p class="text-blue-500 font-medium">💡 Deja esas zonas en blanco en tu diseño</p>
                </div>
            </div>

            {{-- REVERSO --}}
            <div>
                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">📋 REVERSO</h4>
                <div class="relative bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden" style="width: 172px; height: 296px; margin: 0 auto;">

                    @if($reverso_actual)
                        <img src="{{ asset('storage/' . $reverso_actual) }}"
                             class="absolute inset-0 w-full h-full object-cover" />
                    @endif

                    {{-- Zona contacto emergencia --}}
                    <div class="absolute border-2 border-dashed border-red-500 bg-red-500/10 flex items-center justify-center"
                         style="left: 6px; top: 144px; width: 160px; height: 64px;">
                        <span class="text-red-700 text-xs font-bold text-center leading-tight">CONTACTO<br>EMERGENCIA</span>
                    </div>
                </div>

                <div class="mt-3 space-y-1 text-xs text-gray-500">
                    <p>📐 Tamaño: <strong>86 × 148 mm</strong> (igual que el frente)</p>
                    <p>🔴 Contacto emergencia: centrado, desde <strong>73 mm</strong></p>
                    <p class="text-blue-500 font-medium">💡 El nombre y tel del tutor se superpone ahí</p>
                    <p class="text-gray-400">El resto (logo, director, firma, términos) ya va<br>diseñado en tu imagen</p>
                </div>
            </div>

        </div>
    </div>

    {{-- Formulario de carga --}}
    <form wire:submit.prevent="guardar" class="flex flex-col gap-4">

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Subir Templates</h3>
                <p class="text-sm text-gray-400">Formatos aceptados: JPG, PNG, WEBP — máx. 5 MB cada uno.</p>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">

                {{-- FRENTE --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        🪪 Imagen del Frente
                    </label>
                    @if($frente_actual)
                        <img src="{{ asset('storage/' . $frente_actual) }}"
                             class="w-full max-h-48 object-contain rounded-lg border border-gray-200 bg-gray-50 mb-2 p-1" />
                        <p class="text-xs text-green-600 mb-2">✓ Template actual cargado</p>
                    @else
                        <div class="w-full h-32 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center mb-2 bg-gray-50">
                            <p class="text-xs text-gray-400 text-center">Sin template<br>Sube una imagen</p>
                        </div>
                    @endif
                    <input wire:model="frente_nuevo" type="file" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" />
                    @error('frente_nuevo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- REVERSO --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        🔄 Imagen del Reverso
                    </label>
                    @if($reverso_actual)
                        <img src="{{ asset('storage/' . $reverso_actual) }}"
                             class="w-full max-h-48 object-contain rounded-lg border border-gray-200 bg-gray-50 mb-2 p-1" />
                        <p class="text-xs text-green-600 mb-2">✓ Template actual cargado</p>
                    @else
                        <div class="w-full h-32 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center mb-2 bg-gray-50">
                            <p class="text-xs text-gray-400 text-center">Sin template<br>Sube una imagen</p>
                        </div>
                    @endif
                    <input wire:model="reverso_nuevo" type="file" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" />
                    @error('reverso_nuevo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white rounded-xl shadow-sm transition"
                    style="background-color: #00004E;"
                    onmouseover="this.style.opacity='0.85'"
                    onmouseout="this.style.opacity='1'">
                <span wire:loading.remove>
                    <x-heroicon-o-arrow-up-tray class="w-4 h-4 inline -mt-0.5" />
                    Guardar templates
                </span>
                <span wire:loading>Subiendo...</span>
            </button>
        </div>

    </form>

</x-filament-panels::page>
