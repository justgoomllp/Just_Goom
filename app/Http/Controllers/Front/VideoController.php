<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\UserPlan;
use App\Support\SafeText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $perPage = $this->resolvePerPage($request);

        $videos = DB::table('videos')
            ->where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        $planLimits = $this->getPlanLimits($user);

        $stats = [
            'total' => DB::table('videos')->where('user_id', $user->id)->whereNull('deleted_at')->count(),
            'max_allowed' => $planLimits['max_video_count'],
            'max_size_mb' => $planLimits['max_video_size_mb'],
        ];

        return view('front.users.videos', compact('videos', 'stats'));
    }

    public function create(Request $request)
    {
        $planLimits = $this->getPlanLimits($request->user());

        return view('front.users.video-form', [
            'video' => null,
            'planLimits' => $planLimits,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $planLimits = $this->getPlanLimits($user);

        $currentCount = DB::table('videos')
            ->where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->count();

        if ($planLimits['max_video_count'] > 0 && $currentCount >= $planLimits['max_video_count']) {
            return back()->with('error', "You have reached your video upload limit ({$planLimits['max_video_count']} videos). Please upgrade your plan.");
        }

        $maxSize = $planLimits['max_video_size_mb'] > 0
            ? $planLimits['max_video_size_mb'] * 1024
            : 51200;

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200', SafeText::titleRule()],
            'link' => 'required_without:video_file|nullable|url|max:500',
            'video_file' => "required_without:link|nullable|file|max:{$maxSize}|mimes:mp4,avi,mov,wmv,webm",
            'thumbnail' => 'nullable|image|max:2048',
        ], [
            'title.required' => 'Video title is required.',
            'title.regex' => SafeText::titleMessage('Video title'),
            'link.required_without' => 'Provide an external video URL or upload a video file.',
            'video_file.required_without' => 'Provide an external video URL or upload a video file.',
            'link.url' => 'Enter a valid video URL.',
            'thumbnail.image' => 'Thumbnail must be an image file.',
            'thumbnail.max' => 'Thumbnail image may not be greater than 2MB.',
        ]);

        $link = $validated['link'] ?? null;

        if ($request->hasFile('video_file')) {
            $link = $this->uploadFile($request->file('video_file'), 'videos');
        }

        $thumbnail = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $this->uploadFile($request->file('thumbnail'), 'videos/thumbnails');
        }

        DB::table('videos')->insert([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'link' => $link,
            'thumbnail' => $thumbnail,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('front.users.videos')
            ->with('success', 'Video added successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $video = DB::table('videos')
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        abort_unless($video, 404);

        if ($video->link && ! str_starts_with($video->link, 'http')) {
            $this->deleteFile($video->link);
        }

        if (! empty($video->thumbnail)) {
            $this->deleteFile($video->thumbnail);
        }

        DB::table('videos')
            ->where('id', $id)
            ->update(['deleted_at' => now()]);

        return redirect()->route('front.users.videos')
            ->with('success', 'Video deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $video = DB::table('videos')
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->whereNull('deleted_at')
            ->first();

        abort_unless($video, 404);

        $validated = $request->validate([
            'status' => ['required', 'in:0,1'],
        ]);

        DB::table('videos')
            ->where('id', $id)
            ->update([
                'status' => (int) $validated['status'],
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Video status updated.');
    }

    private function resolvePerPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 10);

        return in_array($perPage, [10, 25, 50], true) ? $perPage : 10;
    }

    private function getPlanLimits($user): array
    {
        $activePlan = UserPlan::where('user_id', $user->id)
            ->where('next_purchase_date', '>=', now()->toDateString())
            ->orderByDesc('next_purchase_date')
            ->first();

        if ($activePlan && $activePlan->plan) {
            return [
                'max_video_count' => $activePlan->plan->max_video_count ?? 0,
                'max_video_size_mb' => $activePlan->plan->max_video_size_mb ?? 0,
            ];
        }

        return [
            'max_video_count' => 0,
            'max_video_size_mb' => 0,
        ];
    }

    private function uploadFile($file, string $subfolder): string
    {
        $email = auth()->user()->email;
        $destination = public_path('uploads/' . $email . '/' . $subfolder);
        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0777, true);
        }

        $filename = time() . '-' . Str::random(12) . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/' . $email . '/' . $subfolder . '/' . $filename;
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
