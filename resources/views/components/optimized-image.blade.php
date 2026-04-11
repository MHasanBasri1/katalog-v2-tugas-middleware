@props([
    'src' => '',
    'alt' => '',
    'width' => null,
    'height' => null,
    'class' => '',
    'lazy' => true,
    'sizes' => '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw',
    'fetchpriority' => 'auto'
])

@php
    $isPicsum = str_contains($src, 'picsum.photos');
    $isExternal = str_starts_with($src, 'http://') || str_starts_with($src, 'https://') || str_starts_with($src, '//');
    $isStorage = str_starts_with($src, '/storage/') || str_starts_with($src, 'storage/');
    
    if ($isExternal || $isPicsum || $isStorage) {
        $finalSrc = $src;
    } else {
        $finalSrc = \Illuminate\Support\Facades\Storage::url($src);
    }
    $srcset = '';

    if ($isPicsum && $width && $height) {
        // Assume picsum URL is like https://picsum.photos/id/123/800/600 or similar
        // We'll normalize it to .webp
        if (preg_match('#/id/(\d+)/#', $src, $matches)) {
            $id = $matches[1];
            $finalSrc = "https://picsum.photos/id/{$id}/{$width}/{$height}.webp";
            
            // Generate basic srcset
            $w1 = (int)($width * 0.5);
            $h1 = (int)($height * 0.5);
            $w2 = $width;
            $h2 = $height;
            $w3 = (int)($width * 1.5);
            $h3 = (int)($height * 1.5);
            
            $srcset = "https://picsum.photos/id/{$id}/{$w1}/{$h1}.webp {$w1}w, " .
                      "https://picsum.photos/id/{$id}/{$w2}/{$h2}.webp {$w2}w, " .
                      "https://picsum.photos/id/{$id}/{$w3}/{$h3}.webp {$w3}w";
        }
    }
@endphp

<img 
    src="{{ $finalSrc }}" 
    alt="{{ $alt }}"
    @if($width) width="{{ $width }}" @endif
    @if($height) height="{{ $height }}" @endif
    @if($srcset) srcset="{{ $srcset }}" @endif
    sizes="{{ $sizes }}"
    class="{{ $class }}"
    @if($lazy) loading="lazy" @endif
    decoding="async"
    fetchpriority="{{ $fetchpriority }}"
    {{ $attributes }}
>
