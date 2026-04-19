<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Footer extends Component
{
    public ?Setting $setting = null;

    public array $categories = [];

    public function mount(): void
    {
        $this->setting = Cache::rememberForever(
            'global.settings',
            fn () => Setting::query()->first() ?? new Setting()
        );

        $this->categories = Cache::remember(
            'public.footer.categories',
            now()->addMinutes(10),
            fn () => Category::query()
                ->select('name')
                ->orderBy('name')
                ->limit(5)
                ->pluck('name')
                ->all()
        );
    }

    public function render()
    {
        return view('livewire.public.footer');
    }
}
