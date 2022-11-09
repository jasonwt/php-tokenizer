<?php
    namespace tokenizer\tokens;

    require_once(__DIR__ . "/UnspecifiedToken.php");
    require_once(__DIR__ . "/ParentToken.php");

    class TokenizerToken extends ParentToken {
        public function __construct(string $name = "TOKENIZERTOKEN") {
            parent::__construct($name, "", "");
        }

        public function EndToken(string $str) : bool {
            if ($str != "")
                return false;

            if (strlen($this->noTokenString) > 0) {
                $this->children[] = (new UnspecifiedToken())->Tokenize($this->noTokenString);
                $this->noTokenString = "";
            }
            
            return true;
        }
    }


?>