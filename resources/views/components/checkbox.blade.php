@props([
    'label' => null,
    'description' => null,
    'name' => null,
    'checked' => false,
])

<div x-data="{ isChecked: {{ $checked ? 'true' : 'false' }} }" class="flex items-center group relative">
    <div class="flex-shrink-0">
        <div class="relative flex items-center justify-center w-6 h-6">
            <input id="{{ $name }}" name="{{ $name }}" type="checkbox" x-model="isChecked" class="sr-only" {{ $attributes }}>

            <div
                @click="isChecked = !isChecked"
                :class="isChecked ? 'bg-blue-600 border-blue-600 shadow-lg shadow-blue-500/40 scale-110' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:border-blue-400'"
                class="w-6 h-6 rounded-lg border-2 cursor-pointer transition-all duration-300 flex items-center justify-center overflow-hidden"
            >
                <i class="ti ti-check text-white text-base font-bold transition-all duration-300" :class="isChecked ? 'translate-y-0 opacity-100 scale-100' : 'translate-y-2 opacity-0 scale-50'"></i>
            </div>

            <div class="absolute inset-0 bg-blue-500/20 rounded-lg scale-0 transition-transform duration-300 pointer-events-none group-active:scale-150 group-active:opacity-0"></div>
        </div>
    </div>

    <div class="ml-3 flex flex-col cursor-pointer select-none" @click="isChecked = !isChecked">
        @if ($label)
            <span :class="isChecked ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-white'" class="text-sm font-bold transition-colors duration-300 group-hover:text-blue-500">
                {{ $label }}
            </span>
        @endif

        @if ($description)
            <p class="text-gray-500 dark:text-gray-400 text-[11px] font-medium leading-tight mt-0.5 tracking-tight opacity-70">
                {{ $description }}
            </p>
        @endif

        @error($name)
            <p class="text-[10px] font-bold text-rose-500 mt-1 uppercase tracking-tighter flex items-center gap-1">
                <i class="ti ti-alert-circle text-xs"></i> {{ $message }}
            </p>
        @enderror
    </div>
</div>
