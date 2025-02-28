<?php

namespace Blazer\Core\View;

abstract class Component {
    protected $data = [];
    
    abstract public function render();
    
    public function __toString() {
        return $this->render();
    }
} 