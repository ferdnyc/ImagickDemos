<?php

namespace ImagickDemo\Navigation;

use ImagickDemo\Helper\PageInfo;

class CategoryInfo
{
    protected $currentExample;

    public function getCurrentName(PageInfo $pageInfo)
    {
        $exampleList = CategoryInfo::getCategoryList($pageInfo->getCategory());
        $currentExample = $pageInfo->getExample();


        foreach ($exampleList as $exampleName => $exampleDefinition) {
            if (strcasecmp($currentExample, $exampleName) === 0) {
                return $exampleName;
            }
        }

        return null;
    }

    public static function getExampleName(PageInfo $pageInfo)
    {
        $navName = self::getCurrentName($pageInfo);
        if ($navName) {
            return sprintf('ImagickDemo\%s\%s', $pageInfo->getCategory(), $navName);
        }

        if ($pageInfo->getCategory()) {
            return sprintf('ImagickDemo\%s\IndexExample', $pageInfo->getCategory());
        }
        
        return 'ImagickDemo\HomePageExample';
    }

    public static function getImageFunctionName(PageInfo $pageInfo)
    {
        $category = $pageInfo->getCategory();
        if ($category == null) {
            return 'ImagickDemo\HomePageExample';
        }
        
        $example = $pageInfo->getExample();
        if ($example == null) {
            return sprintf('ImagickDemo\%s\IndexExample', $category);
        }
        
        $exampleToRun = self::getExampleToRun($category, $example);

        return sprintf('ImagickDemo\%s\%s', $category, $exampleToRun);
    }
 
    public static function getControlClassName(PageInfo $pageInfo)
    {
        $category = $pageInfo->getCategory();
        if ($category == null) {
            return null;
        }
        
        $example = $pageInfo->getExample();
        if ($example == null) {
            return null;
        }
        
        $exampleDefinition = self::getExampleToRun($category, $example);

        return $exampleDefinition[1];
    }
    
    
    public static function getCustomImageFunctionName(PageInfo $pageInfo)
    {
        $category = $pageInfo->getCategory();
        $example = $pageInfo->getExample();
        $exampleDefinition = self::getExampleToRun($category, $example);
        $function = $exampleDefinition[0];

        return [sprintf('ImagickDemo\%s\%s', $category, $function), 'renderCustomImage'];
    }

//    public static function getDIInfo(PageInfo $pageInfo)
//    {
//        $category = $pageInfo->getCategory();
//        $example = $pageInfo->getExample();
//
//        if ($category == null || $example == null) {
//            return ['ImagickDemo\Control\NullControl', []];
//        }
//
//        $controlClass = \ImagickDemo\Control\ReactControls::class;
//
//        $params = [];
//
//        return [$controlClass, $params];
//    }

    public static function getExampleToRun($category, $example): string
    {
        $examples = self::getCategoryList($category);

        if (!isset($examples[$example])) {
            throw new \Exception("Somethings borked: example [$category][$example] doesn't exist.");
        }

        return $examples[$example];
    }
    
