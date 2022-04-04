<?php
/**
 * @var $db
 */

require "config/_db.php";

$url = parse_url('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
$page_name = $url['path'];
$url = null;

$products = [];
if (isset($_GET['id']) && $page_name === '/article') {
    $q = $db->prepare("SELECT * FROM product WHERE article_id = :article_id");
    $q->execute(['article_id' => $_GET['id']]);
    $products = $q->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo 'ERROR: Please use "Picked items" sidebar section only on article page';
}


?>

<section id="picked-items">
    <h5 class="section-heading">Picked items</h5>
    <?php foreach ($products as $product) {
        ?>
        <div class="picked-item">
            <div class="picked-item-info">
                <a href="<?= $product['url']?>" target="_blank">
                    <p class="picked-item-title"><?= $product['name']?></p>
                </a>
                <p class="picked-item-brand"><?= $product['brand']?></p>
                <p class="picked-item-description"><?= substr($product['description'], 0, 48)?>...</p>
                <a href="<?= $product['url']?>" target="_blank" class="link link-icon">View price <img class="icon" src="/assets/images/open-icon.svg" alt="Open">
                </a>
            </div>
            <div class="picked-item-image">
                <?= $product['image']?>
            </div>
        </div>
        <?php
    } ?>
</section>
