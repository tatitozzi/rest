<?php

namespace oauth;

class OAuth2 extends \base\Main {
    const DIR = __DIR__ . DIRECTORY_SEPARATOR;

    private $queryArr;

    function __construct() {
        parent::__construct();
        parse_str($_SERVER['QUERY_STRING'] ?? '', $this->queryArr);
        $this->parseActionInfo();
        $this->prepareAndloadConfig();
        
        if ($this->actionInfo['name'] == 'authorize'
            && isset($this->actionInfo['path'][0])
            && $this->actionInfo['path'][0] == 'perfil'
            && isset($this->actionInfo['path'][1])
        ) exit ($this->checkPerfil());

        if ($this->actionInfo['name'] == 'authorize'
            && isset($this->actionInfo['path'][0])
            && $this->actionInfo['path'][0] == 'register'
            && isset($this->actionInfo['path'][1])
        ) exit ($this->register());
        
        if ($this->actionInfo['name'] == 'authorize')
            exit($this->showAuthForm());
    }

    protected function checkParameters() {
        if (!isset($this->queryArr['client_id']))
            die ("tratar erro rest caso o client_id não seja informado");

        if (!isset($this->queryArr['redirect_uri']))
            die ("tratar erro rest caso o redirect_uri não seja informado");

        if (!isset($this->queryArr['scope']))
            die ("tratar erro rest caso o scope não seja informado");
    }
    
    protected function prepareAndloadConfig() {
        $context = new \base\Context();
        parent::loadConfig($context);
        $context->pdo = $this->pdo();
    }
    
    protected function checkPerfil() {
        $username = $this->actionInfo['path'][1] ?? "me";
        $function = $this->config['auth']['code']['check']['user'];
        $result = $function($username);
        return json_encode($result);
    }

    protected function register() {
        $username = $this->actionInfo['path'][1];
        $password = file_get_contents('php://input');
        $function = $this->config['auth']['code']['register'];
        $result = $function($username, $password, $this->queryArr);
        return json_encode($result);
    }

    protected function showAuthForm() {
        $this->checkParameters();
        $view = new \base\View("{$this->config['folder']['view']}/auth.html");
        $view->scopes = $this->config['auth']['code']['translate']['scope-code']($_GET['scope'] ?? 'null');
        return $view;
    }
}