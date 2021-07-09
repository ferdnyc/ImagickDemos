<?php

declare(strict_types = 1);

use Params\Create\CreateFromRequest;
use Params\Create\CreateFromVarMap;
use Params\Create\CreateOrErrorFromVarMap;
use Params\InputParameter;
use Params\ExtractRule\GetBoolOrDefault;
use Params\ExtractRule\GetIntOrDefault;
use Params\ExtractRule\GetStringOrDefault;
use Params\ProcessRule\MaxIntValue;
use Params\ProcessRule\MinIntValue;
use Params\SafeAccess;
use VarMap\VarMap;
use Params\ProcessRule\EnumMap;
use ImagickDemo\ToArray;
use Params\InputParameterList;



function purgeExceptionMessage(\Throwable $exception)
{
    $rawMessage = $exception->getMessage();

    $purgeAfterPhrases = [
        'with params'
    ];

    $message = $rawMessage;

    foreach ($purgeAfterPhrases as $purgeAfterPhrase) {
        $matchPosition = strpos($message, $purgeAfterPhrase);
        if ($matchPosition !== false) {
            $message = substr($message, 0, $matchPosition + strlen($purgeAfterPhrase));
            $message .= '**PURGED**';
        }
    }

    return $message;
}

function getTextForException(\Throwable $exception)
{
    $currentException = $exception;
    $text = '';

    do {
        $text .= sprintf(
            "Exception type:\n  %s\n\nMessage:\n  %s \n\nStack trace:\n%s\n",
            get_class($currentException),
            purgeExceptionMessage($currentException),
            formatLinesWithCount(getExceptionStackAsArray($currentException))
        );

        $currentException = $currentException->getPrevious();
    } while ($currentException !== null);

    return $text;
}

/**
 * Format an array of strings to have a count at the start
 * e.g. $lines = ['foo', 'bar'], output is:
 *
 * #0 foo
 * #1 bar
 */
function formatLinesWithCount(array $lines): string
{
    $output = '';
    $count = 0;

    foreach ($lines as $line) {
        $output .= '  #' . $count . ' '. $line . "\n";
        $count += 1;
    }

    return $output;
}


/**
 * @param Throwable $exception
 * @return string[]
 */
function getExceptionStackAsArray(\Throwable $exception)
{
    $lines = [];
    foreach ($exception->getTrace() as $trace) {
        $lines[] = formatTraceLine($trace);
    }

    return $lines;
}


function formatTraceLine(array $trace)
{
    $location = '??';
    $function = 'unknown';

    if (isset($trace["file"]) && isset($trace["line"])) {
        $location = $trace["file"]. ':' . $trace["line"];
    }
    else if (isset($trace["file"])) {
        $location = $trace["file"] . ':??';
    }

    $baseDir = realpath(__DIR__ . '/../');
    if ($baseDir === false) {
        throw new \Exception("Couldn't find parent directory from " . __DIR__);
    }

    $location = str_replace($baseDir, '', $location);

    if (isset($trace["class"]) && isset($trace["type"]) && isset($trace["function"])) {
        $function = $trace["class"] . $trace["type"] . $trace["function"];
    }
    else if (isset($trace["class"]) && isset($trace["function"])) {
        $function = $trace["class"] . '_' . $trace["function"];
    }
    else if (isset($trace["function"])) {
        $function = $trace["function"];
    }
    else {
        $function = "Function is weird: " . json_encode(var_export($trace, true));
    }

    return sprintf(
        "%s %s",
        $location,
        $function
    );
}


/**
 * Self-contained monitoring system for system signals
 * returns true if a 'graceful exit' like signal is received.
 *
 * We don't listen for SIGKILL as that needs to be an immediate exit,
 * which PHP already provides.
 * @return bool
 */
function checkSignalsForExit()
{
    static $initialised = false;
    static $needToExit = false;
    static $fnSignalHandler = null;

    if ($initialised === false) {
        $fnSignalHandler = function ($signalNumber) use (&$needToExit) {
            $needToExit = true;
        };
        pcntl_signal(SIGINT, $fnSignalHandler, false);
        pcntl_signal(SIGQUIT, $fnSignalHandler, false);
        pcntl_signal(SIGTERM, $fnSignalHandler, false);
        pcntl_signal(SIGHUP, $fnSignalHandler, false);
        pcntl_signal(SIGUSR1, $fnSignalHandler, false);
        $initialised = true;
    }

    pcntl_signal_dispatch();

    return $needToExit;
}


