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
        $this->routes = route();
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

        array_key_exists($this->uri, $this->routes) ? include_once config('paths.controllers').$this->routes[$this->uri].".php" : include_once config('paths.controllers').$this->routes["/404"].".php";

        return $this;
    }

    /**
     * Configuration getter.
     *
     * @return array
     */
    public function config(){

        return $this->config;
    }

    /**
     * Routes getter.
     *
     * @return array
     */
    public function routes(){

        return $this->routes;
    }

    /**
     * Retrieve all modules dirs.
     *
     * @return array
     */
    protected function loadModules(){

        $modules = [];
        foreach(glob($path = config('paths.modules')."*", GLOB_ONLYDIR) as $dir) {
            $modules[] = config('paths.modules').basename($dir);
        }

        //Remove the default App/Functions/ directory (which contains custom functions)
        return array_diff($modules, [config('paths.modules')."Functions"]);
    }

    /**
     * Include fonctions files
     *
     * @return void
     */
    protected function loadFunctions(){

        /**
         * @TODO
         * Improve custom functions inclusion
         */
        $customFunctions = array_diff(scandir(config('paths.modules')."Functions/"), array(".", ".."));
        foreach($customFunctions as $customFunction)
            include_once config('paths.modules')."Functions/".$customFunction;

        /**
         * Retrieve all modules functions
         */
        foreach ($this->modules as $module) {

            if (file_exists( $funcs = $module . "/Functions.php")) {
                include_once $funcs;
            }
        }

    }

}