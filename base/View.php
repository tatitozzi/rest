<?php

namespace base;

class View extends \base\Main {
    protected $html = 'not found';
    protected $find;
    
    function __construct($filenameOrDOMElement) {
        Parent::__construct();
        $this->loadFile($filenameOrDOMElement);
    }

    function __set($key, $val) {
        if (is_array($val))
            return $this->arrayLoop($key, $val);

        return $this->variable($key, $val);
    }

    function __toString() {
        return $this->html->saveHTML();
    }

    public function getDOM() {
        return $this->html;
    }

    protected function variable($key, $val) {
        $key = '{{' . $key . '}}';
        $xpath = [
            "//@*[contains(.,'$key')]",
            "//text()[contains(.,'$key')]"
        ];
        $els = $this->find->query(implode("|", $xpath));
        foreach($els as $el) {
            $el->nodeValue = str_replace($key, $val, $el->nodeValue);
        }
    }

    protected function arrayLoop($key, $arr) {
        $res = $this->find->query('//*[@view-repeat="'.$key.'"]');
        
        if($res->length <= 0) 
            return;
        
        $clonable = $res[0];
        $container = $clonable->parentNode;
        $spaceOrig = $clonable->previousSibling;

        if ($spaceOrig instanceof \DOMText) {
            $spaceArr = explode("\n", $spaceOrig->textContent);
            $spaceStr = array_pop($spaceArr);
            if (trim($spaceStr) == "") {
                $space = new \DOMText("\n".$spaceStr);
                $clonable->parentNode->removeChild($spaceOrig);
            }
        } else {
            $space = new \DOMText('');
        }

        foreach($arr as $val) {
            $clone = $clonable->cloneNode(true);
            $snippetView = new View($clone);
            $delme = $clone->getAttribute('view-repeat');
            $as = $clone->getAttribute('view-repeat-as');
            
            if (is_array($val))
                foreach($val as $innerKey => $innerVal) 
                    $snippetView->{$as.'.'.$innerKey} = $innerVal;
            else
                $snippetView->{$as} = $val;
            
            $nodes = $snippetView->getDOM()->childNodes;
            $clone = $this->html->importNode($nodes[0], true);
            $space = $space->cloneNode(true);
            $clone->removeAttribute('view-repeat');
            $clone->removeAttribute('view-repeat-as');
            $container->insertBefore($space, $clonable);
            $container->insertBefore($clone, $clonable);
        }

        $clonable->parentNode->removeChild($clonable);
    }

    protected function loadFile($filenameOrDOMElement) {
        if ($filenameOrDOMElement instanceof \DOMElement) {
            $newdoc = new \DOMDocument;
            $newdoc->appendChild($newdoc->importNode($filenameOrDOMElement, true));
            $this->html = $newdoc;
            $this->find = new \DOMXPath($this->html);
            return $this->html;
        }

        if (file_exists($filenameOrDOMElement)){
            $this->html = new \DOMDocument();
            $this->html->loadHTMLFile($filenameOrDOMElement);    
            $this->find = new \DOMXPath($this->html);
            return $this->html;
        }

        return "view not found";
    }

}