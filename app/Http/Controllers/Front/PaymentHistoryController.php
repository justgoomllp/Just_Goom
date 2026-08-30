<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PaymentLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentHistoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50], true) ? $perPage : 10;

        $payments = PaymentLog::query()
            ->with(['plan', 'userPlan'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('front.users.payment-history', [
            'payments' => $payments,
        ]);
    }

    public function invoice(Request $request, PaymentLog $paymentLog): Response
    {
        abort_unless((int) $paymentLog->user_id === (int) $request->user()->id, 404);
        abort_unless($paymentLog->isPaid(), 404);

        $paymentLog->loadMissing(['plan', 'userPlan', 'user.companyProfile']);
        $paymentLog->assignInvoiceNumber();

        $user = $paymentLog->user ?: $request->user();
        $plan = $paymentLog->plan;
        $userPlan = $paymentLog->userPlan;

        $pdf = Pdf::loadView('invoices.subscription', [
            'user' => $user,
            'plan' => $plan,
            'userPlan' => $userPlan,
            'paymentLog' => $paymentLog,
            'companyName' => 'JustGoom LLP',
        ])->setPaper('a4');

        $filename = ($paymentLog->invoice_number ?: 'invoice').'.pdf';

        return $pdf->download($filename);
    }
}
