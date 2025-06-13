<?php
class Request {
    private static $instance = null;
    private $get = [];
    private $post = [];
    private $files = [];
    private $server = [];
    private $headers = [];
    private $cookies = [];
    private $input = null;
    private $security = null;

    private function __construct() {
        $this->security = Security::getInstance();
        $this->init();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function init() {
        $this->get = $this->security->xssClean($_GET);
        $this->post = $this->security->xssClean($_POST);
        $this->files = $_FILES;
        $this->server = $_SERVER;
        $this->cookies = $_COOKIE;
        $this->headers = $this->getAllHeaders();
        $this->input = file_get_contents('php://input');
    }

    private function getAllHeaders() {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    public function get($key = null, $default = null) {
        if ($key === null) {
            return $this->get;
        }
        return $this->get[$key] ?? $default;
    }

    public function post($key = null, $default = null) {
        if ($key === null) {
            return $this->post;
        }
        return $this->post[$key] ?? $default;
    }

    public function file($key = null) {
        if ($key === null) {
            return $this->files;
        }
        return $this->files[$key] ?? null;
    }

    public function server($key = null, $default = null) {
        if ($key === null) {
            return $this->server;
        }
        return $this->server[$key] ?? $default;
    }

    public function header($key = null, $default = null) {
        if ($key === null) {
            return $this->headers;
        }
        return $this->headers[$key] ?? $default;
    }

    public function cookie($key = null, $default = null) {
        if ($key === null) {
            return $this->cookies;
        }
        return $this->cookies[$key] ?? $default;
    }

    public function input() {
        return $this->input;
    }

    public function json() {
        return json_decode($this->input, true);
    }

    public function xml() {
        $util = Util::getInstance();
        return $util->xmlToArray($this->input);
    }

    public function method() {
        return $this->server('REQUEST_METHOD');
    }

    public function isGet() {
        return $this->method() === 'GET';
    }

    public function isPost() {
        return $this->method() === 'POST';
    }

    public function isPut() {
        return $this->method() === 'PUT';
    }

    public function isDelete() {
        return $this->method() === 'DELETE';
    }

    public function isAjax() {
        return $this->header('X-Requested-With') === 'XMLHttpRequest';
    }

    public function isJson() {
        return strpos($this->header('Content-Type'), 'application/json') !== false;
    }

    public function isXml() {
        return strpos($this->header('Content-Type'), 'application/xml') !== false;
    }

    public function ip() {
        return $this->security->getClientIp();
    }

    public function userAgent() {
        return $this->server('HTTP_USER_AGENT');
    }

    public function referer() {
        return $this->server('HTTP_REFERER');
    }

    public function uri() {
        return $this->server('REQUEST_URI');
    }

    public function path() {
        return parse_url($this->uri(), PHP_URL_PATH);
    }

    public function query() {
        return parse_url($this->uri(), PHP_URL_QUERY);
    }

    public function host() {
        return $this->server('HTTP_HOST');
    }

    public function scheme() {
        return $this->server('HTTPS') === 'on' ? 'https' : 'http';
    }

    public function url() {
        return $this->scheme() . '://' . $this->host() . $this->uri();
    }

    public function baseUrl() {
        return $this->scheme() . '://' . $this->host();
    }

    public function has($key) {
        return isset($this->get[$key]) || isset($this->post[$key]);
    }

    public function hasFile($key) {
        return isset($this->files[$key]);
    }

    public function hasHeader($key) {
        return isset($this->headers[$key]);
    }

    public function hasCookie($key) {
        return isset($this->cookies[$key]);
    }

    public function validate($rules) {
        $validator = Validator::getInstance();
        $data = array_merge($this->get, $this->post);
        return $validator->validate($data, $rules);
    }

    public function getErrors() {
        $validator = Validator::getInstance();
        return $validator->getErrors();
    }

    public function getFirstError() {
        $validator = Validator::getInstance();
        return $validator->getFirstError();
    }
} 