/**
 * Repeatedly calls a callable until it's time to stop
 *
 * @param callable $callable - the thing to run
 * @param int $secondsBetweenRuns - the minimum time between runs
 * @param int $sleepTime - the time to sleep between runs
 * @param int $maxRunTime - the max time to run for, before returning
 */
function continuallyExecuteCallable($callable, int $secondsBetweenRuns, int $sleepTime, int $maxRunTime)
{
    $startTime = microtime(true);
    $lastRuntime = 0;
    $finished = false;

    echo "starting continuallyExecuteCallable \n";
    while ($finished === false) {
        $shouldRunThisLoop = false;
        if ($secondsBetweenRuns === 0) {
            $shouldRunThisLoop = true;
        }
        else if ((microtime(true) - $lastRuntime) > $secondsBetweenRuns) {
            $shouldRunThisLoop = true;
        }

        if ($shouldRunThisLoop === true) {
            $callable();
            $lastRuntime = microtime(true);
        }

        if (checkSignalsForExit()) {
            break;
        }

        if ($sleepTime > 0) {
            sleep($sleepTime);
        }

        if ((microtime(true) - $startTime) > $maxRunTime) {
            echo "Reach maxRunTime - finished = true\n";
            $finished = true;
        }
    }

    echo "Finishing continuallyExecuteCallable\n";
}


function saneErrorHandler($errorNumber, $errorMessage, $errorFile, $errorLine): bool
{
    if (error_reporting() === 0) {
        // Error reporting has been silenced
        if ($errorNumber !== E_USER_DEPRECATED) {
            // Check it isn't this value, as this is used by twig, with error suppression. :-/
            return true;
        }
    }
    if ($errorNumber === E_DEPRECATED) {
        return false;
    }
    if ($errorNumber === E_CORE_ERROR || $errorNumber === E_ERROR) {
        // For these two types, PHP is shutting down anyway. Return false
        // to allow shutdown to continue
        return false;
    }
    $message = "Error: [$errorNumber] $errorMessage in file $errorFile on line $errorLine.";
    throw new \Exception($message);
}

/**
 * Decode JSON with actual error detection
 */
function json_decode_safe(?string $json)
{
    if ($json === null) {
        throw new \ImagickDemo\Exception\JsonException("Error decoding JSON: cannot decode null.");
    }

    $data = json_decode($json, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        return $data;
    }

    $parser = new \Seld\JsonLint\JsonParser();
    $parsingException = $parser->lint($json);

    if ($parsingException !== null) {
        throw $parsingException;
    }

    if ($data === null) {
        throw new \ImagickDemo\Exception\JsonException("Error decoding JSON: null returned.");
    }

    throw new \ImagickDemo\Exception\JsonException("Error decoding JSON: " . json_last_error_msg());
}


/**
 * @param mixed $data
 * @param int $options
 * @return string
 * @throws Exception
 */
function json_encode_safe($data, $options = 0): string
{
    $result = json_encode($data, $options);

    if ($result === false) {
        throw new \ImagickDemo\Exception\JsonException("Failed to encode data as json: " . json_last_error_msg());
    }

    return $result;
}


function getExceptionText(\Throwable $exception): string
{
    $text = "";
    do {
        $text .= get_class($exception) . ":" . $exception->getMessage() . "\n\n";
        $text .= $exception->getTraceAsString();

        $exception = $exception->getPrevious();
    } while ($exception !== null);

    return $text;
}


function getExceptionInfoAsArray(\Throwable $exception)
{
    $data = [
        'status' => 'error',
        'message' => $exception->getMessage(),
    ];

    $previousExceptions = [];

    do {
        $exceptionInfo = [
            'type' => get_class($exception),
            'message' => $exception->getMessage(),
            'trace' => getExceptionStackAsArray($exception),
        ];

        $previousExceptions[] = $exceptionInfo;
    } while (($exception = $exception->getPrevious()) !== null);

    $data['details'] = $previousExceptions;

    return $data;
}


function peak_memory($real_usage = false)
{
    return number_format(memory_get_peak_usage($real_usage));
}


/**
 * @param $value
 *
 * @return array{string, null}|array{null, mixed}
 */
function convertToValue($value)
{
    if (is_scalar($value) === true) {
        return [
            null,
            $value
        ];
    }
    if ($value === null) {
        return [
            null,
            null
        ];
    }

    $callable = [$value, 'toArray'];
    if (is_object($value) === true && is_callable($callable)) {
        return [
            null,
            $callable()
        ];
    }
    if (is_object($value) === true) {
        if ($value instanceof \DateTime) {
            // Format as Atom time with microseconds
            return [
                null,
                $value->format("Y-m-d\TH:i:s.uP")
            ];
        }
    }

    if (is_array($value) === true) {
        $values = [];
        foreach ($value as $key => $entry) {
            $values[$key] = convertToValue($entry);
        }

        return [
            null,
            $values
        ];
    }

    if (is_object($value) === true) {
        return [
            sprintf(
                "Unsupported type [%s] of class [%s] for toArray.",
                gettype($value),
                get_class($value)
            ),
            null
        ];
    }

    return [
        sprintf(
            "Unsupported type [%s] for toArray.",
            gettype($value)
        ),
        null
    ];
}


