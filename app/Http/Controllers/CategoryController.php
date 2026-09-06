<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RespondsToAdminAjax;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    use RespondsToAdminAjax;

    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return view('admin.categories.index');
    }

    public function datatable(Request $request)
    {
        return $this->categoryService->datatable($request);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $this->categoryService->store($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category saved successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $this->categoryService->update($category, $request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function updateStatus(Request $request, Category $category)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in([0, 1])],
        ]);

        $status = (int) $validated['status'] === 1;
        $this->categoryService->updateStatus($category, $status);

        return $this->adminResponse($request, 'Category status updated to '.($status ? 'Active' : 'Inactive').'.');
    }

    public function destroy(Request $request, Category $category)
    {
        $this->categoryService->delete($category);

        return $this->adminResponse($request, 'Category deleted successfully.', false, 'admin.categories.index');
    }
}
