<?php 

// http://www.restapitutorial.com/httpstatuscodes.html

namespace router;

class Router {
    const PS = DIRECTORY_SEPARATOR;
    const DIR = __DIR__ . Self::PS;

    protected $action;
    protected $method;
    protected $data;
    protected $query;
    protected $actionInfo;
    protected $validatorsLoaded;
    protected $pdo;
    protected $handler;
    protected $handlersProxy;
    protected $config;

    function __construct() {
        $this->go();   
    }

    protected function go() {
        try {
            $this->loadConfig();
            $this->parseMethod();
            $this->parseAction();
            $this->parseQuery();
            $this->parseData();
            $this->handlerHelperAndValidators();
            $this->pdo();
            $this->loadAction();
            $this->checkQuery();
            $this->checkData();
            $this->executeCallback();
        } catch(\Exception $ex) {
            http_response_code($ex->getCode());
            echo json_encode([
                'error' => $ex->getCode(),
                'message' => $ex->getMessage(),
                // 'stackTrace' => $ex->getTraceAsString()
            ]);
        }
    }

    protected function handlerHelperAndValidators() {
        $this->handlersProxy = [
            "action" => &$this->actionInfo,
            "query"  => &$this->query,
            "data"   => &$this->data,
            "pdo"    => &$this->pdo,
        ];
        $this->handlerHelperAndValidators = new HandlerForHelpersAndValidators($this->config, $this->handlersProxy);
    }

    protected function pdo() {
        try {
            $this->pdo = new \PDO(
                $this->config['pdo']['dsn'], 
                $this->config['pdo']['username'], 
                $this->config['pdo']['password'], 
                [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                ]
            );
        } catch(\PDOException  $ex) {
            throw new \Exception('Erro ao connectarse ao banco de dados: '. $ex->getMessage(), 500   );
        }
    }

    protected function executeCallback() {
        if (!isset($this->action['execute']))
            return;

        $callback = $this->action['execute']->bindTo($this->handlerHelperAndValidators);
        echo json_encode($callback());
    }

    protected function loadConfig() {
        $this->config = \Closure::bind(function() {
            return require Self::DIR . "config.php";
        }, new \stdClass)();
    }

    protected function loadAction() {
        if (empty($this->actionInfo['name']))
            throw new \Exception("Action not defined.", 400);

        if (!file_exists($file = $this->config['folder']['action'].Self::PS.$this->actionInfo['name'].".php")) 
            if (!file_exists($file = $this->config['folder']['action'].Self::PS.$this->config['automator']['default'].".php")) 
                throw new \Exception("Action `{$this->actionInfo['name']}` not found.", 404);
        
        $this->action = \Closure::bind(function() use ($file) {
            return require $file;
        }, new HandlerActions($this->config, $this->handlersProxy))();
        
        if ( !isset($this->action[$this->method]) )
            throw new \Exception("Method `{$this->method}` not implemented in action `{$this->actionInfo['name']}`.", 501);
        
        $this->action = $this->action[$this->method];
    }

    protected function loadValidator(string $validatorName) {
        if (isset($this->validatorsLoaded[$validatorName]))
            return $this->validatorsLoaded[$validatorName];
        
        if (file_exists($file = $this->config['folder']['validator'].Self::PS.$validatorName.".php"))
            return $this->validatorsLoaded[$validatorName] = (require $file)->bindTo($this->handlerHelperAndValidators);            
        
        throw new \Exception("Validor `{$validatorName}` not found.", 500);
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
            throw new \Exception(implode("\r\n", $errors), 400);

        return $formedData;
    }

    protected function checkQuery() {
        if (!isset($this->action['query']))
            return;

        $this->query = $this->checkParameters($this->action['query'], $this->query);
    }

    protected function checkData() {
        if ($this->method == 'get')
            return;

        if (!isset($this->action['validate']))
            return;
        
        $this->data = $this->checkParameters($this->action['validate'], $this->data);
    }
    
    protected function parseAction() {
        $pices = explode("/", $_SERVER['PHP_SELF']);
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

    protected function parseData() {
        $this->data = json_decode( file_get_contents('php://input'), true );
    }
};