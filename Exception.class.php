<?php

namespace dc\yukon;

require_once('config.php');

// Exception object. Just in case
// we'd like to add functionality
// to PHP exception class later.
interface iException
{
}

class Exception extends \Exception implements iException
{
}

?>