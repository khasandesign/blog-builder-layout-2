<?php
/**
 * @var $db
 */

require "config/_db.php";

$q = $db->query("SELECT * FROM tag");
$tags = $q->fetchAll(PDO::FETCH_ASSOC);

?>

<section id="search-tag">
    <h5 class="section-heading">Search tag</h5>
    <div class="tag-group">
        <?php foreach ($tags as $tag) {
            ?>
            <a href="search?tag=<?= $tag['tag'] ?>" class="tag"><?= ucfirst($tag['tag']) ?></a>
            <?php
        } ?>
    </div>
</section>
