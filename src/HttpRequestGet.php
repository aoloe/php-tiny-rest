<?php
namespace Aoloe\TinyRest;

class HttpRequestGet implements HttpRequestInterface {
    public function is_get() {
        return true;
    }

    public function is_post() {
        return false;
    }

    public function has($key) {
        return array_key_exists($key, $_GET);
    }

    public function get($key) {
        return array_key_exists($key, $_GET) ? $_GET[$key] : null;
    }
}
