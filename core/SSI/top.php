<?
require_once __DIR__ . "/../init.php";
if ($security) {
    require "security.php";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="API, Shakespeare, literature">
    <meta name="description" content="Application which lets users fetch data about different Shakespeare's plays">
    <title><?=$title?></title>

    <link rel="stylesheet" type="text/css" href="styles/normalize.css">
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
</head>
<body>
    <div id="header" class="header">
        <div id="title" class="title">Shakespeare API</div>
        <ul class="head-nav">
            <li><a href="index.php">Login</a></li>
            <li><a href="#">Analytics</a></li>
            <li><a href="home.php">Home</a></li>
        </ul>
    </div>
    <div class="main-wrapper<?= isset($wrapper_class) ? ' ' . $wrapper_class : ''; ?>">