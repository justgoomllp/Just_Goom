<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\SubCategoryRequest;
use App\Models\SubCategory;
use App\Services\Admin\SubCategoryService;

class SubCategoryController extends Controller
{
    private $subCategoryService;

    public function __construct(SubCategoryService $subCategoryService)
    {
        $this->subCategoryService = $subCategoryService;
    }

    public function index()
    {
        $subCategories = $this->subCategoryService->getAll();

        return view('admin.sub_categories.index', compact('subCategories'));
    }

    public function create()
    {
        $categories = $this->subCategoryService->getCategories();

        return view('admin.sub_categories.create', compact('categories'));
    }

    public function store(SubCategoryRequest $request)
    {
        $this->subCategoryService->store($request->validated());

        return redirect()
            ->route('admin.sub-categories.index')
            ->with('success', 'Sub category saved successfully.');
    }

    public function edit(SubCategory $subCategory)
    {
        $categories = $this->subCategoryService->getCategories();

        return view('admin.sub_categories.edit', compact('subCategory', 'categories'));
    }

    public function update(SubCategoryRequest $request, SubCategory $subCategory)
    {
        $this->subCategoryService->update($subCategory, $request->validated());

        return redirect()
            ->route('admin.sub-categories.index')
            ->with('success', 'Sub category updated successfully.');
    }

    public function destroy(SubCategory $subCategory)
    {
        $this->subCategoryService->delete($subCategory);

        return redirect()
            ->route('admin.sub-categories.index')
            ->with('success', 'Sub category deleted successfully.');
    }
}
