Kjos Laravel Parameter MapperUn package Laravel qui permet de mapper dynamiquement les paramètres GET et POST envoyés par le client vers les clés backend de votre application, et inversement. C'est un outil pratique pour cacher les vrais noms de champs à l’utilisateur (sécurité par obscurcissement) ou pour standardiser l'entrée et la sortie de vos API.🚀 InstallationPour Laravel 12 ou supérieur :composer require kjos/kjos-laravel-parameter-mapper
⚙️ Publication du fichier de configurationPubliez le fichier de configuration parametermap.php dans votre répertoire config :php artisan vendor:publish --tag=parametermap
📝 ConfigurationLe fichier config/parametermap.php vous permet de définir les mappings :return [
    'map' => [
        // frontParam => backendParam
        'id_ur'   => 'user_id',
        'name_lt' => 'last_name',
        'ae'      => 'age',

        // Valeurs spécifiques à mapper (ex: search=id_ur sera transformé en search=user_id)
        // Utile pour les filtres comme 'search', 'filter', etc.
        'values-to-map' => [
            'search',
        ],

        // Mapper les clés DANS des tableaux (ex: sort[id_ur] sera transformé en sort[user_id])
        // Utile pour les structures comme 'sort', 'filter', 'include', etc.
        'array-keys-to-map' => [
            'sort',
        ],
    ],
];
🛡️ MiddlewareLe middleware MapRequestParameters doit être appliqué à vos routes pour intercepter et mapper automatiquement les paramètres de la requête entrante.use Kjos\ParameterMapper\Middleware\MapRequestParameters;
use App\Http\Middleware\AuthenticateAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware([MapRequestParameters::class, AuthenticateAdmin::class])
    ->group(function () {
        Route::post('/api/admins', [AdminController::class, 'store']);
    });
🧰 Classe ParameterMapper (Support)Le package fournit également la façade ParameterMapper qui peut être utilisée manuellement partout dans votre application pour mapper des tableaux de données.use Kjos\ParameterMapper\Support\ParameterMapper;

// 1. Mapper front -> back (Applique le mapping pour les requêtes entrantes)
$mapped = ParameterMapper::apply([
    'id_ur' => 1,          // Devient 'user_id' => 1
    'name_lt' => 'Koffi',  // Devient 'last_name' => 'Koffi'
    'ae' => 10,            // Devient 'age' => 10
    'sort' => ['id_ur' => 'asc'], // Devient 'sort' => ['user_id' => 'asc']
    'search' => 'id_ur',   // Devient 'search' => 'user_id'
]);

// $mapped contient :
// [
//    'user_id' => 1, 
//    'last_name' => 'Koffi', 
//    'age' => 10,
//    'sort' => ['user_id' => 'asc'], 
//    'search' => 'user_id',
// ]


// 2. Mapper back -> front (Inverse le mapping pour les réponses sortantes ou les données de test)
$frontend = ParameterMapper::reverse([
    'user_id' => 1,
    'last_name' => 'Koffi',
    'age' => 10,
    'sort' => ['user_id' => 'asc'],
    'search' => 'user_id',
]);

// $frontend contient :
// [
//    'id_ur' => 1, 
//    'name_lt' => 'Koffi', 
//    'ae' => 10,
//    'sort' => ['id_ur' => 'asc'], 
//    'search' => 'id_ur',
// ]
🧪 Utilisation dans les Factories et TestsLa méthode reverse est particulièrement utile dans les tests d'intégration ou les factories pour générer des données de test avec les clés frontend attendues par la route, sans polluer vos factories avec ces clés.it('should store admin', function ($guestAdmin) {
    // Convertit la factory/le modèle en tableau
    $guestAdmin = $guestAdmin->toArray(); 
    
    // Mappe les clés backend (user_id, last_name, etc.) vers les clés frontend (id_ur, name_lt, etc.)
    $guestAdmin = ParameterMapper::reverse($guestAdmin); 

    $guestAdmin['image'] = generateUploadedFile();
    $guestAdmin['password'] = 'password';

    $response = post('/api/admins/admins', $guestAdmin)
        ->assertCreated();

    Storage::disk('public')->assertExists('admins/' . basename($response['data']['image']));
})->with('guest admin');
💡 Exemple de Flux APIRequête Client (Frontend) :GET /api/admins?search=id_ur&sort[id_ur]=asc
Transformation par le Middleware (Backend) :[
    'search' => 'user_id', 
    'sort' => [
        'user_id' => 'asc'
    ]
]
Le reste de votre application utilise alors uniquement les noms de champs internes (user_id).