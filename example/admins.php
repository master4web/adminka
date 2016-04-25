<?php

require 'vendor/korm/korm.php';
require 'vendor/akdmin/lib/akdmin.php';


include 'config/ini.php';


$admin = new AKdmin();
$admin->init();
