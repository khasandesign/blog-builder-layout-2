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
    public function trimParagraphs($content, $count, $tag = 'p')
    {
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
}