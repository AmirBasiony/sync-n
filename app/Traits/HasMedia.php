<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;

trait HasMedia
{
    public function media()
    {
        return $this->morphMany(Media::class, 'modelable');
    }

    public function addMedia($file, $collection = 'default', $disk = 'public')
    {
        $path = $file->store("media/{$collection}", $disk);

        return $this->media()->create([
            'file_name' => basename($path),
            'path' => $path,
            'collection' => $collection,
            'disk' => $disk,
        ]);
    }

    public function getMedia($collection = 'default')
    {
        return $this->media()->where('collection', $collection)->get();
    }
    
    public function getImageUrlAttribute()
    {
        $media = $this->media()->first();
        return $media ? Storage::disk($media->disk)->url($media->path) : null;
    }
    public function clearMediaCollection($collection = 'default')
    {
        $this->media()->where('collection', $collection)->each(function ($media) {
            Storage::disk($media->disk)->delete($media->path);
            $media->delete();
        });
    }

    public function deleteMedia($mediaId)
    {
        $media = $this->media()->find($mediaId);
        if ($media) {
            Storage::disk($media->disk)->delete($media->path);
            $media->delete();
        }
    }

    // Automatically delete media when the model is deleted
    public static function bootHasMedia()
    {
        static::deleting(function ($model) {
            $model->media()->each(function ($media) {
                Storage::disk($media->disk)->delete($media->path);
                $media->delete();
            });
        });
    }
}
