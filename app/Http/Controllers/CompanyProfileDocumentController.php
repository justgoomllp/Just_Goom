<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfileDocument;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CompanyProfileDocumentController extends Controller
{
    public function updateApproval(Request $request, User $user, CompanyProfileDocument $document): RedirectResponse
    {
        abort_unless((int) $document->user_id === (int) $user->id, 404);

        $approved = $request->boolean('approved');
        $document->update([
            'is_approved' => $approved
                ? CompanyProfileDocument::APPROVAL_APPROVED
                : CompanyProfileDocument::APPROVAL_UNAPPROVED,
        ]);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', $approved ? 'Document approved.' : 'Document unapproved.');
    }
}
