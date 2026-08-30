<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50], true) ? $perPage : 10;

        $logs = AuditLog::query()
            ->with(['plan', 'fromPlan'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('front.users.audit-logs', [
            'logs' => $logs,
        ]);
    }
}