    public static function getCategoryList($category)
    {
        switch ($category) {
            case ('Imagick'): {
                return self::$imagickExamples;
            }
            case ('ImagickDraw'): {
                return self::$imagickDrawExamples;
            }
            case ('ImagickPixel'): {
                return self::$imagickPixelExamples;
            }
            case ('ImagickPixelIterator'): {
                return self::$imagickPixelIteratorExamples;
            }
            case ('ImagickKernel'): {
                return self::$imagickKernelExamples;
            }
            case ('Tutorial'): {
                return self::$tutorialExamples;
            }
        }

        throw new \Exception("Unknown category '$category'");
    }

//    public static function findExample($category, $example)
//    {
//        $examples = self::getCategoryLgetExampleToRunist($category);
//
//        foreach ($examples as $exampleName => $exampleController) {
//            if (strtolower($exampleName) == strtolower($example)) {
//                return [$category, $exampleName];
//            }
//        }
//
//        throw new \Exception("Unknown example '$example' for category '$category'");
//    }
    
    
    public static $imagickExamples = [
        'adaptiveBlurImage' => 'adaptiveBlurImage',
        'adaptiveResizeImage' => 'adaptiveResizeImage',
        'adaptiveSharpenImage' => 'adaptiveSharpenImage',
        'adaptiveThresholdImage' => 'adaptiveThresholdImage',
        //'addImage',
        'addNoiseImage' => 'addNoiseImage',
        'affineTransformImage' => 'affineTransformImage', //Doesn't work?
        //'animateImages',
        'annotateImage' => 'annotateImage',

        'appendImages' => 'appendImages',
        'autoLevelImage' => 'autoLevelImage',
        'blackThresholdImage' => 'blackThresholdImage',
        'blueShiftImage' => 'blueShiftImage',
        'blurImage' => 'blurImage',
        'borderImage' => 'borderImage',
        'brightnessContrastImage' => 'brightnessContrastImage',
        'charcoalImage' => 'charcoalImage',
        'chopImage' => 'chopImage',
        //'clear' - alias of destroy
        //'clipPathImage' - tiff image, no1curr
        'clutImage' => 'clutImage',
        'coalesceImages' => 'coalesceImages',
        
        //This isn't implemented yet.
        //'colorDecisionListImage' => 'colorDecisionListImage',
        'colorizeImage' => 'colorizeImage',
        'colorMatrixImage' => 'colorMatrixImage',
        //'combineImages',
        //'commentImage',
        //'compareImageChannels',
        //'compareImageLayers',
        //'compareImages',
        'compositeImage' => 'compositeImage',
        // CompositeLayers
        //__construct',
        'contrastImage' => 'contrastImage',
        //'contrastStretchImage',
        'convolveImage' => 'convolveImage',
        'cropImage' => 'cropImage',
        //'cropThumbnailImage',
        //'current',
        //'cycleColormapImage',
        //'constituteImage' => [ 'constituteImage', \ImagickDemo\Control\NullControl::class],
        // DestroyImage
        //'debugImage' => ['debugImage', \ImagickDemo\Control\NullControl::class ],
        
        //'decipherImage' - no1curr
        //'deconstructImages',
        //'deleteImageArtifact',
        'deskewImage' => 'deskewImage',
        'despeckleImage' => 'despeckleImage',
        //'destroy',
        //'displayImage' - no1curr, X server,
        //'displayImages' - no1curr, X server,
        'distortImage' => 'distortImage',
        'drawImage' => 'drawImage',
        'edgeImage' => 'edgeImage',
        'embossImage' => 'embossImage',
        //'encipherImage' - no1curr
        'enhanceImage' => 'enhanceImage',
        'equalizeImage' => 'equalizeImage',
        'evaluateImage' =>  'evaluateImage',
        'exportImagePixels' => 'exportImagePixels',
        'extentImage' => 'extentImage',
        'filter' => 'filter',
        //FrameImage
        'flattenImages' => 'flattenImages',
        'flipImage' => 'flipImage',
        'floodFillPaintImage' => 'floodFillPaintImage',
        'flopImage' => 'flopImage',
        'forwardFourierTransformImage' => 'forwardFourierTransformImage',
        'frameImage' => 'frameImage',
        'functionImage' => 'functionImage',
        'fxImage' => 'fxImage',
        'gammaImage' => 'gammaImage',
        'gaussianBlurImage' => 'gaussianBlurImage',
        //'getColorspace',
        //'getCompression',
        //'getCompressionQuality',
        'getCopyright'  => 'getCopyright',
        //'getFilename',
        //'getFont',
        //'getFormat',
        //'getGravity',
        //'getHomeURL',
        //'getImage',
        //'getImageAlphaChannel',
        //'getImageArtifact',
        //'getImageBackgroundColor',
        //'getImageBlob',
        //'getImageBluePrimary',
        //'getImageBorderColor',
        //'getImageChannelDepth',
        //'getImageChannelDistortion',
        //'getImageChannelDistortions',
        //'getImageChannelExtrema',
        //'getImageChannelKurtosis',
        //'getImageChannelMean',
        //'getImageChannelRange',
        'getImageChannelStatistics' => 'getImageChannelStatistics',
        //'getImageClipMask',
        //'getImageColormapColor',
        //'getImageColors',
        //'getImageColorspace',
        //'getImageCompose',
        'getImageCompression' => 'getImageCompression',
        //'getCompressionQuality',
        //'getImageDelay',
        //'getImageDepth',
        //'getImageDispose',
        //'getImageDistortion',
        //'getImageExtrema',
        //'getImageFilename',
        //'getImageFormat',
        //'getImageGamma',
        'getImageGeometry' => 'getImageGeometry',
        //'getImageGravity',
        //'getImageGreenPrimary',
        //'getImageHeight',
        'getImageHistogram' => 'getImageHistogram',
        //'getImageIndex',
        //'getImageInterlaceScheme',
        //'getImageInterpolateMethod',
        //'getImageIterations',
        //'getImageLength',
        //'getImageMagickLicense',
        //'getImageMatte',
        //'getImageMatteColor',
        //'getImageOrientation',
        //'getImagePage',
        //'getImagePixelColor',
        //'getImageProfile',
        //'getImageProfiles',
        //'getImageProperties',
        //'getImageProperty',
        //'getImageRedPrimary',
        //'getImageRegion',
        //'getImageRenderingIntent',
        //'getImageResolution',
        //'getImagesBlob',
        //'getImageScene',
        //'getImageSignature',
        //'getImageSize',
        //'getImageTicksPerSecond',
        //'getImageTotalInkDensity',
        //'getImageType',
        //'getImageUnits',
        //'getImageVirtualPixelMethod',
        //'getImageWhitePoint',
        //'getImageWidth',
        //'getInterlaceScheme',
        //'getIteratorIndex',
        //'getNumberImages',
        //'getOption',
        //'getPackageName',
        //'getPage',
        'getPixelIterator' => 'getPixelIterator',
        'getPixelRegionIterator' => 'getPixelRegionIterator',
        //'getPointSize',
        //'getQuantumDepth',
        //'getQuantumRange',
        //'getReleaseDate',
        //'getResource',
        //'getResourceLimit',
        //'getSamplingFactors',
        //'getSize',
        //'getSizeOffset',
        //'getVersion',

        'haldClutImage' => 'haldClutImage',
        //'hasNextImage',
        //'hasPreviousImage',
        'identifyImage' => 'identifyImage',
        'identifyFormat' => 'identifyFormat',
        
        'inverseFourierTransformImage' => 'forwardFourierTransformImage',
        'implodeImage'  => 'implodeImage',
        'importImagePixels' => 'importImagePixels',
        //'labelImage' => basically does setImageProperty("label", $text)
        
        'levelImage' => 'levelImage',
        'linearStretchImage' => 'linearStretchImage',
        //'liquidRescaleImage',
        'magnifyImage' => 'magnifyImage',
        //'mapImage' - deprecated
        //'matteFloodfillImage' - deprecated
        'medianFilterImage' => 'medianFilterImage',
        'mergeImageLayers'  => 'mergeImageLayers',
        //'minifyImage', //MagickMinifyImage() is a convenience method that scales an image proportionally to one-half its original size
        'modulateImage' => 'modulateImage',
        'montageImage'  => 'montageImage',
        'morphImages' => 'morphImages',
        
        'morphology' => 'morphology',
        
        'mosaicImages' => 'mosaicImages',
        'motionBlurImage' => 'motionBlurImage',
        'negateImage' => 'negateImage',
        //'newImage',
        'newPseudoImage' => 'newPseudoImage',
        //'nextImage',
        'normalizeImage' => 'normalizeImage',
        'oilPaintImage' => 'oilPaintImage',
        'opaquePaintImage' => 'opaquePaintImage',
        //'optimizeImageLayers',
        // OptimizeImageTransparency
        'orderedPosterizeImage' => 'orderedPosterizeImage',
        //'paintOpaqueImage', //deprecated
        //'paintTransparentImage', //deprecated
        'pingImage' => 'pingImage',
        'Quantum'  => 'Quantum',
        //'pingImageBlob',
        //'pingImageFile',
        'polaroidImage'  => 'polaroidImage',
        'posterizeImage' => 'posterizeImage',
        //'previewImages',
        //'previousImage',
        //'profileImage',
        'quantizeImage' => 'quantizeImage',
        //'quantizeImages' => 'quantizeImages',
        'queryFontMetrics'=> 'queryFontMetrics',
        'queryFonts'=> 'queryFonts',
        'queryFormats' => 'queryFormats',
        'radialBlurImage' => 'radialBlurImage',
        'raiseImage' => 'raiseImage',
        'randomThresholdImage' => 'randomThresholdImage',
        //'readImage',
        'readImageBlob'  => 'readImageBlob',
        //'readImageFile',
        'recolorImage' => 'recolorImage',
        'reduceNoiseImage' => 'reduceNoiseImage',
        'remapImage' => 'remapImage',
        //'removeImage',
        //'removeImageProfile',
        //'render',
        'resampleImage' => 'resampleImage',
        //'resetImagePage',
        'resizeImage' => 'resizeImage',
        'rollImage' => 'rollImage',
        'rotateImage' => 'rotateImage',
        'rotationalBlurImage' => 'rotationalBlurImage',
        'roundCorners' => 'roundCorners',
        //'sampleImage',
        'scaleImage' => 'scaleImage',
        'segmentImage' => 'segmentImage',
        'selectiveBlurImage' => 'selectiveBlurImage',
        'separateImageChannel' => 'separateImageChannel',
        'sepiaToneImage' => 'sepiaToneImage',
        //'setBackgroundColor',
        //'setColorspace',
        //'setCompression',
        'setCompressionQuality' => 'setCompressionQuality',
        //'setFilename',
        //'setFirstIterator',
        //'setFont',
        //'setFormat',
        //'setGravity',
        //'setImage',
        //'setImageAlphaChannel',
        'setImageArtifact' => 'setImageArtifact',
        //'setImageBackgroundColor',
        'setImageBias' => 'setImageBias',
        //'setImageBluePrimary',
        //'setImageBorderColor',
        //'setImageChannelDepth',
        'setImageClipMask' => 'setImageClipMask',
        //'setImageColormapColor',
        //'setImageColorspace',
        //'setImageCompose',
        //'setImageCompression',
        'setImageCompressionQuality' => 'setImageCompressionQuality',
        //'setImageDepth',
        'setImageDelay' => 'setImageDelay',
        //'setImageDispose',
        //'setImageExtent',
        //'setImageFilename',
        //'setImageFormat',
        //'setImageGamma',
        //'setImageGravity',
        //'setImageGreenPrimary',
        //'setImageIndex',
        //'setImageInterlaceScheme',
        //'setImageInterpolateMethod',
        //'setImageIterations',
        //'setImageMatte',
        'setImageMask' => 'setImageMask',
        //'setImageMatteColor',
        //'setImageOpacity',
        'setImageOrientation' => 'setImageOrientation',
        //'setImagePage',
        //'setImageProfile',
        //'setImageProperty',
        //'setImageRedPrimary',
        //'setImageRenderingIntent',
        'setImageResolution' => 'setImageResolution',
        //'setImageScene',
        'setImageTicksPerSecond' => 'setImageTicksPerSecond',
        //'setImageType',
        //'setImageUnits',
        //'setImageVirtualPixelMethod',
        //'setImageWhitePoint',
        //'setInterlaceScheme',
        'setIteratorIndex' => 'setIteratorIndex',
        //'setLastIterator',
        'setOption' => 'setOption',
        'setProgressMonitor' => 'setProgressMonitor',
        //'setPage',
        //'setPointSize',
        //'setResolution',
        //'setResourceLimit',
        'setSamplingFactors' => 'setSamplingFactors',
        //'setSize',
        //'setSizeOffset',
        //'setType',
        'shadeImage' => 'shadeImage',
        'shadowImage' => 'shadowImage',
        'sharpenImage' => 'sharpenImage',
        'shaveImage' => 'shaveImage',
        'shearImage' => 'shearImage',
        'sigmoidalContrastImage' => 'sigmoidalContrastImage',
        'sketchImage' => 'sketchImage',
        'smushImages' => 'smushImages',
        'stripImage' => 'stripImage',
        'solarizeImage' => 'solarizeImage',
        'sparseColorImage' => 'sparseColorImage',
        'spliceImage' => 'spliceImage',
        'spreadImage' => 'spreadImage',
        'statisticImage' => 'statisticImage',
        'subImageMatch' => 'subImageMatch',
        'swirlImage' => 'swirlImage',
        'textureImage' => 'textureImage',
        'thresholdImage' => 'thresholdImage',
        'thumbnailImage' => 'thumbnailImage',
        'tintImage' => 'tintImage',
        'transformImage' => 'transformImage',
        'transparentPaintImage' =>  'transparentPaintImage',
        'transposeImage' => 'transposeImage',
        'transformImageColorspace' => 'transformImageColorspace',
        'transverseImage' => 'transverseImage',
        'trimImage' => 'trimImage',
        'uniqueImageColors' => 'uniqueImageColors',
        'unsharpMaskImage' => 'unsharpMaskImage',
        'vignetteImage' => 'vignetteImage',
        'waveImage' => 'waveImage',
        'whiteThresholdImage' => 'whiteThresholdImage',
    ];

