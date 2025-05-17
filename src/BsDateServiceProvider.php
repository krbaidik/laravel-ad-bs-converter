<?php
// src/BsDateServiceProvider.php
namespace Krbaidik\AdBsConverter;

use Illuminate\Support\ServiceProvider;
use Krbaidik\AdBsConverter\Support\NepaliDateSupport;

use Krbaidik\AdBsConverter\Macros\{
    FiscalYearMacro,
    DifferenceMacro
};

class BsDateServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/nepali-date.php', 'nepali-date');
        
        $this->app->singleton(
            \Krbaidik\AdBsConverter\Contracts\DateConverter::class,
            \Krbaidik\AdBsConverter\Services\NepaliDateConverter::class
        );
        
        $this->registerMacros();
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/nepali-date.php' => config_path('nepali-date.php')
        ], 'nepali-date-config');
    }

    protected function registerMacros()
    {
        FiscalYearMacro::register(); 
        NepaliDateSupport::macro('diffInDays', app(DifferenceMacro::class));

        NepaliDateSupport::macro('isNewYear', function() {
            return $this->format('m-d') === '01-01';
        });
    }
}