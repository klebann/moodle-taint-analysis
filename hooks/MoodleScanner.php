<?php

namespace Klebann\MoodleTaintAnalysis\Hooks;

use PhpParser\Node\Arg;
use PhpParser\Node\Scalar\LNumber;
use Psalm\Issue\TaintedSql;
use Psalm\Plugin\Hook\AfterFunctionCallAnalysisInterface;
use PhpParser\Node\Expr\FuncCall;
use Psalm\Codebase;
use Psalm\Context;
use Psalm\FileManipulation;
use Psalm\StatementsSource;
use Psalm\Type\Atomic\TString;
use Psalm\Type\Union;
use Psalm\Type\TaintKindGroup;
use Psalm\CodeLocation;

class MoodleScanner implements AfterFunctionCallAnalysisInterface
{
    /**
     * @param  non-empty-string $function_id
     * @param  FileManipulation[] $file_replacements
     */
    public static function afterFunctionCallAnalysis(
        FuncCall $expr,
        string $function_id,
        Context $context,
        StatementsSource $statements_source,
        Codebase $codebase,
        Union $return_type_candidate,
        array &$file_replacements
    ): void {

        if ( ! ($expr instanceof FuncCall) ){
            return;
        }

        if ($function_id != 'optional_param') {
            return;
        }

        if ( !isset( $expr->args[2] ) && !($expr->args[2] instanceof Arg) ) {
            return;
        }

        if ( !($expr->args[2]->value instanceof LNumber) ){
            return;
        }

        if ( $expr->args[2]->value->value != 1 ) {
            return;
        }

        //TODO
        $expr_type = new Union([ new TString() ]);

        // should be a globally unique id
        // you can use its line number/start offset
        $expr_identifier = $function_id
            . '-' . $statements_source->getFileName()
            . ':' . $expr->getAttribute('startFilePos');

        $codebase->addTaintSource(
            $expr_type,
            $expr_identifier,
            TaintKindGroup::ALL_INPUT,
            new CodeLocation($statements_source, $expr)
        );
    }
}