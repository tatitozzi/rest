<?php

namespace base;

class Main {
    const DIR = 'adicione const DIR na class que estende base\main';
    
    protected $child;
    protected $config;
    protected $actionInfo;
    protected $pdo;

    protected function __construct() {
        $this->child = new \ReflectionClass($this);
    }
    
    protected function loadConfig($_this, $_self = null) {
        if ($this->config)
            return $this->config;

        $DIR = dirname($this->child->getFileName());
        $config = $this->config = \Closure::bind(
            function() use ($DIR) {
                $DIR .= DIRECTORY_SEPARATOR; 
                return require $DIR . "config.php";
            }, 
            $_this ?? new \stdClass, 
            $_self ?? $_this ?? new \stdClass
        )();

        return $this->config = $config;
    }

    protected function parseActionInfo() {
        if ($this->actionInfo)
            return $this->actionInfo;

        $path = parse_url($_SERVER['PATH_INFO'])['path'];
        $pices = explode("/", $path);
        array_shift($pices);
        return $this->actionInfo = [
            "name" => array_shift($pices),
            "path" => $pices
        ];
    }

    protected function pdo() {
        if ($this->pdo)
            return $this->pdo;

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
            throw new \rest\RestException(
                'Erro ao connectarse ao banco de dados: '. $ex->getMessage(),
                $ex->getCode(), 
                500
            );
        }

        return $this->pdo;
    }
}