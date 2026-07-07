--TEST--
Example instantiation returns correct type
--FILE--
<?php

declare(strict_types=1);

use Tests\PHPT;

require implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'bootstrap.php']);

use Ghostwriter\CodingStandard\Example;

$example = new Example();
var_dump($example);

--EXPECTF--
object(Ghostwriter\CodingStandard\Example)#%d (0) {
}
