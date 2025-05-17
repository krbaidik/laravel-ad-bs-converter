<?php
namespace Krbaidik\AdBsConverter\Macros;

use Krbaidik\AdBsConverter\Support\NepaliDateSupport;

class FiscalYearMacro
{
    public static function register()
    {
        NepaliDateSupport::macro('fiscalYear', function(): string {
            /** @var NepaliDateSupport $this */
            $month = (int)$this->getMonth();
            $year = (int)$this->getYear();
            
            return ($month >= 4) ? "$year/" . ($year + 1) : ($year - 1) . "/$year";
        });
    }
}