<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Support\SafeText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $perPage = $this->resolvePerPage($request);

        $offers = $user->offers()
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total' => $user->offers()->count(),
            'active' => $user->offers()->active()->count(),
            'expired' => $user->offers()->where('end_date', '<', now()->toDateString())->count(),
        ];

        return view('front.users.offers', compact('offers', 'stats'));
    }

    public function create()
    {
        return view('front.users.offer-form', ['offer' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200', SafeText::titleRule()],
            'description' => 'nullable|string|max:2000',
            'banner_image' => 'nullable|image|max:2048',
            'link_url' => 'nullable|url|max:500',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'title.regex' => SafeText::titleMessage('Offer title'),
        ]);

        $offer = new Offer();
        $offer->user_id = $request->user()->id;
        $offer->title = $validated['title'];
        $offer->description = $validated['description'] ?? null;
        $offer->link_url = $validated['link_url'] ?? null;
        $offer->start_date = $validated['start_date'];
        $offer->end_date = $validated['end_date'];
        $offer->status = 'active';

        if ($request->hasFile('banner_image')) {
            $offer->banner_image = $this->uploadFile($request->file('banner_image'), 'offers');
        }

        $offer->save();

        return redirect()->route('front.users.offers')
            ->with('success', 'Offer created successfully.');
    }

    public function edit(Offer $offer)
    {
        $this->authorizeOffer($offer);

        return view('front.users.offer-form', compact('offer'));
    }

    public function update(Request $request, Offer $offer)
    {
        $this->authorizeOffer($offer);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200', SafeText::titleRule()],
            'description' => 'nullable|string|max:2000',
            'banner_image' => 'nullable|image|max:2048',
            'link_url' => 'nullable|url|max:500',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,paused',
        ], [
            'title.regex' => SafeText::titleMessage('Offer title'),
        ]);

        $offer->title = $validated['title'];
        $offer->description = $validated['description'] ?? null;
        $offer->link_url = $validated['link_url'] ?? null;
        $offer->start_date = $validated['start_date'];
        $offer->end_date = $validated['end_date'];
        $offer->status = $validated['status'];

        if ($request->hasFile('banner_image')) {
            $this->deleteFile($offer->banner_image);
            $offer->banner_image = $this->uploadFile($request->file('banner_image'), 'offers');
        }

        $offer->save();

        return redirect()->route('front.users.offers')
            ->with('success', 'Offer updated successfully.');
    }

    public function destroy(Offer $offer)
    {
        $this->authorizeOffer($offer);

        $this->deleteFile($offer->banner_image);

        $offer->delete();

        return redirect()->route('front.users.offers')
            ->with('success', 'Offer deleted successfully.');
    }

    public function updateStatus(Request $request, Offer $offer)
    {
        $this->authorizeOffer($offer);

        $validated = $request->validate([
            'status' => ['required', 'in:active,paused'],
        ]);

        $offer->update(['status' => $validated['status']]);

        return back()->with('success', 'Offer status updated.');
    }

    private function authorizeOffer(Offer $offer): void
    {
        abort_unless((int) $offer->user_id === (int) auth()->id(), 403);
    }

    private function resolvePerPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 10);

        return in_array($perPage, [10, 25, 50], true) ? $perPage : 10;
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
