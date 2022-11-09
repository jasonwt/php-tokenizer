<?php

    require_once(__DIR__ . "/../src/ITokenizer.php");
    require_once(__DIR__ . "/../src/Tokenizer.php");

    require_once(__DIR__ . "/../src/tokens/NoParseToken.php");
    require_once(__DIR__ . "/../src/tokens/ParentToken.php");
    require_once(__DIR__ . "/../src/tokens/NumericToken.php");
    require_once(__DIR__ . "/../src/tokens/MatchWholeToken.php");
    require_once(__DIR__ . "/../src/tokens/MatchToken.php");
    require_once(__DIR__ . "/../src/tokens/WhitespaceToken.php");
    require_once(__DIR__ . "/../src/tokens/AlphaNumericToken.php");
    require_once(__DIR__ . "/../src/tokens/AlphaToken.php");

    use tokenizer\Tokenizer;

    use tokenizer\tokens\NoParseToken;
    use tokenizer\tokens\ParentToken;
    use tokenizer\tokens\NumericToken;
    use tokenizer\tokens\MatchWholeToken;
    use tokenizer\tokens\MatchToken;
    use tokenizer\tokens\WhitespaceToken;
    use tokenizer\tokens\AlphaToken;
    use tokenizer\tokens\AlphaNumericToken;
    

    function ExecuteTests(array $tests) {
        $executeTestResults = [
            "success" => [],
            "failure" => []
        ];

        for ($tcnt = 0; $tcnt < count($tests); $tcnt ++) {
            $test = $tests[$tcnt];

            $tokenizeResults = $test["tokenizer"]->Tokenize($test["str"]);

            $testResult = [
                "expectedParsedString" => $test["str"],
                "parsedString" => $tokenizeResults->ParsedString(),
                "tokenizeResults" => "\n" . $tokenizeResults->Hierarchy(null, 6)
            ];

            if ($testResult["expectedParsedString"] == $testResult["parsedString"])
                $executeTestResults["success"][$tcnt] = $testResult;
            else
                $executeTestResults["failure"][$tcnt] = $testResult;            
        }

        return $executeTestResults;
    }

    $tokenizer1 = new Tokenizer();

    $tokenizer1->RegisterToken(new NoParseToken("NoParseToken", '/*', '*/'));
    $tokenizer1->RegisterToken(new ParentToken("ParentToken", ["("], [")"]));
    $tokenizer1->RegisterToken(new NumericToken("NumericToken"));
    $tokenizer1->RegisterToken(new MatchWholeToken("MatchWholeToken", ["min"]));
    $tokenizer1->RegisterToken(new MatchToken("MatchToken", ['**', '+', '-', '*', '/', ',']));
    $tokenizer1->RegisterToken(new WhitespaceToken("WhitespaceToken"));
    $tokenizer1->RegisterToken(new AlphaToken("AlphaToken"));
    $tokenizer1->RegisterToken(new AlphaNumericToken("AlphaNumericToken"));

    $tests = [
        [
            "tokenizer" => $tokenizer1,
            "str" => "min(100, (5*/*4*3**/12)) ** 3"
        ],
        [
            "tokenizer" => $tokenizer1,
            "str" => "ceil(sin(cos(tan(sqrt(abs(pow(10*(10**2), 0.5)))))))"
        ]
    ];
    
    print_r(ExecuteTests($tests));
?>