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
            $this->error_message = 'invalid method '.HttpRequest::get_method();
            return false;
        }

        if (array_key_exists($action, $actions)) {
            $actions[$action]();
            return true;
        } else {
            $this->error_message = 'invalid '.HttpRequest::get_method().' action '.$action;
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

    public static function is_request($key) {
        return array_key_exists($key, $_REQUEST);
    }

    public static function is_method_get() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public static function is_method_post() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function get_method() {
        return $_SERVER['REQUEST_METHOD'];
    }
}

abstract class HttpRequestAbstract {
    protected $data = [];
    public function is_get() {return false;}
    public function is_post() {return false;}
    public function has($key) {
        return array_key_exists($key, $this->data);
    }
    public function get($key) {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }
}

class HttpRequestGet extends HttpRequestAbstract {
    public function __construct() {
        $this->data = $_GET;
    }
    public function is_get() {
        return true;
    }
}

class HttpRequestPost extends HttpRequestAbstract {
    public function __construct() {
        // post variables sent by axios are json in the body
        if (empty($_POST)) {
            $body = file_get_contents('php://input');

            if (!empty($body)) {
                $data = json_decode($body, true);
                if (!json_last_error()) {
                    $this->data = $data;
                }
            }
        } else {
            $this->data = $_POST;
        }
    }

    public function is_post() {
        return true;
    }
}

class HttpResponse {
    public function respond($response) {
        header('Content-Type: application/json');
        echo(json_encode($response));
    }
}
