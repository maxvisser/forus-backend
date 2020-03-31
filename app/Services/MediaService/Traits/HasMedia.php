<?php

namespace App\Services\MediaService\Traits;

use App\Services\MediaService\MediaConfig;
use App\Services\MediaService\MediaService;
use App\Services\MediaService\Models\Media;
use Illuminate\Database\Eloquent\Collection;

/**
 * Trait HasMedia
 * @property Collection $medias
 * @package App\Services\MediaService\Traits
 */
trait HasMedia
{
    /**
     * @param Media $media
     */
    public function attachMedia(Media $media) {
        $mediaConfig = MediaService::getMediaConfig($media->type);

        if ($mediaConfig->getType() == MediaConfig::TYPE_SINGLE) {
            $this->medias->each(function($media) {
                resolve('media')->unlink($media);
            });
        }

        $media->update([
            'mediable_type' => $this->getMorphClass(),
            'mediable_id'   => $this->id,
        ]);
    }

    /**
     * Get all of the mediable's medias.
     */
    public function medias()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}