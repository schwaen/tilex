<?php
namespace Tilex;

use Silex\Route as BaseRoute;

class Route extends BaseRoute
{
    /**
     * @var Tilex\Application;
     */
    protected $app = null;

    public function setContainer(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Sets the HTTP methods (e.g. 'POST') this route is restricted to.
     * So an empty array means that any method is allowed.
     *
     * This method implements a fluent interface.
     *
     * @param string|array $methods The method or an array of methods
     *
     * @return Route The current Route instance
     */
    public function setMethods($methods)
    {
        if (isset($this->app['cors'])) {
            $methods = array_map('strtoupper', (array) $methods);
            $methods_allowed = false;
            foreach ($methods as $method) {
                if ($this->app['cors']->checkMethod($method)) {
                    $methods_allowed = true;
                    break;
                }
            }
            if (!in_array('OPTIONS', $methods) && $methods_allowed) {
                $methods[] = 'OPTIONS';
            }
        }
        parent::setMethods($methods);
        return $this;
    }
}
