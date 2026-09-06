<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RespondsToAdminAjax;
use App\Models\Advertisement;
use App\Support\AdminDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdvertisementController extends Controller
{
    use RespondsToAdminAjax;

    public function index()
    {
        return view('admin.advertisements.index');
    }

    public function datatable(Request $request)
    {
        $query = Advertisement::query();

        if ($request->filled('position')) {
            $query->where('position', $request->string('position'));
        }

        if ($request->input('is_active') !== null && $request->input('is_active') !== '') {
            $query->where('is_active', (int) $request->input('is_active'));
        }

        return AdminDataTable::of(
            $request,
            $query,
            [
                'title' => 'title',
                'position' => 'position',
                'priority' => 'priority',
                'period' => 'start_date',
                'status' => 'is_active',
            ],
            ['title', 'link_url'],
            function (Advertisement $ad, int $index) {
                $banner = '';
                if ($ad->banner_image) {
                    $banner = '<img src="'.e(asset('storage/'.$ad->banner_image)).'" alt="'.e($ad->title).'" style="height:40px; border-radius:4px;">';
                }

                $start = $ad->start_date ? $ad->start_date->format('d M Y') : '-';
                $end = $ad->end_date ? $ad->end_date->format('d M Y') : '-';

                return [
                    'DT_RowIndex' => $index,
                    'banner' => $banner ?: '<span class="text-muted">-</span>',
                    'title' => e($ad->title),
                    'position' => e(ucfirst((string) $ad->position)),
                    'priority' => e((string) $ad->priority),
                    'period' => e($start.' - '.$end),
                    'status' => AdminDataTable::statusToggle(
                        route('admin.advertisements.status', $ad),
                        (bool) $ad->is_active,
                        ['name' => 'is_active']
                    ),
                    'action' => AdminDataTable::actions(
                        route('admin.advertisements.edit', $ad),
                        route('admin.advertisements.destroy', $ad),
                        'Delete advertisement?',
                        'This advertisement will be removed.'
                    ),
                ];
            }
        );
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

    public function updateStatus(Request $request, Advertisement $advertisement)
    {
        $validated = $request->validate([
            'is_active' => ['required', Rule::in([0, 1])],
        ]);

        $isActive = (int) $validated['is_active'] === 1;
        $advertisement->update(['is_active' => $isActive]);

        return $this->adminResponse($request, 'Advertisement status updated to '.($isActive ? 'Active' : 'Inactive').'.');
    }

    public function destroy(Request $request, Advertisement $advertisement)
    {
        Storage::disk('public')->delete($advertisement->banner_image);
        $advertisement->delete();

        return $this->adminResponse($request, 'Advertisement deleted successfully.', false, 'admin.advertisements.index');
    }
}
