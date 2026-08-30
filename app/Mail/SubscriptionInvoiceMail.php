<?php

namespace App\Mail;

use App\Models\PaymentLog;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Plan $plan,
        public UserPlan $userPlan,
        public PaymentLog $paymentLog,
    ) {
    }

    public function envelope(): Envelope
    {
        $invoice = $this->paymentLog->invoice_number ?: 'Invoice';

        return new Envelope(
            subject: $invoice.' — '.$this->plan->name.' plan purchase on '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-invoice',
        );
    }
}
