<?php
// src/Facades/NepaliDate.php
namespace Krbaidik\AdBsConverter\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Krbaidik\AdBsConverter\Support\NepaliDate today(bool $englishDigits = false)
 * @method static \Krbaidik\AdBsConverter\Support\NepaliDate now(bool $englishDigits = false)
 * @method static \Krbaidik\AdBsConverter\Support\NepaliDate createFromEng(int $year, int $month, int $day, bool $englishDigits = false)
 * @method static \Krbaidik\AdBsConverter\Support\NepaliDate createFromNep(int $year, int $month, int $day, bool $englishDigits = false)
 * @method static \Krbaidik\AdBsConverter\Support\NepaliDate parse(string $dateString, bool $englishDigits = false)
 * @method static \Krbaidik\AdBsConverter\Support\NepaliDate fromCarbon(\Carbon\Carbon $date, bool $englishDigits = false, string $locale = 'np')
 */
class NepaliDate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Krbaidik\AdBsConverter\Contracts\DateConverter::class;
    }
}