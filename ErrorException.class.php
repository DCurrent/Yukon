<?php

namespace dc\yukon;

require_once('config.php');

// Error exception object. Just in case
// we'd like to add functionality
// to PHP exception class later.
interface iErrorException
{
}

class ErrorException extends \ErrorException implements iErrorException
{
}

?>