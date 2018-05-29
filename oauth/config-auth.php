<?php

return [

    // CODE 
    'code' => [
        // GET INFO TO SHOW IN VIEW
        'translate' => [
            // RETURN AN ARRAY WITH SCOPE CODES AND DESCRIPTION
            'scope-code' => function(string $scopes) {
                $scopes = explode(' ', $scopes);
                return [
                    'aaa' => 'escopo de teste hardcoded aaa',
                    'bbb' => 'escopo de teste hardcoded bbb',
                    'ccc' => 'escopo de teste hardcoded ccc',
                    'ddd' => 'escopo de teste hardcoded ddd',
                ];
            }
        ],

        //
        'crypto' => [
            // USED TO CRYPTO PASSWORD
            'salt' => function() {}
        ],

        'check' => [
            // CHECK IF USER EXISTS
            'user' => function(string $user) {},

            // CHECK IF USER PASS IS CORRECT
            'pass' => function(string $pass) {},
        ],

        // EXECUTE AFTER CHECK TO STORE CODE
        'register' => function() {}
    ],
        
    // TOKEN
    'token' => [    
        // EXECUTE AFTER CHECK TO STORE CODE
        'register' => function() {}
    ]
];