# A Simple CMS for Laravel

Hi there! `nonoesp/space` is a content management system package for Laravel.

**Space** supports Laravel 5.4.

## Installation

Begin by installing this package through Composer. Edit your project’s `composer.json` file to require `nonoesp/space`.

```json
"require": {
	"nonoesp/space": "^5.4"
}
```

Next, update Composer from the Terminal:

```
composer update
```

Next, add the new providers to the `providers` array of `config/app.php`:

```php
	'providers' => [
		// ...
        // nonoesp/space
        Nonoesp\Space\SpaceServiceProvider::class,        
        Nonoesp\Thinker\ThinkerServiceProvider::class,  
        Nonoesp\Authenticate\AuthenticateServiceProvider::class,          
        GrahamCampbell\Markdown\MarkdownServiceProvider::class,
        Conner\Tagging\Providers\TaggingServiceProvider::class,
        Jenssegers\Date\DateServiceProvider::class,
        Roumen\Feed\FeedServiceProvider::class,
        Thujohn\Twitter\TwitterServiceProvider::class,
        Collective\Html\HtmlServiceProvider::class,
        Vinkla\Hashids\HashidsServiceProvider::class,				
		// ...
	],
```

Then, add the class aliases to the `aliases` array of `config/app.php`:

```php
	'aliases' => [
		// ...
        // nonoesp/space - Models
        'Space' => Nonoesp\Space\Facades\Space::class,
        'User' => 'App\User',
        'Item' => Nonoesp\Space\Models\Item::class,    
        'Property' => Nonoesp\Space\Models\Property::class,
        'Recipient' => Nonoesp\Space\Models\Recipient::class,
        'Subscriber' => Nonoesp\Space\Models\Subscriber::class,

        // nonoesp/space - Dependencies
        'Thinker' => Nonoesp\Thinker\Facades\Thinker::class,
        'Authenticate' => Nonoesp\Authenticate\Facades\Authenticate::class,
        'Date' => Jenssegers\Date\Date::class,
        'Feed' => Roumen\Feed\Feed::class,
        'Markdown' => GrahamCampbell\Markdown\Facades\Markdown::class,
        'Form' => Collective\Html\FormFacade::class,
        'Html' => Collective\Html\HtmlFacade::class,   
        'Input' => Illuminate\Support\Facades\Input::class,
        'Twitter'   => Thujohn\Twitter\Facades\Twitter::class,
        'Hashids'   => Vinkla\Hashids\Facades\Hashids::class,				
		// ...
	],
```

To authenticate, `Space` uses `nonoesp/authenticate` (which should be already installed as a dependency).
But you need to follow the [Instructions to Install `nonoesp/authenticate`](https://github.com/nonoesp/laravel-authenticate/tree/master)

## Migrations

Let's create the tables required by **Space** in your database.

First, make sure your database connection is setup properly in you `.env` file.

Publish `rtconner/tagging` migrations:

```php
php artisan vendor:publish --provider="Conner\Tagging\Providers\TaggingServiceProvider"
```

Then, run the migrations:

```php
php artisan migrate
```

(You can always remove the tables by resetting: `php artisan migrate:reset`.
	But be *careful* as it will remove the contents of your tables.)

## Config

Publish configuration file to `config/space.php`.

```php
php artisan vendor:publish --provider="Nonoesp\Space\SpaceServiceProvider" --tag=config
```

## Publish Middleware

For **Space** translations to work properly we need to publish our `SetLocales.php` middleware:

```php
php artisan vendor:publish --provider="Nonoesp\Space\SpaceServiceProvider" --tag=middleware
```

Then add it to `app/Html/Kernel.php` inside the `$middleware` array:

```php
protected $middleware = [
		/// ...

		\App\Http\Middleware\SetLocales::class,
];
```

## Publish Assets

**Space** ships with compiled CSS and JS assets, in case you want to use it as is,
they can be published as follows:

```php
php artisan vendor:publish --provider="Nonoesp\Space\SpaceServiceProvider" --tag=assets
```

## Publish Development Assets

If you want to customize and compile your own stylesheets,
**Space** also contains SCSS files with numerous variables
you can tweak and development JavaScript files.
You just need to install a dependencies with `npm`,
publish the development assets,
and generate CSS and JavaScript with Laravel Mix.

Publish the development assets with:

If you haven't done so, publish `nonoesp/space` development assets.

```php
php artisan vendor:publish --provider="Nonoesp\Space\SpaceServiceProvider" --tag=dev-assets
```

### Install Dependencies (with npm)

First, let's install all our asset dependencies.

Run the following for Sass development.

```bash
npm install nonoesp/core-scss bourbon font-awesome
```

And the following for JavaScript development.

```bash
npm install vue vue-resource lodash jquery validate-js
```

### Compile Assets (with Laravel Mix)

Run `npm install` to make sure Laravel Mix is setup properly.

Your `webpack.mix.js` file should look like this.
(You can omit the `.js` or the `.sass` part, just keep whatever you are compiling.)

```php
const { mix } = require('laravel-mix');

// ...

mix.sass('resources/assets/sass/space.scss', 'public/nonoesp/space/css');
mix.js('resources/assets/js/space.js', 'public/nonoesp/space/js')
   .extract(['vue', 'vue-resource', 'jquery', 'validate-js', 'lodash', 'axios']);;

```

## License

Space is licensed under the [MIT license](http://opensource.org/licenses/MIT).

## Me

I'm [Nono Martínez Alonso](http://nono.ma) (nono.ma), a computational designer with a penchant for design, code, and simplicity. I tweet at [@nonoesp](http://www.twitter.com/nonoesp) and write at [Getting Simple](http://gettingsimple.com/). If you use this package, I would love to hear about it. Thanks!
