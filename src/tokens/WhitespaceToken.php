<?php
    namespace tokenizer\tokens;
    
    require_once(__DIR__ . "/RegexToken.php");

    class WhitespaceToken extends RegexToken {
        public function __construct(string $name) {            
            parent::__construct($name, '\s+');
        }
    }
?>