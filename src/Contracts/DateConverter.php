<?php

namespace Krbaidik\AdBsConverter\Contracts;

use Krbaidik\AdBsConverter\Support\NepaliDateSupport;

interface DateConverter
{
    public function engToNep(int $year, int $month, int $day): array;
    public function nepToEng(int $year, int $month, int $day): array;
    public function today(bool $englishDigits = false);
    public function createFromEng(int $year, int $month, int $day, bool $englishDigits = false);
    public function createFromNep(int $year, int $month, int $day, bool $englishDigits = false);
    public function parse(string $dateString, bool $englishDigits = false);
    public function fromCarbon(\Carbon\Carbon $date, bool $englishDigits = false, string $locale = 'np'): NepaliDateSupport;
    public function getDaysInMonth(int $year, int $mnth);
}