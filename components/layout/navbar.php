<?php
/**
 * @var string $GLOBALS ['website']['name']
 */

$url = parse_url('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
$page_name = $url['path'];
$url = null;
?>

<nav class="navbar navbar-expand-md navbar-light">
    <div class="container-fluid d-flex d-md-block">
        <a href="/" class="brand">
            <span class="blog-name">
                <span class="symbol"><?= ucfirst($GLOBALS['website']['symbol']) ?></span><?= ucfirst($GLOBALS['website']['name']) ?>
            </span>
        </a>
        <div class="nav-items">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?= $page_name === '/' ? 'active' : '' ?>" aria-current="page" href="/">Home</a>
                </li>
                <?php foreach ($GLOBALS['base']->category as $category) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $_GET['id'] === $category['id'] && $page_name === '/category' ? 'active' : '' ?>"
                           href="/category?id=<?= $category['id'] ?>"><?= ucwords($category['name']) ?></a>
                    </li>
                    <?php
                } ?>
                <li class="nav-item">
                    <a class="nav-link <?= $page_name === '/blog' ? 'active' : '' ?>" href="/blog">Our Blog</a>
                </li>
            </ul>
        </div>
    </div>
</nav>