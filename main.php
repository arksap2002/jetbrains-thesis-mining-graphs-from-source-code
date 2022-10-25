<?php

const OPEN_BRACKET = "{";
const CLOSE_BRACKET = "}";
define("TOKENS", PhpToken::tokenize(file_get_contents($argv[1])));

main();

function main(): void
{
    $isAbstractOrUse = false;
    for ($i = 0; $i < sizeof(TOKENS); $i++) {
        if (TOKENS[$i]->id == T_INTERFACE || TOKENS[$i]->id == T_TRAIT || TOKENS[$i]->id == T_TRAIT_C) {
            break;
        }
        if (TOKENS[$i]->id == T_FUNCTION && !$isAbstractOrUse) {
            functionProcess($i);
        }
        $isAbstractOrUse = $isAbstractOrUse || TOKENS[$i]->id == T_ABSTRACT || TOKENS[$i]->id == T_USE;
        $isAbstractOrUse = $isAbstractOrUse && (TOKENS[$i]->id != T_FUNCTION);
    }
}

function functionProcess(&$index): void
{
    $name = "";
    $numberOfArguments = 0;
    $numberOfIF = 0;
    $numberOfFor = 0;
    $numberOfWhile = 0;
    $size = 0;
    $comment = "";
    $modifier = "";
    goBackOnTokens($index, $comment, $modifier);
    goAheadOnTokens($index, $name, $numberOfArguments, $numberOfIF, $numberOfFor, $numberOfWhile, $size);
    file_put_contents("data.txt",
        $name . "\t" .
        $modifier . "\t" .
        $comment . "\t" .
        $numberOfArguments . "\t" .
        $size . "\t" .
        $numberOfIF . "\t" .
        $numberOfFor . "\t" .
        $numberOfWhile . "\n", FILE_APPEND | LOCK_EX);
}

function goBackOnTokens($index, &$comment, &$modifier): void
{
    $index--;
    $modifier = getModifier($index);
    $comment = getComment($index);
}

function goAheadOnTokens(&$index, &$name, &$numberOfArguments, &$numberOfIF, &$numberOfFor, &$numberOfWhile, &$size): void
{
    $index++;
    $startedIndex = $index;
    $name = getName($index);
    $numberOfArguments = getNumberOfArguments($index);
    processBody($index, $numberOfIF, $numberOfFor, $numberOfWhile);
    $size = TOKENS[$index]->line - TOKENS[$startedIndex]->line;
}

function getName(&$index): string
{
    while (TOKENS[$index]->id == T_WHITESPACE) {
        $index++;
    }
    if (TOKENS[$index]->id == T_STRING) {
        return TOKENS[$index]->text;
    }
    return "";
}

function getNumberOfArguments(&$index): int
{
    $result = 0;
    while (TOKENS[$index]->getTokenName() != OPEN_BRACKET) {
        $result += TOKENS[$index]->id == T_VARIABLE;
        $index++;
    }
    return $result;
}

function processBody(&$index, &$numberOfIF, &$numberOfFor, &$numberOfWhile): void
{
    $balance = 1;
    while ($balance != 0) {
        $index++;
        $balance += TOKENS[$index]->getTokenName() == OPEN_BRACKET;
        $balance -= TOKENS[$index]->getTokenName() == CLOSE_BRACKET;
        $numberOfIF += TOKENS[$index]->id == T_IF;
        $numberOfFor += TOKENS[$index]->id == T_FOR;
        $numberOfWhile += TOKENS[$index]->id == T_WHILE;
    }
}

function skipWhitespaces(&$index): void
{
    while (TOKENS[$index]->id == T_WHITESPACE) {
        $index--;
    }
}

function getModifier(&$index): string
{
    skipWhitespaces($index);
    if (TOKENS[$index]->id == T_PRIVATE || TOKENS[$index]->id == T_PROTECTED || TOKENS[$index]->id == T_PUBLIC) {
        return TOKENS[$index--]->text;
    }
    return "";
}

function getComment(&$index): string
{
    skipWhitespaces($index);
    if (TOKENS[$index]->id == T_COMMENT) {
        return str_replace("\t", "", str_replace("\n", "", TOKENS[$index]->text));
    }
    return "";
}
