<?php
session_start();

$lang_code = "ar";
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar','en'])) {
    $lang_code = $_GET['lang'];
    $_SESSION['lang'] = $lang_code;
} elseif (isset($_SESSION['lang'])) {
    $lang_code = $_SESSION['lang'];
}

$lang_file =  "languages/" . $lang_code . ".php";
if (file_exists($lang_file)) {
    include $lang_file;
} else {
    include  "languages/ar.php";
}
