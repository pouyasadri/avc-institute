<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogCategoryRequest;
use App\Http\Requests\UpdateBlogCategoryRequest;
use App\Models\BlogCategory;
use App\Services\BlogCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function __construct(private readonly BlogCategoryService $service) {}

    public function index(): View
    {
        $categories = $this->service->getAllCategories();

        return view('admin.blog.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $this->authorize('create', BlogCategory::class);

        $parents = $this->service->getAllCategories();

        return view('admin.blog.categories.create', compact('parents'));
    }

    public function store(StoreBlogCategoryRequest $request): RedirectResponse
    {
        $this->authorize('create', BlogCategory::class);

        $this->service->storeCategory($request->validated());

        return redirect()
            ->route('admin.blog.categories.index')
            ->with('success', __('messages.category_saved'));
    }

    public function edit(BlogCategory $category): View
    {
        $this->authorize('update', $category);

        $category->load('translations');
        $parents = $this->service->getAllCategories();

        return view('admin.blog.categories.edit', compact('category', 'parents'));
    }

    public function update(UpdateBlogCategoryRequest $request, BlogCategory $category): RedirectResponse
    {
        $this->authorize('update', $category);

        $this->service->updateCategory($category, $request->validated());

        return redirect()
            ->route('admin.blog.categories.index')
            ->with('success', __('messages.category_updated'));
    }

    public function destroy(BlogCategory $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        $this->service->deleteCategory($category);

        return redirect()
            ->route('admin.blog.categories.index')
            ->with('success', __('messages.category_deleted'));
    }
}
