<?php
    namespace tokenizer\tokens;

    require_once(__DIR__ . "/RegexToken.php");

    class NoParseToken extends RegexToken {
        public function __construct(string $name, $beginTerms, $endTerms) {
            if (!is_array($beginTerms))
                $beginTerms = [$beginTerms];

            if (!is_array($endTerms))
                $endTerms = [$endTerms];

            if (count($beginTerms) != count($endTerms))
                throw new \Exception("arrays beginTerms and endTerms must have the same amount of elements");

            $patterns = [];

            for ($cnt = 0; $cnt < count($beginTerms); $cnt ++) {
                $beginTerm = trim($beginTerms[$cnt]);
                $endTerms = trim($endTerms[$cnt]);

                if (!is_string($beginTerm) || !is_string($endTerms))
                    throw new \Exception("array elements beginTerms[$cnt] and endTerms[$cnt] must be string values");

                if ($beginTerm == "" || $endTerms == "")
                    throw new \Exception("array elements beginTerms[$cnt] and endTerms[$cnt] must not be empty string");

                $patterns[] = preg_quote($beginTerm, "~") . "[\s\S]*" . preg_quote($endTerms, "~");
            }

            parent::__construct($name, $patterns);
        }
    }
?>