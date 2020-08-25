<?php

namespace App\Core\Exceptions;

use Exception;
use Throwable;

class RouteNotFoundException extends Exception implements IException {


    protected $message = 'Route not Found';   // Message de l'exception
    private   $string;                        // __toString cache
    protected $code = 404;                      // Code de l'exception défini par l'utilisateur
    protected $file;                          // Nom du fichier source de l'exception
    protected $line;                          // Ligne de la source de l'exception
    private   $trace;                         // Backtrace

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = $this->message;
        $code = $this->code;

        parent::__construct($message, $code, $previous);
        dd($this->message);
    }
}