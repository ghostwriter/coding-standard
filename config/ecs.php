<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Generic\Sniffs\VersionControl\GitMergeConflictSniff;
use PhpCsFixer\Fixer\Alias\ArrayPushFixer;
use PhpCsFixer\Fixer\Alias\MbStrFunctionsFixer;
use PhpCsFixer\Fixer\Alias\ModernizeStrposFixer;
use PhpCsFixer\Fixer\Alias\NoAliasFunctionsFixer;
use PhpCsFixer\Fixer\Alias\NoAliasLanguageConstructCallFixer;
use PhpCsFixer\Fixer\Alias\NoMixedEchoPrintFixer;
use PhpCsFixer\Fixer\Alias\PowToExponentiationFixer;
use PhpCsFixer\Fixer\Alias\RandomApiMigrationFixer;
use PhpCsFixer\Fixer\Alias\SetTypeToCastFixer;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ArrayNotation\ReturnToYieldFromFixer;
use PhpCsFixer\Fixer\ArrayNotation\WhitespaceAfterCommaInArrayFixer;
use PhpCsFixer\Fixer\AttributeNotation\AttributeEmptyParenthesesFixer;
use PhpCsFixer\Fixer\AttributeNotation\OrderedAttributesFixer;
use PhpCsFixer\Fixer\Basic\PsrAutoloadingFixer;
use PhpCsFixer\Fixer\Basic\SingleLineEmptyBodyFixer;
use PhpCsFixer\Fixer\Casing\ClassReferenceNameCasingFixer;
use PhpCsFixer\Fixer\Casing\ConstantCaseFixer;
use PhpCsFixer\Fixer\Casing\IntegerLiteralCaseFixer;
use PhpCsFixer\Fixer\Casing\LowercaseKeywordsFixer;
use PhpCsFixer\Fixer\Casing\LowercaseStaticReferenceFixer;
use PhpCsFixer\Fixer\Casing\MagicConstantCasingFixer;
use PhpCsFixer\Fixer\Casing\MagicMethodCasingFixer;
use PhpCsFixer\Fixer\Casing\NativeFunctionCasingFixer;
use PhpCsFixer\Fixer\Casing\NativeFunctionTypeDeclarationCasingFixer;
use PhpCsFixer\Fixer\Casing\NativeTypeDeclarationCasingFixer;
use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\CastNotation\LowercaseCastFixer;
use PhpCsFixer\Fixer\CastNotation\ModernizeTypesCastingFixer;
use PhpCsFixer\Fixer\CastNotation\NoShortBoolCastFixer;
use PhpCsFixer\Fixer\CastNotation\NoUnsetCastFixer;
use PhpCsFixer\Fixer\CastNotation\ShortScalarCastFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassDefinitionFixer;
use PhpCsFixer\Fixer\ClassNotation\FinalClassFixer;
use PhpCsFixer\Fixer\ClassNotation\FinalPublicMethodForAbstractClassFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedInterfacesFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedTraitsFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedTypesFixer;
use PhpCsFixer\Fixer\ClassNotation\ProtectedToPrivateFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfStaticAccessorFixer;
use PhpCsFixer\Fixer\ClassNotation\SingleClassElementPerStatementFixer;
use PhpCsFixer\Fixer\ClassNotation\SingleTraitInsertPerStatementFixer;
use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\ClassUsage\DateTimeImmutableFixer;
use PhpCsFixer\Fixer\Comment\CommentToPhpdocFixer;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use PhpCsFixer\Fixer\Comment\MultilineCommentOpeningClosingFixer;
use PhpCsFixer\Fixer\Comment\NoEmptyCommentFixer;
use PhpCsFixer\Fixer\Comment\NoTrailingWhitespaceInCommentFixer;
use PhpCsFixer\Fixer\Comment\SingleLineCommentSpacingFixer;
use PhpCsFixer\Fixer\ConstantNotation\NativeConstantInvocationFixer;
use PhpCsFixer\Fixer\ControlStructure\ControlStructureBracesFixer;
use PhpCsFixer\Fixer\ControlStructure\ControlStructureContinuationPositionFixer;
use PhpCsFixer\Fixer\ControlStructure\ElseifFixer;
use PhpCsFixer\Fixer\ControlStructure\EmptyLoopBodyFixer;
use PhpCsFixer\Fixer\ControlStructure\EmptyLoopConditionFixer;
use PhpCsFixer\Fixer\ControlStructure\IncludeFixer;
use PhpCsFixer\Fixer\ControlStructure\NoAlternativeSyntaxFixer;
use PhpCsFixer\Fixer\ControlStructure\NoBreakCommentFixer;
use PhpCsFixer\Fixer\ControlStructure\NoSuperfluousElseifFixer;
use PhpCsFixer\Fixer\ControlStructure\NoTrailingCommaInListCallFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUnneededBracesFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUnneededControlParenthesesFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUnneededCurlyBracesFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUselessElseFixer;
use PhpCsFixer\Fixer\ControlStructure\SimplifiedIfReturnFixer;
use PhpCsFixer\Fixer\ControlStructure\SwitchCaseSemicolonToColonFixer;
use PhpCsFixer\Fixer\ControlStructure\SwitchCaseSpaceFixer;
use PhpCsFixer\Fixer\ControlStructure\SwitchContinueToBreakFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\FunctionNotation\CombineNestedDirnameFixer;
use PhpCsFixer\Fixer\FunctionNotation\DateTimeCreateFromFormatCallFixer;
use PhpCsFixer\Fixer\FunctionNotation\FopenFlagOrderFixer;
use PhpCsFixer\Fixer\FunctionNotation\FopenFlagsFixer;
use PhpCsFixer\Fixer\FunctionNotation\FunctionDeclarationFixer;
use PhpCsFixer\Fixer\FunctionNotation\FunctionTypehintSpaceFixer;
use PhpCsFixer\Fixer\FunctionNotation\LambdaNotUsedImportFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\FunctionNotation\NoSpacesAfterFunctionNameFixer;
use PhpCsFixer\Fixer\FunctionNotation\NoTrailingCommaInSinglelineFunctionCallFixer;
use PhpCsFixer\Fixer\FunctionNotation\NoUnreachableDefaultArgumentValueFixer;
use PhpCsFixer\Fixer\FunctionNotation\NoUselessSprintfFixer;
use PhpCsFixer\Fixer\FunctionNotation\NullableTypeDeclarationForDefaultNullValueFixer;
use PhpCsFixer\Fixer\FunctionNotation\PhpdocToParamTypeFixer;
use PhpCsFixer\Fixer\FunctionNotation\PhpdocToPropertyTypeFixer;
use PhpCsFixer\Fixer\FunctionNotation\ReturnTypeDeclarationFixer;
use PhpCsFixer\Fixer\FunctionNotation\StaticLambdaFixer;
use PhpCsFixer\Fixer\FunctionNotation\UseArrowFunctionsFixer;
use PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer;
use PhpCsFixer\Fixer\Import\GlobalNamespaceImportFixer;
use PhpCsFixer\Fixer\Import\GroupImportFixer;
use PhpCsFixer\Fixer\Import\NoLeadingImportSlashFixer;
use PhpCsFixer\Fixer\Import\NoUnneededImportAliasFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Import\SingleImportPerStatementFixer;
use PhpCsFixer\Fixer\Import\SingleLineAfterImportsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\ClassKeywordFixer;
use PhpCsFixer\Fixer\LanguageConstruct\CombineConsecutiveIssetsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\CombineConsecutiveUnsetsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DeclareEqualNormalizeFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DeclareParenthesesFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DirConstantFixer;
use PhpCsFixer\Fixer\LanguageConstruct\ErrorSuppressionFixer;
use PhpCsFixer\Fixer\LanguageConstruct\ExplicitIndirectVariableFixer;
use PhpCsFixer\Fixer\LanguageConstruct\FunctionToConstantFixer;
use PhpCsFixer\Fixer\LanguageConstruct\GetClassToClassKeywordFixer;
use PhpCsFixer\Fixer\LanguageConstruct\IsNullFixer;
use PhpCsFixer\Fixer\LanguageConstruct\NoUnsetOnPropertyFixer;
use PhpCsFixer\Fixer\LanguageConstruct\NullableTypeDeclarationFixer;
use PhpCsFixer\Fixer\LanguageConstruct\SingleSpaceAfterConstructFixer;
use PhpCsFixer\Fixer\LanguageConstruct\SingleSpaceAroundConstructFixer;
use PhpCsFixer\Fixer\ListNotation\ListSyntaxFixer;
use PhpCsFixer\Fixer\NamespaceNotation\BlankLineAfterNamespaceFixer;
use PhpCsFixer\Fixer\NamespaceNotation\BlankLinesBeforeNamespaceFixer;
use PhpCsFixer\Fixer\NamespaceNotation\CleanNamespaceFixer;
use PhpCsFixer\Fixer\NamespaceNotation\NoBlankLinesBeforeNamespaceFixer;
use PhpCsFixer\Fixer\NamespaceNotation\NoLeadingNamespaceWhitespaceFixer;
use PhpCsFixer\Fixer\NamespaceNotation\SingleBlankLineBeforeNamespaceFixer;
use PhpCsFixer\Fixer\Naming\NoHomoglyphNamesFixer;
use PhpCsFixer\Fixer\Operator\AssignNullCoalescingToCoalesceEqualFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\IncrementStyleFixer;
use PhpCsFixer\Fixer\Operator\LogicalOperatorsFixer;
use PhpCsFixer\Fixer\Operator\LongToShorthandOperatorFixer;
use PhpCsFixer\Fixer\Operator\NewWithBracesFixer;
use PhpCsFixer\Fixer\Operator\NewWithParenthesesFixer;
use PhpCsFixer\Fixer\Operator\NoSpaceAroundDoubleColonFixer;
use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use PhpCsFixer\Fixer\Operator\NoUselessConcatOperatorFixer;
use PhpCsFixer\Fixer\Operator\NoUselessNullsafeOperatorFixer;
use PhpCsFixer\Fixer\Operator\ObjectOperatorWithoutWhitespaceFixer;
use PhpCsFixer\Fixer\Operator\OperatorLinebreakFixer;
use PhpCsFixer\Fixer\Operator\StandardizeIncrementFixer;
use PhpCsFixer\Fixer\Operator\StandardizeNotEqualsFixer;
use PhpCsFixer\Fixer\Operator\TernaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\TernaryToElvisOperatorFixer;
use PhpCsFixer\Fixer\Operator\TernaryToNullCoalescingFixer;
use PhpCsFixer\Fixer\Operator\UnaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Phpdoc\AlignMultilineCommentFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\NoBlankLinesAfterPhpdocFixer;
use PhpCsFixer\Fixer\Phpdoc\NoEmptyPhpdocFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAlignFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAnnotationWithoutDotFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocIndentFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocListTypeFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocOrderByValueFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocOrderFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocParamOrderFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocScalarFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSeparationFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSingleLineVarSpacingFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSummaryFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTrimConsecutiveBlankLineSeparationFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTrimFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTypesFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTypesOrderFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocVarAnnotationCorrectOrderFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocVarWithoutNameFixer;
use PhpCsFixer\Fixer\PhpTag\FullOpeningTagFixer;
use PhpCsFixer\Fixer\PhpTag\NoClosingTagFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitAttributesFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitConstructFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDataProviderNameFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDataProviderReturnTypeFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDataProviderStaticFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertInternalTypeFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitExpectationFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitFqcnAnnotationFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMethodCasingFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMockFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMockShortWillReturnFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitNamespacedFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitNoExpectationAnnotationFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitSetUpTearDownVisibilityFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestAnnotationFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestCaseStaticMethodCallsFixer;
use PhpCsFixer\Fixer\ReturnNotation\NoUselessReturnFixer;
use PhpCsFixer\Fixer\ReturnNotation\ReturnAssignmentFixer;
use PhpCsFixer\Fixer\ReturnNotation\SimplifiedNullReturnFixer;
use PhpCsFixer\Fixer\Semicolon\NoEmptyStatementFixer;
use PhpCsFixer\Fixer\Semicolon\NoSinglelineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\Semicolon\SemicolonAfterInstructionFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictComparisonFixer;
use PhpCsFixer\Fixer\Strict\StrictParamFixer;
use PhpCsFixer\Fixer\StringNotation\ExplicitStringVariableFixer;
use PhpCsFixer\Fixer\StringNotation\HeredocClosingMarkerFixer;
use PhpCsFixer\Fixer\StringNotation\HeredocToNowdocFixer;
use PhpCsFixer\Fixer\StringNotation\MultilineStringToHeredocFixer;
use PhpCsFixer\Fixer\StringNotation\SimpleToComplexStringVariableFixer;
use PhpCsFixer\Fixer\StringNotation\SingleQuoteFixer;
use PhpCsFixer\Fixer\StringNotation\StringLengthToEmptyFixer;
use PhpCsFixer\Fixer\Whitespace\ArrayIndentationFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBetweenImportGroupsFixer;
use PhpCsFixer\Fixer\Whitespace\CompactNullableTypeDeclarationFixer;
use PhpCsFixer\Fixer\Whitespace\CompactNullableTypehintFixer;
use PhpCsFixer\Fixer\Whitespace\HeredocIndentationFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
use PhpCsFixer\Fixer\Whitespace\NoExtraBlankLinesFixer;
use PhpCsFixer\Fixer\Whitespace\NoSpacesAroundOffsetFixer;
use PhpCsFixer\Fixer\Whitespace\NoSpacesInsideParenthesisFixer;
use PhpCsFixer\Fixer\Whitespace\NoTrailingWhitespaceFixer;
use PhpCsFixer\Fixer\Whitespace\NoWhitespaceInBlankLineFixer;
use PhpCsFixer\Fixer\Whitespace\SingleBlankLineAtEofFixer;
use PhpCsFixer\Fixer\Whitespace\SpacesInsideParenthesesFixer;
use PhpCsFixer\Fixer\Whitespace\StatementIndentationFixer;
use PhpCsFixer\Fixer\Whitespace\TypeDeclarationSpacesFixer;
use PhpCsFixer\Fixer\Whitespace\TypesSpacesFixer;
use Symplify\CodingStandard\Fixer\Spacing\MethodChainingNewlineFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

