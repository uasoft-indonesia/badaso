# Badaso
Laravel CRUD Generator with SPA interface

## what is badaso
Badaso is Scalable SPA Laravel admin dashboard + API generator with full AJAX and REST API based.

# Requirement!
  - Laravel with laravel requirement. (note: tested with Laravel 5.8)
  badaso needs some PHP/Laravel library

| Library | Versi | JSON | 
| ------ | ------ | ------ |
| webpatser/laravel-uuid | ^3.0 | "webpatser/laravel-uuid": "^3.0" |
| doctrine/dbal | 2.12 | "doctrine/dbal": "2.12" |
| tymon/jwt-auth | ^1.0 | "tymon/jwt-auth": "^1.0" |

  - Vue
  badaso needs some JS/Vue library

| Library | Versi | JSON | 
| ------ | ------ | ------ |
|	axios	|	^0.18	|	axios: "^0.18"	|
|	lodash	|	^4.17.4	|	lodash: "^4.17.4"	|
|	vue	|	^2.5.7	|	vue: "^2.5.7"	|
|	@johmun/vue-tags-input	|	^2.1.0	|	@johmun/vue-tags-input: "^2.1.0"	|
|	chart.js	|	^2.8.0	|	chart.js: "^2.8.0"	|
|	luxon	|	^1.25.0	|	luxon: "^1.25.0"	|
|	material-icons	|	^0.3.1	|	material-icons: "^0.3.1"	|
|	prismjs	|	^1.17.1	|	prismjs: "^1.17.1"	|
|	vue-chartjs	|	^3.4.2	|	vue-chartjs: "^3.4.2"	|
|	vue-color	|	^2.7.1	|	vue-color: "^2.7.1"	|
|	vue-datetime	|	^1.0.0-beta.14	|	vue-datetime: "^1.0.0-beta.14"	|
|	vue-draggable-nested-tree	|	^3.0.0-beta2	|	vue-draggable-nested-tree: "^3.0.0-beta2"	|
|	vue-prism-editor	|	^1.2.2	|	vue-prism-editor: "^1.2.2"	|
|	vue-router	|	^3.1.3	|	vue-router: "^3.1.3"	|
|	vue2-editor	|	^2.10.2	|	vue2-editor: "^2.10.2"	|
|	vuedraggable	|	^2.24.3	|	vuedraggable: "^2.24.3"	|
|	vuesax	|	3.12.2	|	vuesax: "3.12.2"	|
|	vuex	|	^3.1.1	|	vuex: "^3.1.1"	|
|	vuex-persistedstate	|	^4.0.0-beta.1	|	vuex-persistedstate: "^4.0.0-beta.1"	|
|	weekstart	|	^1.0.1	|	weekstart: "^1.0.1"	|

# Instalation
Before you install badaso, make sure your laravel can run.
- install badaso from github
  - add this to composer.json
```json
  "require": {
      "uasoft-indonesia/badaso": {version},
  },
  "repositories": [
        {
            "type": "vcs",
            "url":  "https://github.com/uasoft-indonesia/badaso.git"
        }
    ]
```
- run ```composer install```
- register badaso providers dan jwt providers in config/app.php
```
    /*
     * Badaso
     */
    Uasoft\Badaso\Providers\BadasoServiceProvider::class,
    
    /*
     * JWT
     */
    Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
```
- register jwt aliases
```
    /*
     * JWT
     */
    'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,
    'JWTFactory' => Tymon\JWTAuth\Facades\JWTFactory::class,
```
- run ```php artisan vendor:publish --tag=Badaso```
- run ```composer dump-autoload```
- run ```php artisan migrate```
- run ```php artisan db:seed --class=BadasoSeeder```
- copy JS library to packages.json, looks like
```javascript
{
  "private": true,
  "scripts": {
    "dev": "npm run development",
    "development": "cross-env NODE_ENV=development node_modules/webpack/bin/webpack.js --progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js",
    "watch": "npm run development -- --watch",
    "watch-poll": "npm run watch -- --watch-poll",
    "hot": "cross-env NODE_ENV=development node_modules/webpack-dev-server/bin/webpack-dev-server.js --inline --hot --config=node_modules/laravel-mix/setup/webpack.config.js",
    "prod": "npm run production",
    "production": "cross-env NODE_ENV=production node_modules/webpack/bin/webpack.js --no-progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js"
  },
  "devDependencies": {
    "axios": "^0.18",
    "bootstrap": "^4.0.0",
    "popper.js": "^1.12",
    "cross-env": "^5.1",
    "jquery": "^3.2",
    "laravel-mix": "^2.0",
    "lodash": "^4.17.4",
    "vue": "^2.5.7"
  },
  "dependencies": {
    "@johmun/vue-tags-input": "^2.1.0",
    "botman-tinker": "^0.0.1",
    "boxicons": "^2.0.7",
    "chart.js": "^2.8.0",
    "luxon": "^1.25.0",
    "material-icons": "^0.3.1",
    "prismjs": "^1.17.1",
    "vue-chartjs": "^3.4.2",
    "vue-color": "^2.7.1",
    "vue-datetime": "^1.0.0-beta.14",
    "vue-draggable-nested-tree": "^3.0.0-beta2",
    "vue-prism-editor": "^1.2.2",
    "vue-router": "^3.1.3",
    "vue2-editor": "^2.10.2",
    "vuedraggable": "^2.24.3",
    "vuesax": "3.12.2",
    "vuex": "^3.1.1",
    "vuex-persistedstate": "^4.0.0-beta.1",
    "weekstart": "^1.0.1"
  }
}

```
- run ```npm install```
- add this path to webpack.mix.js
```json
    mix.js('resources/js/badaso/app.js', 'public/js/badaso.js')
```
- add this to env
```
    JWT_SECRET={Your JWT secret key}
    MIX_DASHBOARD_ROUTE_PREFIX={your dashboard prefix}
```
- Setup auth config
  - add badaso guard
```
'badaso_guard' => [
    'driver' => 'jwt',
    'provider' => 'badaso_users',
],
```
  - add badaso auth providers
```
'badaso_users' => [
    'driver' => 'eloquent',
    'model' => \Uasoft\Badaso\Models\User::class,
],
```
  
- for development, run
    npm run watch
    
- create admin
```
php artisan badaso:admin your@email.com --create
```
    