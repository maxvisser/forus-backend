<?php

namespace App\Services\MediaService;

use App\Services\MediaService\Models\Media;
use App\Services\MediaService\Models\MediaPreset;
use Illuminate\Contracts\Filesystem\Filesystem;
use Intervention\Image\Constraint;

class MediaImagePreset extends \App\Services\MediaService\MediaPreset
{
    /**
     * Preset image width
     * @var int
     */
    public $width = 1000;

    /**
     * Preset image height
     * @var int
     */
    public $height = null;

    /**
     * Keep media aspect ratio
     * @var bool
     */
    public $preserve_aspect_ratio = true;

    /**
     * Media preset format
     * Set null to preserve original format
     * @var bool
     */
    public $format = 'jpg';

    /**
     * @var bool
     */
    public $allow_transparency = false;

    /**
     * @var bool
     */
    public $transparent_bg_color = '#ffffff';

    /**
     * @var bool
     */
    protected $use_original = false;

    /**
     * @param bool $allow
     * @return $this
     */
    public function setTransparency($allow = true) {
        $this->allow_transparency = $allow;
        return $this;
    }

    /**
     * @param string $hex_color
     * @return $this
     */
    public function setTransparencyBgColor(string $hex_color = "#ffffff") {
        $this->transparent_bg_color = $hex_color;
        return $this;
    }

    /**
     * @param string $format
     * @return $this
     */
    public function setFormat($format = 'jpg') {
        $this->allow_transparency = $format;
        return $this;
    }

    /**
     * @param bool $preserve_aspect_ratio
     * @return $this
     */
    public function setPreserveAspectRatio(bool $preserve_aspect_ratio = true) {
        $this->preserve_aspect_ratio = $preserve_aspect_ratio;
        return $this;
    }

    /**
     * MediaImagePreset constructor.
     * @param string $name
     * @param int $width
     * @param int|null $height
     * @param bool $preserveAspectRatio
     * @param int $quality
     * @param string $format
     */
    public function __construct(
        string $name,
        int $width = 1000,
        int $height = 1000,
        bool $preserveAspectRatio = true,
        int $quality = 75,
        ?string $format = 'jpg'
    ) {
        $this->width = $width;
        $this->height = $height;
        $this->preserve_aspect_ratio = $preserveAspectRatio;

        parent::__construct($name, $format, $quality);
    }

    /**
     * Use original image
     *
     * @param string $name
     * @return MediaImagePreset
     */
    public static function createOriginal(string $name) {
        return (new self($name))->setUseOriginal(true);
    }

    /**
     * @param string $sourcePath
     * @param Filesystem $storage
     * @param string $storagePath
     * @param Media $media
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    public function makePresetModel(
        string $sourcePath,
        Filesystem $storage,
        string $storagePath,
        Media $media
    ) {
        if ($this->use_original) {
            $outPath = $this->makeUniquePath($storage, $storagePath, $media->ext);
            $storage->put($outPath, file_get_contents($sourcePath), 'public');
        } else {
            $format = $this->format ?: $media->ext;
            $outPath = $this->makeUniquePath($storage, $storagePath, $format);
            $image = \Image::make(file_get_contents($sourcePath))->backup();

            if ($this->preserve_aspect_ratio) {
                $image = $image->resize($this->width, $this->height, function (
                    Constraint $constraint
                ) {
                    $constraint->aspectRatio();
                });
            } else {
                $image = $image->fit($this->width, $this->height);
            }

            if ($format != 'png' || !$this->allow_transparency) {
                $image = \Image::canvas(
                    $image->width(),
                    $image->height(),
                    $this->transparent_bg_color
                )->insert($image)->backup();
            }

            $storage->put($outPath, $image->encode(
                $format, $this->quality
            )->encoded, 'public');

            $image->reset();
        }

        // media size row create
        return tap($media->presets()->firstOrCreate([
            'key'  => $this->name
        ]))->update([
            'path' => $outPath
        ]);
    }

    /**
     * @param Filesystem $storage
     * @param string $storagePath
     * @param MediaPreset $presetModel
     * @param Media $media
     * @return mixed
     */
    public function copyPresetModel(
        Filesystem $storage,
        string $storagePath,
        MediaPreset $presetModel,
        Media $media
    ) {
        $format = $this->format ?: $media->ext;
        $outPath = $this->makeUniquePath($storage, $storagePath, $format);
        $storage->copy($presetModel->path, $outPath);

        // media size row create
        return $media->presets()->create([
            'key'   => $presetModel->key,
            'path'  => $outPath
        ]);
    }

    /**
     * @return bool
     */
    public function getUseOriginal(): bool
    {
        return $this->use_original;
    }

    /**
     * @param bool $use_original
     *
     * @return $this
     */
    public function setUseOriginal(bool $use_original): self
    {
        $this->use_original = $use_original;
        return $this;
    }
}