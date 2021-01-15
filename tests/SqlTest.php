<?php


function optional_param($parname, $default, $type) : bool {
    return true;
}

/**
 * @psalm-taint-sink callable $sql
 */
function sql_run($sql) : bool {
    return true;
}
$a = optional_param('test',NULL,1);
sql_run($a);
$b = optional_param('test',NULL,2);
sql_run($b);