<?php


namespace ULF\Core;

class ULF
{

    protected array $config;
    protected array $routes;
    protected array $modules;

    public string   $uri;

    /**
     * ULF constructor.
     */
    public function __construct()
    {
        $this->config   = [];
        $this->routes   = [];
        $this->modules  = [];
    }

    /**
     * Run the framework. Let's go!
     */
    public function run(){

        $this->config = (new KeyBuilder('../config'))->build();
        $this->routes = $this->loadRoutes();
        $this->modules = $this->loadModules();
        $this->loadFunctions(); //Include functions files

        //Start session
        if(!session_id())
            session_start();

        //$uri = strtok(strtok(strip_tags($_SERVER['REQUEST_URI']), "?"), "&");
        $this->uri = strtok(strtok(strip_tags($_SERVER['REQUEST_URI']), "?"), "&");

        //Remove the end / if so
        if((substr($this->uri, -1, 1) === "/") && $this->uri !== "/")
            $this->uri = substr($this->uri, 0, strlen($this->uri)-1);

        array_key_exists($this->uri, $this->routes) ? include_once config('core.paths.controllers').$this->routes($this->uri).".php" : include_once config('core.paths.controllers').$this->routes("errors.404").".php";

        return $this;
    }

    /**
     * Configuration getter.
     *
     * @return array
     */
    public function config($key = NULL){

        if(!$key)
            return $this->config;

        if(!isset($this->config[$key])){
            trigger_error("[Configuration] $key configuration key does not exist.", E_USER_ERROR);
            return;
        }

        return $this->config[$key];
    }

    /**
     * Routes getter.
     *
     * @return array
     */
    public function routes($route_path = NULL){

        if(!$route_path)
            return $this->routes;

        if(!isset($this->routes[$route_path])){
            trigger_error("[Router] $route_path route key does not exist.", E_USER_ERROR);
            return;
        }

        return $this->routes[$route_path];

    }

    /**
     * Retrieve all modules dirs.
     *
     * @return array
     */
    private function loadModules(){

        $modules = [];
        foreach(glob($path = config('core.paths.modules')."*", GLOB_ONLYDIR) as $dir) {
            $modules[] = config('core.paths.modules').basename($dir);
        }

        //Remove the default App/Functions/ directory (which contains custom functions)
        return array_diff($modules, [config('core.paths.modules')."Functions"]);
    }

    /**
     * Include fonctions files
     *
     * @return void
     */
    private function loadFunctions(){

        /**
         * @TODO
         * Improve custom functions inclusion
         */
        $customFunctions = array_diff(scandir(config('core.paths.modules')."Functions/"), array(".", ".."));
        foreach($customFunctions as $customFunction)
            include_once config('core.paths.modules')."Functions/".$customFunction;

        /**
         * Retrieve all modules functions
         */
        foreach ($this->modules as $module) {

            if (file_exists( $funcs = $module . "/Functions.php")) {
                include_once $funcs;
            }
        }

    }

    /**
     * Load routes from files
     *
     * @return array
     */
    private function loadRoutes(){

        $dir = '../routes/';
        $routes_dir = array_diff(scandir($dir), ['.', '..']);
        $routes = [];

        foreach($routes_dir as $route_file){

            $route_file = include_once $dir.$route_file;

            foreach ($route_file as $uri => $route)
                $routes[(string)$uri] = $route;
        }

        return $routes;
    }

}