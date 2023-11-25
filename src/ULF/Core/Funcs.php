<?php

/**
 *
 * File-linkage functions
 *
 */

/**
 * Get an asset by it's relative path to asset folder
 *
 * @param string $path
 *
 * @return void
 *
 **/

if(!function_exists('asset')){
    function asset(string $path): string
    {
        return "/assets/".$path;
    }
}

/**
 * Get a layout by it's name
 *
 * @param string $name
 * @param array $datas
 *
 * @return void
 *
 **/

if(!function_exists("layout")){
    function layout(string $name, array $datas = NULL): void
    {
        require_once config('core.paths.layouts').$name.'.php';
    }
}

/**
 * Get a controller by it's name
 *
 * @param string $name
 *
 * @return void
 *
 **/

if(!function_exists("controller")){
    function controller(string $name): void
    {
        include config('core.paths.controllers').$name.".php";
    }
}

/**
 * Get a module by it's name
 *
 * @param string $name
 *
 * @return void
 *
 **/

if(!function_exists("module")){
    function module(string $name): void
    {
        require_once config('core.paths.modules').$name.".php";
    }
}

/**
 * Retrieve a view by it's name/relative path to view path configured in config/config.php
 *
 * @param string $name
 * @param array $datas
 *
 * @return string
 */
if(!function_exists("view")){
    function view(string $name, array $data = NULL): void
    {

        if($data)
            extract($data);

        if(!is_file(config('core.paths.views').$name.'.php')){
            debug_print_backtrace();
            trigger_error("[View] View $name not found.", E_USER_ERROR);
        }

        require_once config('core.paths.views').$name.'.php' ;
    }
}

/**
 *
 * This function secures a string before displaying into views, avoiding
 * common vulnerabilities such as XSS injection.
 * ALWAYS use it when you have to render ANY USER DATA or NON-DEV DATA
 *
 * @param string $string
 *
 * @return string
 *
 */
if(!function_exists("sec")){

    function sec(string $string): string
    {
        return htmlentities($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    }

}

if(!function_exists("dd")){

    function dd($var): void
    {
        var_dump($var);
        die;

    }

}

/**
 * Returns a value from configuration files and performs existing check
 * @TODO Implement this everywhere instead of accessing directly to $config
 *
 * @param string|NULL $key
 *
 * @return array|string|void
 */
if(!function_exists('config')){

    function config(string $key = NULL): array|string
    {
       global $core;
       return $core->config($key);

    }

}