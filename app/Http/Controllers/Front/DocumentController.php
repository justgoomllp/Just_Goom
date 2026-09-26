<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\Concerns\GuardsPlanLimits;
use App\Http\Requests\Front\DocumentRequest;
use App\Models\Document;
use App\Services\Front\DocumentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    use GuardsPlanLimits;

    public function __construct(private DocumentService $documentService)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $perPage = $this->resolvePerPage($request);

        return view('front.users.documents', [
            'documents' => $this->documentService->listForUser($user, $perPage),
            'stats' => $this->documentService->statsForUser($user),
            'planQuota' => $this->planQuota($user, 'documents'),
        ]);
    }

    public function create(Request $request)
    {
        if ($denied = $this->denyIfPlanLimitClosed($request->user(), 'documents')) {
            return $denied;
        }

        return view('front.users.document-add');
    }

    public function store(DocumentRequest $request)
    {
        if ($denied = $this->denyIfPlanLimitClosed($request->user(), 'documents')) {
            return $denied;
        }

        $this->documentService->store($request->user(), $request->validated());

        return redirect()
            ->route('front.users.documents')
            ->with('success', 'Document uploaded successfully.');
    }

    public function edit(Request $request, Document $document)
    {
        abort_unless($this->documentService->belongsToUser($document, $request->user()), 404);

        return view('front.users.document-edit', compact('document'));
    }

    public function update(DocumentRequest $request, Document $document)
    {
        abort_unless($this->documentService->belongsToUser($document, $request->user()), 404);

        $this->documentService->update($request->user(), $document, $request->validated());

        return redirect()
            ->route('front.users.documents')
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(Request $request, Document $document)
    {
        abort_unless($this->documentService->belongsToUser($document, $request->user()), 404);

        $this->documentService->delete($request->user(), $document);

        return redirect()
            ->route('front.users.documents')
            ->with('success', 'Document removed successfully.');
    }

    public function updateStatus(Request $request, Document $document)
    {
        abort_unless($this->documentService->belongsToUser($document, $request->user()), 404);

        $validated = $request->validate([
            'status' => ['required', 'in:0,1'],
        ]);

        $this->documentService->updateStatus($document, (int) $validated['status']);

        return back()->with('success', 'Document status updated.');
    }

    private function resolvePerPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 10);

        return in_array($perPage, [10, 25, 50], true) ? $perPage : 10;
    }
}
