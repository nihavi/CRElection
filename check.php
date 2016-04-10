<?php
require_once("config.php");
session_start();

$htmlOutput = '';
if ( allowed() ) {
    print("true");
}
else{
    print("false");
}
?>