/**
 * Fetch data and return statusCode, body and headers
 */
function fetchUri(string $uri, string $method, array $queryParams = [], string $body = null, array $headers = [])
{
    $query = http_build_query($queryParams);
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $uri . $query);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

    $allHeaders = [];

    if ($body !== null) {
        $allHeaders[] = 'Content-Type: application/json';
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    }


    foreach ($headers as $header) {
        $allHeaders[] = $header;
    }

    curl_setopt($curl, CURLOPT_HTTPHEADER, $allHeaders);

    $headers = [];
    $handleHeaderLine = function ($curl, $headerLine) use (&$headers) {
        $headers[] = $headerLine;
        return strlen($headerLine);
    };
    curl_setopt($curl, CURLOPT_HEADERFUNCTION, $handleHeaderLine);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $body = curl_exec($curl);
    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    return [$statusCode, $body, $headers];
}



// Define a function that writes a string into the response object.
function convertStringToHtmlResponse(
    string $result,
    \Psr\Http\Message\ResponseInterface $response
): \Psr\Http\Message\ResponseInterface {
    $response = $response->withHeader('Content-Type', 'text/html');
    $response->getBody()->write($result);
    return $response;
}

/**
 * @return array<string, int>
 */
function getEyeColourSpaceOptions()
{
    $colorSpaceTypes = [
        'RGB' => \Imagick::COLORSPACE_RGB,
//        \Imagick::COLORSPACE_GRAY => 'Gray',
//        \Imagick::COLORSPACE_TRANSPARENT => 'Transparent',
//        \Imagick::COLORSPACE_OHTA => 'OHTA',
//        \Imagick::COLORSPACE_LAB => 'LAB',
//        \Imagick::COLORSPACE_XYZ => 'XYZ',
//        \Imagick::COLORSPACE_YCBCR => 'YCBCR',
//        \Imagick::COLORSPACE_YCC => 'YCC',
        'YIC' => \Imagick::COLORSPACE_YIQ,
//        \Imagick::COLORSPACE_YPBPR => 'YPBPR',
        'YUV' => \Imagick::COLORSPACE_YUV,
//        \Imagick::COLORSPACE_CMYK => 'CMYK',
        'SRGB' => \Imagick::COLORSPACE_SRGB,
        'HSB' => \Imagick::COLORSPACE_HSB,
        'HSL' => \Imagick::COLORSPACE_HSL,
//        \Imagick::COLORSPACE_HWB => 'HWB',
//        \Imagick::COLORSPACE_REC601LUMA => 'REC601LUMA',
//        \Imagick::COLORSPACE_REC709LUMA => 'REC709LUMA',
//        \Imagick::COLORSPACE_LOG => 'LOG',
        'CMY' => \Imagick::COLORSPACE_CMY,
    ];

    return $colorSpaceTypes;
}


function getEyeColorSpaceStringFromValue(int $value)
{
    $colorspaceOptions = getEyeColourSpaceOptions();

    foreach ($colorspaceOptions as $string => $int) {
        if ($value === $int) {
            return $string;
        }
    }

    throw new \Exception("Bad option for getEyeColorSpaceStringFromValue $value");
}


function getImagePathForOption(string $selected_option)
{
    $imageOptions = getImagePathOptions();

    foreach ($imageOptions as $path => $option) {
        if ($option === $selected_option) {
            return $path;
        }
    }

    foreach ($imageOptions as $key => $value) {
        return $key;
    }


    return array_key_first($imageOptions);
}


function getImagepathInputParameter()
{
    return new InputParameter(
        'imagepath',
        new GetStringOrDefault('Lorikeet'),
        new EnumMap(getImagePathOptions())
    );
}

//class SleepyRule implements ProcessRule
//{
//   public function process(
//        $value,
//        ProcessedValues $processedValues,
//        InputStorage $inputStorage
//    ): ValidationResult {
//        if ($value === 'true') {
//            return \Params\ValidationResult::finalValueResult(1);
//        }
//
//        return \Params\ValidationResult::finalValueResult(0);
//    }
//
//    public function updateParamDescription(ParamDescription $paramDescription): void {
//    }
//}
