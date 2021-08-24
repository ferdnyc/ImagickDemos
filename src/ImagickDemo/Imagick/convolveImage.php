<?php

namespace ImagickDemo\Imagick;

use ImagickDemo\Imagick\Controls\ConvolveImageControl;

class convolveImage extends \ImagickDemo\Example
{
    function getOriginalImage()
    {
        return $this->control->getOriginalURL();
    }

    function getOriginalFilename()
    {
        return $this->control->getImagePath();
    }

    public function render()
    {
        return $this->renderImageURL();
    }



    public static function getParamType(): string
    {
        return ConvolveImageControl::class;
    }
}
