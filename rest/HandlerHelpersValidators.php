<?php

namespace rest;

class HandlerHelpersValidators extends Handler {
    protected $helpersLoaded;

    function helper($helperName) {
        if (isset($this->helpersLoaded[$helperName]))
            return $this->helpersLoaded[$helperName];
    
        if (file_exists($file = $this->config['folder']['helper'].Self::PS.$helperName.".php")) 
            return $this->helpersLoaded[$helperName] = require $file;            
        
        throw new \rest\RestException(
            "Helper `{$helperName}` not found.", 
            0000, 
            500
        );
    }
}