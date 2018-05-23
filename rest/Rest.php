<?php 

// http://www.restapitutorial.com/httpstatuscodes.html
// http://blog.caelum.com.br/rest-principios-e-boas-praticas/

namespace rest;

class Rest {
    const PS = DIRECTORY_SEPARATOR;
    const DIR = __DIR__ . Self::PS;

    protected $action;
    protected $method;
    protected $body;
    protected $query;
    protected $actionInfo;
    protected $validatorsLoaded;
    protected $pdo;
    protected $handler;
    protected $handlersProxy;
    protected $config;
    protected $response;

    function __construct() {
        $this->go();   
    }

    protected function go() {
        try {
            $this->loadConfig();
            $this->parse();
            $this->handlerHelperValidators();
            $this->pdo();
            $this->loadAction();
            $this->check();
            $this->executeCallback();
            $this->response();
        } catch(RestException $ex) {
            $this->responseError($ex);
        }
    }

    protected function response() {
        header("Content-Type: application/json");
        http_response_code(200);
        echo json_encode($this->response);
    }

    protected function responseError(RestException $ex) {
        header("Content-Type: application/json");
        http_response_code($ex->getHttpStatusCode());
        $response = [
            'error' => [
                'code' => $ex->getCustomCode(),
                'message' => $ex->getMessage(),
            ]
        ];

        if ($this->config['system']['error']['trace']) 
            $response['error']['trace'] = $ex->getTrace();

        echo json_encode($response);
    }

    protected function handlerHelperValidators() {
        $this->handlersProxy = [
            "action" => &$this->actionInfo,
            "query"  => &$this->query,
            "body"   => &$this->body,
            "pdo"    => &$this->pdo,
        ];
        $this->handlerHelperValidators = new HandlerHelpersValidators($this->config, $this->handlersProxy);
    }

    protected function pdo() {
        try {
            $this->pdo = new \PDO(
                $this->config['pdo']['dsn'], 
                $this->config['pdo']['username'], 
                $this->config['pdo']['password'], [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                ]
            );
        } catch(\PDOException  $ex) {
            throw new RestException(
                'Erro ao connectarse ao banco de dados: '. $ex->getMessage(),
                $ex->getCustomCode(), 
                500
            );
        }
    }

    protected function executeCallback() {
        if (!isset($this->action['callback']))
            return;

        $callback = $this->action['callback']->bindTo($this->handlerHelperValidators);
        $this->response = $callback();
    }

    protected function loadConfig() {
        $this->config = \Closure::bind(function() {
            return require Self::DIR . "config.php";
        }, new \stdClass)();
    }

    protected function loadAction() {
        if (empty($this->actionInfo['name']))
            throw new RestException("Action not defined.", 0000, 400);

        if (!file_exists($file = $this->config['folder']['action'].Self::PS.$this->actionInfo['name'].".php")) 
            if (!file_exists($file = $this->config['folder']['action'].Self::PS.$this->config['action']['default'].".php")) 
                throw new RestException(
                    "Action `{$this->actionInfo['name']}` not found.", 
                    0000, 
                    404
                );
        
        $this->action = \Closure::bind(function() use ($file) {
            return require $file;
        }, new HandlerActions($this->config, $this->handlersProxy))();
        
        if ( !isset($this->action[$this->method]) )
            throw new RestException(
                "Method `{$this->method}` not implemented in action `{$this->actionInfo['name']}`.", 
                0000, 
                501
            );
        
        $this->action = $this->action[$this->method];
    }

    protected function loadValidator(string $validatorName) {
        if (isset($this->validatorsLoaded[$validatorName]))
            return $this->validatorsLoaded[$validatorName];
        
        if (file_exists($file = $this->config['folder']['validator'].Self::PS.$validatorName.".php"))
            return $this->validatorsLoaded[$validatorName] = (require $file)->bindTo($this->handlerHelperValidators);            
        
        throw new RestException(
            "Validor `{$validatorName}` not found.", 
            0000, 
            500
        );
    }

    protected function check() {
        $this->checkQuery();
        $this->checkBody();
    }

    protected function checkParameters(Array $parameters, Array $data) {
        $errors = [];
        $formedData = [];

        foreach($parameters as $key => $validator) {
            $isNotNull = array_shift($validator);
            $validatorName = array_shift($validator);

            if ($isNotNull && !isset($data[$key])) {
                $errors[] = "`${key}` não foi definido.";
                continue;
            }
            
            if ($isNotNull && empty($data[$key])) {
                $errors[] = "`${key}` não pode ser vazio.";
                continue;
            }

            try {
                $formedData[$key] = $this->loadValidator($validatorName)($data[$key] ?? null, $key, ... $validator);
            } catch(\Exception $ex) {
                $errors[] = $ex->getMessage();
            }
        }

        if(!empty($errors))
            throw new RestException(
                implode("\r\n", $errors), 
                'A100', 
                400
            );

        return $formedData;
    }

    protected function checkQuery() {
        if (!isset($this->action['query']))
            return;

        $this->query = $this->checkParameters($this->action['query'], $this->query);
    }

    protected function checkBody() {
        if ($this->method == 'get')
        return;
        
        if (!isset($this->action['body']))
        return;
        
        $this->body = $this->checkParameters($this->action['body'], $this->body);
    }
    
    protected function parse() {
        $this->parseMethod();
        $this->parseAction();
        $this->parseQuery();
        $this->parseBody();
    }

    protected function parseAction() {
        $path = parse_url($_SERVER['REQUEST_URI'])['path'];
        $pices = explode("/", $path);
        array_shift($pices);
        $this->actionInfo = [
            "name" => array_shift($pices),
            "path" => $pices
        ];
    }

    protected function parseMethod() {
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
    }

    protected function parseQuery() {
        parse_str($_SERVER['QUERY_STRING'] ?? '', $this->query);
    }

    protected function parseBody() {
        $this->body = json_decode( file_get_contents('php://input'), true );
    }
};