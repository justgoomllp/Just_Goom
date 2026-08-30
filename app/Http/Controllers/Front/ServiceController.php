<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\ServiceRequest;
use App\Models\Service;
use App\Services\Front\ServiceService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(private ServiceService $serviceService)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $type = $request->query('type');

        return view('front.users.services', [
            'services' => $this->serviceService->listForUser($user, $type),
            'stats' => $this->serviceService->statsForUser($user),
        ]);
    }

    public function create()
    {
        return view('front.users.service-add');
    }

    public function store(ServiceRequest $request)
    {
        $this->serviceService->store($request->user(), $request->validated());

        return redirect()
            ->route('front.users.services')
            ->with('success', 'Service added successfully.');
    }

    public function edit(Request $request, Service $service)
    {
        abort_unless($this->serviceService->belongsToUser($service, $request->user()), 404);

        return view('front.users.service-edit', compact('service'));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        abort_unless($this->serviceService->belongsToUser($service, $request->user()), 404);

        $validated = $request->validated();

        if (! $this->serviceService->hasChanges($service, $validated, $request->file('product_image'))) {
            return redirect()
                ->route('front.users.services.edit', $service)
                ->with('info', 'No changes to save.');
        }

        $this->serviceService->update($request->user(), $service, $validated);

        return redirect()
            ->route('front.users.services')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Request $request, Service $service)
    {
        abort_unless($this->serviceService->belongsToUser($service, $request->user()), 404);

        $this->serviceService->delete($request->user(), $service);

        return redirect()
            ->route('front.users.services')
            ->with('success', 'Service removed successfully.');
    }
}
