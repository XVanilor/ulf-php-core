<?php

namespace ULF\Core;

use ReflectionObject;
use ReflectionException;

/**
 * Class Helper
 * @package App\Core
 *
 */

class Helper
{

    /**
     * @var array $properties
     */
    private static array $properties;

    /**
     *
     */
    private static function getStaticVars(){
        self::$properties["configPath"] = self::getRelativeRoot()."config/";
        self::$properties["config"] = "";
    }

    /**
     * @param null $var
     * @return mixed
     */
    public static function getEnv($var = NULL){

        $env = json_decode(file_get_contents(self::getRelativeRoot()."App/env.json"), true);

        if(!$var)
            return $env;
        else {
            $vPath = explode(".", strtoupper($var));
            $vPathLenght = count($vPath);

            $requested_param = $env;
            for($i = 0; $i < $vPathLenght; $i++)
                $requested_param = $requested_param[$vPath[$i]];

            return $requested_param;
        }

    }

    /**
     *
     * Throws a HTTP 301 redirection request
     *
     * @param string $url
     *
     * @return void
     *
     */
    public static function redirect(string $url){

        header("Location: " . $url);
        return;
    }

    /**
     * @return string
     */
    public static function getAbsoluteRoot(){
        return str_replace("\\", "/", dirname(dirname(__DIR__)))."/";
    }

    /**
     * @return string
     */
    public static function getRelativeRoot(){
        return "../";
    }

    /**
     * Class casting
     *
     * @param string|object $destination
     * @param object $sourceObject
     *
     * @return object
     * @throws ReflectionException
     */
    public static function castObject($destination, $sourceObject)
    {
        if (is_string($destination)) {
            $destination = new $destination();
        }

        $sourceReflection = new ReflectionObject($sourceObject);
        $destinationReflection = new ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();

        foreach ($sourceProperties as $sourceProperty) {

            var_dump($sourceProperty);
            echo "<br /><br />";

            if($sourceProperty->isPublic()) {

                $name = $sourceProperty->getName();
                $value = $sourceProperty->getValue($sourceObject);

                if ($destinationReflection->hasProperty($name)) {

                    $destProperty = $destinationReflection->getProperty($name);
                    var_dump($destProperty);
                    echo "<br /><br />";

                    if ($destProperty->isPublic()) {
                        $destProperty->setValue($destination, $value);
                    }
                }
                else
                    $destination->$name = $value;
            }

        }
        return $destination;
    }
}