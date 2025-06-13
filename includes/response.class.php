<?php
class Response {
    private static $instance = null;
    private $headers = [];
    private $statusCode = 200;
    private $contentType = 'text/html';
    private $charset = 'UTF-8';
    private $data = null;
    private $format = 'html';

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setHeader($name, $value) {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setStatusCode($code) {
        $this->statusCode = $code;
        return $this;
    }

    public function setContentType($type) {
        $this->contentType = $type;
        return $this;
    }

    public function setCharset($charset) {
        $this->charset = $charset;
        return $this;
    }

    public function setFormat($format) {
        $this->format = $format;
        return $this;
    }

    public function json($data, $code = 200) {
        $this->setContentType('application/json')
             ->setStatusCode($code)
             ->setFormat('json')
             ->setData($data);
        return $this;
    }

    public function xml($data, $code = 200) {
        $this->setContentType('application/xml')
             ->setStatusCode($code)
             ->setFormat('xml')
             ->setData($data);
        return $this;
    }

    public function html($data, $code = 200) {
        $this->setContentType('text/html')
             ->setStatusCode($code)
             ->setFormat('html')
             ->setData($data);
        return $this;
    }

    public function text($data, $code = 200) {
        $this->setContentType('text/plain')
             ->setStatusCode($code)
             ->setFormat('text')
             ->setData($data);
        return $this;
    }

    public function download($file, $filename = null) {
        if (!file_exists($file)) {
            throw new Exception('File not found');
        }

        $filename = $filename ?? basename($file);
        $mimeType = $this->getMimeType($file);

        $this->setHeader('Content-Type', $mimeType)
             ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
             ->setHeader('Content-Length', filesize($file))
             ->setHeader('Cache-Control', 'no-cache, must-revalidate')
             ->setHeader('Pragma', 'no-cache')
             ->setHeader('Expires', '0');

        $this->data = file_get_contents($file);
        return $this;
    }

    public function redirect($url, $code = 302) {
        $this->setHeader('Location', $url)
             ->setStatusCode($code);
        return $this;
    }

    public function setData($data) {
        $this->data = $data;
        return $this;
    }

    public function success($data = null, $message = 'success') {
        return $this->json([
            'code' => 0,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function error($message = 'error', $code = 1, $data = null) {
        return $this->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], 400);
    }

    public function notFound($message = 'Not Found') {
        return $this->error($message, 404, null)->setStatusCode(404);
    }

    public function forbidden($message = 'Forbidden') {
        return $this->error($message, 403, null)->setStatusCode(403);
    }

    public function unauthorized($message = 'Unauthorized') {
        return $this->error($message, 401, null)->setStatusCode(401);
    }

    public function send() {
        http_response_code($this->statusCode);

        $this->setHeader('Content-Type', $this->contentType . '; charset=' . $this->charset);

        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }

        if ($this->data !== null) {
            switch ($this->format) {
                case 'json':
                    echo json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    break;
                case 'xml':
                    $util = Util::getInstance();
                    echo $util->arrayToXml($this->data);
                    break;
                default:
                    echo $this->data;
            }
        }

        exit;
    }

    private function getMimeType($file) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file);
        finfo_close($finfo);
        return $mimeType;
    }
} 