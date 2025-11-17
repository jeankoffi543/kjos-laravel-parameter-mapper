# Kjos Laravel Parameter Mapper

Un package Laravel qui permet de mapper dynamiquement les paramètres GET et POST envoyés par le client vers les clés backend de votre application, et inversement. Pratique pour cacher les vrais noms de champs à l’utilisateur ou pour standardiser vos API.

---

## Installation

Pour Laravel 12 ou supérieur :

```bash
composer require kjos/kjos-laravel-parameter-mapper
```

## Publier la configuration

```bash
php artisan vendor:publish --tag=parametermap
```

## Configuration

```php
return [
    'map' => [
        // frontParam => backendParam
        'id_ur'   => 'user_id',
        'name_lt' => 'last_name',
        'ae'      => 'age',

        // Valeurs spécifiques à mapper (ex: search=id_us => search=user_id)
        'values-to-map' => [
            'search',
        ],

        // Mapper les clés dans des tableaux (ex: sort[id_us] => sort[user_id])
        'array-keys-to-map' => [
            'sort',
        ],
    ],
];
```

## Middleware

```php
use Kjos\ParameterMapper\Middleware\MapRequestParameters;
use App\Http\Middleware\AuthenticateAdmin;

Route::middleware([MapRequestParameters::class, AuthenticateAdmin::class])
    ->group(function () {
        Route::post('/api/admins', [AdminController::class, 'store']);
    });
```

## Classe `ParameterMapper`

```php
use Kjos\ParameterMapper\Support\ParameterMapper;

// Mapper front -> back
$mapped = ParameterMapper::apply([
    'id_ur' => 1,
    'name_lt' => 'Koffi',
    'ae' => 10,
    'sort' => ['id_ur' => 'asc'],
    'search' => 'id_ur',
]);

// Mapper back -> front
$frontend = ParameterMapper::reverse([
    'user_id' => 1,
    'last_name' => 'Koffi',
    'age' => 10,
    'sort' => ['user_id' => 'asc'],
    'search' => 'user_id',
]);
```

## Utilisation dans les Factories

```php
it('should store admin', function ($guestAdmin) {
    $guestAdmin = $guestAdmin->toArray();
    $guestAdmin = ParameterMapper::reverse($guestAdmin);

    $guestAdmin['image'] = generateUploadedFile();
    $guestAdmin['password'] = 'password';

    $response = post('/api/admins/admins', $guestAdmin)
        ->assertCreated();

    Storage::disk('public')->assertExists('admins/' . basename($response['data']['image']));
})->with('guest admin');
```

## Exemple API

Requête :

```
GET /api/admins?search=id_us&sort[id_us]=asc
```

Transformée automatiquement en :

```php
[
    'search' => 'user_id',
    'sort' => ['user_id' => 'asc']
];
```

