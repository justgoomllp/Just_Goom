<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RespondsToAdminAjax;
use App\Http\Requests\Admin\AdvertisementRequest;
use App\Models\Advertisement;
use App\Models\User;
use App\Support\AdminDataTable;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $query = Advertisement::query()->with('user:id,fname,lname,email');

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
                    $banner = '<img src="'.e($ad->bannerUrl()).'" alt="'.e($ad->title).'" style="height:40px; border-radius:4px;">';
                }

                $start = $ad->start_date ? $ad->start_date->format('d M Y') : '-';
                $end = $ad->end_date ? $ad->end_date->format('d M Y') : '-';

                return [
                    'DT_RowIndex' => $index,
                    'banner' => $banner ?: '<span class="text-muted">-</span>',
                    'title' => e($ad->title),
                    'name' => e($ad->user?->fullName() ?: '-'),
                    'email' => e($ad->user?->email ?: '-'),
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
            },
            ['user' => ['fname', 'lname', 'email']]
        );
    }

    public function create()
    {
        return view('admin.advertisements.create', [
            'users' => $this->formUsers(),
        ]);
    }

    public function store(AdvertisementRequest $request)
    {
        $validated = $request->validated();
        $validated['banner_image'] = $this->storeBanner($request->file('banner_image'));
        $validated['priority'] = $validated['priority'] ?? 0;

        Advertisement::create($validated);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Advertisement created successfully.');
    }

    public function edit(Advertisement $advertisement)
    {
        return view('admin.advertisements.edit', [
            'advertisement' => $advertisement,
            'users' => $this->formUsers(),
        ]);
    }

    public function update(AdvertisementRequest $request, Advertisement $advertisement)
    {
        $validated = $request->validated();
        $validated['priority'] = $validated['priority'] ?? 0;

        if ($request->hasFile('banner_image')) {
            $this->deleteBanner($advertisement->banner_image);
            $validated['banner_image'] = $this->storeBanner($request->file('banner_image'));
        } else {
            unset($validated['banner_image']);
        }

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
        $this->deleteBanner($advertisement->banner_image);
        $advertisement->delete();

        return $this->adminResponse($request, 'Advertisement deleted successfully.', false, 'admin.advertisements.index');
    }

    /**
     * @return Collection<int, User>
     */
    private function formUsers(): Collection
    {
        return User::query()
            ->whereIn('type', ['user', 'agent'])
            ->orderBy('fname')
            ->orderBy('lname')
            ->get(['id', 'fname', 'lname', 'email']);
    }

    private function storeBanner(UploadedFile $file): string
    {
        $destination = public_path('advertisement');
        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = time().'-'.Str::random(12).'.'.$file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'advertisement/'.$filename;
    }

    private function deleteBanner(?string $path): void
    {
        if (! $path) {
            return;
        }

        $publicPath = public_path($path);
        if (File::exists($publicPath)) {
            File::delete($publicPath);

            return;
        }

        Storage::disk('public')->delete($path);
    }
}
