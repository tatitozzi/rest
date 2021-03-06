<?php

function salt(string $username, string $password) {
    return sha1(md5($password).'salt salt salt');
};

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
                    // 'ddd' => 'escopo de teste hardcoded ddd',
                    // 'eee' => 'escopo de teste hardcoded eee',
                    // 'fff' => 'escopo de teste hardcoded fff',
                    // 'ggg' => 'escopo de teste hardcoded ggg',
                    // 'hhh' => 'escopo de teste hardcoded hhh',
                    // 'iii' => 'escopo de teste hardcoded iii',
                    // 'jjj' => 'escopo de teste hardcoded jjj',
                    // 'kkk' => 'escopo de teste hardcoded kkk',
                    // 'lll' => 'escopo de teste hardcoded lll',
                    // 'mmm' => 'escopo de teste hardcoded mmm',
                    // 'nnn' => 'escopo de teste hardcoded nnn',
                    // 'ooo' => 'escopo de teste hardcoded ooo',
                    // 'ppp' => 'escopo de teste hardcoded ppp',
                    // 'qqq' => 'escopo de teste hardcoded qqq',
                    // 'rrr' => 'escopo de teste hardcoded rrr',
                    // 'sss' => 'escopo de teste hardcoded sss',
                    // 'ttt' => 'escopo de teste hardcoded ttt',
                ];
            }
        ],

        'check' => [
            // CHECK IF USER EXISTS
            'user' => function(string $user) {
                $stmt = $this->pdo->prepare('SELECT id, user FROM user WHERE user=? LIMIT 1');
                $stmt->execute([$user]);
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            }
        ],

        // GENERATE CODE HASH
        'register' => function(string $user, string $pass, $queryArr) {
            // VERIFICAR USUÁRIO

            $userData = $this->pdo->prepare('SELECT * FROM user WHERE `user`=? LIMIT 1');
            $userData->execute([$user]);
            $userData = $userData->fetch(\PDO::FETCH_ASSOC);
            
            if (!$userData['id'])
                die ("tratar erro rest caso o usuario não seja encontrado");
            
            if ($userData['password'] !== salt($user, $pass))
                die ("tratar erro rest caso a senha não seja correta");
            
            // VERIFICAR INFORMAÇÕES DA APLICAÇÃO
            
            $clientData = $this->pdo->prepare('SELECT * FROM client WHERE `id`=? LIMIT 1');
            $clientData->execute([$queryArr['client_id']]);
            $clientData = $clientData->fetch(\PDO::FETCH_ASSOC);

            if ($queryArr['redirect_uri'] !== $clientData['redirect_uri'])
                die ("tratar erro rest caso o redirect_uri seja diferente do setado na tabela client");
            
            // CRIAR O CODE
            
            $code = '';
            while (strlen($code) < 255)
                $code .= md5(uniqid(rand(), true));
            $code = substr($code, 0, 255);

            // REGISTRAR TOKEN/CODE

            $hashData = $this->pdo->prepare('SELECT * FROM hash WHERE `client_id`=? AND `user_id`=? LIMIT 1');
            $hashData->execute([$queryArr['client_id'], $userData['id']]);
            $hashData = $hashData->fetch(\PDO::FETCH_ASSOC);

            $save = (!$hashData['id'])
                ? $this->pdo->prepare("INSERT INTO hash(code, token, client_id, client_secret, user_id, redirect_uri, scope) VALUES(:code, :token, :client_id, :client_secret, :user_id, :redirect_uri, :scope)")
                : $this->pdo->prepare("UPDATE hash SET code=:code, token=:token, client_id=:client_id, client_secret=:client_secret, user_id=:user_id,redirect_uri=:redirect_uri,scope=:scope WHERE id={$hashData['id']}");

            $save->execute([
                'code' => $code, 
                'token' => null, 
                'client_id' => $clientData['id'], 
                'client_secret' => $clientData['secret'], 
                'user_id' => $userData['id'],
                'redirect_uri' => $queryArr['redirect_uri'],
                'scope' => $queryArr['scope']
            ]);

            return [
                "href" => "{$queryArr['redirect_uri']}?code=$code"
            ];
        },
    ],
        
    // TOKEN // GENERATE TOKEN HASH
    'token' => [
        
        'authorization_code' => function($queryArr) { 
            // https://www.oauth.com/oauth2-servers/access-tokens/refreshing-access-tokens/
            
            // client_id
            // client_secret
            // redirect_uri
            // grant_type => authorization_code
            // code
            
            $hashData = $this->pdo->prepare('SELECT * FROM hash WHERE `code`=? LIMIT 1');
            $hashData->execute([$queryArr['code']]);
            $hashData = $hashData->fetch(\PDO::FETCH_ASSOC);

            if (!$hashData['code'])
                die('tratar erro rest caso code não seja encontrado');

            if ($hashData['redirect_uri'] !== $queryArr['redirect_uri'])
                die('tratar erro rest caso redirect_uri não seja compativel com os valores salvos no banco');

            if ($hashData['client_id'] !== $queryArr['client_id'])
                die('tratar erro rest caso client_id não seja compativel com os valores salvos no banco');

            if ($hashData['client_secret'] !== $queryArr['client_secret'])
                die('tratar erro rest caso client_secret não seja compativel com os valores salvos no banco');
                
            print_r($hashData);

            // {
            //     "access_token": "EAAEt8l7jMT4BAKYG66knpHo3yC31H2vbo9FucS1DvMcUtzUOZCsbrbCuXnGRHjc8wZCz8bbSDEPemYxKpWZAVwsWwrCtZABea7TE29N5oaKcrsO77xOGhwYrLeT2w40IcARpZACHB9KQXelm72TxmgYHwujzIgBMmUoKyIKZCGQb9SbRC92qvE",
            //     "token_type": "bearer",
            //     "expires_in": 5183962
            // }
            return "faz o toke aqui";
        }
    ]
];