    public static $imagickDrawExamples = [

        'affine' => 'affine',
        'arc' => 'arc',
        'bezier' => 'bezier',
        'circle' => 'circle',
        'composite' => 'composite',
        'ellipse' => 'ellipse',
        //'getVectorGraphics' => 'setVectorGraphics',
        'line' => 'line',
        'matte' => 'matte',

        'pathCurveToQuadraticBezierAbsolute' => 'pathCurveToQuadraticBezierAbsolute',
        'pathCurveToQuadraticBezierSmoothAbsolute' =>  'pathCurveToQuadraticBezierSmoothAbsolute',
        'pathStart' => 'pathStart',
        'point' => 'point',
        'polygon' => 'polygon',
        'polyline' => 'polyline',
        'pop' => 'push',
        'popClipPath' => 'setClipPath',
        'popPattern' => 'pushPattern',
        'popDefs' => 'popDefs',
        'push' =>  'push',
        'pushClipPath' => 'setClipPath',
        'pushPattern' => 'pushPattern',
        'rectangle' => 'rectangle',
        //'render' => 'render',
        'rotate' => 'rotate',
        'roundRectangle' => 'roundRectangle',
        'scale' => 'scale',
        'setClipPath' => 'setClipPath',
        'setClipRule' => 'setClipRule',
        'setClipUnits' => 'setClipUnits',
        'setFillAlpha' => 'setFillAlpha',
        'setFillColor' => 'setFillColor',
        'setFillOpacity' => 'setFillOpacity',
        'setFillRule' => 'setFillRule',
        'setFont' => 'setFont',
        'setFontFamily' => 'setFontFamily',
        'setFontSize' => 'setFontSize',
        'setFontStretch' => 'setFontStretch',
        'setFontStyle' => 'setFontStyle',
        'setFontWeight' => 'setFontWeight',
        'setGravity' => 'setGravity',
        'setStrokeAlpha' => 'setStrokeAlpha',
        'setStrokeAntialias' => 'setStrokeAntialias',
        'setStrokeColor' => 'setStrokeColor',
        'setStrokeDashArray' => 'setStrokeDashArray',
        'setStrokeDashOffset' => 'setStrokeDashOffset',
        'setStrokeLineCap' => 'setStrokeLineCap',
        'setStrokeLineJoin' => 'setStrokeLineJoin',
        'setStrokeMiterLimit' => 'setStrokeMiterLimit',
        'setStrokeOpacity' => 'setStrokeOpacity',
        'setStrokeWidth' => 'setStrokeWidth',
        'setTextAlignment' => 'setTextAlignment',
        'setTextAntialias' => 'setTextAntialias',
        'setTextDecoration' => 'setTextDecoration',
        'setTextUnderColor' => 'setTextUnderColor',
        //'setVectorGraphics' => 'setVectorGraphics',
        'setViewBox' => 'setViewBox',
        'skewX' => 'skewX',
        'skewY' => 'skewY',
        'translate' => 'translate',
    ];


