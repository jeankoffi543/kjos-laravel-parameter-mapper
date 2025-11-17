
![Packagist Version](https://img.shields.io/packagist/v/kjos/kjos-laravel-parameter-mapper)
![PHP](https://img.shields.io/badge/PHP-%5E8.0-blue)
![License](https://img.shields.io/github/license/jeankoffi543/kjos-laravel-parameter-mapper)


## 🌐 Multilingue | Multilingue

- 🇫🇷 [Version Française](README.fr.md)
- 🇬🇧 [English Version](README.md)

## 🇫🇷 Français

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
Route::middleware([MapRequestParameters::class])
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
    $datas = ParameterMapper::reverse([
      'user_id' => 1,
      'last_name' => 'Koffi',
      'age' => 10,
    ]);
```
Devient:
```php
    [
      'id_ur' => 1,
      'name_lt' => 'Koffi',
      'ae' => 10,
    ]
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

## Schema
![Schema](docs/vers/schema.png)


# 📁 Directory Structure
```
project-root/
   ├── CHANGELOG.md
   ├── LICENSE
   ├── README.md
   ├── composer.json
   ├── composer.lock
   ├── config
   │   └── parameter-mapper.php
   ├── grumphp.yml
   ├── phpunit.xml
   ├── pint.json
   ├── schema.png
   ├── src
   │   ├── Middleware
   │   ├── ParameterMapperServiceProvider.php
   │   └── Support
   ├── tests
   │   ├── ExampleTest.php
   │   ├── Feature
   │   ├── Pest.php
   │   ├── TestCase.php
   │   └── Unit
```


# 👤 Author
Maintained by [Jean Koffi](https://www.linkedin.com/in/konan-kan-jean-sylvain-koffi-39970399/)

# 📄 License
MIT © kjos/kjos-laravel-parameter-mapper

# 🤝 Call for contributions
This project is open to contributions!
Are you a developer, passionate about Laravel, or interested in multi-tenant architecture?

- Fork the project

- Create a branch (klpm/my-feature)

- Make a PR 🧪
