<?php

namespace App\Http\Controllers\Api;

use App\Domain\Category\Actions\StoreCategoryAction;
use App\Domain\Category\Actions\UpdateCategoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Infrastructure\Persistence\Repositories\EloquentCategoryReponsitory;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class categoryController extends Controller
{
    private $categoryRepository;

    public function __construct(EloquentCategoryReponsitory $repository)
    {
        $this->categoryRepository = $repository;
    }

    public function index(): Response
    {
        return Inertia::render('Categories/Index', [
            'categories' => $this->categoryRepository->getAllWithCategories()
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Categories/Create');
    }

    public function store(StoreCategoryRequest $request,StoreCategoryAction $storeAction): RedirectResponse
    {
        return $storeAction->execute($request->validated());
    }

    public function edit(string $id): Response
    {
        $category = $this->categoryRepository->find($id);
        return Inertia::render('Categories/Edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, string $id, UpdateCategoryAction $updateAction): RedirectResponse
    {
        return  $updateAction->execute($id, $request->validated());
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->categoryRepository->delete($id);
        return to_route('categories.index');
    }
}
