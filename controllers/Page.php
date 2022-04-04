<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require('Controller.php');
require './vendor/autoload.php';

class Page extends Controller
{
    public $title;
    public $description;

    public function __construct()
    {
        parent::__construct();
        $this->title = 'Review Articles';
        $this->description = 'All about the history of handmade and handmade businesses in our blog';
    }

    /**
     * Default page of the site
     * @return false|string
     */
    public function actionIndex()
    {
        /**
         * @var $db
         */
        require "config/_db.php";

        // Get banners
        $banners = [];
        $q = $db->query("SELECT src FROM banner ORDER BY RAND()");
        foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $row) {
            array_push($banners, $row['src']);
        }

        // Get articles
        $q = $db->query("SELECT a.id, a.title, a.content, c.id as category_id, c.name as category_name, GROUP_CONCAT(t.tag) tags FROM article a INNER JOIN article_tag at ON a.id = at.article_id INNER JOIN tag t ON t.id = at.tag_id INNER JOIN category c on a.category_id = c.id GROUP BY a.id ORDER BY a.id desc LIMIT 9");
        $articles = $q->fetchAll(PDO::FETCH_ASSOC);

        array_walk($articles, function (&$a) {
            // Hide products
            $a['content'] = preg_replace('/{{.*?}}/', '', $a['content']);
            $a['content'] = preg_replace('/<p[^>]*><\\/p[^>]*>/', '', $a['content']);

            // Trim content
            $a['content'] = $this->trimParagraphs($a['content'], 2);

            // Explode tags
            $a['tags'] = explode(',', $a['tags']);
        });

        // Specify new article
        array_push($articles[0]['tags'], 'new');

        // Get videos
        $q = $db->query("SELECT v.embed_url, c.id as category_id, c.name as category_name FROM video v JOIN category c on v.category_id = c.id");
        $videos = $q->fetchAll(PDO::FETCH_ASSOC);

        // Build feed
        $feed = [];

        // Section: New Article
        $feed['new_article']['section_title'] = 'New article';
        $feed['new_article']['articles'] = [$articles[0]];
        $feed['new_article']['videos'] = [];
        $feed['new_article']['section_id'] = str_replace(' ', '-', strtolower($feed['new_article']['section_title']));

        // Section: Articles & Videos (by Categories)
        $articles = array_reverse($articles);
        foreach ($articles as $article) {
            if (!isset($feed[$article['category_id']]['articles'])) {
                $feed[$article['category_id']]['articles'] = [];
            }
            $feed[$article['category_id']]['section_title'] = ucfirst(strtolower($article['category_name']));
            array_push($feed[$article['category_id']]['articles'], $article);

            // Set id
            $feed[$article['category_id']]['section_id'] = str_replace(' ', '-', strtolower($article['category_name']));
        }
        foreach ($videos as $video) {
            if (!isset($feed[$video['category_id']]['videos'])) {
                $feed[$video['category_id']]['videos'] = [];
            }
            if (count($feed[$video['category_id']]['videos']) < 2) {
                array_push($feed[$video['category_id']]['videos'], $video);
            }
        }

        // Get target for 2nd part of the sidebar
        $sidebar_second = '';
        if ($feed[2]) {
            $sidebar_second = $feed[2]['section_id'];
        }

        // Meta
        $this->title = $GLOBALS['website']['title'];
        $this->description = $GLOBALS['website']['description'];

