<?php

namespace App\Http\Traits;

use App\Helpers\Util;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Exceptions\InvalidBase64Data;

trait HasFile
{
    use InteractsWithMedia;

    public static function bootHasFile()
    {
        static::created(
        /**
         * @throws FileCannotBeAdded
         * @throws FileDoesNotExist
         * @throws FileIsTooBig
         * @throws InvalidBase64Data
         */
            function ($item) {
                foreach ($item->files as $fileAttr) {
                    $file = null;
                    if (!array_key_exists($fileAttr, $item->attributes)) {
                        continue;
                    }
                    if ($item->attributes[$fileAttr] instanceof UploadedFile) {
                        $file = $item->addMedia($item->attributes[$fileAttr])->toMediaCollection($fileAttr)->getUrl();
                    }
                    $item->attributes[$fileAttr] = $file;
                }
                $item->saveQuietly();
            });
        static::updating(/**
         * @throws FileCannotBeAdded
         * @throws FileIsTooBig
         * @throws FileDoesNotExist
         * @throws InvalidBase64Data
         */ function ($item) {
            foreach ($item->files as $fileAttr) {
                $file = $item->$fileAttr;
                if (!array_key_exists($fileAttr, $item->attributes)) {
                    continue;
                }
                if (is_string($file)) {
                    $file = $item->original[$fileAttr];
                } else if ($item->attributes[$fileAttr] instanceof UploadedFile) {
                    $item->getMedia($fileAttr)->map(function ($media) {
                        $media->delete();
                    });
                    $file = $item->addMedia($item->attributes[$fileAttr])->toMediaCollection($fileAttr)->getUrl();
                } else {
                    $file = null;
                    $item->getMedia($fileAttr)->map(function ($media) {
                        $media->delete();
                    });
                }
                $item->attributes[$fileAttr] = $file;
            }
        });

        static::deleting(function ($item) {
            foreach ($item->files as $fileAttr) {
                $item->getMedia($fileAttr)->map(function ($media) {
                    Util::deleteFile($media->getPath());
                    $media->delete();
                });
            }
        });
    }
}
