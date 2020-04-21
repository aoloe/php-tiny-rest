<?php
namespace Aoloe\TinyRest;

class HttpRequest {
    public static function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return new HttpRequestGet();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return new HttpRequestPost();
        } else {
            // TODO: return a HTTPReqeuestInvalid
        }   

    }
    public function is_request($key) {
        return array_key_exists($key, $_REQUEST);
    }

    public function is_method_get() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public function is_method_post() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function get_get() {
    }

    public function get_post() {
    }
}
