<?php
function find_all($table) {
    global $conn;
    return $conn->query("SELECT * FROM {$table}");
}

function remove_junk($str) {
    return htmlentities($str, ENT_QUOTES, 'UTF-8');
}

function validate_fields($fields) {
    global $errors;
    foreach ($fields as $field) {
        if (empty($_POST[$field])) {
            $errors = "Field '{$field}' cannot be blank.";
        }
    }
}

function count_id() {
    static $count = 1;
    return $count++;
}

function redirect($url, $permanent = false) {
    if (headers_sent() === false) {
        header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }
    exit();
}

function display_msg($msg) {
    if (!empty($msg)) {
        return '<div class="alert alert-info">' . $msg . '</div>';
    }
}
