<?php

declare(strict_types=1);

use Rector\PHPUnit\Set\PHPUnitSetList;
use PHPUnit\Framework\TestCase;
use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\Config\RectorConfig;
use Rector\PHPUnit\CodeQuality\Rector\ClassMethod\RemoveEmptyTestMethodRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\AddSeeTestAnnotationRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\ConstructClassMethodToSetUpTestCaseRector;
use Rector\PHPUnit\CodeQuality\Rector\Foreach_\SimplifyForeachInstanceOfRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertCompareToSpecificMethodRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertComparisonToSpecificMethodRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertEmptyNullableObjectToAssertInstanceofRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertEqualsOrAssertSameFloatParameterToSpecificMethodsTypeRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertEqualsToSameRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertFalseStrposToContainsRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertInstanceOfComparisonRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertIssetToSpecificMethodRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertNotOperatorRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertPropertyExistsRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertRegExpRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertSameBoolNullToSpecificMethodRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertSameTrueFalseToAssertTrueFalseRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertTrueFalseToSpecificMethodRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\RemoveExpectAnyFromMockRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\UseSpecificWillMethodRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\UseSpecificWithMethodRector;
use Rector\PHPUnit\PHPUnit50\Rector\StaticCall\GetMockRector;
use Rector\PHPUnit\PHPUnit60\Rector\ClassMethod\ExceptionAnnotationRector;
use Rector\PHPUnit\PHPUnit60\Rector\MethodCall\DelegateExceptionArgumentsRector;
use Rector\PHPUnit\PHPUnit60\Rector\MethodCall\GetMockBuilderGetMockToCreateMockRector;
use Rector\PHPUnit\PHPUnit70\Rector\Class_\RemoveDataProviderTestPrefixRector;
use Rector\PHPUnit\PHPUnit80\Rector\MethodCall\AssertEqualsParameterToSpecificMethodsTypeRector;
use Rector\PHPUnit\PHPUnit80\Rector\MethodCall\SpecificAssertContainsRector;
use Rector\PHPUnit\PHPUnit80\Rector\MethodCall\SpecificAssertInternalTypeRector;
use Rector\PHPUnit\PHPUnit90\Rector\Class_\TestListenerToHooksRector;
use Rector\PHPUnit\PHPUnit90\Rector\MethodCall\ExplicitPhpErrorApiRector;
use Rector\PHPUnit\PHPUnit90\Rector\MethodCall\SpecificAssertContainsWithoutIdentityRector;
use Rector\PHPUnit\Rector\Class_\PreferPHPUnitSelfCallRector;
use Rector\PHPUnit\Rector\ClassMethod\CreateMockToAnonymousClassRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\ValueObject\PhpVersion;

$workingDirectory = \getcwd() ?: __DIR__;

$existingPaths = \array_filter(
    [$workingDirectory . '/config', $workingDirectory . '/src', $workingDirectory . '/tests'],
    static fn (string $path): bool => \file_exists($path)
);

$existingSkips = \array_merge(
    \array_filter([$workingDirectory . '/tests/Fixture'], static fn (string $path): bool => \file_exists($path)),
    ['Fixture', 'Analyzer']
);

$existingSkips[] = CallableThisArrayToAnonymousFunctionRector::class;

return RectorConfig::configure()
    ->withAttributesSets(phpunit: true)
    ->withConfiguredRule(RenameMethodRector::class, [
        new MethodCallRename(TestCase::class, 'setExpectedException', 'expectedException'),
        new MethodCallRename(TestCase::class, 'setExpectedExceptionRegExp', 'expectedException'),
    ])
    ->withImportNames(removeUnusedImports: true)
    ->withPaths($existingPaths)
    ->withPhpSets(php82: true)
    ->withPhpVersion(PhpVersion::PHP_80)
    ->withPreparedSets(
        deadCode: false,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        naming: true,
        instanceOf: true,
        earlyReturn: true,
        strictBooleans: true
    )
    ->withRootFiles()
    ->withRules([
        AddSeeTestAnnotationRector::class,
        StringClassNameToClassConstantRector::class,
        AddVoidReturnTypeWhereNoReturnRector::class,
        AssertCompareToSpecificMethodRector::class,
        AssertComparisonToSpecificMethodRector::class,
        AssertEmptyNullableObjectToAssertInstanceofRector::class,
        AssertEqualsOrAssertSameFloatParameterToSpecificMethodsTypeRector::class,
        AssertEqualsParameterToSpecificMethodsTypeRector::class,
        AssertEqualsToSameRector::class,
        AssertFalseStrposToContainsRector::class,
        AssertInstanceOfComparisonRector::class,
        AssertIssetToSpecificMethodRector::class,
        AssertNotOperatorRector::class,
        AssertPropertyExistsRector::class,
        AssertRegExpRector::class,
        AssertSameBoolNullToSpecificMethodRector::class,
        AssertSameTrueFalseToAssertTrueFalseRector::class,
        AssertTrueFalseToSpecificMethodRector::class,
        ConstructClassMethodToSetUpTestCaseRector::class,
        DelegateExceptionArgumentsRector::class,
        ExceptionAnnotationRector::class,
        ExplicitPhpErrorApiRector::class,
        GetMockBuilderGetMockToCreateMockRector::class,
        GetMockRector::class,
        PreferPHPUnitSelfCallRector::class,
        RemoveDataProviderTestPrefixRector::class,
        RemoveEmptyTestMethodRector::class,
        RemoveExpectAnyFromMockRector::class,
        RestoreDefaultNullToNullableTypePropertyRector::class,
        SimplifyForeachInstanceOfRector::class,
        SpecificAssertContainsRector::class,
        SpecificAssertContainsWithoutIdentityRector::class,
        SpecificAssertInternalTypeRector::class,
        TestListenerToHooksRector::class,
        UseSpecificWillMethodRector::class,
        UseSpecificWithMethodRector::class,
        CreateMockToAnonymousClassRector::class,
    ])
    ->withSets([PHPUnitSetList::PHPUNIT_100])
    ->withSkip($existingSkips);
