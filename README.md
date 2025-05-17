# Laravel Nepali Date Converter

[![Latest Version](https://img.shields.io/packagist/v/krbaidik/laravel-ad-bs-converter.svg)](https://packagist.org/packages/krbaidik/laravel-ad-bs-converter)
[![License](https://img.shields.io/github/license/krbaidik/laravel-ad-bs-converter.svg)](LICENSE.md)

A complete Nepali (Bikram Sambat) to English (Gregorian) date conversion package for Laravel with advanced features.

## Features

- **Bi-directional Conversion**: Convert between BS and AD dates
- **Carbon Integration**: Seamless interoperability with Carbon
- **Localization**: Support for Nepali and English output
- **Fiscal Year Calculation**: Nepali fiscal year support
- **Database Ready**: Eloquent cast support
- **Macroable**: Extend functionality easily
- **Weekend Detection**: Identify weekends in Nepali calendar


## Installation

You can install the package via composer:

```bash
composer require krbaidik/laravel-ad-bs-converter
```

Optionally, you can publish the config file with:

```bash
php artisan vendor:publish --tag="nepali-date-config"
```

# Basic Usage

```php
use Krbaidik\AdBsConverter\Facades\NepaliDate;

// English to Nepali
$nepaliDate = NepaliDate::createFromEng(2023, 5, 15);
echo $nepaliDate->format('Y-m-d'); // २०८०-०२-०१

// Nepali to English
$englishDate = NepaliDate::createFromNep(2080, 2, 1)->toCarbon();

//Current Date
$today = NepaliDate::today()->format('Y F d, D');
// २०८० भाद्र १५, सोमबार



//Formatting Options
$date = NepaliDate::parse('2080-09-15');

// Default format
$date->format('Y-m-d'); // २०८०-०९-१५

// English digits
$date->format('Y-m-d', englishDigits: true); // 2080-09-15


//Fiscal Year Calculation
NepaliDate::today()->fiscalYear(); // "2080/81"
NepaliDate::today()->fiscalYearNepali(); // "२०८०/८१"


//Database Integration
// In your model
protected $casts = [
    'event_date' => \Krbaidik\AdBsConverter\Casts\NepaliDateCast::class
];

// Automatic conversion
$event = Event::create([
    'event_date' => '2080-09-15' // Stored as string, retrieved as NepaliDate object
]);

//Weekend Detection
NepaliDate::today()->isWeekend(); // true | false

// Total days in a month of a year
NepaliDate::getDaysInMonth(2082,1); // 31

// New year detection
NepaliDate::today()->isNewYear(); // true | false

$date1 = NepaliDate::parse('2080-01-01');
$date2 = NepaliDate::parse('2080-09-15');

// Difference in days
$date1->diffInDays($date2);

$date = NepaliDate::parse('2080-01-01');

// Add 15 days
$futureDate = $date->addDays(15);
$futureDate->format('Y-m-d'); // २०८०-०१-१६


// Nepali (default)
NepaliDate::today()->format('F'); // "भाद्र"

// English
NepaliDate::today()->setLocale('en')->format('F'); // "Bhadra"
```
## API Reference

Main Methods
- `today()`	        - Get today's date in BS
- `now()`	        - Alias for today()
- `createFromEng()`	- Create from English date
- `createFromNep()`	- Create from Nepali date
- `parse()`	        - Parse date string
- `fromCarbon()`    - Create from Carbon instance
- `getDaysInMonth()`- Get total days in a month os a year


Instance Methods
- `format()`     -  Format the date
- `toCarbon()`   -	Convert to Carbon instance
- `isWeekend()`  -	Check if weekend
- `fiscalYear()` -	Get fiscal year
- `fiscalYearNepali()` - Get fiscal year in nepali
- `setLocale()`  -	Change output locale
- `getLocale()`  -	Get current locale
- `isNewYear()`  -	Check if new year
- `diffInDays()` -	Get day difference in two dates
- `...`

## Format Specifiers

The following format specifiers are supported for formatting dates:

- `Y` - Year in four digits
- `y` - Year in two digits
- `m` - Month in two digits with leading zero (01-12/०१-१२)
- `n` - Month in one or two digits without leading zero (1-12/१-१२)
- `F` - Month in full name (January-December/बैशाख-चैत्र)
- `d` - Day in two digits with leading zero (01-31/०१-३२)
- `j` - Day in one or two digits without leading zero (1-31/१-३२)
- `D` - Day in full name (Sunday-Saturday/आइतबार-शनिबार)
- `S` - Day in two letters (st, nd, rd, th)


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Khubi Ram Baidik](https://github.com/krbaidik)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.