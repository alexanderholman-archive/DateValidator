<?php

class Result {

    /**
     * @var string
     */
    private $message = '';

    /**
     * @var bool
     */
    private $valid = false;

    /**
     * @param $message
     */
    public function setMessage($message) {
        if (!is_string($message)) {
            return false;
        }
        $this->message = $message;
        return true;
    }

    /**
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    public function setValidity($validity) {
        if (!is_bool($validity)) {
            return false;
        }
        $this->valid = $validity;
        return true;
    }

    public function isValid() {
        return $this->valid;
    }

}