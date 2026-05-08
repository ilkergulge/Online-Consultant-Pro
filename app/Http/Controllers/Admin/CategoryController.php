<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;
    protected $categoryRepository;

    public function __construct(CategoryService $categoryService, CategoryRepository $categoryRepository)
    {
        $this->categoryService = $categoryService;
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->getAll();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = $this->categoryRepository->getAll();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->categoryService->handleCreate($request->validated());
        return redirect()->route('admin.categories.index')->with('success', __('admin.category_created'));
    }

    public function edit(Category $category)
    {
        $categories = $this->categoryRepository->getAll();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->categoryService->handleUpdate($category, $request->validated());
        return redirect()->route('admin.categories.index')->with('success', __('admin.category_updated'));
    }

    public function destroy(Category $category)
    {
        $this->categoryRepository->delete($category);
        return redirect()->route('admin.categories.index')->with('success', __('admin.category_deleted'));
    }
}
