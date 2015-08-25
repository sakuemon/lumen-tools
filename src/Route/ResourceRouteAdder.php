<?php
namespace Sakuemon\LumenTools;

use Laravel\Lumen\Application;

/**
 * Class RestfulControllerRouter
 *
 * @package Infrastructure\UserInterfaces
 */
class ResourceRouteAdder
{
    protected $app;

    protected $routeSettings;

    /**
     * method to path binding values.
     * @var array
     */
    protected $methodPathBind = [
        'index' => '',
        'create' => '/new',
        'show' => '/{id}',
        'edit' => '/{id}/edit',
        'store' => '',
        'update' => '',
        'delete' => '',
    ];

    /**
     * constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->routeSettings = [];
    }

    /**
     * @param $pathRoot
     * @param $controller
     * @param $routeNamePrefix
     * @param array $methods
     */
    public function add($pathRoot, $controller, $routeNamePrefix, array $methods = [])
    {
        if (substr($pathRoot, 0, 1) != '/') {
            throw new \InvalidArgumentException('argument $pathRoot must starts slash.');
        }
        if (substr($pathRoot, -1) == '/') {
            throw new \InvalidArgumentException('argument $pathRoot must NOT ends slash.');
        }
        $routes = new \stdClass();
        $routes->pathRoot = $pathRoot;
        $routes->controller = $controller;
        $routes->routeNamePrefix = $routeNamePrefix;

        if (empty($methods)) {
            $routes->methods = array_keys($this->methodPathBind);
        } else {
            $routes->methods = $methods;
        }
        $this->routeSettings[] = $routes;

    }

    /**
     * register all route.
     */
    public function register()
    {
        foreach ($this->routeSettings as $setting) {
            foreach ($setting->methods as $method) {
                $this->$method($setting->pathRoot, $setting->controller, $setting->routeNamePrefix);
            }
        }
    }

    public function __invoke($x)
    {
        $this->register();
    }

    protected function index($pathRoot, $controller, $routeNamePrefix)
    {
        $this->get('index', $pathRoot, $controller, $routeNamePrefix);
    }

    protected function create($pathRoot, $controller, $routeNamePrefix)
    {
        $this->get('create', $pathRoot, $controller, $routeNamePrefix);
    }

    protected function show($pathRoot, $controller, $routeNamePrefix)
    {
        $this->get('show', $pathRoot, $controller, $routeNamePrefix);
    }

    protected function edit($pathRoot, $controller, $routeNamePrefix)
    {
        $this->get('edit', $pathRoot, $controller, $routeNamePrefix);
    }

    protected function store($pathRoot, $controller, $routeNamePrefix)
    {
        $path = $pathRoot . $this->methodPathBind['store'];
        $this->app->post($path, $this->getAction($routeNamePrefix, $controller, 'store'));
    }

    protected function update($pathRoot, $controller, $routeNamePrefix)
    {
        $path = $pathRoot . $this->methodPathBind['update'];
        $this->app->patch($path, $this->getAction($routeNamePrefix, $controller, 'update'));
    }

    protected function delete($pathRoot, $controller, $routeNamePrefix)
    {
        $path = $pathRoot . $this->methodPathBind['delete'];
        $this->app->delete($path, $this->getAction($routeNamePrefix, $controller, 'delete'));
    }

    protected function get($method, $pathRoot, $controller, $routeNamePrefix)
    {
        $path = $pathRoot . $this->methodPathBind[$method];
        $this->app->get($path, $this->getAction($routeNamePrefix, $controller, $method));
    }

    protected function getAction($routeNamePrefix, $controller, $method)
    {
        return [
            'as' => $this->getName($routeNamePrefix, $method),
            'uses' => $this->getActionPath($controller, $method)
        ];
    }

    protected function getName($routeNamePrefix, $method)
    {
        return $routeNamePrefix . '.' . $method;
    }

    protected function getActionPath($controllerPath, $method)
    {
        return $controllerPath . '@' . $method;
    }
}
