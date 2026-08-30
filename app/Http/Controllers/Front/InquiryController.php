<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Services\Front\InquiryService;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function __construct(private InquiryService $inquiryService)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        return view('front.users.inquiries', [
            'inquiries' => $this->inquiryService->listForUser($user),
            'stats' => $this->inquiryService->statsForUser($user),
        ]);
    }

    public function show(Request $request, Inquiry $inquiry)
    {
        abort_unless($this->inquiryService->belongsToUser($inquiry, $request->user()), 404);

        return view('front.users.inquiry-view', compact('inquiry'));
    }

    public function reply(Request $request, Inquiry $inquiry)
    {
        abort_unless($this->inquiryService->belongsToUser($inquiry, $request->user()), 404);

        return view('front.users.inquiry-reply', compact('inquiry'));
    }

    public function storeReply(Request $request, Inquiry $inquiry)
    {
        abort_unless($this->inquiryService->belongsToUser($inquiry, $request->user()), 404);

        $validated = $request->validate([
            'reply' => ['required', 'string', 'min:5', 'max:5000'],
        ], [
            'reply.required' => 'Your reply is required.',
            'reply.min' => 'Reply must be at least 5 characters.',
            'reply.max' => 'Reply must not exceed 5000 characters.',
        ]);

        $this->inquiryService->reply($inquiry, $validated['reply']);

        return redirect()
            ->route('front.users.inquiries.show', $inquiry)
            ->with('success', 'Reply sent successfully.');
    }

    public function updateStatus(Request $request, Inquiry $inquiry)
    {
        abort_unless($this->inquiryService->belongsToUser($inquiry, $request->user()), 404);

        $validated = $request->validate([
            'status' => ['required', 'in:new,replied'],
        ]);

        if ($validated['status'] === Inquiry::STATUS_REPLIED && blank($inquiry->reply)) {
            return redirect()
                ->route('front.users.inquiries.reply', $inquiry)
                ->with('info', 'Write a reply to mark this inquiry as replied.');
        }

        $this->inquiryService->updateStatus($inquiry, $validated['status']);

        return back()->with('success', 'Inquiry status updated.');
    }
}
