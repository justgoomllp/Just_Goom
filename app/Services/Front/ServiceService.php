<?php

namespace App\Services\Front;

use App\Models\Service;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ServiceService
{
    public function listForUser(User $user, ?string $type = null): LengthAwarePaginator
    {
        $query = $user->services()->latest();

        if ($type) {
            $query->where('type', $type);
        }

        return $query->paginate(10)->withQueryString();
    }

    public function listForPublicProfile(User $user, ?string $type = null): Collection
    {
        $query = $user->services()->latest();

        if ($type) {
            $query->where('type', $type);
        }

        return $query->get();
    }

    public function statsForUser(User $user): array
    {
        $total = $user->services()->count();
        $services = $user->services()->where('type', 'service')->count();
        $products = $user->services()->where('type', 'product')->count();

        return [
            'total' => $total,
            'services' => $services,
            'products' => $products,
            'active' => $total,
            'featured' => 0,
        ];
    }

    public function store(User $user, array $data): Service
    {
        return $user->services()->create([
            'type' => $data['type'] ?? 'service',
            'product_name' => $data['product_name'],
            'product_desc' => $data['product_desc'] ?? null,
            'product_image' => $this->uploadImage($data['product_image'] ?? null),
            'price' => $data['price'] ?? null,
        ]);
    }

    public function hasChanges(Service $service, array $data, mixed $image = null): bool
    {
        $type = $data['type'] ?? $service->type;
        $price = $data['price'] ?? null;
        $currentPrice = $service->price === null || $service->price === '' ? null : (string) $service->price;
        $newPrice = $price === null || $price === '' ? null : (string) $price;

        if ($type !== $service->type) {
            return true;
        }

        if ($data['product_name'] !== $service->product_name) {
            return true;
        }

        if (($data['product_desc'] ?? null) !== $service->product_desc) {
            return true;
        }

        if ($newPrice !== $currentPrice) {
            return true;
        }

        if ($image instanceof UploadedFile) {
            return true;
        }

        return false;
    }

    public function update(User $user, Service $service, array $data): Service
    {
        abort_unless($this->belongsToUser($service, $user), 404);

        $payload = [
            'type' => $data['type'] ?? $service->type,
            'product_name' => $data['product_name'],
            'product_desc' => $data['product_desc'] ?? null,
            'price' => $data['price'] ?? null,
        ];

        if (($data['product_image'] ?? null) instanceof UploadedFile) {
            $this->deleteImage($service->product_image);
            $payload['product_image'] = $this->uploadImage($data['product_image']);
        }

        $service->update($payload);

        return $service->fresh();
    }

    public function delete(User $user, Service $service): void
    {
        abort_unless($this->belongsToUser($service, $user), 404);

        DB::transaction(function () use ($service) {
            $this->deleteImage($service->product_image);
            $service->delete();
        });
    }

    public function belongsToUser(Service $service, User $user): bool
    {
        return (int) $service->user_id === (int) $user->id;
    }

    private function uploadImage(mixed $file): ?string
    {
        if (! $file instanceof UploadedFile) {
            return null;
        }

        $email = auth()->user()->email;
        $destination = public_path('uploads/' . $email . '/services');
        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0777, true);
        }

        $filename = time().'-'.Str::random(12).'.'.$file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/' . $email . '/services/' . $filename;
    }

    private function deleteImage(?string $image): void
    {
        if (! $image) {
            return;
        }

        $path = public_path($image);
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