$workingDirectory = \getcwd() ?: __DIR__;

$existingPaths = \array_filter(
    [
        __FILE__,
        $workingDirectory . '/bin',
        $workingDirectory . '/config',
        $workingDirectory . '/data',
        $workingDirectory . '/docs',
        $workingDirectory . '/ecs.php',
        $workingDirectory . '/index.php',
        $workingDirectory . '/module',
        $workingDirectory . '/public',
        $workingDirectory . '/rector.php',
        $workingDirectory . '/resource',
        $workingDirectory . '/src',
        $workingDirectory . '/test',
        $workingDirectory . '/tests',
    ],
    static fn (string $path): bool => \file_exists($path)
);

$existingSkips = \array_merge(
    \array_filter(
        [$workingDirectory . '/vendor', $workingDirectory . '/tests/Fixture', $workingDirectory . '/tests/fixture'],
        static fn (string $path): bool => \file_exists($path)
    ),
    [
        '*/tests/Fixture/*',
        '*/tests/fixture/*',
        '*/vendor/*',
        BinaryOperatorSpacesFixer::class,
        FinalPublicMethodForAbstractClassFixer::class,
        GeneralPhpdocAnnotationRemoveFixer::class,
        GroupImportFixer::class,
        NoSuperfluousPhpdocTagsFixer::class,
        PhpUnitStrictFixer::class => ['tests/Unit/ParameterBuilderTest.php'],
        PhpdocLineSpanFixer::class,
        PhpdocTrimFixer::class,
        SemicolonAfterInstructionFixer::class,
        MethodChainingNewlineFixer::class,
    ]
);

