<?php

namespace OAuth;

class OAuth2 extends \Base\Main {
    const DIR = __DIR__ . DIRECTORY_SEPARATOR;

    function __construct() {
        parent::__construct();
        $this->parseActionInfo();
        $this->prepareAndloadConfig();
        
        if (
            $this->actionInfo['name'] == 'authorize'
            && isset($this->actionInfo['path'][0])
            && isset($this->actionInfo['path'][1])
            && $this->actionInfo['path'][0] == 'perfil'
        ) exit($this->checkPerfil());

        if (
            $this->actionInfo['name'] == 'authorize'
            && isset($this->actionInfo['path'][0])
            && isset($this->actionInfo['path'][1])
            && $this->actionInfo['path'][0] == 'register'
        ) exit($this->register());
        
        if (
            $this->actionInfo['name'] == 'authorize'
        ) exit($this->showAuthForm());
        
        if (
            $this->actionInfo['name'] == 'access_token'
        ) exit($this->token());
    }

    protected function checkFormParameters() {
        parse_str($_SERVER['QUERY_STRING'] ?? '', $queryArr);

        if (!isset($queryArr['client_id']))
            die ("tratar erro rest caso o client_id não seja informado");

        if (!isset($queryArr['redirect_uri']))
            die ("tratar erro rest caso o redirect_uri não seja informado");

        if (!isset($queryArr['scope']))
            die ("tratar erro rest caso o scope não seja informado");

        return $queryArr;
    }

    protected function checkTokenParameters() {
        parse_str($_SERVER['QUERY_STRING'] ?? '', $queryArr);
        
        if (isset($queryArr['grant_type']) && $queryArr['grant_type'] == 'authorization_code' )
            return $this->checkTokenParametersForAuthorizationCode($queryArr);
        
        echo __METHOD__ . " - checar se tem os parametros para code \r\n";
        
        return $queryArr;
    }

    protected function checkTokenParametersForAuthorizationCode($queryArr) {
        if (!isset($queryArr['code']))
            die ("tratar erro rest caso o code não seja informado");
        
        if (!isset($queryArr['client_id']))
            die ("tratar erro rest caso o client_id não seja informado");
        
        if (!isset($queryArr['client_secret']))
            die ("tratar erro rest caso o client_secret não seja informado");
        
        if (!isset($queryArr['redirect_uri']))
            die ("tratar erro rest caso o redirect_uri não seja informado");
        
        return $queryArr;
    }
    
    protected function prepareAndloadConfig() {
        $context = new \Base\Context();
        parent::loadConfig($context);
        $context->pdo = $this->pdo();
    }
    
    protected function checkPerfil() {
        $username = $this->actionInfo['path'][1] ?? "me";
        $function = $this->config['auth']['code']['check']['user'];
        $result = $function($username);
        return json_encode($result);
    }
    

    protected function token() {
        $queryArr = $this->checkTokenParameters();
        $function = $function = $this->config['auth']['token'][ $queryArr['grant_type'] ] ?? null;
        if (!$function) 
            die ('tratar erro rest caso o grant_type não for implementado no arquivo de configuração config-auth.php');
        $result = $function($queryArr);
        return json_encode($result);
    }

    protected function register() {
        $username = $this->actionInfo['path'][1];
        $password = file_get_contents('php://input');
        $function = $this->config['auth']['code']['register'];
        $result = $function($username, $password, $this->checkFormParameters());
        return json_encode($result);
    }

    protected function showAuthForm() {
        $this->checkFormParameters();
        $view = new \Base\View("{$this->config['folder']['view']}/auth.html");
        $view->scopes = $this->config['auth']['code']['translate']['scope-code']($_GET['scope'] ?? 'null');
        return $view;
    }
}