# Rest.php

Contém a classe de controle as requisições Restful, suas responsabilidades são:

Para entender os métodos parser, considere a URL como sendo uma string com o seguinte padrão [esquema]()://[domínio]():[porta]()/[caminho]()/[recurso]()?[query-string]()#[fragmento](),
onde:

- https://pt.wikipedia.org/wiki/URL
- __esquema__: ...
- __domínio__: ...
- __porta__: ...
- __caminho__: ...
- __recurso__: ...
- __query-string__: ...
- __fragmento__: ...


| Descrição da responsabilidade                                                    | Método                           |
|----------------------------------------------------------------------------------|:---------------------------------|
| Carregar arquivo de configurações.                                               | Rest::loadConfig                 |
| Higienizar nome do método de acesso HTTP.                                        | Rest::parseMethod                |
| Transformar [URL recurso]() em array contendo informações sobre o [action]().    | Rest::parseAction                |
| Transformar cria array com dados enviados via [URL query-string]().              | Rest::parseQuery                 |
| Transformar cria array com JSON string enviado no corpo da requisição HTTP.      | Rest::parseData                  |
| Instânciar objeto handler para helpers e validators.                             | Rest::handlerHelperAndValidators |
| Instânciar objeto nativo PDO.                                                    | Rest::pdo                        |
| Carregar e retornar array action específico para o método de acesso atual.       | Rest::loadAction                 |
| Validar e formatar dados informados pela URL query string.                       | Rest::checkQuery                 |
| Validar e formatar dados infromados pelo corpo da requisição.                    | Rest::checkData                  |
| Executar [callback]() da action atual e imprimir o valor retornado.                | Rest::executeCallback            |









