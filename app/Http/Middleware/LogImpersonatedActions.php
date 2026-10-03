<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use App\Models\User;
use App\Services\Front\AgentPortalService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @author KP PATEL
 */
class LogImpersonatedActions
{
    /** @var list<string> */
    private const SKIP_ROUTES = [
        'front.agent.leave-customer',
        'front.agent.customers.switch',
        'front.logout',
        'logout',
        'front.login.submit',
        'front.razorpay.webhook',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldLog($request, $response)) {
            return $response;
        }

        try {
            $this->write($request);
        } catch (Throwable) {
            // Never block the customer action if audit logging fails.
        }

        return $response;
    }

    private function shouldLog(Request $request, Response $response): bool
    {
        if (! $request->hasSession()) {
            return false;
        }

        if ((int) $request->session()->get(AgentPortalService::IMPERSONATOR_ID_KEY, 0) <= 0) {
            return false;
        }

        if (! in_array(strtoupper($request->method()), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return false;
        }

        if ($response->getStatusCode() >= 400) {
            return false;
        }

        $routeName = (string) $request->route()?->getName();
        if (in_array($routeName, self::SKIP_ROUTES, true)) {
            return false;
        }

        $path = ltrim($request->path(), '/');
        if (str_starts_with($path, '_debugbar') || str_starts_with($path, 'livewire')) {
            return false;
        }

        return true;
    }

    private function write(Request $request): void
    {
        $customer = $request->user();
        if (! $customer instanceof User) {
            return;
        }

        $agentId = (int) $request->session()->get(AgentPortalService::IMPERSONATOR_ID_KEY, 0);
        $agent = User::query()->find($agentId);
        if (! $agent) {
            return;
        }

        $routeName = (string) $request->route()?->getName();
        $summary = $this->actionSummary($request, $routeName);

        AuditLog::recordAgentSwitch(AuditLog::ACTION_ACTED, $agent, $customer, [
            'summary' => $summary,
            'method' => strtoupper($request->method()),
            'route' => $routeName !== '' ? $routeName : null,
            'path' => '/'.$request->path(),
        ], $request);
    }

    private function actionSummary(Request $request, string $routeName): string
    {
        $labels = [
            'front.users.profile.update' => 'Updated profile',
            'front.users.change-password.update' => 'Tried to change password',
            'front.users.documents.store' => 'Added a document',
            'front.users.documents.update' => 'Updated a document',
            'front.users.documents.status' => 'Changed document status',
            'front.users.documents.destroy' => 'Deleted a document',
            'front.users.services.store' => 'Added a service/product',
            'front.users.services.update' => 'Updated a service/product',
            'front.users.services.destroy' => 'Deleted a service/product',
            'front.users.projects.store' => 'Added a project',
            'front.users.projects.update' => 'Updated a project',
            'front.users.projects.status' => 'Changed project status',
            'front.users.projects.destroy' => 'Deleted a project',
            'front.users.articles.store' => 'Added an article',
            'front.users.articles.update' => 'Updated an article',
            'front.users.articles.status' => 'Changed article status',
            'front.users.articles.destroy' => 'Deleted an article',
            'front.users.videos.store' => 'Added a video',
            'front.users.videos.status' => 'Changed video status',
            'front.users.videos.destroy' => 'Deleted a video',
            'front.users.offers.store' => 'Added an offer',
            'front.users.offers.update' => 'Updated an offer',
            'front.users.offers.status' => 'Changed offer status',
            'front.users.offers.destroy' => 'Deleted an offer',
            'front.users.team.store' => 'Added a team member',
            'front.users.team.update' => 'Updated a team member',
            'front.users.team.destroy' => 'Deleted a team member',
            'front.users.inquiries.reply.store' => 'Replied to an inquiry',
            'front.users.inquiries.status' => 'Changed inquiry status',
            'front.users.notifications.mark-all-read' => 'Marked notifications read',
            'front.users.notifications.destroy' => 'Deleted a notification',
            'front.users.subscription.order' => 'Started a plan checkout',
            'front.users.subscription.verify' => 'Completed a plan payment',
            'front.users.subscription.failed' => 'Plan payment failed',
        ];

        if (isset($labels[$routeName])) {
            return $labels[$routeName];
        }

        if ($routeName !== '') {
            $pretty = trim(str_replace(['front.users.', 'front.', '.', '-', '_'], ['', '', ' ', ' ', ' '], $routeName));

            return ucfirst($pretty);
        }

        return strtoupper($request->method()).' /'.$request->path();
    }
}