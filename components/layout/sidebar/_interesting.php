<?php
/**
 * @var $db
 */

require "config/_db.php";

$q = $db->query("SELECT embed_url FROM video ORDER BY RAND() LIMIT 1");
$video = $q->fetch(PDO::FETCH_ASSOC);

?>

<section id="interesting">
    <h5 class="section-heading">Interesting</h5>
    <div class="video">
        <div class="embed">
            <iframe src="<?= $video['embed_url'] ?>" title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen=""></iframe>
        </div>
        <p class="license-footnote">All videos are copyrighted by <a href="https://creativecommons.org/">Creative
                <br>
                Commons</a> <a href="https://creativecommons.org/licenses/by/3.0/legalcode">CC BY</a>.</p>
    </div>
</section>
