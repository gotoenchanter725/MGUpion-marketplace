<?php

namespace MGLara\Library\Breadcrumb;


class Breadcrumb
{
    private $page;
    private $header;
    private $breadcrumbs;

    private $page_prefix;

    public function __construct($header = null, $page = null) {
        $this->header = $header; // PEGAR NOME ROTA
        $this->page = $page;
        $this->page_prefix = 'MGLara - ';
        $this->addItem('MGLara', url('/'));
    }


    public function __get($property) {
        switch ($property) {
            case 'page':
                if (empty($this->page)) {
                    return $this->page_prefix . $this->header;
                }
            default:
                return $this->$property;
        }

    }

    public function __set($property, $value) {
        $this->$property = $value;
    }

    public function addItem($label, $url = null) {
        $this->breadcrumbs[] = new BreadcrumbItem($label, $url);

    }

}
