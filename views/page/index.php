<?php
$gradient_bg = component('layout/gradient-bg', [
    'image' => $this->website['image'],
    'source' => $this->website['source']
]);
$header = component('layout/header', [
    'size' => 'lg',
    'title' => $this->website['title'],
    'description' => $this->website['description']
]);
$banners = component('layout/banners', [
    'banners' => $this->banners
]);
$sidebar = component('layout/sidebar', [
    'parts' => [
        // Sections' ids separated by sidebar parts
        [
            'target' => '',
            'sections' => ['search_tag', 'hot_deals', 'interesting']
        ],
        [
            'target' => $this->sidebar_second,
            'sections' => ['trending', 'info']
        ]
    ],
]);
?>

<?= $gradient_bg ?>
<div class="container">
    <?= $header ?>

    <div class="row content">
        <div class="col-xl-2 d-none d-xl-block">
            <?= $banners ?>
        </div>
        <div class="col-xl-6 col-lg-8">
            <div class="feed">
                <?php foreach ($this->feed as $section) {
                    ?>
                    <section id="<?= $section['section_id'] ?>">
                        <h4 class="section-heading"><?= $section['section_title'] ?></h4>
                        <?php foreach ($section['articles'] as $key => $article) {
                            ?>
                            <div class="article article-md">
                                <a href="/category?id=<?= $article['category_id'] ?>"
                                   class="par-2 semi-bold article-category"><?= $article['category_name'] ?></a>
                                <a href="/article?id=<?= $article['id'] ?>">
                                    <h3 class="article-title"><?= $article['title'] ?></h3>
                                </a>
                                <div class="tag-group">
                                    <?php foreach ($article['tags'] as $tag) {
                                        ?>
                                        <a href="/search?tag=<?= $tag ?>" class="tag <?= $tag ?>"><?= $tag ?></a>
                                        <?php
                                    } ?>
                                </div>
                                <div class="article-content"><?= $article['content'] ?></div>
                                <a href="/article?id=<?= $article['id'] ?>" class="continue-reading">
                                    <div class="overlay"></div>
                                    <button class="btn btn-md btn-secondary">Continue reading</button>
                                </a>
                            </div>
                            <?php
                            end($section['articles']);
                            echo $key !== key($section['articles']) ? '<hr>' : '';
                        } ?>
                        <?php
                        if (!empty($section['videos'])) {
                            foreach ($section['videos'] as $key => $video) {
                                if ($key == 0) {
                                    ?>
                                    <hr>
                                    <p class="license-note">All videos are copyrighted by <a
                                                href="https://creativecommons.org/">Creative <br>
                                            Commons</a> <a href="https://creativecommons.org/licenses/by/3.0/legalcode">CC
                                            BY</a>.</p>
                                    <?php
                                }
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
                            }
                        } ?>
                    </section>
                    <?php
                } ?>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-4 d-none d-lg-block">
            <?= $sidebar ?>
        </div>
    </div>
</div>