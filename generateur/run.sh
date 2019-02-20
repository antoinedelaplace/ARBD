#!/bin/bash
rm -rf composer..lock
php composer.phar install
php script2.php
