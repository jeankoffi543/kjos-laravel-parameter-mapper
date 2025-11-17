![Packagist Version](https://img.shields.io/packagist/v/kjos/kjos-laravel-parameter-mapper)
![PHP](https://img.shields.io/badge/PHP-%5E8.0-blue)
![License](https://img.shields.io/github/license/jeankoffi543/kjos-laravel-parameter-mapper)


## 🌐 Multilanguage | Multilingual

- 🇫🇷 [French Version](README.fr.md)
- 🇬🇧 [English Version](README.md)

## 🇬🇧 English

# Kjos Laravel Parameter Mapper

A Laravel package that allows you to dynamically map GET and POST parameters sent by the client to your application's backend keys, and vice versa. Useful for hiding the actual field names from the user or for standardizing your APIs.

---

## Installation

For Laravel 12 or higher:

```bash
composer require kjos/kjos-laravel-parameter-mapper
```

## Publish the configuration

```bash
php artisan vendor:publish --tag=parametermap
```

## Configuration

```php
return [

'map' => [
// frontParam => backendParam
'id_ur' => 'user_id',
'name_lt' => 'last_name',

'ae' => 'age',

// Specific values ​​to map (e.g., search=id_us => search=user_id)

'values-to-map' => [
'search',

],

// Map the keys into arrays (e.g., sort[id_us] => sort[user_id])

'array-keys-to-map' => [ 
'spell', 
], 
],
];
```

##Middleware

```php
use Kjos\ParameterMapper\Middleware\MapRequestParameters;
Route::middleware([MapRequestParameters::class])
```

## Class `ParameterMapper`

```php
use Kjos\ParameterMapper\Support\ParameterMapper;

// Map front -> back
$mapped = ParameterMapper::apply([ 
'id_ur' => 1, 
'name_lt' => 'Koffi', 
'ae' => 10, 
'sort' => ['id_ur' => 'asc'], 
'search' => 'id_ur',
]);

// Map back -> front
$frontend = ParameterMapper::reverse([ 
'user_id' => 1, 
'last_name' => 'Koffi', 
'age' => 10, 
'sort' => ['user_id' => 'asc'], 
'search' => 'user_id',
]);
```

## Use in Factories

```php 
$datas = ParameterMapper::reverse([ 
'user_id' => 1, 
'last_name' => 'Koffi', 
'age' => 10, 
]);
```
Becomes:
```php
[
'id_ur' => 1,

'name_lt' => 'Koffi',

'ae' => 10,

]
```

## API Example

Request:

```
GET /api/admins?search=id_us&sort[id_us]=asc
```

Automatically transformed into:

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
│ └── parameter-mapper.php 
├── grumphp.yml 
├── phpunit.xml 
├── pint.json 
├── schema.png 
├── src 
│ ├── Middleware 
│ ├── ParameterMapperServiceProvider.php 
│ └── Support 
├── tests 
│ ├── ExampleTest.php 
│ ├── Feature 
│ ├── Pest.php 
│ ├── TestCase.php 
│ └── Unit
```


# 👤 Author
Maintained by [Jean Koffi](https://www.linkedin.com/in/konan-kan-jean-sylvain-koffi-39970399/)

# 📄 License
MIT © kjos/kjos-laravel-parameter-mapper

# 🤝 Call for contributions
This project is open to contributions!
Are you a developer, passionate about Laravel, or interested in multi-tenant architecture?

-Fork the project

- Create a branch (klpm/my-feature)

- Make a PR 🧪