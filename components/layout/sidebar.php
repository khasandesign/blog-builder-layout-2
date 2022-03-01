<?php
/**
 * @var array $parts
 * @var array $sidebar_data
 */
?>

<div class="sidebar">
    <?php foreach ($parts as $part) {
        ?>
        <div data-target="<?= $part['target'] ?>" class="sidebar-part">
            <?php foreach ($part['sections'] as $section) {
                echo component('layout/sidebar/_' . $section);
            } ?>
        </div>
        <?php
    } ?>
</div>
