<?php
$gradient_bg = component('layout/gradient-bg');
$banners = component('layout/banners', [
    'banners' => $this->banners
]);
$sidebar = component('layout/sidebar', [
    'parts' => [
        // Sections' ids separated by sidebar parts
        [
            'target' => '',
            'sections' => ['search_tag', 'hot_deals']
        ],
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
                <section id="articles">
                    <h3 class="section-heading"><?= ucfirst($this->s) ?></h3>
                    <?= empty($this->articles) ? '<h6 class="label-3">No articles found...</h6>' : '' ?>
                    <?php foreach ($this->articles as $key => $article) {
                        ?>
                        <div class="article article-sm">
                            <a href="/category?id=<?= $article['category_id'] ?>"
                               class="par-2 semi-bold article-category"><?= $article['category_name'] ?></a>
                            <a href="/article?id=<?= $article['id'] ?>">
                                <h4 class="article-title"><?= $article['title'] ?></h4>
                            </a>
                            <div class="tag-group">
                                <?php foreach ($article['tags'] as $tag) {
                                    ?>
                                    <a href="/search?tag=<?= $tag ?>" class="tag"><?= ucfirst($tag) ?></a>
                                    <?php
                                } ?>
                            </div>
                            <div class="article-content">
                                <p><?= $article['content'] ?>... <a href="/article?id=<?= $article['id'] ?>" class="read-more">Read more</a></p>
                            </div>
                        </div>
                        <?php
                        end($this->articles);
                        echo $key !== key($this->articles) ? '<hr>' : '';
                    } ?>
                </section>
                <section id="videos">
                    <h4 class="section-heading">Videos</h4>
                    <p class="license-note">All videos are copyrighted by <a href="https://creativecommons.org/">Creative
                            <br> Commons</a> <a href="https://creativecommons.org/licenses/by/3.0/legalcode">CC BY</a>.
                    </p>
                    <?php foreach ($this->videos as $video) {
                        ?>
                        <div class="video">
                            <div class="embed">
                                <iframe src="<?= $video['embed_url'] ?>" title="YouTube video player"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen=""></iframe>
                            </div>
                        </div>
                        <?php
                    } ?>
                </section>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-4 d-none d-lg-block">
            <?= $sidebar ?>
        </div>
    </div>
</div>