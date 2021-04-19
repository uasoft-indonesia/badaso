# Contribute to badaso

Badaso is an open-source project administered by [uasoft](https://soft.uatech.co.id). We appreciate your interest and efforts to contribute to Badaso.

All efforts to contribute are highly appreciated, we recommend you talk to a maintainer prior to spending a lot of time making a pull request that may not align with the project roadmap.

## Open Development & Community Driven

Badaso is an open-source project. See the [license](https://github.com/uasoft-indonesia/badaso/blob/master/license) file for licensing information. All the work done is available on GitHub.

The core team and the contributors send pull requests which go through the same validation process.

## Feature Requests

Feature Requests by the community are highly encouraged. Please feel free to submit your ides on [github discussion](https://github.com/uasoft-indonesia/badaso/discussions/categories/ideas)

## Code of Conduct

This project and everyone participating in it are governed by the [Badaso Code of Conduct](code_of_conduct.md). By participating, you are expected to uphold this code. Please read the [full text](code_of_conduct.md) so that you can read which actions may or may not be tolerated.

## Bugs

We are using [GitHub Issues](https://github.com/uasoft-indonesia/badaso/issues) to manage our public bugs. We keep a close eye on this so before filing a new issue, try to make sure the problem does not already exist.

---

## Before Submitting a Pull Request

The core team will review your pull request and will either merge it, request changes to it, or close it.

**Before submitting your pull request** make sure the following requirements are fulfilled:

To do : complete this section

## Contribution Prerequisites

- You are familiar with Git.

## Development Workflow

Before develop Badaso, please get BADASO_LICENSE_KEY by  register on <a href="https://badaso.uatech.co.id/" target="_blank">Badaso</a> or contact badaso core team. This key must be included in the laravel project's .env.
Steps for registering and getting a license on Badaso Dashboard can be found on <a href="https://badaso-docs.uatech.co.id/docs/en/getting-started/installation/" target="_blank">Badaso Docs</a>.

### Installation step

After getting the license, you can proceed to Badaso installation.

1, Clone badaso into Laravel project. Sample:
- Root Laravel Project
  - /packages // new folder
    - /uasoft-indonesia // new folder
      - badaso // clone here

cd into uasoft-indonesia directory, then run
```
git clone https://github.com/uasoft-indonesia/badaso.git
```

2. Add the following Badaso provider and JWT provider to ```/config/app.php```.

```
'providers' => [
  ...,
  Uasoft\Badaso\Providers\BadasoServiceProvider::class,
  Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
]
```

3. Add the following aliases to ```config/app.php```.
```
'aliases' => [
    ...,
    'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,
    'JWTFactory' => Tymon\JWTAuth\Facades\JWTFactory::class,
]
```

4. Add badaso providers to autoload

```
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Uasoft\\Badaso\\": "packages/uasoft-indonesia/badaso/src/"
    },
    ...
}
```

5. Copy required library from ```packages/uasoft-indonesia/badaso/composer.json``` to ```/composer.json``` then ```composer install```

6. Run the following commands to update dependencies in package.json and webpack.
```
php artisan badaso:setup
```

7. Run the following commands in sequence.
```
composer dump-autoload
php artisan migrate
php artisan db:seed --class=BadasoSeeder
```

8. Open the ```env``` file then add the following lines.
```
#Set a key as secret key for generating JWT token
JWT_SECRET=

#Set JWT Token lifetime, default 60 minutes
BADASO_AUTH_TOKEN_LIFETIME=

#License key (get the license on https://badaso.uatech.co.id)
BADASO_LICENSE_KEY=

#Set default menu to generate menu in dashboard. 
#By default Badaso provide `admin` as default menu
MIX_DEFAULT_MENU=admin

#Set Route prefix for your dashboard. 
#Access dashboard via {HOST}/{MIX_ADMIN_PANEL_ROUTE_PREFIX}
MIX_ADMIN_PANEL_ROUTE_PREFIX=dashboard

#Set prefix for api that badaso provide. By default 
#Badaso provide `badaso-api` as prefix. 
MIX_API_ROUTE_PREFIX=admin

#Badaso provide Log Viewer feature. please set a route to access this feature
MIX_LOG_VIEWER_ROUTE="log-viewer"
```
:::important
MIX_ADMIN_PANEL_ROUTE_PREFIX, MIX_API_ROUTE_PREFIX & MIX_LOG_VIEWER_ROUTE should be different
:::

9. Add the following Badaso guard and auth provider in ```config/auth.php```. Make sure to use Badaso guard as auth default in ```config/auth.php```.
<!--DOCUSAURUS_CODE_TABS-->
<!--PHP-->
```php
return [
  'defaults' => [
    'guard' => 'badaso_guard',
    'passwords' => 'users',
  ],

  'guards' => [
    ...,
    'badaso_guard' => [
        'driver' => 'jwt',
        'provider' => 'badaso_users',
    ],
  ],

  'providers' => [
    ...,
    'badaso_users' => [
        'driver' => 'eloquent',
        'model' => \Uasoft\Badaso\Models\User::class,
    ],
  ],

  ...,
]
```
<!--END_DOCUSAURUS_CODE_TABS-->

10. Create an admin account by typing the following command.
```
php artisan badaso:admin your@email.com --create
```

11. Run the command ```npm install``` to install all of dependencies
12. Update webpack.mix.js from
```
// Badaso
mix.js(
        "vendor/uasoft-indonesia/badaso/src/resources/js/app.js",
        "public/js/badaso.js"
    )
```
into
```
// Badaso
mix.js(
        "packages/uasoft-indonesia/badaso/src/resources/js/app.js",
        "public/js/badaso.js"
    )
```
13. Run laravel with the command ```npm run watch``` if it is under development environment or ```npm run prod``` for production environment.

## Running the tests

To do : complete this section

---

### Reporting an issue

Before submitting an issue you need to make sure:

- You are experiencing a concrete technical issue with Badaso.
- You have already searched for related [issues](https://github.com/uasoft-indonesia/badaso/issues), and found none open (if you found a related _closed_ issue, please link to it from your post).
- You are not asking a question about how to use Badaso or about whether or not Badaso has a certain feature. For general help using Badaso, you may:
  - Refer to [the official Badaso documentation](https://badaso-docs.uatech.co.id).
  - Ask a question on [github discussion](https://github.com/uasoft-indonesia/badaso/discussions).
- Your issue title is concise, on-topic and polite.
- You can and do provide steps to reproduce your issue.
- You have tried all the following (if relevant) and your issue remains:
  - Make sure you have the right application started.
  - Make sure the [issue template](.github/ISSUE_TEMPLATE) is respected.
  - Make sure your issue body is readable and [well formatted](https://guides.github.com/features/mastering-markdown).
  - Make sure the application you are using to reproduce the issue has a clean `node_modules` or `vendor` directory, meaning:
    - that you haven't made any inline changes to files in the `node_modules` or `vendor` folder
    - that you don't have any weird global dependency loops. The easiest way to double-check any of the above, if you aren't sure, is to run: `$ rm -rf node_modules && npm cache clear && npm install`.