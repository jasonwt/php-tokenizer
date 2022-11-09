<?php
    namespace tokenizer\tokens;

    require_once(__DIR__ . "/IToken.php");
    require_once(__DIR__ . "/AbstractToken.php");
    require_once(__DIR__ . "/UnspecifiedToken.php");

    class ParentToken extends AbstractToken {        
        protected $noTokenString = "";
        protected array $beginTerms = [];
        protected array $endTerms = [];
        protected array $children = [];

        public function __construct(string $name, $beginTerms, $endTerms) {
            parent::__construct($name);

            if (!is_array($beginTerms))
                $beginTerms = [$beginTerms];

            if (!is_array($endTerms))
                $endTerms = [$endTerms];

            if (count($beginTerms) != count($endTerms))
                throw new \Exception("arrays beginTerm and endTerm must have the same amount of elements");

            $this->beginTerms = $beginTerms;
            $this->endTerms = $endTerms;
        }

        public function Children() : array {
            return $this->children;
        }

        public function NoTokenStringAppend(string $appendValue) {
            $this->noTokenString .= $appendValue;
        }

        public function Hierarchy(?IToken $token = null, $indent = 0) : string {
            $returnValue = "";

            if (is_null($token))
                $token = $this;

            $returnValue .= str_repeat("\t", $indent) . "Type  : " . get_class($token) . "\n";
            $returnValue .= str_repeat("\t", $indent) . "Name  : " . $token->Name() . "\n";
            $returnValue .= str_repeat("\t", $indent) . "Value : " . $token->Value() . "\n";

            if (is_a($token, "tokenizer\\tokens\\ParentToken")) {
                $returnValue .= str_repeat("\t", $indent+1) . "------------------------ Children -------------------------\n";
                foreach ($token->Children() as $child) {
                    $returnValue .= "\n" . $this->Hierarchy($child, $indent + 1);
                
                }                

                $returnValue .= str_repeat("\t", $indent) . $token->EndTokenTerm() . "\n";
            }

            return $returnValue;
        }

        public function ParsedString(): string {
            $parsedString = $this->value;

            foreach ($this->children as $child)
                $parsedString .= $child->ParsedString();            

            return $parsedString . $this->EndTokenTerm();
        }        

        public function EndTokenTerm() : ?string {
            if (($endTermIndex = array_search($this->value, $this->beginTerms)) === false)
                throw new \Exception("endTokenTerm '" . $this->value . "' not found.");

            return $this->endTerms[$endTermIndex];
        }

        public function BeginTokenTerm() : ?string {
            return $this->value;
        }

        public function AddChild(IToken $token) {
            if (strlen($this->noTokenString) > 0) {
                $this->children[] = (new UnspecifiedToken())->Tokenize($this->noTokenString);
                $this->noTokenString = "";
            }
             
            $this->children[] = $token;            
        }

        public function EndToken(string $str) : bool {
            $endTerm = $this->EndTokenTerm();

            if (strlen($str) > 0) {
                if ($endTerm == "")
                    return false;

                if (strlen($str) >= strlen($endTerm)) {
                    if (substr($str, 0, strlen($endTerm)) != $endTerm)
                        return false;      
                } else {
                    // should not be here. no closing term found
                }
            }            

            if (strlen($this->noTokenString) > 0) {
                $this->children[] = (new UnspecifiedToken())->Tokenize($this->noTokenString);
                $this->noTokenString = "";
            }
 
            return true;           
        }

    	/**
         *
         * @param string $str
         *
         * @return IToken|null
         */
        function Tokenize(string $str): ?IToken {
            if (strlen($str) == 0)
                return null;

            for ($btcnt = 0; $btcnt < count($this->beginTerms); $btcnt ++) {
                $beginTerm = $this->beginTerms[$btcnt];

                if (strlen($str) < strlen($beginTerm))
                    continue;

                if (substr($str, 0, strlen($beginTerm)) == $beginTerm)
                    break;
            }

            if ($btcnt == count($this->beginTerms))
                return null;

            $newToken = clone($this);
            $newToken->value = $beginTerm;

            return $newToken;
        }
    }
?>