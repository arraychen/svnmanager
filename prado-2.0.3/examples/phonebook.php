<?php
if (!extension_loaded('sqlite'))
die('Sorry, the php sqlite module is required for this example to work.');
require_once(dirname(__FILE__).'/../framework/prado.php');
pradoGetApplication('phonebook/application.spec')->run();
?>