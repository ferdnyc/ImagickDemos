<?php

namespace ImagickDemo\ImagickDraw;

use ImagickDemo\ImagickDraw\Controls\FourColors;

class scale extends ImagickDrawExample
{
    public function getDescription()
    {
        return "";
    }



    public static function getParamType(): string
    {
        return FourColors::class;
    }
}
