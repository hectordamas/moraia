<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')->orderBy('sort_order', 'asc')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:categories,id',
        ]);

        foreach ($request->input('order') as $position => $categoryId) {
            Category::where('id', $categoryId)->update(['sort_order' => $position + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Orden de categorías actualizado correctamente.',
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'seo_title' => 'nullable|string|max:150',
            'seo_description' => 'nullable|string|max:300',
            'image' => 'nullable|image|max:4096',
        ]);

        $uploadDir = public_path('uploads/categories');
        if (! file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $imagePath = 'images/categories/cat_pijamas.jpg';
        if ($request->hasFile('image')) {
            $filename = 'cat_'.uniqid().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $imagePath = 'uploads/categories/'.$filename;
        }

        $maxSort = Category::max('sort_order') ?? 0;

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'image_path' => $imagePath,
            'sort_order' => $validated['sort_order'] ?? ($maxSort + 1),
            'is_active' => ! empty($validated['is_active']),
            'is_featured' => ! empty($validated['is_featured']),
            'seo_title' => $validated['seo_title'] ?? $validated['name'].' | Moraia',
            'seo_description' => $validated['seo_description'] ?? $validated['description'],
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Categoría creada con éxito.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'seo_title' => 'nullable|string|max:150',
            'seo_description' => 'nullable|string|max:300',
            'image' => 'nullable|image|max:4096',
        ]);

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? $category->sort_order,
            'is_active' => ! empty($validated['is_active']),
            'is_featured' => ! empty($validated['is_featured']),
            'seo_title' => $validated['seo_title'] ?? $validated['name'].' | Moraia',
            'seo_description' => $validated['seo_description'] ?? $validated['description'],
        ];

        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/categories');
            if (! file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = 'cat_'.uniqid().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $data['image_path'] = 'uploads/categories/'.$filename;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Categoría actualizada con éxito.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Categoría eliminada.');
    }
}
