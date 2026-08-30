<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Support\ProjectSection;
use App\Support\SafeText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->loadMissing('category');
        $sectionType = ProjectSection::forUser($user);
        $copy = ProjectSection::copy($sectionType);
        $perPage = $this->resolvePerPage($request);

        $projects = $user->projects()
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        $stats = $this->buildStats($user, $sectionType);

        return view('front.users.projects', compact('projects', 'stats', 'sectionType', 'copy'));
    }

    public function create(Request $request)
    {
        $user = $request->user()->loadMissing('category');
        $sectionType = ProjectSection::forUser($user);
        $copy = ProjectSection::copy($sectionType);

        return view('front.users.project-add', compact('sectionType', 'copy'));
    }

    public function store(Request $request)
    {
        $user = $request->user()->loadMissing('category');
        $sectionType = ProjectSection::forUser($user);
        $validated = $this->validateProject($request, $sectionType, false);

        $project = new Project();
        $project->user_id = $user->id;
        $project->title = $validated['title'];
        $project->description = $validated['description'] ?? null;
        $project->section_type = $sectionType;
        $project->status = 1;
        $project->meta = $this->buildMeta($validated, $sectionType);

        if ($sectionType === ProjectSection::NORMAL) {
            $project->type = $validated['type'];
            if ($request->hasFile('file')) {
                $project->file_path = $this->uploadFile($request->file('file'), 'projects');
            }
            if (! empty($validated['external_url'])) {
                $project->external_url = $validated['external_url'];
            }
            if ($request->hasFile('thumbnail')) {
                $project->thumbnail = $this->uploadFile($request->file('thumbnail'), 'projects/thumbnails');
            }
        } else {
            $project->type = ProjectSection::mediaTypeFor($sectionType);
            if (! empty($validated['external_url'])) {
                $project->external_url = $validated['external_url'];
            }

            if (ProjectSection::usesGalleryMedia($sectionType)) {
                $paths = $this->uploadMediaFiles($request);
                $project->media = implode(',', $paths);
                $project->thumbnail = $paths[0] ?? null;
                $project->meta = array_merge($project->meta ?? [], [
                    'photo_count' => count($paths),
                ]);
            } elseif ($request->hasFile('thumbnail')) {
                $project->thumbnail = $this->uploadFile($request->file('thumbnail'), 'projects/thumbnails');
            }
        }

        $project->save();

        return redirect()->route('front.users.projects')
            ->with('success', ProjectSection::label($sectionType).' added successfully.');
    }

    public function edit(Request $request, Project $project)
    {
        $this->authorizeProject($project);

        $user = $request->user()->loadMissing('category');
        // Always follow the user's category (e.g. category_id=3 → real estate),
        // not a stale project.section_type from an older save.
        $sectionType = ProjectSection::forUser($user);
        $copy = ProjectSection::copy($sectionType);

        return view('front.users.project-edit', compact('project', 'sectionType', 'copy'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorizeProject($project);

        $sectionType = ProjectSection::forUser($request->user()->loadMissing('category'));
        $validated = $this->validateProject($request, $sectionType, true);

        $project->title = $validated['title'];
        $project->description = $validated['description'] ?? null;
        $project->section_type = $sectionType;
        $project->meta = $this->buildMeta($validated, $sectionType, $project->meta ?? []);

        if ($sectionType === ProjectSection::NORMAL) {
            $project->type = $validated['type'];
            if ($request->hasFile('file')) {
                $this->deleteFile($project->file_path);
                $project->file_path = $this->uploadFile($request->file('file'), 'projects');
            }
            if (! empty($validated['external_url'])) {
                $project->external_url = $validated['external_url'];
            } elseif (($validated['type'] ?? '') !== 'link') {
                $project->external_url = null;
            }
            if ($request->hasFile('thumbnail')) {
                $this->deleteFile($project->thumbnail);
                $project->thumbnail = $this->uploadFile($request->file('thumbnail'), 'projects/thumbnails');
            }
        } else {
            $project->type = ProjectSection::mediaTypeFor($sectionType);
            $project->external_url = $validated['external_url'] ?? $project->external_url;

            if (ProjectSection::usesGalleryMedia($sectionType)) {
                $existing = $project->mediaImages();
                $remove = array_filter((array) $request->input('remove_media', []));
                if ($remove) {
                    foreach ($remove as $path) {
                        $this->deleteFile($path);
                    }
                    $existing = array_values(array_filter(
                        $existing,
                        fn ($path) => ! in_array($path, $remove, true)
                    ));
                }

                $uploaded = $this->uploadMediaFiles($request);
                $all = array_values(array_filter(array_merge($existing, $uploaded)));

                if (count($all) === 0) {
                    return back()
                        ->withInput()
                        ->withErrors(['media' => 'Please keep or upload at least one listing image.']);
                }

                if (count($all) > 12) {
                    return back()
                        ->withInput()
                        ->withErrors(['media' => 'You may have a maximum of 12 images.']);
                }

                $project->media = implode(',', $all);
                $project->thumbnail = $all[0] ?? null;
                $project->meta = array_merge($project->meta ?? [], [
                    'photo_count' => count($all),
                ]);
            } elseif ($request->hasFile('thumbnail')) {
                $this->deleteFile($project->thumbnail);
                $project->thumbnail = $this->uploadFile($request->file('thumbnail'), 'projects/thumbnails');
            }
        }

        $project->save();

        return redirect()->route('front.users.projects')
            ->with('success', ProjectSection::label($sectionType).' updated successfully.');
    }

    public function destroy(Project $project)
    {
        $this->authorizeProject($project);

        $this->deleteFile($project->file_path);
        $this->deleteFile($project->thumbnail);
        foreach ($project->mediaImages() as $path) {
            if ($path !== $project->thumbnail) {
                $this->deleteFile($path);
            }
        }

        $project->delete();

        return redirect()->route('front.users.projects')
            ->with('success', 'Deleted successfully.');
    }

    public function updateStatus(Request $request, Project $project)
    {
        $this->authorizeProject($project);

        $validated = $request->validate([
            'status' => ['required', 'in:0,1'],
        ]);

        $project->update(['status' => (int) $validated['status']]);

        return back()->with('success', 'Status updated.');
    }

    private function validateProject(Request $request, string $sectionType, bool $isUpdate): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:200', SafeText::titleRule()],
            'description' => 'nullable|string|max:2000',
            'external_url' => 'nullable|url|max:500',
        ];

        if ($sectionType === ProjectSection::NORMAL) {
            $rules['thumbnail'] = ['nullable', 'image', 'max:2048'];
            $rules['type'] = 'required|in:document,video,link';
            $rules['file'] = [
                Rule::requiredIf(fn () => ! $isUpdate && $request->input('type') !== 'link'),
                'nullable',
                'file',
                'max:102400',
                'mimes:pdf,doc,docx,ppt,pptx,mp4,avi,mov,wmv',
            ];
            $rules['external_url'] = 'required_if:type,link|nullable|url|max:500';
        }

        if ($sectionType === ProjectSection::REAL_ESTATE) {
            $hasExistingMedia = $isUpdate && filled($request->route('project')?->media ?? $request->route('project')?->thumbnail);
            $rules = array_merge($rules, [
                'price' => 'required|string|max:100',
                'emi' => 'nullable|string|max:150',
                'location' => 'required|string|max:200',
                'config' => 'nullable|string|max:100',
                'sale_type' => 'nullable|string|max:100',
                'possession' => 'nullable|string|max:100',
                'parking' => 'nullable|string|max:100',
                'amenities' => 'nullable|string|max:500',
                'media' => [
                    Rule::requiredIf(fn () => ! $isUpdate || ! $hasExistingMedia),
                    'nullable',
                    'array',
                    'max:12',
                ],
                'media.*' => ['image', 'max:2048'],
                'remove_media' => 'nullable|array',
                'remove_media.*' => 'string|max:500',
            ]);
        }

        if ($sectionType === ProjectSection::ENGINEERING) {
            $hasExistingMedia = $isUpdate && filled($request->route('project')?->media ?? $request->route('project')?->thumbnail);
            $rules = array_merge($rules, [
                'price' => 'required|string|max:100',
                'price_note' => 'nullable|string|max:150',
                'location' => 'required|string|max:200',
                'service_type' => 'nullable|string|max:100',
                'domain' => 'nullable|string|max:100',
                'lead_time' => 'nullable|string|max:100',
                'capacity' => 'nullable|string|max:100',
                'features' => 'nullable|string|max:500',
                'media' => [
                    Rule::requiredIf(fn () => ! $isUpdate || ! $hasExistingMedia),
                    'nullable',
                    'array',
                    'max:12',
                ],
                'media.*' => ['image', 'max:2048'],
                'remove_media' => 'nullable|array',
                'remove_media.*' => 'string|max:500',
            ]);
        }

        if ($sectionType === ProjectSection::ECOMMERCE) {
            $rules = array_merge($rules, [
                'price' => 'required|string|max:100',
                'thumbnail' => [
                    ! $isUpdate ? 'required' : 'nullable',
                    'image',
                    'max:2048',
                ],
            ]);
        }

        return $request->validate($rules, [
            'title.regex' => SafeText::titleMessage('Title'),
            'file.max' => 'The uploaded file may not be greater than 100MB.',
            'file.mimes' => 'Allowed file types: PDF, DOC, DOCX, PPT, PPTX, MP4, AVI, MOV, WMV.',
            'thumbnail.required' => 'Please upload an image.',
            'media.required' => 'Please upload at least one listing image.',
            'media.max' => 'You may upload a maximum of 12 images.',
            'media.*.image' => 'Each listing file must be an image.',
            'media.*.max' => 'Each image may not be greater than 2MB.',
            'price.required' => 'Price is required.',
            'location.required' => 'Location is required.',
        ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @param  array<string, mixed>  $existing
     * @return array<string, mixed>|null
     */
    private function buildMeta(array $validated, string $sectionType, array $existing = []): ?array
    {
        if ($sectionType === ProjectSection::REAL_ESTATE) {
            return array_merge($existing, [
                'price' => $validated['price'] ?? null,
                'emi' => $validated['emi'] ?? null,
                'location' => $validated['location'] ?? null,
                'config' => $validated['config'] ?? null,
                'sale_type' => $validated['sale_type'] ?? null,
                'possession' => $validated['possession'] ?? null,
                'parking' => $validated['parking'] ?? null,
                'amenities' => $validated['amenities'] ?? null,
            ]);
        }

        if ($sectionType === ProjectSection::ENGINEERING) {
            return array_merge($existing, [
                'price' => $validated['price'] ?? null,
                'price_note' => $validated['price_note'] ?? null,
                'location' => $validated['location'] ?? null,
                'service_type' => $validated['service_type'] ?? null,
                'domain' => $validated['domain'] ?? null,
                'lead_time' => $validated['lead_time'] ?? null,
                'capacity' => $validated['capacity'] ?? null,
                'features' => $validated['features'] ?? null,
            ]);
        }

        if ($sectionType === ProjectSection::ECOMMERCE) {
            return array_merge($existing, [
                'price' => $validated['price'] ?? null,
            ]);
        }

        return null;
    }

    /**
     * @return list<string>
     */
    private function uploadMediaFiles(Request $request): array
    {
        if (! $request->hasFile('media')) {
            return [];
        }

        $paths = [];
        foreach ($request->file('media') as $file) {
            if ($file && $file->isValid()) {
                $paths[] = $this->uploadFile($file, 'projects/media');
            }
        }

        return $paths;
    }

    private function buildStats($user, string $sectionType): array
    {
        $base = ['total' => $user->projects()->count()];

        if ($sectionType === ProjectSection::REAL_ESTATE) {
            return $base + [
                'listings' => $user->projects()->where('section_type', ProjectSection::REAL_ESTATE)->count(),
                'active' => $user->projects()->where('status', 1)->count(),
                'inactive' => $user->projects()->where('status', 0)->count(),
            ];
        }

        if ($sectionType === ProjectSection::ENGINEERING) {
            return $base + [
                'listings' => $user->projects()->where('section_type', ProjectSection::ENGINEERING)->count(),
                'active' => $user->projects()->where('status', 1)->count(),
                'inactive' => $user->projects()->where('status', 0)->count(),
            ];
        }

        if ($sectionType === ProjectSection::ECOMMERCE) {
            return $base + [
                'products' => $user->projects()->where('section_type', ProjectSection::ECOMMERCE)->count(),
                'active' => $user->projects()->where('status', 1)->count(),
                'inactive' => $user->projects()->where('status', 0)->count(),
            ];
        }

        return $base + [
            'documents' => $user->projects()->where('type', 'document')->count(),
            'videos' => $user->projects()->where('type', 'video')->count(),
            'links' => $user->projects()->where('type', 'link')->count(),
        ];
    }

    private function authorizeProject(Project $project): void
    {
        abort_unless((int) $project->user_id === (int) auth()->id(), 403);
    }

    private function resolvePerPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 10);

        return in_array($perPage, [10, 25, 50], true) ? $perPage : 10;
    }

    private function uploadFile($file, string $subfolder): string
    {
        $email = auth()->user()->email;
        $destination = public_path('uploads/'.$email.'/'.$subfolder);
        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0777, true);
        }

        $filename = time().'-'.Str::random(12).'.'.$file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/'.$email.'/'.$subfolder.'/'.$filename;
    }

    private function deleteFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $fullPath = public_path($path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
