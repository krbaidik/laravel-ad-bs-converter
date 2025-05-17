<?php
// src/Support/NepaliDate.php
namespace Krbaidik\AdBsConverter\Support;

use Carbon\Carbon;
use Illuminate\Support\Traits\Macroable;
use Krbaidik\AdBsConverter\Contracts\DateConverter;
use Krbaidik\AdBsConverter\Services\NepaliDateConverter;
use Krbaidik\AdBsConverter\Traits\HasConfig;

class NepaliDateSupport implements \JsonSerializable
{
    use Macroable, HasConfig;

    protected array $dateParts;
    protected bool $englishDigits;
    protected string $locale;
    protected DateConverter $converter;

    public function __construct(
        array $dateParts,
        bool $englishDigits = false,
        string $locale = 'np',
        ?DateConverter $converter = null
    ) {
        $this->dateParts = $dateParts;
        $this->englishDigits = $englishDigits;
        $this->locale = $locale;
        $this->converter = $converter ?? app(DateConverter::class);
    }

    public function format(string $format): string
    {
        $replacements = [
            'Y' => $this->getYear(),
            'y' => $this->getTrimmedYear(),
            'm' => $this->getPaddedMonth(),
            'n' => $this->getMonth(),
            'd' => $this->getPaddedDay(),
            'j' => $this->getDay(),
            'F' => $this->getLocalizedMonth(),
            'D' => $this->getLocalizedDay(),
            'S' => $this->getOrdinalSuffix(),
            '\\' => ''
        ];

        return strtr($format, $replacements);
    }

    public function toCarbon(): Carbon
    {
        $eng = $this->converter->nepToEng(
            $this->getYear(),
            $this->getMonth(),
            $this->getDay()
        );

        return Carbon::create($eng['year'], $eng['month'], $eng['date']);
    }

    public function addDays(int $days): self
    {
        $carbon = $this->toCarbon()->addDays($days);
        return $this->converter->createFromEng(
            $carbon->year,
            $carbon->month,
            $carbon->day,
            $this->englishDigits
        );
    }

    public function subDays(int $days): self
    {
        return $this->addDays(-$days);
    }

    public function diffInDays(self $date): int
    {
        return $this->toCarbon()->diffInDays($date->toCarbon());
    }

    public function diffForHumans(self $date): string
    {
        return $this->toCarbon()->diffForHumans($date->toCarbon());
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    public function isHoliday(): bool
    {
        $holidays = config('holidays');
        return isset($holidays[$this->format('Y-m-d')]);
    }

    public function isWeekend(): bool
    {
        $dayOfWeek = $this->dateParts['num_day'];

        return $dayOfWeek === 7 || $dayOfWeek === 1;
    }


    public function getFiscalYear(): string
    {
        $month = (int) $this->dateParts['month'];
        $year = (int) $this->dateParts['year'];
        return ($month >= 4) ? "$year/" . ($year + 1) : ($year - 1) . "/$year";
    }


    public function fiscalYearNepali(): string
    {
        $month = (int) $this->dateParts['month'];
        $year = (int) $this->dateParts['year'];
        return ($month >= 4) ? NepaliDateConverter::convert_to_nepali_number($year)."/" . NepaliDateConverter::convert_to_nepali_number($year + 1) : NepaliDateConverter::convert_to_nepali_number($year - 1) . "/".NepaliDateConverter::convert_to_nepali_number($year);
    }

    public function toArray(): array
    {
        $this->englishDigits = true;
        return [
            'bs' => $this->dateParts,
            'ad' => $this->toCarbon()->format('Y-m-d'),
            'formatted' => $this->format('Y F d, D'),
            'fiscal_year' => $this->getFiscalYear(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return $this->format('Y-m-d');
    }

    protected function getYear(): string
    {
        return $this->englishDigits
            ? $this->dateParts['year']
            : NepaliDateConverter::convert_to_nepali_number($this->dateParts['year']);
    }

    protected function getTrimmedYear(): string
    {
        return $this->englishDigits
            ? substr($this->dateParts['year'], 1)
            : mb_substr(NepaliDateConverter::convert_to_nepali_number($this->dateParts['year']), 1);
    }

    protected function getMonth(): string
    {
        return $this->englishDigits
            ? $this->dateParts['month']
            : NepaliDateConverter::convert_to_nepali_number($this->dateParts['month']);
    }

    protected function getPaddedMonth(): string
    {
        if ($this->englishDigits)
            return str_pad($this->getMonth(), 2, '0', STR_PAD_LEFT);

        return $this->padNepNumber($this->getMonth());
    }

    protected function getDay(): string
    {
        return $this->englishDigits
            ? $this->dateParts['date']
            : NepaliDateConverter::convert_to_nepali_number($this->dateParts['date']);
    }

    /**
     * Get localized month name
     */
    public function getLocalizedMonth(): string
    {
        return $this->getConfig("locales.{$this->locale}.months.{$this->dateParts['month']}", '');
    }

    /**
     * Get localized day name
     */
    public function getLocalizedDay(): string
    {
        return $this->getConfig("locales.{$this->locale}.days.{$this->dateParts['num_day']}", '');
    }

    protected function getPaddedDay(): string
    {
        if ($this->englishDigits)
            return str_pad($this->getDay(), 2, '0', STR_PAD_LEFT);

        return $this->padNepNumber($this->getDay());
    }

    protected function getOrdinalSuffix(): string
    {
        if (!$this->englishDigits)
            return '';

        $day = (int) $this->dateParts['date'];
        if ($day > 10 && $day < 20)
            return 'th';
        switch ($day % 10) {
            case 1:
                return 'st';
            case 2:
                return 'nd';
            case 3:
                return 'rd';
            default:
                return 'th';
        }
    }

    protected function convertDigits(string $value): string
    {
        $nepaliDigits = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($nepaliDigits, $englishDigits, $value);
    }

    public function padNepNumber($num)
    {
        if (mb_strlen($num) == 1) {
            return '०' . $num;
        }
        return $num;
    }
}