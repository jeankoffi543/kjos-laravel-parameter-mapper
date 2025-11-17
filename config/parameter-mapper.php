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


    // Valeurs spécifiques à mapper (ex: search=id_us => search=user_id)
    'values-to-map' => [
        'search',
    ],

    // Mapper les clés dans des tableaux (ex: sort[id_us] => sort[user_id])
    'array-keys-to-map' => [
        'sort',
    ],

    /* 
        The reject_known parameter allows you to automatically reject any request that contains a parameter corresponding to an internal backend key.
        
        Purpose:
        To prevent users from directly sending backend (internal) field names that are normally hidden behind the front-end map.
        For example, if you have this map:
    */
    'reject-knowns' => true,

    /** The map_response parameter allows you to automatically map the response data. */
    'map-response' => true,
];
