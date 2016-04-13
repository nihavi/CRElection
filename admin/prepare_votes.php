<?php

require_once '../config.php';

if (php_sapi_name() !== 'cli') {
    exit("This script must be run from the command line.");
}

if (count($argv) < 2) {
    exit("usage: prepare.php <electorate size>\n");
}

$n = $argv[1];

$values_string = implode(",", array_fill(0, $n, "()"));

if (mysqli_query($DB, "INSERT INTO `votes` VALUES " . $values_string)) {
    echo "Successfully inserted $n empty rows into 'votes' table.\n";
}
