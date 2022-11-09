<?php
    namespace tokenizer\tokens;

    require_once(__DIR__ . "/RegexToken.php");

    class AlphaNumericToken extends RegexToken {
        public function __construct(string $name) {            
            parent::__construct($name, '[A-Za-z0-9]+');
        }
    }
?>