        return $this->render('index', ['website' => $GLOBALS['website'], 'banners' => $banners, 'feed' => $feed, 'sidebar_second' => $sidebar_second]);
    }

    public function actionCategory()
    {
        /**
         * @var $db
         */
        require "config/_db.php";

        // Require id
        if (isset($_GET['id']) && $_GET['id']) {
            $id = $_GET['id'];
        } else {
            return $this->actionError(400);
        }

        // Get banners
        $banners = [];
        $q = $db->query("SELECT src FROM banner ORDER BY RAND()");
        foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $row) {
            array_push($banners, $row['src']);
        }

        // Get category
        $q = $db->query("SELECT * FROM category WHERE id = {$id}");
        $category = $q->fetch(PDO::FETCH_ASSOC);

        // Get articles
        $q = $db->prepare("SELECT a.id, a.title, a.content, c.id as category_id, c.name as category_name, GROUP_CONCAT(t.tag) tags FROM article a INNER JOIN article_tag at ON a.id = at.article_id INNER JOIN tag t ON t.id = at.tag_id INNER JOIN category c on a.category_id = c.id WHERE a.category_id = :id GROUP BY a.id ORDER BY a.id desc LIMIT 3");
        $q->execute(['id' => $id]);
        $articles = $q->fetchAll(PDO::FETCH_ASSOC);

        array_walk($articles, function (&$a) use (&$db) {
            // Trim content
            $a['content'] = $this->trimParagraphs($a['content'], 5);

            // Explode tags
            $a['tags'] = explode(',', $a['tags']);
        });

        // Get videos
        $q = $db->prepare("SELECT v.embed_url, c.id as category_id, c.name as category_name FROM video v JOIN category c on v.category_id = c.id WHERE v.category_id = :id LIMIT 4");
        $q->execute(['id' => $id]);
        $videos = $q->fetchAll(PDO::FETCH_ASSOC);

        // Build feed
        $feed = [];

        // Section: Most Read
        $feed['most_read']['section_title'] = 'Most read';
        $feed['most_read']['articles'] = [end($articles)];
        end($videos);
        $feed['most_read']['videos'] = [prev($videos)];
        $feed['most_read']['section_id'] = str_replace(' ', '-', strtolower($feed['most_read']['section_title']));

        // Section: More Articles
        $feed['more_articles']['section_title'] = 'More articles';
        $feed['more_articles']['articles'] = array_slice($articles, 0, -1);
        $feed['more_articles']['videos'] = array_slice($videos, 0, -1);
        $feed['more_articles']['section_id'] = str_replace(' ', '-', strtolower($feed['more_articles']['section_title']));

        // Section: Watch also
        $feed['watch_also']['section_title'] = 'Watch also';
        $feed['watch_also']['articles'] = [];
        $feed['watch_also']['videos'] = [end($videos)];
        $feed['watch_also']['section_id'] = str_replace(' ', '-', strtolower($feed['watch_also']['section_title']));

        // Meta
        $this->title = $category['title'];
        $this->description = $category['description'];

        return $this->render('category', ['category' => $category, 'banners' => $banners, 'feed' => $feed]);
    }

    public function actionArticle()
    {
        /**
         * @var $db
         */
        require "config/_db.php";

        if (isset($_GET['id']) && $_GET['id']) {
            $id = $_GET['id'];
        } else {
            return $this->actionError(400);
        }

        // Redirect random item
        if (isset($_GET['ref']) && $_GET['ref']) {
            $q = $db->query("SELECT * FROM product ORDER BY RAND()");
            $product = $q->fetch(PDO::FETCH_ASSOC);
            header('Location: ' . $product['url']);
        }

        // Get banners
        $banners = [];
        $q = $db->query("SELECT src FROM banner ORDER BY RAND()");
        foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $row) {
            array_push($banners, $row['src']);
        }

        // Get article
        $q = $db->prepare("SELECT a.id, a.title, a.content, c.id as category_id, c.name as category_name, GROUP_CONCAT(t.tag) tags FROM article a INNER JOIN article_tag at ON a.id = at.article_id INNER JOIN tag t ON t.id = at.tag_id INNER JOIN category c on a.category_id = c.id WHERE a.id = :id GROUP BY a.id ORDER BY a.id desc");
        $q->execute(['id' => $id]);
        $article = $q->fetch(PDO::FETCH_ASSOC);
        $article['tags'] = explode(',', $article['tags']);

        // Get products
        $q = $db->prepare("SELECT * FROM product WHERE article_id = :article_id");
        $q->execute(['article_id' => $article['id']]);
        $products = $q->fetchAll(PDO::FETCH_ASSOC);

        // Get next article
        $q = $db->query("SELECT a.id, a.title, a.content, c.id as category_id, c.name as category_name, GROUP_CONCAT(t.tag) tags FROM article a INNER JOIN article_tag at ON a.id = at.article_id INNER JOIN tag t ON t.id = at.tag_id INNER JOIN category c on a.category_id = c.id GROUP BY a.id ORDER BY RAND() LIMIT 1");
        $article_next = $q->fetch(PDO::FETCH_ASSOC);
        $article_next['content'] = substr($article_next['content'], 0, 130);
        $article_next['tags'] = explode(',', $article_next['tags']);
        // Hide products
        $article_next['content'] = preg_replace('/{{.*?}}/', '', $article_next['content']);
        $article_next['content'] = preg_replace('/<p[^>]*><\\/p[^>]*>/', '', $article_next['content']);

        // Get video
        $q = $db->query("SELECT * FROM video ORDER BY RAND() LIMIT 1");
        $video = $q->fetch(PDO::FETCH_ASSOC);

        // Meta
        $this->title = $article['title'];
        $this->description = explode('</p><p>', $article['content'])[0];

        return $this->render('article', ['banners' => $banners, 'article' => $article, 'article_next' => $article_next, 'video' => $video, 'products' => $products]);
    }

    public function actionSearch()
    {
        /**
         * @var $db
         */
        require "config/_db.php";

        // Validate tag
        if (!isset($_GET['tag'])) {
            return $this->actionError(400);
        }
        $s = strtolower($_GET['tag']);


        // Get articles
        $q = $db->query("SELECT a.id, a.title, a.content, c.id as category_id, c.name as category_name, GROUP_CONCAT(t.tag) tags FROM article a INNER JOIN article_tag at ON a.id = at.article_id INNER JOIN tag t ON t.id = at.tag_id INNER JOIN category c on a.category_id = c.id GROUP BY a.id ORDER BY a.id DESC");
        $articles = $q->fetchAll(PDO::FETCH_ASSOC);

        array_walk($articles, function (&$a, $key) use (&$articles, $s) {
            // Hide products
            $a['content'] = preg_replace('/{{.*?}}/', '', $a['content']);
            $a['content'] = preg_replace('/<p[^>]*><\\/p[^>]*>/', '', $a['content']);

            // Trim content
            $a['content'] = substr($a['content'], 0, 130);
            $a['tags'] = explode(',', $a['tags']);

            // Filter by GET tag
            if (!in_array($s, $a['tags'])) {
                unset($articles[$key]);
            }
        });

        // Get videos
        $q = $db->prepare("SELECT embed_url FROM video_tag vt JOIN video v on vt.video_id = v.id JOIN tag t on vt.tag_id = t.id WHERE t.tag = :tag GROUP BY v.id ORDER BY v.id DESC");
        $q->execute(['tag' => $_GET['tag']]);
        $videos = $q->fetchAll(PDO::FETCH_ASSOC);

        // Get banners
        $banners = [];
        $q = $db->query("SELECT src FROM banner ORDER BY RAND() LIMIT 2");
        foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $row) {
            array_push($banners, $row['src']);
        }

        // Meta
        $this->title = ucfirst($s) . ' – Search';

        return $this->render('search', ['s' => $s, 'banners' => $banners, 'articles' => $articles, 'videos' => $videos]);
    }

    public function actionBlog()
    {
        /**
         * @var $db
         */
        require "config/_db.php";

        // Get banners
        $banners = [];
        $q = $db->query("SELECT src FROM banner ORDER BY RAND()");
        foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $row) {
            array_push($banners, $row['src']);
        }

        // Get articles
        $q = $db->query("SELECT a.id, a.title, a.content, c.id as category_id, c.name as category_name, GROUP_CONCAT(t.tag) tags FROM article a INNER JOIN article_tag at ON a.id = at.article_id INNER JOIN tag t ON t.id = at.tag_id INNER JOIN category c on a.category_id = c.id GROUP BY a.id ORDER BY a.id DESC");
        $articles = $q->fetchAll(PDO::FETCH_ASSOC);

        array_walk($articles, function (&$a) {
            // Hide products
            $a['content'] = preg_replace('/{{.*?}}/', '', $a['content']);
            $a['content'] = preg_replace('/<p[^>]*><\\/p[^>]*>/', '', $a['content']);

            // Trim content
            $a['content'] = substr($a['content'], 0, 130);
            $a['tags'] = explode(',', $a['tags']);
        });

        // Get category count
        $q = $db->query("SELECT COUNT(*) FROM category");
        $category_count = $q->fetch(PDO::FETCH_NUM)[0];

        // Get videos
        $q = $db->prepare("SELECT v.embed_url, c.id as category_id, c.name as category_name FROM video v JOIN category c on v.category_id = c.id LIMIT :limit");
        $q->bindValue('limit', $category_count * 2, PDO::PARAM_INT);
        $q->execute();
        $videos = $q->fetchAll(PDO::FETCH_ASSOC);

        // Build feed
        $feed = [];

        // Section: Blog Videos
        $feed['blog_videos']['section_title'] = 'Videos';
        $feed['blog_videos']['articles'] = [];
        foreach ($videos as $video) {
            if (!isset($feed['videos'][$video['category_id']])) {
                $feed['videos'][$video['category_id']] = [];
            }
            array_push($feed['videos'][$video['category_id']], $video);
        }
        $feed['blog_videos']['section_id'] = str_replace(' ', '-', strtolower($feed['blog_videos']['section_title']));

        // Meta
        $this->title = 'Our Blog – Review Articles';

        return $this->render('blog', ['banners' => $banners, 'articles' => $articles, 'videos' => $feed['videos']]);
    }

    public function actionPrivacyPolicy()
    {
        $email = $GLOBALS['website']['email']; // Get website info here and pass email as prop

        // Meta
        $this->title = 'Privacy Policy';

        return $this->render('privacy-policy', ['email' => $email]);
    }

    public function actionCookiePolicy()
    {
        $email = $GLOBALS['website']['email'];
        $GLOBALS['website']['name'] = explode('.', $_SERVER['HTTP_HOST'])[0];

        // Meta
        $this->title = 'Cookie Policy';

        return $this->render('cookie-policy', ['email' => $email, 'site_name' => $GLOBALS['website']['name']]);
    }

    public function actionTermsOfUse()
    {
        // Meta
        $this->title = 'Terms of use';

        return $this->render('terms-of-use');
    }

    public function actionDisclaimer()
    {
        // Meta
        $this->title = 'Disclaimer';

        return $this->render('disclaimer');
    }

    public function actionContacts()
    {
        /**
         * @var $db
         */
        require "config/_db.php";

        // PHPMailer
        $reply_email = '';
        if ($_POST) {
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->SMTPAuth = true;
            $mail->Username = 'khasan.shadiyarov@gmail.com';
            $mail->Password = 'affiliate1';
            $mail->setFrom('khasan.shadiyarov@gmail.com');
            $mail->addReplyTo($_POST['email']);
            $mail->addAddress('khasan.shadiyarov@gmail.com', ucfirst($GLOBALS['website']['name']));
            $mail->Subject = 'Message from ' . $GLOBALS['website']['name'] . '.com - Contact Form';
            $mail->msgHTML($_POST['message'] . '<br> Please reply: <a href="mailto:' . $_POST['email'] . '">' . $_POST['email']);
            $mail->AltBody = $_POST['message'];
            $mail->addBCC('sabohiddin07@gmail.com');
            $mail->SMTPDebug = 0;

            if ($mail->send()) {
                $reply_email = $_POST['email'];
            } else {
                echo $mail->ErrorInfo;
            }
        }

        // Meta
        $this->title = 'Contacts';


        return $this->render('contacts', ['email' => $GLOBALS['website']['email'], 'reply_email' => $reply_email]);
    }
}


