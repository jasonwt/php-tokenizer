<?php
    namespace tokenizer;
    require_once(__DIR__ . "/ITokenizer.php");

    require_once(__DIR__ . "/tokens/IToken.php");
    require_once(__DIR__ . "/tokens/ParentToken.php");
    require_once(__DIR__ . "/tokens/TokenizerToken.php");    

    use tokenizer\tokens\IToken;
    use tokenizer\tokens\ParentToken;
    use tokenizer\tokens\TokenizerToken;

    class Tokenizer implements ITokenizer {
        protected array $registeredTokens = [];

    	/**
         *
         * @param IToken $token
         * @param int $priority
         *
         * @return bool
         */
        function RegisterToken(IToken $token, int $priority = 0): bool {
            $name = $token->Name();

            $insertIndex = null;

            for ($tcnt = 0; $tcnt < count($this->registeredTokens); $tcnt ++) {
                if ($this->registeredTokens[$tcnt]["object"]->Name() == $name)
                    return false;

                if ($priority < $this->registeredTokens[$tcnt]["priority"])
                    $insertIndex = $tcnt;
            }

            if (!is_null($insertIndex)) {
                $this->registeredTokens = array_merge(
                    array_slice($this->registeredTokens, 0, $insertIndex),
                    ["priority" => $priority, "object" => $token],
                    array_slice($this->registeredTokens, $insertIndex)
                );
            } else {
                $this->registeredTokens[] = ["priority" => $priority, "object" => $token];
            }

            return true;
        }
        
        /**
         *
         * @param string $name
         *
         * @return bool
         */
        function UnregisterToken(string $name): bool {
            if (($name = trim($name)) == "")
                return false;

            for ($tcnt = 0; $tcnt < count($this->registeredTokens); $tcnt ++) {
                if ($this->registeredTokens[$tcnt]["name"] == $name) {
                    if ($tcnt == 0) {
                        array_shift($this->registeredTokens);
                    } else if ($tcnt == count($this->registeredTokens)-1) {
                        $this->registeredTokens = array_slice($this->registeredTokens, $tcnt - 1);
                    } else {
                        $this->registeredTokens = array_merge(
                            array_slice($this->registeredTokens, 0, $tcnt),
                            array_slice($this->registeredTokens, $tcnt)
                        );
                    }

                    return true;
                }                
            }

            return false;
        }
        
        /**
         *
         * @param string $str
         * @param ParentToken|null $parentToken
         *
         * @return ParentToken
         */
        function Tokenize(string $str, ?ParentToken $parentToken = null): ParentToken {            
            if (is_null($parentToken))
                $parentToken = new TokenizerToken();

            $strHead = 0;

            while (!$parentToken->EndToken((($subStr = substr($str, $strHead)) == false ? "" : $subStr))) {

                for ($rtcnt = 0; $rtcnt < count($this->registeredTokens); $rtcnt ++) {
                    $registeredToken = $this->registeredTokens[$rtcnt];

                    if (($results = $registeredToken["object"]->Tokenize(substr($str, $strHead))) != null) {                        
                        if (is_a($results, "tokenizer\\tokens\\ParentToken"))
                            $results = $this->Tokenize(substr($str, $strHead + strlen($results->BeginTokenTerm())), $results);                                                    

                        $parentToken->AddChild($results);

                        $strHead = ($strHead + strlen($results->ParsedString()));

                        break;
                    }
                }

                if ($rtcnt < count($this->registeredTokens)) 
                    continue;

                $parentToken->NoTokenStringAppend($str[$strHead++]);                
            }

            return $parentToken;
        }
    }

?>