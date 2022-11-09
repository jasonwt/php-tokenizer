<?php
    namespace tokenizer;

    require_once(__DIR__ . "/tokens/IToken.php");
    require_once(__DIR__ . "/tokens/ParentToken.php");

    use tokenizer\tokens\ParentToken;
    use tokenizer\tokens\IToken;

    interface ITokenizer {
        public function RegisterToken(IToken $token, int $priority = 0): bool;
//
        public function UnregisterToken(string $name): bool;
//
        public function Tokenize(string $str, ?ParentToken $parentToken = null) : ParentToken;
    }

?>