<?php
namespace Aoloe\TinyRest;

class App {
    private $action = 'action';
    private $actions = ['post'=> [], 'get' => []];
    public $error_message = '';

    public function post($action, $function) {
        $this->actions['post'][$action] = $function;
    }

    public function run($request) {
        if (!$request->has($this->action)) {
            $this->error_message = 'no action specified';
            return false;
        }

        if ($request->is_post()) {
            $action = $request->get($this->action);
            if (isset($action) && array_key_exists($action, $this->actions['post'])) {
                $this->actions['post'][$action]();
                return true;
            } else {
                $this->error_message = 'invalid post action';
                return false;
            }
        }
        if ($request->is_get()) {
            $this->error_message = 'invalid get action';
            return false;
        }

        $this->error_message = 'invalid request.';
        return false;
    }
}