    public static $imagickPixelExamples = [
        'construct' => 'construct',
        'getColor' => 'getColor',
        'getColorAsString' => 'getColorAsString',
        'getColorCount' => 'getColorCount',
        'getColorValue' => 'getColorValue',
        'getColorValueQuantum' => 'getColorValueQuantum',
        'getHSL' => 'getHSL',
        'isSimilar' => 'isPixelSimilar',
        'isPixelSimilar' => 'isPixelSimilar',
        'setColor' => 'setColor',
        'setColorValue' => 'setColorValue',
        'setColorValueQuantum' => 'setColorValueQuantum',
        'setHSL' => 'setHSL',
    ];


    public static $imagickPixelIteratorExamples = [
        'clear' => 'clear',
        'construct' => 'construct',
        //'getCurrentIteratorRow' => 'getCurrentIteratorRow',
        //'getIteratorRow' => 'setIteratorRow',
        'getNextIteratorRow' => 'getNextIteratorRow',
        //'getPreviousIteratorRow' => 'getPreviousIteratorRow',
        //'newPixelIterator', deprecated
        //'newPixelRegionIterator', deprecated
        'resetIterator' => 'resetIterator',
        //'setIteratorFirstRow' => 'setIteratorFirstRow',
        //'setIteratorLastRow' => 'setIteratorLastRow',
        'setIteratorRow' => 'setIteratorRow',
        'syncIterator' => 'construct',
    ];



