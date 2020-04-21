<?php
namespace Aoloe\TinyRest;

interface HttpRequestInterface {
    public function is_get();
    public function is_post();
    public function has($key);
    public function get($key);
}

