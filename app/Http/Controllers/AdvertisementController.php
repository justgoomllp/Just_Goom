<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::orderByDesc('created_at')->paginate(15);

        return view('admin.advertisements.index', compact('advertisements'));
    }

    public function create()
    {
        return view('admin.advertisements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'banner_image' => 'required|image|max:2048',
            'link_url' => 'nullable|url|max:500',
            'position' => 'required|in:homepage,sidebar',
            'priority' => 'nullable|integer|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        $validated['banner_image'] = $request->file('banner_image')->store('advertisements', 'public');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['priority'] = $validated['priority'] ?? 0;

        Advertisement::create($validated);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Advertisement created successfully.');
    }

    public function edit(Advertisement $advertisement)
    {
        return view('admin.advertisements.edit', compact('advertisement'));
    }

    public function update(Request $request, Advertisement $advertisement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'banner_image' => 'nullable|image|max:2048',
            'link_url' => 'nullable|url|max:500',
            'position' => 'required|in:homepage,sidebar',
            'priority' => 'nullable|integer|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('banner_image')) {
            Storage::disk('public')->delete($advertisement->banner_image);
            $validated['banner_image'] = $request->file('banner_image')->store('advertisements', 'public');
        } else {
            unset($validated['banner_image']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['priority'] = $validated['priority'] ?? 0;

        $advertisement->update($validated);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Advertisement updated successfully.');
    }

    public function destroy(Advertisement $advertisement)
    {
        Storage::disk('public')->delete($advertisement->banner_image);
        $advertisement->delete();

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Advertisement deleted successfully.');
    }
}
