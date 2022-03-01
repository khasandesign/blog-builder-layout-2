<?php
/**
 * Base queries provide data, which is used on every page
 * E.g. Categories
 */

class Base {
    public $category;
    public $website;

    public function __construct()
    {
        /**
         * @var $db
         */
        require "config/_db.php";

        $obj_vars = get_object_vars($this);
        foreach ($obj_vars as $key => $var) {
            $q = $db->query("SELECT * FROM {$key}");
            $r = $q->fetchAll(PDO::FETCH_ASSOC);
            $this->$key = $r;
        }
    }
}

$GLOBALS['base'] = new Base();