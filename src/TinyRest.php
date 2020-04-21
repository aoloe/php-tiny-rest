<?php
namespace Aoloe\TinyRest;

class App {
    private $action = 'action';
    private $actions = ['post'=> [], 'get' => []];
    public $error_message = '';

    public function post($action, $function) {
        $this->actions['post'][$action] = $function;
    }
    public function get($action, $function) {
        $this->actions['get'][$action] = $function;
    }

    public function run($request) {
        if (!$request->has($this->action)) {
            $this->error_message = 'no action specified';
            return false;
        }

        $action = $request->get($this->action);
        if (is_null($action)) {
            $this->error_message = 'no action defined';
            return false;
        }

        $actions = null;
        if ($request->is_post()) {
            $actions = $this->actions['post'];
        } else if ($request->is_get()) {
            $actions = $this->actions['get'];
        }

        if (is_null($actions)) {
            $this->error_message = 'invalid method '.$request->get_method();
            return false;
        }

        if (array_key_exists($action, $actions)) {
            $actions[$action]();
            return true;
        } else {
            $this->error_message = 'invalid '.$return->get_method().' action '.$action;
            return false;
        }
    }
}

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

    public function get_method() {
        return $_SERVER['REQUEST_METHOD'];
    }
}

interface HttpRequestInterface {
    public function is_get();
    public function is_post();
    public function has($key);
    public function get($key);
}

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

class HttpResponse {
    public function respond($response) {
        header('Content-Type: application/json');
        echo(json_encode($response));
    }
}
