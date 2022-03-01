<?php
/**
 * @var $db
 */

require "config/_db.php";

$q = $db->query("SELECT * FROM product WHERE hot_deal LIMIT 3");
$products = $q->fetchAll(PDO::FETCH_ASSOC);

?>

<section id="picked-items">
    <h5 class="section-heading">Hot deals</h5>
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
