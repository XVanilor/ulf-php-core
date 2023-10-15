<?php

/**
 *
 * TODO
 * Release the error handler
 *
 */

namespace ULF\Core\ErrorHandler;

class ErrorHandler {

    public static function throw(): void
    {
        ob_clean();
        flush();
        exit();

    }

}