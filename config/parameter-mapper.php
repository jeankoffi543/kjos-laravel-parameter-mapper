<?php

return [

    // Mapping POST + GET
    // frontParam => backendParam
    'map' => [

        'id_ur'   => 'user_id',
        'name_lt' => 'last_name',
        'ae'      => 'age',
        //add more...
    ],


    'values-to-map' => [
        'search',
    ],

    'array-keys-to-map' => [
        'sort',
    ],

    'reject_knowns' => true,
];
