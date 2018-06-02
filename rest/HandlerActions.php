<?php

namespace Rest;

class HandlerActions extends HandlerHelpersValidators {
    protected $automaatorsLoaded;

    function automator($automatorName) {
        if (isset($this->automaatorsLoaded[$automatorName]))
            return $this->automaatorsLoaded[$automatorName];
    
        if (file_exists($file = $this->config['folder']['automator'].Self::PS.$automatorName.".php")) 
            return $this->automaatorsLoaded[$automatorName] = require $file;            
        
        throw new \Rest\RestException(
            "Automator `{$automatorName}` not found.", 
            0000, 
            500
        );
    }
}