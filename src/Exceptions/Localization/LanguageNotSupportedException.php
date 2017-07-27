<?php
namespace Empari\Support\Exceptions\Localization;

use League\Flysystem\Exception;

class LanguageNotSupportedException extends Exception
{
    public function __construct($message = "Language not supported.", $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}