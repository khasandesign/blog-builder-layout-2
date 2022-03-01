<?php
$gradient_bg = component('layout/gradient-bg');
$header = component('layout/header', [
    'size' => 'sm',
    'name' => '#' . $this->code,
    'title' => $this->title,
    'description' => $this->message
]);
?>

<?= $gradient_bg ?>
<div class="container">
    <div class="not-found">
        <?= $header ?>
        <div class="tag-group tag-center">
            <?php foreach ($this->tags as $tag) {
                ?>
                <a href="search?tag=<?= $tag['tag']?>" class="tag"><?= $tag['tag']?></a>
                <?php
            } ?>
        </div>
    </div>
</div>
