<?php
/**
 * @var array $banners
 */
?>

<div class="banners">
    <?php foreach ($banners as $banner) {
        ?>
        <div class="banner">
            <?= $banner?>
        </div>
        <?php
    } ?>
</div>