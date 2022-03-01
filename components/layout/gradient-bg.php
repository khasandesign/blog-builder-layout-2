<?php
/**
 * @var string $image
 * @var string $source
 */

if (!isset($image))
    $image = '';

if (!isset($source))
    $source = '';

$image_url = $image ? 'https://source.unsplash.com/' . $image . '/1920x1080' : '';
?>

<div class="bg-wrap">
    <div class="<?= $image ? 'gradient-' : ''?>bg" style="background-image: url(<?= $image_url?>)">
        <div class="color"></div>
        <div class="gradient"></div>
    </div>
    <p class="source par-2"><?= $source ?></p>
</div>