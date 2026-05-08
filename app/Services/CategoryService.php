<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function handleCreate(array $data)
    {
        return $this->categoryRepository->create($data);
    }

    public function handleUpdate(Category $category, array $data)
    {
        return $this->categoryRepository->update($category, $data);
    }
}
