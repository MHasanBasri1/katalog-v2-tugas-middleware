<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannerController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $limit = (int) $request->integer('limit', 10);
        $limit = max(1, min(50, $limit));

        $banners = Banner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->limit($limit)
            ->get(['id', 'title', 'subtitle', 'image_url', 'cta_label', 'cta_url', 'sort_order', 'is_active', 'created_at', 'updated_at']);

        return $this->success($banners);
    }
}
