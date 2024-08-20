<?php

namespace App\Repositories;

use App\Models\Category;
use App\Contracts\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllCategories(): array
    {
        return Category::all()->toArray();
    }

    public function findCategoryById(int $id): ?Category
    {
        return Category::find($id);
    }

    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    public function updateCategory(int $id, array $data): bool
    {
        $category = Category::find($id);

        if ($category) {
            return $category->update($data);
        }

        return false;
    }

    public function deleteCategory(int $id): bool
    {
        $category = Category::find($id);

        if ($category) {
            return $category->delete();
        }

        return false;
    }
}
