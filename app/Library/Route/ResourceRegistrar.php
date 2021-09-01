<?php

namespace MGLara\Library\Route;

use Illuminate\Routing\ResourceRegistrar as OriginalRegistrar;

class ResourceRegistrar extends OriginalRegistrar
{
    // add data to the array
    /**
     * The default actions for a resourceful controller.
     *
     * @var array
     */
    //protected $resourceDefaults = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'data'];
    protected $resourceDefaults = ['index', 'create', 'store', 'datatable', 'show', 'edit', 'update', 'destroy', 'inactivate', 'activate'];

    protected function addResourceInactivate($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/{'.$base.'}/inactivate';

        $action = $this->getResourceAction($name, $controller, 'inactivate', $options);

        return $this->router->match(['PUT', 'PATCH'], $uri, $action);
    }
    
    protected function addResourceActivate($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/{'.$base.'}/activate';

        $action = $this->getResourceAction($name, $controller, 'activate', $options);

        return $this->router->match(['PUT', 'PATCH'], $uri, $action);
    }
    
    protected function addResourceDatatable($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/datatable';

        $action = $this->getResourceAction($name, $controller, 'datatable', $options);

        return $this->router->match(['GET'], $uri, $action);
    }
}