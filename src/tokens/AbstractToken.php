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
        /**
         *
         * @param string $str
         *
         * @return null
         */
        public function Name() : string {
            return $this->name;
        }
        /**
         *
         * @return string
         */
        public function ParsedString(): string {
            return $this->value;
        }
        /**
         *
         * @return string
         */
        public function Value() : string {
            return $this->value;
        }        
    }


?>