<?php
/**
 * @var $db
 */

require "config/_db.php";

$q = $db->query("SELECT article.id, article.title, article.content, c.id as category_id, c.name as category_name FROM article JOIN category c on article.category_id = c.id LIMIT 2");
$articles = $q->fetchAll(PDO::FETCH_ASSOC);

// Hide products
array_walk($articles, function (&$a) {
    $a['content'] = preg_replace('/{{.*?}}/', '', $a['content']);
    $a['content'] = preg_replace('/<p[^>]*><\\/p[^>]*>/', '', $a['content']);
});

?>

<section id="trending">
    <h5 class="section-heading">Trending</h5>
    <?php foreach ($articles as $article) {
        ?>
        <div class="article article-xs">
            <a href="/category?id=<?= $article['category_id'] ?>"
               class="article-category"><?= $article['category_name'] ?></a>
            <a href="/article?id=<?= $article['id'] ?>">
                <p class="article-title subheading"><?= $article['title'] ?></p>
            </a>
            <p class="article-description"><?= substr($article['content'], 0, 92) ?>... <a
                        href="/article?id=<?= $article['id'] ?>" class="read-more">Read more</a></p>
        </div>
        <?php
    } ?>
</section>
