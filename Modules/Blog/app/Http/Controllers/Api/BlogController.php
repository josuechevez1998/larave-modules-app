<?php

namespace Modules\Blog\Http\Controllers\Api;

use App\Services\ListQueryBuilder;
use App\Services\BlogService;
use Modules\Blog\Models\Blog;
use Modules\Blog\Http\Requests\BlogRequest;
use Modules\Blog\Http\Resources\BlogResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Blog\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function __construct(private readonly BlogService $service) {}

    public function index(Request $request)
    {
        $blogs = ListQueryBuilder::for(Blog::query(), ['id', 'created_at', 'updated_at'])
            ->apply($request)
            ->paginate($request);

        return BlogResource::collection($blogs);
    }

    public function store(BlogRequest $request): JsonResponse
    {
        $blog = $this->service->create($request->validated());

        return response()->json(new BlogResource($blog), 201);
    }

    public function show(Blog $blog): JsonResponse
    {
        return response()->json(new BlogResource($blog));
    }

    public function update(BlogRequest $request, Blog $blog): JsonResponse
    {
        $blog = $this->service->update($blog, $request->validated());

        return response()->json(new BlogResource($blog));
    }

    public function destroy(Blog $blog): Response
    {
        $this->service->delete($blog);

        return response()->noContent();
    }
}
