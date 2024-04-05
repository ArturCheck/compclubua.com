<?php
require_once('db.php');

// Розпочнемо сесію (якщо ще не розпочата)
session_start();

// Закінчимо сесію
session_destroy();

// Перенаправлення на головну сторінку або в інше місце
header('Location: login.php');
exit;
