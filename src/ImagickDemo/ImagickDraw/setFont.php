<?php

namespace ImagickDemo\ImagickDraw;

use ImagickDemo\ImagickDraw\Controls\ThreeColors;

class setFont extends ImagickDrawExample
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
