<?php
    namespace tokenizer\tokens;

    require_once(__DIR__ . "/IToken.php");

    abstract class AbstractToken implements IToken {
        protected $name = "";
        protected $value = "";

        public function __construct(string $name) {
            $name = trim($name);

            if ($name == "")
                throw new \Exception("name must not be blank.");

            $this->name = $name;
        }

        public function Name() : string {
            return $this->name;
        }

        public function ParsedString(): string {
            return $this->value;
        }

        public function Value() : string {
            return $this->value;
        }

        
    }


?>