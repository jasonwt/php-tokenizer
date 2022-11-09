<?php
    namespace tokenizer\tokens;

    require_once(__DIR__ . "/RegexToken.php");

    class MatchToken extends RegexToken {
        public function __construct(string $name, $words) {
            if (!is_array($words))
                $words = [$words];

            $patterns = [];

            foreach ($words as $word) {
                if (($word = trim($word)) == "")
                    throw new \Exception("word must not be empty");

                $patterns[] = preg_quote($word, "~");
            }

            parent::__construct($name, $patterns);
        }
    }
?>