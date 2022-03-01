<?php
$gradient_bg = component('layout/gradient-bg');
$banners = component('layout/banners', [
    'banners' => $this->banners
]);
$sidebar = component('layout/sidebar', [
    'parts' => [
        // Sections' ids separated by sidebar parts
        [
            'target' => 'article-tags',
            'sections' => ['picked_items']
        ]
    ],
    'sidebar_data' => [] // Data for all possible sidebar sections
]);
?>

<?= $gradient_bg ?>
<div class="container">
    <div class="row content">
        <div class="col-xl-2 d-none d-xl-block">
            <?= $banners ?>
        </div>
        <div class="col-xl-6 col-lg-8">
            <div class="feed">
                <section id="article-1">
                    <div class="article">
                        <a href="/category?id=<?= $this->article['category_id'] ?>" class="par-2 semi-bold article-category"><?= $this->article['category_name'] ?></a>
                        <a href="">
                            <h3 class="article-title"><?= $this->article['title'] ?></h3>
                        </a>
                        <div class="tag-group" id="article-tags">
                            <?php foreach ($this->article['tags'] as $tag) {
                                ?>
                                <a href="/search?tag=<?= $tag ?>" class="tag"><?= $tag ?></a>
                                <?php
                            } ?>
                        </div>
                        <div class="article-content"><?= $this->article['content'] ?></div>
                    </div>
                </section>
                <section id="see-also">
                    <h4 class="section-heading">See also</h4>
                    <div class="article article-sm">
                        <a href="/category?id=<?= $this->article_next['category_id'] ?>" class="par-2 semi-bold article-category"><?= $this->article_next['category_name'] ?></a>
                        <a href="">
                            <h4 class="article-title"><?= $this->article_next['title'] ?></h4>
                        </a>
                        <div class="tag-group">
                            <?php foreach ($this->article['tags'] as $tag) {
                                ?>
                                <a href="/search?tag=<?= $tag ?>" class="tag"><?= $tag ?></a>
                                <?php
                            } ?>
                        </div>
                        <div class="article-content">
                            <p><?= $this->article_next['content'] ?> <a href="/article?id=<?= $this->article_next['id'] ?>" class="read-more">Read more</a></p>
                        </div>
                    </div>
                    <hr>
                    <div class="video">
                        <p class="license-note">All videos are copyrighted by <a href="https://creativecommons.org/">Creative
                                <br>
                                Commons</a> <a href="https://creativecommons.org/licenses/by/3.0/legalcode">CC BY</a>.
                        </p>
                        <div class="embed">
                            <iframe src="<?= $this->video['embed_url']?>" title="YouTube video player"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen=""></iframe>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-4 d-none d-lg-block">
            <?= $sidebar ?>
        </div>
    </div>
</div>