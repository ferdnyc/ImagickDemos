<?php

namespace ImagickDemo\Params;


use Params\ExtractRule\GetFloatOrDefault;
use Params\InputParameter;
use Params\Param;
use Params\ProcessRule\RangeFloatValue;

#[\Attribute]
class Radius implements Param
{
    public function __construct(
        private float $default,
        private string $name,
        private float $max = 10
    ) {
    }

    public function getInputParameter(): InputParameter
    {
        return new InputParameter(
            $this->name,
            new GetFloatOrDefault($this->default),
            new RangeFloatValue(0, $this->max)
        );
    }
}
