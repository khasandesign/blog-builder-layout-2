<?php
/**
 * @var string $GLOBALS ['website']['name']
 */
?>

<footer>
    <div class="container">
        <div class="col-xl-8 mx-auto">
            <a href="/" class="brand">
                <span class="blog-name">
                    <span class="symbol"><?= ucfirst($GLOBALS['website']['symbol']) ?></span><?= ucfirst($GLOBALS['website']['name']) ?>
                </span>
            </a>
            <p class="footer-note"><a href="https://<?= $GLOBALS['website']['name'] ?>.com"
                                      target="_blank"><?= $GLOBALS['website']['name'] ?>.com</a> is a
                participant in the Amazon Services LLC Associates
                Program, an affiliate advertising program designed to provide a means for sites to earn advertising fees
                by
                advertising and linking to <a href="https://amazon.com" target="_blank">Amazon.com</a></p>
            <div class="footer-items">
                <ul class="footer-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/privacy-policy">Privacy Policy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/cookie-policy">Cookie Policy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/terms-of-use">Terms of Use</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/disclaimer">Disclaimer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contacts">Contact Us</a>
                    </li>
                </ul>
            </div>
            <p class="copywrite">© <?= $GLOBALS['website']['name'] ?> <?= Date('Y') ?></p>
        </div>
    </div>
</footer>