<?php

namespace App\Services\PSB;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    private $allowedImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    private $allowedDocumentTypes = ['application/pdf'];
    private $maxImageSize = 2048; // 2MB
    private $maxDocumentSize = 5120; // 5MB

    public function uploadFile(UploadedFile $file, string $path, string $type = 'document'): array
    {
        try {
            // Validasi tipe file
            if ($type === 'image' && !in_array($file->getMimeType(), $this->allowedImageTypes)) {
                throw new \Exception('Format file tidak didukung. Gunakan format JPG, JPEG, atau PNG');
            }

            if ($type === 'document' && !in_array($file->getMimeType(), $this->allowedDocumentTypes)) {
                throw new \Exception('Format file tidak didukung. Gunakan format PDF');
            }

            // Validasi ukuran file
            $maxSize = $type === 'image' ? $this->maxImageSize : $this->maxDocumentSize;
            if ($file->getSize() > ($maxSize * 1024)) {
                throw new \Exception("Ukuran file maksimal {$maxSize}MB");
            }

            // Generate nama file unik
            $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            
            // Upload file
            $filePath = $file->storeAs($path, $fileName, 'public');

            if (!$filePath) {
                throw new \Exception('Gagal mengupload file');
            }

            return [
                'status' => 'success',
                'file_path' => $filePath,
                'file_name' => $fileName,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function deleteFile(string $filePath): bool
    {
        try {
            if (Storage::disk('public')->exists($filePath)) {
                return Storage::disk('public')->delete($filePath);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
} 