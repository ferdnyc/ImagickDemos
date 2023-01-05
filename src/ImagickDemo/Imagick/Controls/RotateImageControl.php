<?php

declare(strict_types = 1);

namespace ImagickDemo\Imagick\Controls;


use DataType\DataType;
use ImagickDemo\ToArray;
use DataType\Create\CreateFromVarMap;
use DataType\GetInputTypesFromAttributes;
use DataType\SafeAccess;

use ImagickDemo\Params\ImagickColorParam;
use ImagickDemo\Params\Image;
use ImagickDemo\Params\Crop;
use ImagickDemo\Params\Sigma;
use ImagickDemo\Params\Angle;
//$image_path, $angle, $color, $crop

class RotateImageControl  implements DataType
{
    use SafeAccess;
    use CreateFromVarMap;
    use ToArray;
    use GetInputTypesFromAttributes;

    public function __construct(
        #[Angle('angle')]
        private string $angle,
        #[Crop('crop')]
        private string $crop,
        #[ImagickColorParam('red', 'color')]
        private string $color,
        #[Image('image_path')]
        private string $image_path,
    ) {
    }

    public function getValuesForForm(): array
    {
        return [
            'angle' => $this->angle,
            'color' => $this->color,
            'crop' => getOptionFromOptions($this->crop, getCropOptions()),
            'image_path' => getOptionFromOptions($this->image_path, getImagePathOptions()),
        ];
    }

    public function getImagePath(): string
    {
        return $this->image_path;
    }
}