<?php
// src/Exceptions/InvalidNepaliDateException.php
namespace Krbaidik\AdBsConverter\Exceptions;

class InvalidNepaliDateException extends \Exception
{
    protected $message = 'Invalid Nepali date provided';
}