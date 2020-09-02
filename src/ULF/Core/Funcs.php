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
    function asset(string $path){
        return \ULF\Core\Helper::getRelativeRoot()."assets/".$path;
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
    function layout(string $name, array $datas = NULL){

        require_once config('core.paths.layouts').$name.'.php';
        return;
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
    function controller(string $name){

        include config('core.paths.back').$name.".php";
        return;
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
    function module(string $name){

        require_once config('core.paths.modules').$name.".php";
        return;
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
    function view(string $name, array $datas = NULL){

        if($datas)
            extract($datas);

        if(!is_file(config('core.paths.views').$name.'.php')){
            trigger_error("[View] View $name not found.", E_USER_ERROR);
            return;
        }

        require_once config('core.paths.views').$name.'.php' ;
        return;
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

    function sec(string $string){

        return htmlentities($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    }

}

if(!function_exists("dd")){

    function dd($var){

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

    function config(string $key = NULL) {

       global $core;
       return $core->config($key);

    }

}