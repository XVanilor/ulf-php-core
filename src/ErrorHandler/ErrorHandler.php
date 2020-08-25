<?php

/**
 *
 * TODO
 * Release the error handler
 *
 */

namespace App\Core\ErrorHandler;

class ErrorHandler {

    public static function throw(){

        ob_clean();
        flush();
        exit();

    }

}