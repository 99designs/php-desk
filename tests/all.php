#!/usr/bin/env php
<?php

$dir = dirname(__FILE__);

// Initialise Composer autoloader
require_once($dir . '/../vendor/autoload.php');

// Run any test classes that get defined
require_once($dir . '/../vendor/lastcraft/simpletest/autorun.php');

// Include all the test suites
// TODO
