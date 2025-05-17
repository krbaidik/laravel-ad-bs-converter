<?php
namespace Krbaidik\AdBsConverter\Macros;

use Krbaidik\AdBsConverter\Support\NepaliDateSupport;

class DifferenceMacro
{
    public function __invoke()
    {
        return function(NepaliDateSupport $date): int {
            /** @var NepaliDateSupport $this */
            return $this->toCarbon()->diffInDays($date->toCarbon());
        };
    }
}