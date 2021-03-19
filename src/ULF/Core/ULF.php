<?php


namespace ULF\Core;

class ULF
{

    protected array $config;
    protected array $routes;
    protected array $modules;

    public string   $uri;
    public const PREVENT_DEFAULT_ROUTING   = "prevent_routing";
    public const PREVENT_DEFAULT_CONFIG    = "prevent_config";
    public const PREVENT_DEFAULT_MODULES   = "prevent_modules";
    public const PREVENT_DEFAULT_FUNCS     = "prevent_funcs";
    public const PREVENT_DEFAULT_SESSION   = "prevent_session";

    private array   $prevents;


    /**
     * ULF constructor.
     */
    public function __construct()
    {
        $this->config   = [];
        $this->routes   = [];
        $this->modules  = [];
        $this->prevents = [];
    }

    /**
     * Prevents some default features of the core in order to let you code yours. Enjoy!
     *
     * @param string $prevent_const
     *
     * @return $this
     */
    public function preventDefault(string $prevent_const){

        $this->prevents[$prevent_const] = 1;
        return $this;
    }

    /**
     * Run the framework. Let's go!
     */
    public function run(){

        if(!isset($this->prevents[self::PREVENT_DEFAULT_CONFIG]))
            $this->config = (new KeyBuilder('../config'))->build();

        if(!isset($this->prevents[self::PREVENT_DEFAULT_MODULES]))
            $this->modules = $this->loadModules();

        if(!isset($this->prevents[self::PREVENT_DEFAULT_FUNCS]))
            $this->loadFunctions(); //Include functions files

        if(!isset($this->prevents[self::PREVENT_DEFAULT_SESSION])){
            //Start session
            if(!session_id())
                session_start();
        }

        if(!isset($this->prevents[self::PREVENT_DEFAULT_ROUTING])){

            $this->routes = $this->loadRoutes();

            //$uri = strtok(strtok(strip_tags($_SERVER['REQUEST_URI']), "?"), "&");
            $this->uri = strtok(strtok(strip_tags($_SERVER['REQUEST_URI']), "?"), "&");

            //Remove the end / if so
            if((substr($this->uri, -1, 1) === "/") && $this->uri !== "/")
                $this->uri = substr($this->uri, 0, strlen($this->uri)-1);

            array_key_exists($this->uri, $this->routes) ? include_once config('core.paths.controllers').$this->route($this->uri).".php" : include_once config('core.paths.controllers').$this->route("errors.404").".php";

        }

        return $this;
    }

    /**
     * Configuration getter.
     *
     * @return array|string
     */
    public function config($key = NULL){

        if(!$key)
            return $this->config;

        if(!isset($this->config[$key])){
            debug_print_backtrace();
            trigger_error("[Configuration] $key configuration key does not exist.", E_USER_ERROR);
        }

        return $this->config[$key];
    }

    /**
     * Route getter.
     *
     * @return array|string
     */
    public function route($route_path = NULL){

        if(!$route_path)
            return $this->routes;

        if(!isset($this->routes[$route_path])){
            debug_print_backtrace();
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
     * Include functions files
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