<!-- Global Confirmation Modal -->
<div 
    x-data
    x-show="$store.confirm.show"
    x-cloak
    class="fixed inset-0 z-[400] flex items-center justify-center p-4"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <!-- Backdrop -->
    <div 
        class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="$store.confirm.close()"
    ></div>

    <!-- Modal Content -->
    <div 
        class="bg-white dark:bg-gray-900 w-full max-w-sm rounded-[2rem] shadow-2xl overflow-hidden relative border border-gray-100 dark:border-gray-800"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
    >
        <div class="p-8 text-center">
            <!-- Icon -->
            <div 
                class="w-20 h-20 mx-auto rounded-3xl flex items-center justify-center mb-6"
                :class="{
                    'bg-rose-50 dark:bg-rose-900/20 text-rose-600': $store.confirm.variant === 'danger',
                    'bg-amber-50 dark:bg-amber-900/20 text-amber-600': $store.confirm.variant === 'warning',
                    'bg-blue-50 dark:bg-blue-900/20 text-blue-600': $store.confirm.variant === 'primary'
                }"
            >
                <template x-if="$store.confirm.variant === 'danger'">
                    <i class="ti ti-trash text-4xl"></i>
                </template>
                <template x-if="$store.confirm.variant === 'warning'">
                    <i class="ti ti-alert-triangle text-4xl"></i>
                </template>
                <template x-if="$store.confirm.variant === 'primary'">
                    <i class="ti ti-help-circle text-4xl"></i>
                </template>
            </div>

            <!-- Text -->
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2" x-text="$store.confirm.title"></h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed" x-text="$store.confirm.message"></p>
        </div>

        <!-- Actions -->
        <div class="p-6 bg-gray-50 dark:bg-gray-800/50 flex flex-col gap-2">
            <button 
                @click="$store.confirm.confirm()"
                class="w-full py-3.5 rounded-2xl text-white font-bold text-sm transition-all shadow-lg active:scale-[0.98]"
                :class="{
                    'bg-rose-600 hover:bg-rose-700 shadow-rose-200 dark:shadow-none': $store.confirm.variant === 'danger',
                    'bg-amber-600 hover:bg-amber-700 shadow-amber-200 dark:shadow-none': $store.confirm.variant === 'warning',
                    'bg-blue-600 hover:bg-blue-700 shadow-blue-200 dark:shadow-none': $store.confirm.variant === 'primary'
                }"
                x-text="$store.confirm.confirmText"
            ></button>
            <button 
                @click="$store.confirm.close()"
                class="w-full py-3.5 rounded-2xl text-gray-700 dark:text-gray-200 font-bold text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                x-text="$store.confirm.cancelText"
            ></button>
        </div>
    </div>
</div>
