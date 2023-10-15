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
    private static function getStaticVars(): void
    {
        self::$properties["configPath"] = self::getRelativeRoot()."config/";
        self::$properties["config"] = "";
    }

    /**
     * @param null $var
     * @return mixed
     */
    public static function getEnv($var = NULL): mixed
    {

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
    public static function redirect(string $url): void
    {
        header("Location: " . $url);
    }

    /**
     * @return string
     */
    public static function getAbsoluteRoot(): string
    {
        return str_replace("\\", "/", dirname(dirname(__DIR__)))."/";
    }

    /**
     * @return string
     */
    public static function getRelativeRoot(): string
    {
        return "../";
    }

    /**
     * Class casting
     *
     * @param string|object $destination
     * @param object $sourceObject
     *
     * @return object
     */
    public static function castObject(object|string $destination, object $sourceObject): object
    {
        if (is_string($destination)) {
            $destination = new $destination();
        }

        $sourceReflection = new ReflectionObject($sourceObject);
        $destinationReflection = new ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();

        foreach ($sourceProperties as $sourceProperty) {

            if($sourceProperty->isPublic()) {

                $name = $sourceProperty->getName();
                $value = $sourceProperty->getValue($sourceObject);

                if ($destinationReflection->hasProperty($name)) {

                    $destProperty = $destinationReflection->getProperty($name);

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