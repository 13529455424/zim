<?php
/**
 * File Router.php
 * @henter
 * Time: 2018-11-24 20:17
 *
 */

namespace Zim\Routing;

use Zim\App;
use Zim\Http\Exception\NotFoundException;

class Router
{
    /**
     * @var RouteCollection
     */
    protected $routes;

    /**
     * @var Matcher
     */
    protected $matcher;

    /**
     * load from config
     *
     * @return RouteCollection
     */
    public static function loadRoutes()
    {
        $routes = new RouteCollection();

        $configRoutes = App::config('routes');
        foreach ($configRoutes as list($pattern, $to)) {
            list($controller, $action) = explode('@', $to);
            $routes->add($pattern, new Route($pattern, ['_controller' => 'App\\Controller\\'.$controller.'Controller', '_action' => $action.'Action']));
        }

        return $routes;
    }

    public function __construct()
    {
        $this->routes = self::loadRoutes();
        $this->matcher = new Matcher($this->routes);
    }

    /**
     * @param $uri
     * @return array
     */
    public function match($uri)
    {
        try {
            return $this->matcher->match($uri);
        } catch (\Exception $e) {
            throw new NotFoundException($uri.' not found');
        }
    }

}