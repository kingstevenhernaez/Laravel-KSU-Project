<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class FileManager extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'file_type',
        'storage_type',
        'original_name',
        'file_name',
        'user_id',
        'path',
        'extension',
        'size',
        'external_link',
    ];

    public function upload($to, $file, $name = NULL, $id = NULL)
    {

        try {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $size = $file->getSize();
            if ($name == '') {
                $file_name = rand(000,999).time() . '.' . $extension;
            } else {
                $file_name = $name . '-' . time() . '.' . $extension;
            }
            $file_name = str_replace(' ', '_', $file_name);

            // Store and track using the SAME disk to avoid "no image" issues when previewing.
            $disk = config('app.STORAGE_DRIVER') ?: config('filesystems.default');

            Storage::disk($disk)
                ->put('uploads/' . $to . '/' . $file_name, file_get_contents($file->getRealPath()));

            if ($disk == 'public') {
                // Windows/Laragon safety: if symlink isn't available, copy public storage
                if (!function_exists('symlink')) {
                    copyFolder(storage_path('app/public'), public_path() . "/storage/");
                }
            }

            $fileManager = (is_null($id)) ? new self() : self::find($id);
            $fileManager = is_null($fileManager) ? new self() : $fileManager;
            $fileManager->file_type = $file->getMimeType();
            $fileManager->storage_type = $disk;
            $fileManager->original_name = $originalName;
            $fileManager->file_name = $file_name;
            $fileManager->user_id = auth()->id();
            $fileManager->path = 'uploads/' . $to . '/' . $file_name;
            $fileManager->extension = $extension;
            $fileManager->size = $size;
            $fileManager->tenant_id = getTenantId();
            $fileManager->save();
            return $fileManager;

        } catch (\Exception $e) {
            return NULL;
        }
    }

public function removeFile()
    {
        $disk = $this->storage_type ?: (config('app.STORAGE_DRIVER') ?: config('filesystems.default'));

        if (Storage::disk($disk)->exists($this->path)) {
            Storage::disk($disk)->delete($this->path);
            return 100;
        }
        return 200;
    }
}
