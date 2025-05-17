<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Date Conversion Range
    |--------------------------------------------------------------------------
    |
    | These values define the minimum and maximum years supported for conversion
    | between English and Nepali dates.
    |
    */
    'min_english_year' => 1944,
    'max_english_year' => 2033,
    'min_nepali_year' => 2000,
    'max_nepali_year' => 2089,

    /*
    |--------------------------------------------------------------------------
    | Default Format
    |--------------------------------------------------------------------------
    |
    | The default format to use when returning converted dates
    |
    */
    'default_format' => 'Y-m-d',
    'default_locale' => 'np',

    /*
    |--------------------------------------------------------------------------
    | Month and Day Names
    |--------------------------------------------------------------------------
    |
    | Customize month and day names if needed
    |
    */
    'locales' => [
        'np' => [
            'months' => [
                1 => 'बैशाख', 2 => 'जेठ', 3 => 'असार',
                4 => 'साउन', 5 => 'भदौ', 6 => 'असोज',
                7 => 'कार्तिक', 8 => 'मंसिर', 9 => 'पुष',
                10 => 'माघ', 11 => 'फागुन', 12 => 'चैत'
            ],
            'days' => [
                1 => 'आइतबार', 2 => 'सोमबार', 3 => 'मङ्गलबार',
                4 => 'बुधबार', 5 => 'बिहिबार', 6 => 'शुक्रबार',
                7 => 'शनिबार'
            ]
        ],
        'en' => [
            'months' => [
                1 => 'Baisakh', 2 => 'Jestha', 3 => 'Ashad',
                4 => 'Shrawan', 5 => 'Bhadra', 6 => 'Ashwin',
                7 => 'Kartik', 8 => 'Mangsir', 9 => 'Poush',
                10 => 'Magh', 11 => 'Falgun', 12 => 'Chaitra'
            ],
            'days' => [
                1 => 'Sunday', 2 => 'Monday', 3 => 'Tuesday',
                4 => 'Wednesday', 5 => 'Thursday', 6 => 'Friday',
                7 => 'Saturday'
            ]
        ]
    ],
];