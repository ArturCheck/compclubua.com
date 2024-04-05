<?php
require "libs/rb-mysql.php"; //db.php

R::setup('mysql:host=localhost; dbname=mybd', 'mysql', 'mysql');

session_start();
