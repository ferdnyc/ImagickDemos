<?php

namespace ImagickDemo\ImagickDraw;

use ImagickDemo\ImagickDraw\Controls\ThreeColors;

class setStrokeAntialias extends ImagickDrawExample
{
    public function getDescription()
    {
        return "";
    }



    public static function getParamType(): string
    {
        return ThreeColors::class;
    }
}
