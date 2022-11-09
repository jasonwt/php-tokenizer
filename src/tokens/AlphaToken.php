<?php
    namespace tokenizer\tokens;

    require_once(__DIR__ . "/RegexToken.php");

    class AlphaToken extends RegexToken {
        public function __construct(string $name) {            
            parent::__construct($name, '\b[a-zA-z]+\b');
        }
    }
?>