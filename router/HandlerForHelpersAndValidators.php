<?php

namespace router;

class HandlerForHelpersAndValidators extends Handler {
    protected $helpersLoaded;

    function helper($helperName) {
        if (isset($this->helpersLoaded[$helperName]))
            return $this->helpersLoaded[$helperName];
    
        if (file_exists($file = $this->config['folder']['helper'].Self::PS.$helperName.".php")) 
            return $this->helpersLoaded[$helperName] = require $file;            
        
        throw new \Exception("Helper `{$helperName}` not found.", 500);
    }
}