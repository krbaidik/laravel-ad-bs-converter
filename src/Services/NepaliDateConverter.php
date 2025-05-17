<?php
// src/Services/NepaliDateConverter.php
namespace Krbaidik\AdBsConverter\Services;

use Krbaidik\AdBsConverter\Constants\NepaliDateArray;
use Krbaidik\AdBsConverter\Exceptions\InvalidNepaliDateException;
use Krbaidik\AdBsConverter\Support\NepaliDateSupport;

class NepaliDateConverter implements \Krbaidik\AdBsConverter\Contracts\DateConverter
{
    public function engToNep(int $yy, int $mm, int $dd): array
    {
        $chk = $this->_is_in_range_eng($yy, $mm, $dd);
        if ($chk !== true)
            throw new InvalidNepaliDateException($chk);

        $month = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        $def_eyy = 1944;
        $def_nyy = 2000;
        $def_nmm = 9;
        $def_ndd = 16;
        $total_eDays = 0;
        $total_nDays = 0;
        $a = 0;
        $day = 6;
        $m = 0;
        $y = 0;
        $i = 0;
        $j = 0;
        $numDay = 0;

        // Count total days in terms of year
        for ($i = 0; $i < ($yy - $def_eyy); $i++) {
            $total_eDays += $this->is_leap_year($def_eyy + $i) ? 366 : 365;
        }

        // Count total days in terms of month
        for ($i = 0; $i < ($mm - 1); $i++) {
            $total_eDays += $this->is_leap_year($yy) ?
                ($i == 1 ? 29 : $month[$i]) :
                ($i == 1 ? 28 : $month[$i]);
        }

        $total_eDays += $dd;
        $i = 0;
        $j = $def_nmm;
        $total_nDays = $def_ndd;
        $m = $def_nmm;
        $y = $def_nyy;

        while ($total_eDays != 0) {
            $a = NepaliDateArray::$bsDateArray[$i][$j];
            $total_nDays++;
            $day++;

            if ($total_nDays > $a) {
                $m++;
                $total_nDays = 1;
                $j++;
            }

            if ($day > 7)
                $day = 1;
            if ($m > 12) {
                $y++;
                $m = 1;
            }
            if ($j > 12) {
                $j = 1;
                $i++;
            }

            $total_eDays--;
        }

        return [
            'year' => $y,
            'month' => $m,
            'date' => $total_nDays,
            // 'day' => $this->_get_day_of_week($day),
            // 'nmonth' => $this->_get_nepali_month($m),
            'num_day' => $day
        ];
    }

    public function nepToEng(int $yy, int $mm, int $dd): array
    {
        $chk = $this->_is_in_range_nep($yy, $mm, $dd);
        if ($chk !== true)
            throw new InvalidNepaliDateException($chk);

        $def_eyy = 1943;
        $def_emm = 4;
        $def_edd = 13;
        $def_nyy = 2000;
        $def_nmm = 1;
        $def_ndd = 1;
        $total_eDays = 0;
        $total_nDays = 0;
        $a = 0;
        $day = 3;
        $m = 0;
        $y = 0;
        $i = 0;
        $k = 0;
        $numDay = 0;
        $month = [0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        $lmonth = [0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        // Count total days in terms of year
        for ($i = 0; $i < ($yy - $def_nyy); $i++) {
            for ($j = 1; $j <= 12; $j++) {
                $total_nDays += NepaliDateArray::$bsDateArray[$k][$j];
            }
            $k++;
        }

        // Count total days in terms of month
        for ($j = 1; $j < $mm; $j++) {
            $total_nDays += NepaliDateArray::$bsDateArray[$k][$j];
        }

        $total_nDays += $dd;
        $total_eDays = $def_edd;
        $m = $def_emm;
        $y = $def_eyy;

        while ($total_nDays != 0) {
            $a = $this->is_leap_year($y) ? $lmonth[$m] : $month[$m];
            $total_eDays++;
            $day++;

            if ($total_eDays > $a) {
                $m++;
                $total_eDays = 1;
                if ($m > 12) {
                    $y++;
                    $m = 1;
                }
            }

            if ($day > 7)
                $day = 1;
            $total_nDays--;
        }

        return [
            'year' => $y,
            'month' => $m,
            'date' => $total_eDays,
            'day' => $this->_get_day_of_week($day),
            'emonth' => $this->_get_english_month($m),
            'num_day' => $day
        ];
    }

    public function today(bool $englishDigits = true, string $locale = 'np'): NepaliDateSupport
    {
        return $this->now($englishDigits, $locale);
    }

    public function now(bool $englishDigits = false, string $locale = 'np'): NepaliDateSupport
    {
        $now = now();
        return $this->createFromEng(
            $now->year,
            $now->month,
            $now->day,
            $englishDigits,
            $locale
        );
    }

    public function createFromEng(
        int $year,
        int $month,
        int $day,
        bool $englishDigits = false,
        string $locale = 'np'
    ): NepaliDateSupport {
        $bs = $this->engToNep($year, $month, $day);

        return new NepaliDateSupport($bs, $englishDigits, $locale, $this);
    }

    public function createFromNep(
        int $year,
        int $month,
        int $day,
        bool $englishDigits = true,
        string $locale = 'np'
    ): NepaliDateSupport {
        $this->validateNepaliDate($year, $month, $day);
        $eng = $this->nepToEng($year, $month, $day);
        return $this->createFromEng(
            $eng['year'],
            $eng['month'],
            $eng['date'],
            $englishDigits,
            $locale
        );
    }

    public function parse(
        string $dateString,
        bool $englishDigits = true,
        string $locale = 'np'
    ): NepaliDateSupport {
        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $dateString, $matches)) {
            return $this->createFromNep($matches[1], $matches[2], $matches[3], $englishDigits, $locale);
        }

        throw new InvalidNepaliDateException("Unsupported date format");
    }

