<?php

namespace App\Core;

class KeyBuilder
{

    public string   $dir_path;
    public array    $keys;

    public function __construct(string $dir_path)
    {
        if(!is_dir($dir_path)) {
            trigger_error("[KeyBuilder] $dir_path directory does not exists. Can't build keys from this directory.", E_USER_ERROR);
            exit;
        }

        $this->dir_path = $dir_path;
        $this->keys     = [];

    }

    /**
     * Build a configuration directory keys from it's files
     *
     * @return array
     */
    public function build(){

        $subs = array_diff(scandir($this->dir_path), [".", ".."]);
        $built_files = [];

        foreach($subs as $sub){

            //Check if the dir contains sub directories, else handle files
            if(is_dir($this->dir_path.'/'.$sub)) {

                $key_files = array_diff(scandir($this->dir_path.'/'.$sub), [".", ".."]);

                foreach($key_files as $key_file){
                    //Building each file key
                    $built_files[] = $this->buildFile($this->dir_path.'/'.$sub.'/'.$key_file, str_replace(".php", "", $sub));
                }
            }

            else {
                //Current entry isn't a dir. Building file..
                $built_files[] = $this->buildFile($this->dir_path.'/'.$sub, str_replace(".php", "", $sub));
            }

        }

        //As each files are pushed in their own key, push all keys in the main array $this->keys
        foreach($built_files as $file)
            foreach($file as $built_key => $built_value)
                $this->keys[$built_key] = $built_value;

        return $this->keys;

    }

    /**
     * Build a configuration file. It MUST returns an array.
     * @see /config/core.php
     *
     * @details Example
     * ["foo" => "bar"] becomes "foo.bar"
     * Handles multidimensional arrays
     *
     * @param string $file      File path to build
     * @param string $file_key  The file name without extension
     *
     * @return array
     */
    private function buildFile(string $file, string $file_key){

            //Building keys
            $stack              = include_once $file;
            $current_file_key   = $file_key;

            $current_key        = $current_file_key;
            $file_built         = [];

            if(!is_array($stack)){
                trigger_error("[KeyBuilder] File $file is not returning an array. Aborting.", E_USER_ERROR);
                exit;
            }

            while(!empty($stack)){

                $key        = array_key_first($stack);
                $trans_str  = $stack[$key];

                $current_key .= ".$key";

                if(is_array($trans_str)){

                    foreach($trans_str as $sub_key => $sub_trans){
                        $stack["$key.$sub_key"] = $sub_trans;
                    }
                }

                else {
                    $file_built[str_replace(" ", "-", $current_key)] = $trans_str; //Automatically replace space by '-' in keys
                }

                $current_key = $current_file_key;
                unset($stack[$key]);

            }

            return $file_built;
        }


}