<?php
    namespace tokenizer\tokens;

    require_once(__DIR__ . "/IToken.php");
    require_once(__DIR__ . "/AbstractToken.php");

    use tokenizer\tokens\IToken;
    use tokenizer\tokens\AbstractToken;

    class RegexToken extends AbstractToken {
        protected array $regexPaterns = [];
        
        public function __construct(string $name, $regexPaterns) {
            parent::__construct($name);

            if (!is_array($regexPaterns)) 
                $regexPaterns = [$regexPaterns];

            $this->regexPaterns = $regexPaterns;
        }

        public function Tokenize(string $str): ?IToken { 

            for ($rpcnt = 0; $rpcnt < count($this->regexPaterns); $rpcnt ++) {
                $regexPattern = $this->regexPaterns[$rpcnt];

                $matches = [];            

//                echo "pattern: ~" . $regexPattern . "~" . "\n";

                if ((preg_match("~^" . $regexPattern . "~", $str, $matches)) === false)
                    continue;

                if (count($matches) == 0)
                    continue;

                break;
            }

            if ($rpcnt == count($this->regexPaterns))
                return null;

            $returnValue = clone($this);

            $returnValue->value = $matches[0];

//            echo "matched pattern: " . $regexPattern . "\n";

            return $returnValue;
        }
    }
?>