<?php

namespace ImagickDemo\ImagickPixel;

class setColorValue extends \ImagickDemo\Example
{
    public function renderTitle(): string
    {
        return "Set color value";
    }

    public function render()
    {
        return "" . $this->renderImageURL();
    }
}
