<?php

namespace App\Contracts;

interface CategoryRepositoryInterface
{
    public function getAllCategories(): array;

    public function findCategoryById(int $id);

    public function createCategory(array $data);

    public function updateCategory(int $id, array $data): bool;

    public function deleteCategory(int $id): bool;
}
