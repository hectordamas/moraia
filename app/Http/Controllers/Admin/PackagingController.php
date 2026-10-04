<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Packaging;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackagingController extends Controller
{
    public function index(): View
    {
        $packagings = Packaging::withCount('orders')
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('admin.packagings.index', compact('packagings'));
    }

    public function create(): View
    {
        return view('admin.packagings.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:1000',
            'capacity' => 'nullable|string|max:150',
            'price' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
            'image' => 'nullable|image|max:4096',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/packagings');
            if (! file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = 'pack_'.uniqid().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $imagePath = 'uploads/packagings/'.$filename;
        }

        $isDefault = ! empty($validated['is_default']);
        if ($isDefault) {
            Packaging::where('is_default', true)->update(['is_default' => false]);
        }

        $maxSort = Packaging::max('sort_order') ?? 0;

        Packaging::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'capacity' => $validated['capacity'] ?? null,
            'price' => $validated['price'] ?? 0.00,
            'image_path' => $imagePath,
            'sort_order' => $validated['sort_order'] ?? ($maxSort + 1),
            'is_active' => ! empty($validated['is_active']),
            'is_default' => $isDefault,
        ]);

        return redirect()->route('admin.packagings.index')->with('success', 'Empaque creado con éxito.');
    }

    public function edit(Packaging $packaging): View
    {
        return view('admin.packagings.edit', compact('packaging'));
    }

    public function update(Request $request, Packaging $packaging): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:1000',
            'capacity' => 'nullable|string|max:150',
            'price' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
            'image' => 'nullable|image|max:4096',
            'remove_image' => 'nullable|boolean',
        ]);

        $imagePath = $packaging->image_path;

        if (! empty($validated['remove_image']) && $imagePath && file_exists(public_path($imagePath))) {
            @unlink(public_path($imagePath));
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/packagings');
            if (! file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            if ($imagePath && file_exists(public_path($imagePath))) {
                @unlink(public_path($imagePath));
            }
            $filename = 'pack_'.uniqid().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $imagePath = 'uploads/packagings/'.$filename;
        }

        $isDefault = ! empty($validated['is_default']);
        if ($isDefault && ! $packaging->is_default) {
            Packaging::where('id', '!=', $packaging->id)->where('is_default', true)->update(['is_default' => false]);
        }

        $packaging->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'capacity' => $validated['capacity'] ?? null,
            'price' => $validated['price'] ?? 0.00,
            'image_path' => $imagePath,
            'sort_order' => $validated['sort_order'] ?? $packaging->sort_order,
            'is_active' => ! empty($validated['is_active']),
            'is_default' => $isDefault,
        ]);

        return redirect()->route('admin.packagings.index')->with('success', 'Empaque actualizado con éxito.');
    }

    public function destroy(Packaging $packaging): RedirectResponse
    {
        if ($packaging->image_path && file_exists(public_path($packaging->image_path))) {
            @unlink(public_path($packaging->image_path));
        }

        $packaging->delete();

        return redirect()->route('admin.packagings.index')->with('success', 'Empaque eliminado correctamente.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:packagings,id',
        ]);

        foreach ($request->input('order') as $position => $packagingId) {
            Packaging::where('id', $packagingId)->update(['sort_order' => $position + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Orden de empaques actualizado correctamente.',
        ]);
    }

    public function toggleActive(Packaging $packaging): JsonResponse|RedirectResponse
    {
        $packaging->update(['is_active' => ! $packaging->is_active]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $packaging->is_active,
                'message' => $packaging->is_active ? 'Empaque activado' : 'Empaque desactivado',
            ]);
        }

        return redirect()->back()->with('success', 'Estado del empaque actualizado.');
    }
}
