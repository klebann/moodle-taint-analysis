<?php


function optional_param($parname, $default, $type) : bool {
    return true;
}

/**
 * @psalm-taint-sink sql $sql
 */
function sql_run($sql) : bool {
    return true;
}

//TRIGER BECAUSE TYPE == 1
$a = optional_param('test',NULL,1);
sql_run($a);

//IGNORE
$b = optional_param('test',NULL,2);
sql_run($b);