<?php
    
header('Content-type: text/html;charset=utf-8');
date_default_timezone_set('Europe/Vienna');
mb_internal_encoding("UTF-8");

require 'Console.php';
require 'Hitradio.php';

$hitradio = new Hitradio();
$hitradio->init();