<?php
require "config/_functions.php";
require "config/_base_query.php";
require "controllers/Page.php";

// Validate DB
if (!isTableEmpty()) {
    echo 'Please first of all fill up all DB tables with minimum content. Thanks!';
    return false;
}

// Get content
$page = new Page();
$content = $page->getContent();

// Use base layout components
$navbar = component('layout/navbar');
$footer = component('layout/footer');

// Base functions to execute
$url = getFullUrl();
updateFavicon();
generateRobotsTxt();
generateSitemap();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $page->title ?></title>

    <meta name="description" content="<?= $GLOBALS['website']['description'] ?>">
    <meta name="keywords" content="<?= $GLOBALS['tags'] ?>">
    <meta name="image" content="<?= $url ?>/assets/images/symbol.svg">

    <meta name="Author" content="<?= $GLOBALS['website']['name'] ?>">
    <meta name="Copywrite" content="© <?= ucfirst($GLOBALS['website']['name']) . ' ' . date('Y') ?>">

    <!-- Schema.org for Google -->
    <meta itemprop="name" content="<?= strip_tags($GLOBALS['website']['title']) ?>">
    <meta itemprop="description" content="<?= $GLOBALS['website']['description'] ?>">
    <meta itemprop="image" content="<?= $url ?>/assets/images/symbol.svg">
    <!-- Twitter -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?= strip_tags($GLOBALS['website']['title']) ?>">
    <meta name="twitter:description" content="<?= $GLOBALS['website']['description'] ?>">
    <meta name="twitter:image:src" content="https://source.unsplash.com/<?= $GLOBALS['website']['image'] ?>">
    <!-- Open Graph general (Facebook, Pinterest & Google+) -->
    <meta name="og:title" content="<?= strip_tags($GLOBALS['website']['title']) ?>">
    <meta name="og:description" content="<?= $GLOBALS['website']['description'] ?>">
    <meta name="og:image" content="https://source.unsplash.com/<?= $GLOBALS['website']['image'] ?>">
    <meta name="og:url"
          content="<?= $url . '/' ?>">
    <meta name="og:site_name" content="<?= $GLOBALS['website']['name'] ?>">
    <meta name="og:locale" content="en_US">
    <meta name="og:type" content="website">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        <?php
        $css = file_get_contents('assets/css/app.css');
        echo $css;

        $accent_rgb = implode(',', sscanf($GLOBALS['website']['accent_color'], "#%02x%02x%02x"));
        ?>
        :root {
            --accent: <?= $accent_rgb ?>;
        }
    </style>
    <!--    <link rel="stylesheet" href="assets/css/app.css">-->
    <link rel="shortcut icon" href="assets/images/symbol.svg" type="image/x-icon">
</head>
<body>
<?php
echo $navbar;
echo $content;
echo $footer;
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script src="assets/js/script.js"></script>
</body>
</html>