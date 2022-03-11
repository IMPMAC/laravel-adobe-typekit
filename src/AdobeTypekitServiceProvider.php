<?php

namespace Weble\AdobeTypekit;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Weble\AdobeTypekit\Commands\FetchAdobeTypekitCommand;

class AdobeTypekitServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('adobe-typekit')
            ->hasConfigFile()
            ->hasCommand(FetchAdobeTypekitCommand::class);
    }

    public function packageRegistered()
    {
        $this->app->singleton(AdobeTypekit::class, function (Application $app) {
            return new AdobeTypekit(
                filesystem: $app->make(FilesystemManager::class)->disk(config('adobe-typekit.disk')),
                path: config('adobe-typekit.path'),
                inline: config('adobe-typekit.inline'),
                fallback: config('adobe-typekit.fallback'),
                userAgent: config('adobe-typekit.user_agent'),
                fonts: config('adobe-typekit.fonts'),
            );
        });
    }

    public function packageBooted()
    {
        Blade::directive('typekit', function ($expression) {
            return "<?php echo app(\Weble\AdobeTypekit\AdobeTypekit::class)->load($expression)->toHtml(); ?>";
        });
    }
}
