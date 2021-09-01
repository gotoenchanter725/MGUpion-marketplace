<?php

namespace MGLara\Library\Breadcrumb;

class BreadcrumbItem {
    
    public $url;
    public $label;

    public function __construct($label, $url = null) {
        $this->url = $url;
        $this->label = $label;
    }
    
}
