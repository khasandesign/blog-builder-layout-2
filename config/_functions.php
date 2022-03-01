<?php
/**
 * Get component by its path and pass props
 * @param $path
 * @param array $props
 * @return false|string
 */
function component($path, array $props = [])
{
    require "config/_params.php";

    ob_start();
    if ($props) {
        foreach ($props as $key => $prop) {
            ${$key} = $prop;
        }
    }
    include 'components/' . $path . '.php';
    return ob_get_clean();
}

/**
 * Check if table/tables exist in the connected DB
 * @param array|string $table - Leave empty to check all tables
 * @return false|void
 */
function isTableEmpty($table = false)
{
    /**
     * @var $db
     */
    require "config/_db.php";

    // Get all tables
    if (!$table) {
        // Get DB name
        $database = $db->query('select database()')->fetchColumn();
        $q = $db->prepare("select table_name from information_schema.tables WHERE table_schema = :database");
        $q->execute(['database' => $database]);
        $table = $q->fetchAll(PDO::FETCH_NUM);

        foreach ($table as $key => $tb) {
            $table[$key] = $tb[0];
        }
    }

    // Check tables
    if (is_array($table)) {
        foreach ($table as $tb) {
            $q = $db->query("SELECT COUNT(1) FROM " . $tb);
            $existance = $q->fetch(PDO::FETCH_NUM)[0];
            if (!$existance) {
                echo 'Table <strong>' . $tb . '</strong> is empty' . '<br>';
                return false;
            }
        }
    } else {
        $q = $db->query("SELECT COUNT(1) FROM " . $table);
        $existance = $q->fetch(PDO::FETCH_ASSOC)[0];
        if (!$existance)
            return false;
    }

    return true;
}

/**
 * Save symbol SVG code from DB as SVG file
 */
function updateFavicon() {
    file_put_contents('./assets/images/symbol.svg', $GLOBALS['website']['symbol']);
}

/**
 * Get all tags as string from DB
 * @return string
 */
function getAllTags() {
    /**
     * @var $db
     */
    require "config/_db.php";

    $q = $db->query("SELECT tag FROM tag");
    $tags = $q->fetchAll(PDO::FETCH_NUM);
    $tags_str = '';
    array_walk($tags, function (&$t, $key) use (&$tags_str) {
        $tags_str .= $t[0] . ',';
    });

    return substr($tags_str, 0, -1);
}

/**
 * Get full URL of the current website
 * @return string
 */
function getFullUrl() {
    return $_SERVER['REQUEST_URI'] != '/' ? 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : 'https://' . $_SERVER['SERVER_NAME'];
}