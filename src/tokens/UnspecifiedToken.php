<?php
    namespace tokenizer\tokens;

    require_once(__DIR__ . "/IToken.php");
    require_once(__DIR__ . "/AbstractToken.php");

    class UnspecifiedToken extends AbstractToken {
        public function __construct(string $name = "UnspecifiedToken") {
            parent::__construct($name);
        }
    	/**
         *
         * @param string $str
         *
         * @return IToken|null
         */
        public function Tokenize(string $str): ?IToken {
            $newToken = clone($this);
            $newToken->value = $str;

            return $newToken;
        }
    }
?>