<?php
namespace Aoloe\TinyRest;

class HttpResponse {
    public function respond($response) {
        header('Content-Type: application/json');
        echo(json_encode($response));
    }
}