return ECSConfig::configure()
    ->withCache($workingDirectory . '/.cache/ecs')
    ->withParallel()
    ->withPaths($existingPaths)
    ->withRootFiles()
    ->withSkip($existingSkips)
    ->withPhpCsFixerSets(
        php54Migration: true,
        php56MigrationRisky: true,
        php70Migration: true,
        php70MigrationRisky: true,
        php71Migration: true,
        php71MigrationRisky: true,
        php73Migration: true,
        php74Migration: true,
        php74MigrationRisky: true,
        php80Migration: true,
        php80MigrationRisky: true,
        php81Migration: true,
        php82Migration: true,
        php83Migration: true,
        phpunit30MigrationRisky: true,
        phpunit32MigrationRisky: true,
        phpunit35MigrationRisky: true,
        phpunit43MigrationRisky: true,
        phpunit48MigrationRisky: true,
        phpunit50MigrationRisky: true,
        phpunit52MigrationRisky: true,
        phpunit54MigrationRisky: true,
        phpunit55MigrationRisky: true,
        phpunit56MigrationRisky: true,
        phpunit57MigrationRisky: true,
        phpunit60MigrationRisky: true,
        phpunit75MigrationRisky: true,
        phpunit84MigrationRisky: true,
        phpunit100MigrationRisky: true,
    )
    ->withPreparedSets(
        psr12: true,
        common: false,
        symplify: true,
        arrays: true,
        comments: true,
        docblocks: true,
        spaces: true,
        namespaces: true,
        controlStructures: true,
        phpunit: true,
        strict: true,
    )
    ->withRules([
        AlignMultilineCommentFixer::class,
        ArrayIndentationFixer::class,
        ArrayPushFixer::class,
        ArraySyntaxFixer::class,
        AssignNullCoalescingToCoalesceEqualFixer::class,
        AttributeEmptyParenthesesFixer::class,
        BlankLineAfterNamespaceFixer::class,
        BlankLineBeforeStatementFixer::class,
        BlankLineBetweenImportGroupsFixer::class,
        BlankLinesBeforeNamespaceFixer::class,
        CastSpacesFixer::class,
        ClassAttributesSeparationFixer::class,
        ClassDefinitionFixer::class,
        ClassKeywordFixer::class,
        ClassReferenceNameCasingFixer::class,
        CleanNamespaceFixer::class,
        CombineConsecutiveIssetsFixer::class,
        CombineConsecutiveUnsetsFixer::class,
        CombineNestedDirnameFixer::class,
        CommentToPhpdocFixer::class,
        CompactNullableTypeDeclarationFixer::class,
        CompactNullableTypehintFixer::class,
        ConstantCaseFixer::class,
        ControlStructureBracesFixer::class,
        ControlStructureContinuationPositionFixer::class,
        DateTimeCreateFromFormatCallFixer::class,
        DateTimeImmutableFixer::class,
        DeclareEqualNormalizeFixer::class,
        DeclareParenthesesFixer::class,
        DeclareStrictTypesFixer::class,
        DirConstantFixer::class,
        ElseifFixer::class,
        EmptyLoopBodyFixer::class,
        EmptyLoopConditionFixer::class,
        ErrorSuppressionFixer::class,
        ExplicitIndirectVariableFixer::class,
        ExplicitStringVariableFixer::class,
        FinalClassFixer::class,
        FinalPublicMethodForAbstractClassFixer::class,
        FopenFlagOrderFixer::class,
        FopenFlagsFixer::class,
        FullOpeningTagFixer::class,
        FullyQualifiedStrictTypesFixer::class,
        FunctionDeclarationFixer::class,
        FunctionToConstantFixer::class,
        FunctionTypehintSpaceFixer::class,
        GetClassToClassKeywordFixer::class,
        GitMergeConflictSniff::class,
        GlobalNamespaceImportFixer::class,
        HeredocClosingMarkerFixer::class,
        HeredocIndentationFixer::class,
        HeredocToNowdocFixer::class,
        IncludeFixer::class,
        IncrementStyleFixer::class,
        IntegerLiteralCaseFixer::class,
        IsNullFixer::class,
        LambdaNotUsedImportFixer::class,
        ListSyntaxFixer::class,
        LogicalOperatorsFixer::class,
        LongToShorthandOperatorFixer::class,
        LowercaseCastFixer::class,
        LowercaseKeywordsFixer::class,
        LowercaseStaticReferenceFixer::class,
        MagicConstantCasingFixer::class,
        MagicMethodCasingFixer::class,
        MbStrFunctionsFixer::class,
        MethodChainingIndentationFixer::class,
        ModernizeStrposFixer::class,
        ModernizeTypesCastingFixer::class,
        MultilineCommentOpeningClosingFixer::class,
        MultilineStringToHeredocFixer::class,
        NativeFunctionCasingFixer::class,
        NativeFunctionTypeDeclarationCasingFixer::class,
        NativeTypeDeclarationCasingFixer::class,
        NewWithBracesFixer::class,
        NewWithParenthesesFixer::class,
        NoAliasFunctionsFixer::class,
        NoAliasLanguageConstructCallFixer::class,
        NoAlternativeSyntaxFixer::class,
        NoBlankLinesAfterPhpdocFixer::class,
        NoBlankLinesBeforeNamespaceFixer::class,
        NoBreakCommentFixer::class,
        NoClosingTagFixer::class,
        NoEmptyCommentFixer::class,
        NoEmptyPhpdocFixer::class,
        NoEmptyStatementFixer::class,
        NoExtraBlankLinesFixer::class,
        NoHomoglyphNamesFixer::class,
        NoLeadingImportSlashFixer::class,
        NoLeadingNamespaceWhitespaceFixer::class,
        NoMixedEchoPrintFixer::class,
        NoShortBoolCastFixer::class,
        NoSinglelineWhitespaceBeforeSemicolonsFixer::class,
        NoSpaceAroundDoubleColonFixer::class,
        NoSpacesAfterFunctionNameFixer::class,
        NoSpacesAroundOffsetFixer::class,
        NoSpacesInsideParenthesisFixer::class,
        NoSuperfluousElseifFixer::class,
        NoTrailingCommaInListCallFixer::class,
        NoTrailingCommaInSinglelineFunctionCallFixer::class,
        NoTrailingWhitespaceFixer::class,
        NoTrailingWhitespaceInCommentFixer::class,
        NoUnneededBracesFixer::class,
        NoUnneededControlParenthesesFixer::class,
        NoUnneededCurlyBracesFixer::class,
        NoUnneededImportAliasFixer::class,
        NoUnreachableDefaultArgumentValueFixer::class,
        NoUnsetCastFixer::class,
        NoUnsetOnPropertyFixer::class,
        NoUnusedImportsFixer::class,
        NoUselessConcatOperatorFixer::class,
        NoUselessElseFixer::class,
        NoUselessNullsafeOperatorFixer::class,
        NoUselessReturnFixer::class,
        NoUselessSprintfFixer::class,
        NoWhitespaceInBlankLineFixer::class,
        NotOperatorWithSuccessorSpaceFixer::class,
        NullableTypeDeclarationFixer::class,
        NullableTypeDeclarationForDefaultNullValueFixer::class,
        ObjectOperatorWithoutWhitespaceFixer::class,
        OperatorLinebreakFixer::class,
        OrderedAttributesFixer::class,
        OrderedImportsFixer::class,
        OrderedInterfacesFixer::class,
        OrderedTraitsFixer::class,
        OrderedTypesFixer::class,
        PhpUnitAttributesFixer::class,
        PhpUnitConstructFixer::class,
        PhpUnitDataProviderNameFixer::class,
        PhpUnitDataProviderReturnTypeFixer::class,
        PhpUnitDataProviderStaticFixer::class,
        PhpUnitDedicateAssertFixer::class,
        PhpUnitDedicateAssertInternalTypeFixer::class,
        PhpUnitExpectationFixer::class,
        PhpUnitFqcnAnnotationFixer::class,
        PhpUnitMethodCasingFixer::class,
        PhpUnitMockFixer::class,
        PhpUnitMockShortWillReturnFixer::class,
        PhpUnitNamespacedFixer::class,
        PhpUnitNoExpectationAnnotationFixer::class,
        PhpUnitSetUpTearDownVisibilityFixer::class,
        PhpUnitStrictFixer::class,
        PhpUnitTestAnnotationFixer::class,
        PhpdocAlignFixer::class,
        PhpdocAnnotationWithoutDotFixer::class,
        PhpdocIndentFixer::class,
        PhpdocListTypeFixer::class,
        PhpdocOrderByValueFixer::class,
        PhpdocOrderFixer::class,
        PhpdocParamOrderFixer::class,
        PhpdocScalarFixer::class,
        PhpdocSeparationFixer::class,
        PhpdocSingleLineVarSpacingFixer::class,
        PhpdocSummaryFixer::class,
        PhpdocToParamTypeFixer::class,
        PhpdocToPropertyTypeFixer::class,
        PhpdocTrimConsecutiveBlankLineSeparationFixer::class,
        PhpdocTrimFixer::class,
        PhpdocTypesFixer::class,
        PhpdocTypesOrderFixer::class,
        PhpdocVarAnnotationCorrectOrderFixer::class,
        PhpdocVarWithoutNameFixer::class,
        PowToExponentiationFixer::class,
        ProtectedToPrivateFixer::class,
        RandomApiMigrationFixer::class,
        ReturnAssignmentFixer::class,
        ReturnToYieldFromFixer::class,
        ReturnTypeDeclarationFixer::class,
        SelfAccessorFixer::class,
        SelfStaticAccessorFixer::class,
        SemicolonAfterInstructionFixer::class,
        SetTypeToCastFixer::class,
        ShortScalarCastFixer::class,
        SimpleToComplexStringVariableFixer::class,
        SimplifiedIfReturnFixer::class,
        SimplifiedNullReturnFixer::class,
        SingleBlankLineAtEofFixer::class,
        SingleBlankLineBeforeNamespaceFixer::class,
        SingleClassElementPerStatementFixer::class,
        SingleImportPerStatementFixer::class,
        SingleLineAfterImportsFixer::class,
        SingleLineCommentSpacingFixer::class,
        SingleLineEmptyBodyFixer::class,
        SingleQuoteFixer::class,
        SingleSpaceAfterConstructFixer::class,
        SingleSpaceAroundConstructFixer::class,
        SingleTraitInsertPerStatementFixer::class,
        SpacesInsideParenthesesFixer::class,
        StandardizeIncrementFixer::class,
        StandardizeNotEqualsFixer::class,
        StatementIndentationFixer::class,
        StaticLambdaFixer::class,
        StrictComparisonFixer::class,
        StrictParamFixer::class,
        StringLengthToEmptyFixer::class,
        SwitchCaseSemicolonToColonFixer::class,
        SwitchCaseSpaceFixer::class,
        SwitchContinueToBreakFixer::class,
        TernaryOperatorSpacesFixer::class,
        TernaryToElvisOperatorFixer::class,
        TernaryToNullCoalescingFixer::class,
        TypeDeclarationSpacesFixer::class,
        TypesSpacesFixer::class,
        UnaryOperatorSpacesFixer::class,
        UseArrowFunctionsFixer::class,
        VisibilityRequiredFixer::class,
        WhitespaceAfterCommaInArrayFixer::class,
    ])
    ->withConfiguredRule(YodaStyleFixer::class, [
        'always_move_variable' => true,
        'equal' => true,
        'identical' => true,
        'less_and_greater' => true,
    ])
    ->withConfiguredRule(HeaderCommentFixer::class, [
        'header' => '',
        'comment_type' => 'PHPDoc',
        'location' => 'after_declare_strict',
    ])
    ->withConfiguredRule(BinaryOperatorSpacesFixer::class, [
        'default' => 'at_least_single_space',
    ])
    ->withConfiguredRule(ConcatSpaceFixer::class, [
        'spacing' => 'one',
    ])
    ->withConfiguredRule(PhpdocLineSpanFixer::class, [
        'const' => 'single',
        'property' => 'single',
        'method' => 'single',
    ])
    ->withConfiguredRule(FullyQualifiedStrictTypesFixer::class, [
        'import_symbols' => true,
        'leading_backslash_in_global_namespace' => true,
    ])
    ->withConfiguredRule(OrderedAttributesFixer::class, [
        'sort_algorithm' => 'alpha',
    ])
    ->withConfiguredRule(ArraySyntaxFixer::class, [
        'syntax' => 'short',
    ])
    ->withConfiguredRule(GlobalNamespaceImportFixer::class, [
        'import_classes' => true,
        'import_constants' => true,
        'import_functions' => true,
    ])
    ->withConfiguredRule(NativeConstantInvocationFixer::class, [
        'scope' => 'all',
        'fix_built_in' => true,
        'strict' => true,
    ])
    ->withConfiguredRule(NativeFunctionInvocationFixer::class, [
        'include' => ['@all'],
        'scope' => 'all',
        'strict' => true,
    ])
    ->withConfiguredRule(OrderedClassElementsFixer::class, [
        'case_sensitive' => true,
        'sort_algorithm' => 'alpha',
        'order' => [
            'use_trait',
            'case',
            //            'public',
            //            'protected',
            //            'private',
            //            'constant',
            'constant_public',
            'constant_protected',
            'constant_private',
            //            'property',
            'property_public',
            'property_protected',
            'property_private',
            'property_static',

            'property_public_readonly',
            'property_protected_readonly',
            'property_private_readonly',

            'property_public_static',
            'property_protected_static',
            'property_private_static',

            'phpunit',
            'construct',
            'method:new',
            'destruct',
            'magic',

            'method_abstract',
            'method_static',

            'method_public',
            'method_protected',
            'method_private',

            'method_public_abstract',
            'method_protected_abstract',
            'method_private_abstract',

            'method_public_abstract_static',
            'method_protected_abstract_static',
            'method_private_abstract_static',

            'method_public_static',
            'method_protected_static',
            'method_private_static',
        ],
    ])
    ->withConfiguredRule(OrderedImportsFixer::class, [
        'imports_order' => ['class', 'const', 'function'],
        'sort_algorithm' => 'alpha',
    ])
    ->withConfiguredRule(OrderedInterfacesFixer::class, [
        'order' => 'alpha',
    ])
    ->withConfiguredRule(PhpdocAlignFixer::class, [
        'tags' => ['method', 'param', 'property', 'return', 'throws', 'type', 'var'],
    ])
    ->withConfiguredRule(PhpUnitTestCaseStaticMethodCallsFixer::class, [
        'call_type' => 'self',
    ])
    ->withConfiguredRule(ConstantCaseFixer::class, [
        'case' => 'lower',
    ])
    ->withConfiguredRule(SingleImportPerStatementFixer::class, [
        'group_to_single_imports' => false,
    ])
    ->withConfiguredRule(GeneralPhpdocAnnotationRemoveFixer::class, [
        'annotations' => [
            'author',
            'covers',
            'group',
            'package',
            // 'since',
            'subpackage',
            // 'throws',
            'version',
        ],
    ])
    ->withConfiguredRule(MethodArgumentSpaceFixer::class, [
        'on_multiline' => 'ensure_fully_multiline',
        'attribute_placement' => 'standalone',
        'keep_multiple_spaces_after_comma' => false,
        'after_heredoc' => true,
    ])
    ->withConfiguredRule(SingleClassElementPerStatementFixer::class, [
        'elements' => ['property'],
    ])
    ->withConfiguredRule(ConcatSpaceFixer::class, [
        'spacing' => 'one',
    ])
    ->withConfiguredRule(VisibilityRequiredFixer::class, [
        'elements' => ['const', 'method', 'property'],
    ])
    ->withConfiguredRule(PsrAutoloadingFixer::class, [
        'dir' => $workingDirectory . \DIRECTORY_SEPARATOR . 'src',
    ]);
