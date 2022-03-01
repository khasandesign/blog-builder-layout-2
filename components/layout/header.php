<?php
/**
 * @var string $GLOBALS['website']['name']
 * @var string $name
 * @var string $title
 * @var string $description
 * @var string $size
 */
?>

<section id="header">
    <div class="header header-<?= $size?>">
        <h4 class="name"><?= isset($name) ? $name : '#' . ucfirst($GLOBALS['website']['name'])?></h4>
        <h2 class="title"><?= $title?></h2>
        <p class="subheading description"><?= $description?></p>
    </div>
</section>
