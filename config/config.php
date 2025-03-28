<?php
session_start();
$con = new mysqli("192.168.33.222", "root", "root", "nitrosgu", 6080);
if ($con->connect_errno) {
    echo "Не удалось подключиться к MySQL 33.156: (" . $con->connect_errno . ") " . $con->connect_error;
    die();
}
$conapps = new mysqli("192.168.33.222", "root", "root", "nitroapps", 6080);
if ($con->connect_errno) {
    echo "Не удалось подключиться к MySQL 33.156: (" . $conapps->connect_errno . ") " . $conapps->connect_error;
    die();
}
$conedo = new mysqli("192.168.33.222", "root", "root", "edo", 6080);
if ($con->connect_errno) {
    echo "Не удалось подключиться к MySQL 33.156: (" . $conedo->connect_errno . ") " . $conedo->connect_error;
    die();
}
$condocs = new mysqli("192.168.33.154", "mtb", "madina168", "docs");
if ($con->connect_errno) {
    echo "Не удалось подключиться к MySQL 33.154: (" . $con->connect_errno . ") " . $con->connect_error;
    die();
}
$condocs->query("SET time_zone = '+05:00'");

$conj = new mysqli("192.168.33.140", "root", "root", $_SESSION['journal']);
if ($conj->connect_errno) {
    echo "Не удалось подключиться к MySQL 33.140: (" . $conj->connect_errno . ") " . $conj->connect_error;
    die();
}
$conj->query("SET time_zone = '+05:00'");
foreach ($_POST as $key => $val) { $v = "$key"; $$v = mysqli_real_escape_string($con,$val); }
foreach ($_GET  as $key => $val) { $v = "$key"; $$v = mysqli_real_escape_string($con,$val); }

$_SESSION['emaildir'] = '/data/emails/';
$mpdir = '/files/mp/';
$rector = 4077;
$coo = 5249;
$rukor = 368;
$ror = 368;
$coodir = '/data/coo/';
$datadir = '/data/docs/';
$vedodir = '/data/vedo/';
$vdir = '/data/v/';
$idir = '/data/i/';
$gzdir = '/data/gz/';
$_SESSION['lang'] = 'RU';
$lang = $_SESSION['lang'];
$_SESSION['key'] = 'bWzogfhBJqr6ELxCgP1N0Qbe4';
include('function.php');
include('class.php');
include('form.php');
