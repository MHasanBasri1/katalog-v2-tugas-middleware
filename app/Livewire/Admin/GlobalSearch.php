<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Blog;
use Livewire\Component;

class GlobalSearch extends Component
{
    public $search = '';

    public function render()
    {
        $products = [];
        $categories = [];
        $blogs = [];

        if (strlen($this->search) >= 2) {
            $products = Product::where('name', 'like', '%' . $this->search . '%')
                ->with(['primaryImage', 'category'])
                ->limit(5)
                ->get();

            $categories = Category::where('name', 'like', '%' . $this->search . '%')
                ->limit(3)
                ->get();
            
            $blogs = Blog::where('title', 'like', '%' . $this->search . '%')
                ->limit(3)
                ->get();
        }

        return view('livewire.admin.global-search', [
            'products' => $products,
            'categories' => $categories,
            'blogs' => $blogs,
        ]);
    }
}
