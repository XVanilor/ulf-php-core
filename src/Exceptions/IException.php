<?php


namespace App\Core\Exceptions;


interface IException
{

    /**
     *  IMPORTANT NOTE
     *
     * Please use these properties (as PHP does not handle them in Interfaces currently (PHP 7.4)
     *
     *   protected $message = 'Exception inconnue'; // message de l'exception
     *   private   $string;                        // __toString cache
     *   protected $code = 0;                      // code de l'exception défini par l'utilisateur
     *   protected $file;                          // nom du fichier source de l'exception
     *   protected $line;                          // ligne de la source de l'exception
     *   private   $trace;                         // backtrace
     *   private   $previous;
     * @return mixed
     */

    public function getMessage();
    public function getCode();
    public function getFile();
    public function getLine();
    public function getTrace();
    public function getPrevious();
    public function getTraceAsString();

    public function __toString();

}