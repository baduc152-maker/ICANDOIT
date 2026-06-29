<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TransactionCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'income' => TransactionCategory::income()->withCount('transactions')->orderBy('name')->get(),
            'expense' => TransactionCategory::expense()->withCount('transactions')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        TransactionCategory::create($this->validateData($request));

        return back()->with('status', 'Đã thêm danh mục.');
    }

    public function update(Request $request, TransactionCategory $category): RedirectResponse
    {
        $category->update($this->validateData($request, $category));

        return back()->with('status', 'Đã cập nhật danh mục.');
    }

    public function destroy(TransactionCategory $category): RedirectResponse
    {
        $category->delete();

        return back()->with('status', 'Đã xóa danh mục.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request, ?TransactionCategory $category = null): array
    {
        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('transaction_categories', 'name')
                    ->where(fn ($q) => $q->where('type', $request->input('type')))
                    ->ignore($category),
            ],
            'type' => ['required', 'in:income,expense'],
        ], [], [
            'name' => 'tên danh mục',
            'type' => 'loại',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        return $validated;
    }
}
