<?php
class Validator {
    private static $instance = null;
    private $errors = [];
    private $rules = [];
    private $messages = [];

    private function __construct() {
        $this->initMessages();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function initMessages() {
        $this->messages = [
            'required' => ':field 不能为空',
            'email' => ':field 必须是有效的邮箱地址',
            'url' => ':field 必须是有效的URL地址',
            'ip' => ':field 必须是有效的IP地址',
            'numeric' => ':field 必须是数字',
            'integer' => ':field 必须是整数',
            'float' => ':field 必须是浮点数',
            'min' => ':field 不能小于 :min',
            'max' => ':field 不能大于 :max',
            'between' => ':field 必须在 :min 和 :max 之间',
            'length' => ':field 长度必须是 :length',
            'min_length' => ':field 长度不能小于 :min',
            'max_length' => ':field 长度不能大于 :max',
            'between_length' => ':field 长度必须在 :min 和 :max 之间',
            'in' => ':field 必须是以下值之一: :values',
            'not_in' => ':field 不能是以下值之一: :values',
            'match' => ':field 必须与 :field2 相同',
            'regex' => ':field 格式不正确',
            'date' => ':field 必须是有效的日期',
            'date_format' => ':field 必须符合格式 :format',
            'before' => ':field 必须在 :date 之前',
            'after' => ':field 必须在 :date 之后',
            'alpha' => ':field 只能包含字母',
            'alpha_num' => ':field 只能包含字母和数字',
            'alpha_dash' => ':field 只能包含字母、数字、下划线和破折号',
            'phone' => ':field 必须是有效的手机号码',
            'id_card' => ':field 必须是有效的身份证号码',
            'postal_code' => ':field 必须是有效的邮政编码',
            'credit_card' => ':field 必须是有效的信用卡号'
        ];
    }

    public function validate($data, $rules) {
        $this->errors = [];
        $this->rules = $rules;

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            $this->validateField($field, $value, $fieldRules);
        }

        return empty($this->errors);
    }

    private function validateField($field, $value, $rules) {
        $rules = is_array($rules) ? $rules : explode('|', $rules);

        foreach ($rules as $rule) {
            $params = [];
            if (strpos($rule, ':') !== false) {
                list($rule, $paramStr) = explode(':', $rule, 2);
                $params = explode(',', $paramStr);
            }

            $method = 'validate' . str_replace('_', '', ucwords($rule, '_'));
            if (method_exists($this, $method)) {
                if (!$this->$method($value, $params)) {
                    $this->addError($field, $rule, $params);
                }
            }
        }
    }

    private function addError($field, $rule, $params = []) {
        $message = $this->messages[$rule] ?? ':field 验证失败';
        $message = str_replace(':field', $field, $message);
        
        foreach ($params as $key => $value) {
            $message = str_replace(':' . ($key + 1), $value, $message);
        }
        
        $this->errors[$field][] = $message;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getFirstError() {
        foreach ($this->errors as $field => $errors) {
            return $errors[0];
        }
        return null;
    }

    private function validateRequired($value) {
        return !empty($value) || $value === '0' || $value === 0;
    }

    private function validateEmail($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function validateUrl($value) {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    private function validateIp($value) {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    private function validateNumeric($value) {
        return is_numeric($value);
    }

    private function validateInteger($value) {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    private function validateFloat($value) {
        return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
    }

    private function validateMin($value, $params) {
        return $value >= $params[0];
    }

    private function validateMax($value, $params) {
        return $value <= $params[0];
    }

    private function validateBetween($value, $params) {
        return $value >= $params[0] && $value <= $params[1];
    }

    private function validateLength($value, $params) {
        return mb_strlen($value) == $params[0];
    }

    private function validateMinLength($value, $params) {
        return mb_strlen($value) >= $params[0];
    }

    private function validateMaxLength($value, $params) {
        return mb_strlen($value) <= $params[0];
    }

    private function validateBetweenLength($value, $params) {
        $length = mb_strlen($value);
        return $length >= $params[0] && $length <= $params[1];
    }

    private function validateIn($value, $params) {
        return in_array($value, $params);
    }

    private function validateNotIn($value, $params) {
        return !in_array($value, $params);
    }

    private function validateMatch($value, $params) {
        return $value === $params[0];
    }

    private function validateRegex($value, $params) {
        return preg_match($params[0], $value) === 1;
    }

    private function validateDate($value) {
        return strtotime($value) !== false;
    }

    private function validateDateFormat($value, $params) {
        $date = DateTime::createFromFormat($params[0], $value);
        return $date && $date->format($params[0]) === $value;
    }

    private function validateBefore($value, $params) {
        return strtotime($value) < strtotime($params[0]);
    }

    private function validateAfter($value, $params) {
        return strtotime($value) > strtotime($params[0]);
    }

    private function validateAlpha($value) {
        return preg_match('/^[a-zA-Z]+$/', $value) === 1;
    }

    private function validateAlphaNum($value) {
        return preg_match('/^[a-zA-Z0-9]+$/', $value) === 1;
    }

    private function validateAlphaDash($value) {
        return preg_match('/^[a-zA-Z0-9_-]+$/', $value) === 1;
    }

    private function validatePhone($value) {
        return preg_match('/^1[3-9]\d{9}$/', $value) === 1;
    }

    private function validateIdCard($value) {
        return preg_match('/^[1-9]\d{5}(19|20)\d{2}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])\d{3}[\dX]$/', $value) === 1;
    }

    private function validatePostalCode($value) {
        return preg_match('/^[1-9]\d{5}$/', $value) === 1;
    }

    private function validateCreditCard($value) {
        $value = str_replace([' ', '-'], '', $value);
        $sum = 0;
        $length = strlen($value);
        
        for ($i = 0; $i < $length; $i++) {
            $digit = (int) $value[$length - 1 - $i];
            if ($i % 2 == 1) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }
        
        return $sum % 10 == 0;
    }
} 