    public static $imagickKernelExamples = [
        'addKernel'      => 'addKernel',
        'addUnityKernel' => 'addUnityKernel',
        'fromMatrix'     => 'fromMatrix',
        'fromBuiltin'    => 'fromBuiltin',
        'getMatrix'      => 'getMatrix',
        'scale'          => 'scale',
        'separate'       => 'separate',
    ];


    public static $tutorialExamples = [
        'backgroundMasking' => 'backgroundMasking',
        'composite' => 'composite',
        //'colorspaceLinearity' => 'colorspaceLinearity',
        'diffMarking' => 'diffMarking',
        'edgeExtend' => 'edgeExtend',
        //'compressImages' => 'compressImages',
        'fxAnalyzeImage' => 'fxAnalyzeImage',
        'eyeColorResolution' => 'EyeColourResolution',
        //'creatingGifs' => 'creatingGifs',
        'deconstructGif' => 'deconstructGif',
        'fontEffect' => 'fontEffect',
        //'gifGeneration' => 'gifGeneration',
        'gradientGeneration' => 'gradientGeneration',
        'gradientReflection' =>  'gradientReflection',
        'imagickComposite' => 'imagickComposite',
        'imagickCompositeGen' => 'imagickCompositeGen',
        'listColors' => 'listColors',
        'levelizeImage' => 'levelizeImage',
        'layerPSD' => 'layerPSD',
        'logoTshirt' => 'logoTshirt',
        'multiLineWrap' => 'multiLineWrap',
        'psychedelicFont' => 'psychedelicFont',
        'psychedelicFontGif' => 'psychedelicFontGif',
        'whirlyGif' =>  'whirlyGif',
        'svgExample' => 'svgExample',
        'screenEmbed' => 'screenEmbed',
        'imageGeometryReset' => 'imageGeometryReset',
        'HumanFeelings' => 'HumanFeelings',
    ];
}
