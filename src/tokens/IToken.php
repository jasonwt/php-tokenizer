<?php
    namespace tokenizer\tokens;

    interface IToken {
        public function Name() : string;
        
        public function Tokenize(string $str) : ?IToken;

        public function ParsedString() : string;

        public function Value() : string;
    }
?>