<?php

namespace App\Http\Requests\Front;

use App\Support\SafeText;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim((string) $this->input('title', '')),
            'file_type' => strtolower(trim((string) $this->input('file_type', 'pdf'))),
        ]);
    }

    public function rules(): array
    {
        $isUpdate = $this->route('document') !== null;
        $attachmentRules = $this->attachmentRulesForType($this->input('file_type', 'pdf'));

        if ($isUpdate) {
            array_unshift($attachmentRules, 'nullable');
        } else {
            array_unshift($attachmentRules, 'required');
        }

        return [
            'title' => ['required', 'string', 'min:2', 'max:200', SafeText::titleRule()],
            'file_type' => ['required', 'in:pdf,word,excel,image'],
            'attachment' => $attachmentRules,
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $file = $this->file('attachment');
            if (! $file || $validator->errors()->has('attachment')) {
                return;
            }

            $selected = $this->input('file_type');
            $detected = $this->detectFileTypeFromUpload($file);

            if ($selected !== $detected) {
                $validator->errors()->add(
                    'attachment',
                    'The uploaded file does not match the selected document type.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Document name is required.',
            'title.min' => 'Document name must be at least 2 characters.',
            'title.max' => 'Document name must not exceed 200 characters.',
            'title.regex' => SafeText::titleMessage('Document name'),
            'file_type.required' => 'Document type is required.',
            'file_type.in' => 'Please select a valid document type.',
            'attachment.required' => 'Please upload a document file.',
            'attachment.file' => 'Please upload a valid document file.',
            'attachment.mimes' => 'The uploaded file format is not allowed for the selected document type.',
            'attachment.max' => 'The uploaded file is too large for the selected document type.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'document name',
            'file_type' => 'document type',
            'attachment' => 'document file',
        ];
    }

    private function attachmentRulesForType(string $fileType): array
    {
        return match ($fileType) {
            'pdf' => ['file', 'mimes:pdf', 'max:5120'],
            'word' => ['file', 'mimes:doc,docx', 'max:5120'],
            'excel' => ['file', 'mimes:xls,xlsx,csv', 'max:5120'],
            'image' => ['file', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            default => ['file', 'max:5120'],
        };
    }

    private function detectFileTypeFromUpload($file): string
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
}
