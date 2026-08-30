<?php

namespace App\Services\Front;

use App\Models\Document;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DocumentService
{
    public function listForUser(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $user->documents()
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function listForPublicProfile(User $user): Collection
    {
        return $user->documents()
            ->where('status', 1)
            ->latest()
            ->get();
    }

    public function statsForUser(User $user): array
    {
        $documents = $user->documents()->get(['file_type']);

        return [
            'total' => $documents->count(),
            'pdf' => $documents->where('file_type', 'pdf')->count(),
            'other' => $documents->whereIn('file_type', ['word', 'excel', 'image'])->count(),
        ];
    }

    public function store(User $user, array $data): Document
    {
        return $user->documents()->create([
            'title' => $data['title'],
            'attachment' => $this->uploadFile($data['attachment']),
            'file_type' => $data['file_type'],
            'status' => (int) ($data['status'] ?? 1),
        ]);
    }

    public function update(User $user, Document $document, array $data): Document
    {
        abort_unless($this->belongsToUser($document, $user), 404);

        $payload = [
            'title' => $data['title'],
            'file_type' => $data['file_type'],
        ];

        if (array_key_exists('status', $data)) {
            $payload['status'] = (int) $data['status'];
        }

        if (($data['attachment'] ?? null) instanceof UploadedFile) {
            $this->deleteFile($document->attachment);
            $payload['attachment'] = $this->uploadFile($data['attachment']);
        }

        $document->update($payload);

        return $document->fresh();
    }

    public function delete(User $user, Document $document): void
    {
        abort_unless($this->belongsToUser($document, $user), 404);

        DB::transaction(function () use ($document) {
            $this->deleteFile($document->attachment);
            $document->delete();
        });
    }

    public function belongsToUser(Document $document, User $user): bool
    {
        return (int) $document->user_id === (int) $user->id;
    }

    public function updateStatus(Document $document, int $status): Document
    {
        $document->update(['status' => $status ? 1 : 0]);

        return $document->fresh();
    }

    public function detectFileType(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $mime = strtolower((string) $file->getMimeType());

        if ($extension === 'pdf' || str_contains($mime, 'pdf')) {
            return 'pdf';
        }

        if (in_array($extension, ['doc', 'docx'], true) || str_contains($mime, 'word')) {
            return 'word';
        }

        if (in_array($extension, ['xls', 'xlsx', 'csv'], true) || str_contains($mime, 'sheet') || str_contains($mime, 'excel')) {
            return 'excel';
        }

        return 'image';
    }

    private function uploadFile(UploadedFile $file): string
    {
        $email = auth()->user()->email;
        $destination = public_path('uploads/' . $email . '/documents');
        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0777, true);
        }

        $filename = time().'-'.Str::random(12).'.'.$file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/' . $email . '/documents/' . $filename;
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
