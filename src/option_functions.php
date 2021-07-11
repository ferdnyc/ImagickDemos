<?php

declare(strict_types = 1);


/**
 * @param string|int $value
 * @param array<string, int> $values
 * @return string
 */
function getOptionFromOptions(string|int $option, array $values): string
{
    foreach ($values as $key => $value) {
        if (strcmp($option, (string)$value) === 0) {
            return $key;
        }
    }

    throw new \Exception("Unknown option [$option]" . json_encode($values));
}


/**
 * @return array<string, int>
 */
function getPaintTypeOptions(): array
{
    return [
        "Point" => \Imagick::PAINT_POINT,
        "Replace" => \Imagick::PAINT_REPLACE,
        "Floodfill"=> \Imagick::PAINT_FLOODFILL,
        "Fill to border" => \Imagick::PAINT_FILLTOBORDER,
        "Reset" => \Imagick::PAINT_RESET,
    ];
}


/**
 * @return array<string, int>
 */
function getTextDecorationOptions(): array
{
    return [
        'None' => \Imagick::DECORATION_NO,
        'Underline' =>  \Imagick::DECORATION_UNDERLINE,
        'Overline' =>  \Imagick::DECORATION_OVERLINE,
        'Linethrough' => \Imagick::DECORATION_LINETROUGH
    ];
}

/**
 * @return array<string, string>
 */
function getImagePathOptions()
{
    $images = [
        'Skyline' => realpath(__DIR__ . "/../public/images/Skyline_400.jpg"),
        'Lorikeet' => realpath(__DIR__ . "/../public/images/Biter_500.jpg"),
        'People' => realpath(__DIR__ . "/../public/images/SydneyPeople_400.jpg"),
        'Low contrast' => realpath(__DIR__ . "/../public/images/LowContrast.jpg"),
    ];

    return $images;
}

/**
 * @return array<string, string>
 */
function getKernelRenderOptions()
{
    $images = [
        'image' => 'Image',
        'values' => 'Values',
    ];

    return $images;
}

function getKernelTypes(): array
{
    return [
        "Unity" => \Imagick::KERNEL_UNITY,
        "Gaussian" => \Imagick::KERNEL_GAUSSIAN,
        "Difference of Gaussians" => \Imagick::KERNEL_DIFFERENCE_OF_GAUSSIANS,
        "Laplacian of Gaussians" => \Imagick::KERNEL_LAPLACIAN_OF_GAUSSIANS,
        "Blur" => \Imagick::KERNEL_BLUR,
        "Comet" => \Imagick::KERNEL_COMET,
        "Laplacian" => \Imagick::KERNEL_LAPLACIAN,
        "Sobel" => \Imagick::KERNEL_SOBEL,
        "FreiChen" => \Imagick::KERNEL_FREI_CHEN,
        "Roberts" => \Imagick::KERNEL_ROBERTS,
        "Prewitt" => \Imagick::KERNEL_PREWITT,
        "Compass" => \Imagick::KERNEL_COMPASS,
        "Kirsch" => \Imagick::KERNEL_KIRSCH,
        "Diamond" => \Imagick::KERNEL_DIAMOND,
        "Square" => \Imagick::KERNEL_SQUARE,
        "Rectangle" => \Imagick::KERNEL_RECTANGLE,
        "Octagon" => \Imagick::KERNEL_OCTAGON,
        "Disk" => \Imagick::KERNEL_DISK,
        "Plus" => \Imagick::KERNEL_PLUS,
        "Cross" => \Imagick::KERNEL_CROSS,
        "Ring" => \Imagick::KERNEL_RING,
        "Peaks" => \Imagick::KERNEL_PEAKS,
        "Edges" => \Imagick::KERNEL_EDGES,
        "Corners" => \Imagick::KERNEL_CORNERS,
        "Diagonals" => \Imagick::KERNEL_DIAGONALS,
        "Line Ends" => \Imagick::KERNEL_LINE_ENDS,
        "Line Junctions" => \Imagick::KERNEL_LINE_JUNCTIONS,
        "Ridges" => \Imagick::KERNEL_RIDGES,
        "Convex Hull" => \Imagick::KERNEL_CONVEX_HULL,
        "Thin SE" => \Imagick::KERNEL_THIN_SE,
        "Skeleton" => \Imagick::KERNEL_SKELETON,
        "Chebyshev" => \Imagick::KERNEL_CHEBYSHEV,
        "Manhattan" => \Imagick::KERNEL_MANHATTAN,
        "Octagonal" => \Imagick::KERNEL_OCTAGONAL,
        "Euclidean" => \Imagick::KERNEL_EUCLIDEAN,
        "Binomial" => \Imagick::KERNEL_BINOMIAL,
        // \Imagick::KERNEL_USER_DEFINED => "User Defined", This isn't needed
        // Imagick has fromMatrix which is far saner to use.
    ];
}