<?php

class Controller
{
    public $page;

    public function __construct()
    {
        $this->page = isset($_GET['page']) ? $_GET['page'] : null;
    }

    /**
     * Render returned action file by its name
     * @param $view
     * @param array $props
     * @return false|string
     */
    public function render($view, array $props = [])
    {
        ob_start();
        foreach ($props as $key => $prop) {
            $this->$key = $prop;
        }
        require("views/" . lcfirst(get_class($this)) . '/' . $view . '.php');
        return ob_get_clean();
    }

    /** Get action according to the current page
     * @return string
     */
    public function getAction() {
        return 'action' . str_replace(' ', '', ucwords(str_replace('-', ' ', $this->page)));
    }

    /**
     * Call required action method
     * @return mixed
     */
    public function getContent()
    {
        require "config/_params.php";

        $action = $this->getAction();
        if (method_exists($this, $action)) {
            return $this->$action();
        } else {
            return $this->actionError(404);
        }
    }

    /**
     * Error displaying action
     * @param $code
     * @return false|string
     */
    public function actionError($code)
    {
        /**
         * @var $db
         */
        require "config/_db.php";

        $title = '';
        $message = '';
        if ($code === 404) {
            $title = 'Page Not Found';
            $message = 'Sorry, this page doesn’t exist <br> or it was moved.';
        } else if ($code === 400) {
            $title = 'Bad request';
            $message = 'Link is incorrect or required parameters <br> missed. Please check it again.';
        } else {
            $title = 'Reconstruction';
            $message = 'Website is under reconstruction working <br> right now, we will finish soon.';
        }

        // Get tags
        $q = $db->query("SELECT * FROM tag");
        $tags = $q->fetchAll(PDO::FETCH_ASSOC);

        return $this->render('error', ['code' => $code, 'title' => $title, 'message' => $message, 'tags' => $tags]);
    }

    /**
     * @param $content string
     * @param $count number How many paragraphs to show
     * @param $tag
     */
    public function trimParagraphs($content, $count, $tag = 'p') {
        $dom = new DOMDocument();
        $paragraphs = array();
        $dom->loadHTML($content);
        foreach($dom->getElementsByTagName($tag) as $node)
        {
            $paragraphs[] = $dom->saveHTML($node);
        }
        $paragraphs = array_splice($paragraphs, 0, $count);
        return implode($paragraphs);
    }

    /**
     * Extract text-in IDs and replace them with HTML template filled of data
     * @param $text
     * @param $wrap_pattern - Pattern for determining IDs' wraps - E.g <strong>/{{.*}}/</strong> for {{ID}}
     * @param $db
     * @return string
     */
    public function insertProduct($text, $wrap_pattern, $db) {
        $q_product = $db->prepare("SELECT * FROM product WHERE id = :id");

        $dom = new DOMDocument();
        $paragraphs = array();
        $tag = 'p';
        $dom->loadHTML($text);
        foreach($dom->getElementsByTagName($tag) as $node)
        {
            $par = $dom->saveHTML($node);
            if (preg_match($wrap_pattern, $par)) {
                $id = (int) filter_var($par, FILTER_SANITIZE_NUMBER_INT);
                $q_product->execute(['id' => $id]);
                $product = $q_product->fetch(PDO::FETCH_ASSOC);
                if ($product) {
                    $par = '<div class="picked-item-card">
                <div class="item-arrow"></div>
                <div class="item-content">
                  <div class="item-overlay"></div>
                  <div class="item-info">
                    <a target="_blank" class="tag active">Our pick</a>
                    <a href="' . $product['url'] . '" target="_blank">
                      <h6 class="item-title">' . $product['name'] . '</h6>
                    </a>
                    <a href="' . $product['url'] . '" target="_blank">
                      <p class="item-brand">' . $product['brand'] . '</p>
                    </a>
                    <p class="item-description">' . $product['description'] . '</p>
                  </div>
                  <div class="item-image">
                    ' . $product['image'] . '
                  </div>
                </div>
              </div>';
                }
            }
            $paragraphs[] = $par;
        }
        // Hide not matched anchors
        $result = preg_replace('/{{.*?}}/', '', implode($paragraphs));
        return preg_replace('/<p[^>]*><\\/p[^>]*>/', '', $result);
    }
}