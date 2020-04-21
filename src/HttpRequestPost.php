<?php
namespace Aoloe\TinyRest;

class HttpRequestPost implements HttpRequestInterface {
    public function __construct() {
        // post variables sent by axios are json in the body
        if (empty($_POST)) {
            $body = file_get_contents('php://input');

            if (!empty($body)) {
                $data = json_decode($body, true);
                if (!json_last_error()) {
                    $_POST = $data;
                }
            }
        }
    }

    public function is_get() {
        return false;
    }

    public function is_post() {
        return true;
    }
    public function has($key) {
        return array_key_exists($key, $_POST);
    }
    public function get($key) {
        return array_key_exists($key, $_POST) ? $_POST[$key] : null;
    }
}
