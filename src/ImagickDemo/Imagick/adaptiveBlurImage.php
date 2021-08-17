<?php

namespace ImagickDemo\Imagick;

use ImagickDemo\Example;
use ImagickDemo\Imagick\Controls\AdaptiveBlurImageControl;

class adaptiveBlurImage extends Example
{
    public function hasOriginalImage()
    {
        return true;
    }

    public function renderTitle()
    {
        return "Adaptive blur image";
    }

    public function render()
    {
        return $this->renderImageURL();
    }

    public function hasReactControls(): bool
    {
        return true;
    }

    public static function getParamType(): string
    {
        return AdaptiveBlurImageControl::class;
    }
}
