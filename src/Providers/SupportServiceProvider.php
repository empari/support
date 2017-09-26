<?php namespace Empari\Support\Providers;

use Bootstrapper\Facades\Navbar;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\FilesystemCache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Foundation\AliasLoader;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Module Name
     * @var string
     */
    protected $moduleName = 'empari-support';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->publishMigrationsAndSeeders();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if (class_exists(BootstrapServiceProvider::class)) {
            $this->app->register(BootstrapServiceProvider::class);
        }

        if (class_exists(\Collective\Html\HtmlServiceProvider::class)) {
            $this->app->register(\Collective\Html\HtmlServiceProvider::class);
        }

        if (class_exists(\Bootstrapper\BootstrapperL5ServiceProvider::class)) {
            $this->app->register(\Bootstrapper\BootstrapperL5ServiceProvider::class);
        }

        $this->registerAnnotations();
        $this->registerAliases();
    }

    protected function registerAliases()
    {
        // Bootstraper
        if (class_exists(\Bootstrapper\Facades\Helpers::class)) {
            AliasLoader::getInstance()->alias('Accordion' , \Bootstrapper\Facades\Accordion::class);
            AliasLoader::getInstance()->alias('Alert' , \Bootstrapper\Facades\Alert::class);
            AliasLoader::getInstance()->alias('Badge' , \Bootstrapper\Facades\Badge::class);
            AliasLoader::getInstance()->alias('Breadcrumb' , \Bootstrapper\Facades\Breadcrumb::class);
            AliasLoader::getInstance()->alias('Button' , \Bootstrapper\Facades\Button::class);
            AliasLoader::getInstance()->alias('ButtonGroup' , \Bootstrapper\Facades\ButtonGroup::class);
            AliasLoader::getInstance()->alias('Carousel' , \Bootstrapper\Facades\Carousel::class);
            AliasLoader::getInstance()->alias('ControlGroup' , \Bootstrapper\Facades\ControlGroup::class);
            AliasLoader::getInstance()->alias('DropdownButton' , \Bootstrapper\Facades\DropdownButton::class);
            AliasLoader::getInstance()->alias('Form' , \Bootstrapper\Facades\Form::class);
            AliasLoader::getInstance()->alias('Helpers' , \Bootstrapper\Facades\Helpers::class);
            AliasLoader::getInstance()->alias('Icon' , \Bootstrapper\Facades\Icon::class);
            AliasLoader::getInstance()->alias('InputGroup' , \Bootstrapper\Facades\InputGroup::class);
            AliasLoader::getInstance()->alias('Image' , \Bootstrapper\Facades\Image::class);
            AliasLoader::getInstance()->alias('Label' , \Bootstrapper\Facades\Label::class);
            AliasLoader::getInstance()->alias('MediaObject' , \Bootstrapper\Facades\MediaObject::class);
            AliasLoader::getInstance()->alias('Modal' , \Bootstrapper\Facades\Modal::class);
            AliasLoader::getInstance()->alias('Navbar' , \Bootstrapper\Facades\Navbar::class);
            AliasLoader::getInstance()->alias('Navigation' , \Bootstrapper\Facades\Navigation::class);
            AliasLoader::getInstance()->alias('Panel' , \Bootstrapper\Facades\Panel::class);
            AliasLoader::getInstance()->alias('ProgressBar' , \Bootstrapper\Facades\ProgressBar::class);
            AliasLoader::getInstance()->alias('Tabbable' , \Bootstrapper\Facades\Tabbable::class);
            AliasLoader::getInstance()->alias('Table' , \Bootstrapper\Facades\Table::class);
            AliasLoader::getInstance()->alias('Thumbnail' , \Bootstrapper\Facades\Thumbnail::class);
        }

        // Vendor Packages
        if (class_exists(\Collective\Html\FormFacade::class)) {
            AliasLoader::getInstance()->alias('Form', \Collective\Html\FormFacade::class);
            AliasLoader::getInstance()->alias('Html', \Collective\Html\HtmlFacade::class);
        }

        // Packages
        AliasLoader::getInstance()->alias('NavBarAuth', \Empari\Support\Facade\NavBarAuthorizationFacade::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path($this->moduleName .'.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../../config/config.php', $this->moduleName
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/vendor/empari/'. $this->moduleName);

        $sourcePath = __DIR__.'/../../resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/vendor/empari/'. $this->moduleName;
        }, \Config::get('view.paths')), [$sourcePath]), $this->moduleName);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/vendor/empari/'. $this->moduleName);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleName);
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../../resources/lang', $this->moduleName);
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Register Annotations
     */
    public function registerAnnotations()
    {
        $loader = require base_path() .'/vendor/autoload.php';
        AnnotationRegistry::registerLoader([$loader, 'loadClass']);
        $this->registerAnnotationReader();
    }

    /**
     * Register Annotations Reader
     */
    public function registerAnnotationReader()
    {
        $this->app->bind(Reader::class, function () {
            return new CachedReader(
                new AnnotationReader(),
                new FilesystemCache(storage_path('framework/cache/doctrine-annotations')),
                $debug = config('app.debug')
            );
        });
    }

    /**
     * Register all migrations and seeders
     *
     */
    public function publishMigrationsAndSeeders()
    {
        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../../database/seeders' => database_path('seeds')
        ], 'seeders');
    }
}
