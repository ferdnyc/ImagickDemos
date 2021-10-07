<?php

namespace ImagickDemo\Imagick;

use ImagickDemo\Imagick\Controls\ChopImageControl;

class chopImage extends \ImagickDemo\Example
{
    public function render()
    {
        return $this->renderImageURL();
    }

    public function renderTitle(): string
    {
        return "Chop image";
    }


    public static function getParamType(): string
    {
        return ChopImageControl::class;
    }
}