    /**
     * Create NepaliDate from Carbon instance
     */
    public function fromCarbon(\Carbon\Carbon $date, bool $englishDigits = false, string $locale = 'np'): NepaliDateSupport
    {
        return $this->createFromEng(
            $date->year,
            $date->month,
            $date->day,
            $englishDigits,
            $locale
        );
    }

    public function getDaysInMonth($year, $month)
    {

        foreach (NepaliDateArray::$bsDateArray as $bsDate) {
            if ($bsDate[0] == (int) $year) {
                return $bsDate[$month];
            }
        }
    }

    protected function validateNepaliDate(int $year, int $month, int $day): void
    {
        if (!$this->_is_in_range_nep($year, $month, $day)) {
            throw new InvalidNepaliDateException("Invalid Nepali date: $year-$month-$day");
        }
    }

    private function _is_in_range_eng($yy, $mm, $dd)
    {
        if ($yy < 1944 || $yy > 2033)
            return 'Supported only between 1944-2033';
        if ($mm < 1 || $mm > 12)
            return 'Month must be 1-12';
        if ($dd < 1 || $dd > 31)
            return 'Day must be 1-31';
        return true;
    }

    private function _is_in_range_nep($yy, $mm, $dd)
    {
        if ($yy < 2000 || $yy > 2089)
            return 'Supported only between 2000-2089';
        if ($mm < 1 || $mm > 12)
            return 'Month must be 1-12';
        if ($dd < 1 || $dd > 32)
            return 'Day must be 1-32';
        return true;
    }

    private function is_leap_year($year)
    {
        return ($year % 400 == 0) || ($year % 100 != 0 && $year % 4 == 0);
    }

    private function _get_day_of_week($day)
    {
        $days = [
            1 => 'आइतबार',
            2 => 'सोमबार',
            3 => 'मङ्गलबार',
            4 => 'बुधबार',
            5 => 'बिहिबार',
            6 => 'शुक्रबार',
            7 => 'शनिबार'
        ];
        return $days[$day] ?? 'अज्ञात';
    }

    private function _get_nepali_month($m)
    {
        $months = [
            1 => 'बैशाख',
            2 => 'जेठ',
            3 => 'असार',
            4 => 'साउन',
            5 => 'भदौ',
            6 => 'असोज',
            7 => 'कार्तिक',
            8 => 'मंसिर',
            9 => 'पुष',
            10 => 'माघ',
            11 => 'फागुन',
            12 => 'चैत'
        ];
        return $months[$m] ?? 'अज्ञात';
    }

    private function _get_english_month($m)
    {
        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];
        return $months[$m] ?? 'Unknown';
    }

    public static function convert_to_nepali_number($str)
    {
        $nepaliDigits = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
        return str_replace(range(0, 9), $nepaliDigits, $str);
    }
}