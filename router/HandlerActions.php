<?php

namespace router;

class HandlerActions extends HandlerForHelpersAndValidators {
    protected $automaatorsLoaded;

    function automator($automatorName) {
        if (isset($this->automaatorsLoaded[$automatorName]))
            return $this->automaatorsLoaded[$automatorName];
    
        if (file_exists($file = $this->config['folder']['automator'].Self::PS.$automatorName.".php")) 
            return $this->automaatorsLoaded[$automatorName] = require $file;            
        
        throw new \Exception("Automator `{$automatorName}` not found.", 500);
    }
}