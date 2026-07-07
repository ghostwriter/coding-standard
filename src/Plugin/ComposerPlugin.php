<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Plugin;

use Composer\Command\BaseCommand;
use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Json\JsonManipulator;
use Composer\Package\Link;
use Composer\Package\Locker;
use Composer\Package\RootPackageInterface;
use Composer\Plugin\Capability\CommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Composer\Util\ProcessExecutor;
use Generator;
use Ghostwriter\CodingStandard\Command\Composer\ComposerBumpCommand;
use Ghostwriter\CodingStandard\Command\Composer\ComposerUpdateCommand;
use Ghostwriter\CodingStandard\Command\ComposerRequireChecker\ComposerRequireCheckerCommand;
use Ghostwriter\CodingStandard\Command\ComposerUnused\ComposerUnusedCommand;
use Ghostwriter\CodingStandard\Command\Infection\InfectionRunCommand;
use Ghostwriter\CodingStandard\Command\Infection\InfectionUpdateConfigCommand;
use Ghostwriter\CodingStandard\Command\Phive\PhiveInstallCommand;
use Ghostwriter\CodingStandard\Command\Phive\PhiveUninstallCommand;
use Ghostwriter\CodingStandard\Command\Phive\PhiveUpdateCommand;
use Ghostwriter\CodingStandard\Command\PHPBench\PHPBenchCommand;
use Ghostwriter\CodingStandard\Command\PHPUnit\PHPUnitMigrateCommand;
use Ghostwriter\CodingStandard\Command\PHPUnit\PHPUnitTestCommand;
use Ghostwriter\CodingStandard\Command\Psalm\PsalmBaselineCommand;
use Ghostwriter\CodingStandard\Command\Psalm\PsalmCommand;
use Ghostwriter\CodingStandard\Command\Psalm\PsalmSecurityCommand;
use Ghostwriter\CodingStandard\WindowsPathConverter;
use RuntimeException;
use Throwable;

use const PATHINFO_EXTENSION;
use const PHP_EOL;

use function array_key_exists;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function is_numeric;
use function iterator_to_array;
use function mb_substr;
use function mb_trim;
use function pathinfo;
use function preg_replace;
use function sprintf;
use function str_contains;
use function str_ends_with;
use function str_starts_with;

final readonly class ComposerPlugin implements Capable, CommandProvider, EventSubscriberInterface, PluginInterface
{
    public function activate(Composer $composer, IOInterface $io): void
    {
        // $io->write('Activating the plugin... ' . self::class);
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
        // $io->write('Deactivating the plugin... ' . self::class);
    }

    public function getCapabilities(): array
    {
        return [
            CommandProvider::class => self::class,
        ];
    }

    /**
     * @return list<BaseCommand>
     */
    public function getCommands(): array
    {
        $processExecutor = new ProcessExecutor();
        $windowsPathConverter = new WindowsPathConverter();

        return [
            new ComposerBumpCommand($processExecutor, $windowsPathConverter),
            new ComposerRequireCheckerCommand($processExecutor, $windowsPathConverter),
            new ComposerUpdateCommand($processExecutor, $windowsPathConverter),
            new ComposerUnusedCommand($processExecutor, $windowsPathConverter),
            new PhiveInstallCommand($processExecutor, $windowsPathConverter),
            new PhiveUninstallCommand($processExecutor, $windowsPathConverter),
            new PhiveUpdateCommand($processExecutor, $windowsPathConverter),
            new InfectionRunCommand($processExecutor, $windowsPathConverter),
            new InfectionUpdateConfigCommand($processExecutor, $windowsPathConverter),
            new PHPBenchCommand($processExecutor, $windowsPathConverter),
            new PHPUnitMigrateCommand($processExecutor, $windowsPathConverter),
            new PHPUnitTestCommand($processExecutor, $windowsPathConverter),
            new PsalmBaselineCommand($processExecutor, $windowsPathConverter),
            new PsalmCommand($processExecutor, $windowsPathConverter),
            new PsalmSecurityCommand($processExecutor, $windowsPathConverter),
        ];
    }

    public function onCommand(): void
    {
        // echo 'The COMMAND event occurs as a command begins', PHP_EOL;
    }

    public function onInit(): void
    {
        // echo 'The INIT event occurs after a Composer instance is done being initialized', PHP_EOL;
    }

    public function onPostArchiveCmd(): void
    {
        // echo 'The post-archive-cmd event occurs after the archive command has been executed', PHP_EOL;
    }

    public function onPostAutoloadDump(): void
    {
        // echo 'The post-autoload-dump event occurs after the autoload file has been generated', PHP_EOL;
    }

    public function onPostCreateProjectCmd(): void
    {
        // echo 'The POST_CREATE_PROJECT_CMD event occurs after the create-project command has been executed', PHP_EOL;
    }

    public function onPostFileDownload(): void
    {
        // echo 'The POST_FILE_DOWNLOAD event occurs after downloading a package dist file', PHP_EOL;
    }

    public function onPostInstallCmd(): void
    {
        // echo 'The post-install-cmd event occurs after the install command has been executed', PHP_EOL;
    }

    public function onPostRootPackageInstall(): void
    {
        // echo 'The POST_ROOT_PACKAGE_INSTALL event occurs after the root package has been installed', PHP_EOL;
    }

    public function onPostStatusCmd(): void
    {
        // echo 'The post-status-cmd event occurs after the status command has been executed', PHP_EOL;
    }

    public function onPostUpdateCmd(): void
    {
        // echo 'The post-update-cmd event occurs after the update command has been executed', PHP_EOL;
    }

    public function onPreArchiveCmd(): void
    {
        // echo 'The PRE_ARCHIVE_CMD event occurs before the update command is executed', PHP_EOL;
    }

    public function onPreAutoloadDump(): void
    {
        // echo 'The PRE_AUTOLOAD_DUMP event occurs before the autoload file is generated', PHP_EOL;
    }

    public function onPreCommandRun(): void
    {
        // echo 'The PRE_COMMAND_RUN event occurs before a command is executed and lets you modify the input arguments/options', PHP_EOL;
    }

    public function onPreFileDownload(): void
    {
        // echo 'The PRE_FILE_DOWNLOAD event occurs before downloading a file', PHP_EOL;
    }

    public function onPreInstallCmd(): void
    {
        // echo 'The PRE_INSTALL_CMD event occurs before the install command is executed', PHP_EOL;
    }

    public function onPrePoolCreate(): void
    {
        // echo 'The PRE_POOL_CREATE event occurs before the Pool of packages is created, and lets you filter the list of packages which is going to enter the Solver', PHP_EOL;
    }

    public function onPreStatusCmd(): void
    {
        // echo 'The PRE_STATUS_CMD event occurs before the status command is executed', PHP_EOL;
    }

    public function onPreUpdateCmd(): void
    {
        // echo 'The PRE_UPDATE_CMD event occurs before the update command is executed', PHP_EOL;
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
        $io->write('Uninstalling the plugin... ' . self::class);
    }

    public static function bump(Event $composerEvent): void
    {
        $io = $composerEvent->getIO();

        if (! file_exists(__DIR__)) {
            self::log('Package not found (probably scheduled for removal); package bumping skipped.', $io);

            return;
        }

        self::log('Bumping package versions...', $io);

        $composer = $composerEvent->getComposer();

        $composerJsonFile = $composer->getConfig()
            ->getConfigSource()
            ->getName();

        $composerLockFile = pathinfo($composerJsonFile, PATHINFO_EXTENSION) === 'json'
            ? mb_substr($composerJsonFile, 0, -4) . 'lock'
            : $composerJsonFile . '.lock';

        $contents = file_get_contents($composerJsonFile);
        if (false === $contents) {
            throw new RuntimeException('Unable to read the composer.json file');
        }

        $composerJson = self::updateComposerDependencies(
            $io,
            new JsonManipulator($contents),
            $composer->getLocker(),
            $composer->getPackage()
        );

        $written = file_put_contents($composerJsonFile, $composerJson);
        if (false === $written) {
            throw new RuntimeException('Unable to write the composer.json file');
        }

        self::updateLockContentHash($composerLockFile, Locker::getContentHash($composerJson));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            /** The INIT event occurs after a Composer instance is done being initialized */
            PluginEvents::INIT => [['onInit']],
            /** The COMMAND event occurs as a command begins */
            PluginEvents::COMMAND => [['onCommand']],
            /** The PRE_FILE_DOWNLOAD event occurs before downloading a file */
            PluginEvents::PRE_FILE_DOWNLOAD => [['onPreFileDownload']],
            /** The POST_FILE_DOWNLOAD event occurs after downloading a package dist file */
            PluginEvents::POST_FILE_DOWNLOAD => [['onPostFileDownload']],
            /** The PRE_COMMAND_RUN event occurs before a command is executed and lets you modify the input arguments/options */
            PluginEvents::PRE_COMMAND_RUN => [['onPreCommandRun']],
            /** The PRE_POOL_CREATE event occurs before the Pool of packages is created, and lets you filter the list of packages which is going to enter the Solver */
            PluginEvents::PRE_POOL_CREATE => [['onPrePoolCreate']],
            /** The POST_ROOT_PACKAGE_INSTALL event occurs after the root package has been installed */
            ScriptEvents::POST_ROOT_PACKAGE_INSTALL => [['onPostRootPackageInstall']],
            /** The POST_CREATE_PROJECT_CMD event occurs after the create-project command has been executed */
            ScriptEvents::POST_CREATE_PROJECT_CMD => [['onPostCreateProjectCmd']],
            /** The post-autoload-dump event occurs after the autoload file has been generated */
            ScriptEvents::POST_AUTOLOAD_DUMP => [['onPostAutoloadDump']],
            /** The PRE_ARCHIVE_CMD event occurs before the update command is executed */
            ScriptEvents::PRE_ARCHIVE_CMD => [['onPreArchiveCmd']],
            /** The POST_ARCHIVE_CMD event occurs after the status command is executed */
            ScriptEvents::POST_ARCHIVE_CMD => [['onPostArchiveCmd']],
            /** The PRE_INSTALL_CMD event occurs before the install command is executed */
            ScriptEvents::PRE_INSTALL_CMD => [['onPreInstallCmd']],
            /** The POST_INSTALL_CMD event occurs after the install command is executed */
            ScriptEvents::POST_INSTALL_CMD => [['onPostInstallCmd'], ['bump']],
            /** The PRE_UPDATE_CMD event occurs before the update command is executed */
            ScriptEvents::PRE_UPDATE_CMD => [['onPreUpdateCmd']],
            /** The POST_UPDATE_CMD event occurs after the update command is executed */
            ScriptEvents::POST_UPDATE_CMD => [['onPostUpdateCmd'], ['bump']],
            /** The PRE_STATUS_CMD event occurs before the status command is executed */
            ScriptEvents::PRE_STATUS_CMD => [['onPreStatusCmd']],
            /** The POST_STATUS_CMD event occurs after the status command is executed */
            ScriptEvents::POST_STATUS_CMD => [['onPostStatusCmd']],
            /** The PRE_AUTOLOAD_DUMP event occurs before the autoload file is generated */
            ScriptEvents::PRE_AUTOLOAD_DUMP => [['onPreAutoloadDump']],
        ];
    }

    /**
     * @param array<string,Link> $links
     *
     * @return Generator<string,string>
     */
    private static function extractVersions(array $links): Generator
    {
        foreach ($links as $packageName => $required) {
            if (! str_contains($packageName, '/')) {
                // only accept packages with `/` separator, ignoring "php" and "ext-*"
                continue;
            }

            yield $packageName => $required->getConstraint()
                ->getPrettyString();
        }
    }

    /**
     * @return Generator<string,string>
     */
    private static function getLockedVersions(Locker $locker, RootPackageInterface $rootPackage): Generator
    {
        /** @var array{packages:list<array{name:string,version:string}>,packages-dev:list<array{name:string,version: string}>} $lockData */
        $lockData = $locker->getLockData();

        foreach (['packages', 'packages-dev'] as $packageType) {
            foreach ($lockData[$packageType] ?? [] as $package) {
                yield $package['name'] => $package['version'];
            }
        }

        foreach ($rootPackage->getReplaces() as $replace) {
            $version = $replace->getPrettyConstraint();

            yield $replace->getTarget() => 'self.version' === $version ? $version : $rootPackage->getVersion();
        }

        yield $rootPackage->getName() => $rootPackage->getVersion();
    }

    private static function log(string $message, IOInterface $io): void
    {
        $io->write(sprintf('<info>ghostwriter/coding-standard:</info> %s', $message));
    }

    private static function updateComposerDependencies(
        IOInterface $IO,
        JsonManipulator $manipulator,
        Locker $locker,
        RootPackageInterface $rootPackage,
    ): string {
        $lockVersions = iterator_to_array(self::getLockedVersions($locker, $rootPackage));

        $keys = [ 'require','require-dev']; //
        foreach ($keys as $configKey) {
            $isDev = 'require-dev' === $configKey;

            $requiredVersions = $isDev ? self::extractVersions($rootPackage->getDevRequires()) : self::extractVersions(
                $rootPackage->getRequires()
            );

            foreach ($requiredVersions as $package => $version) {
                self::log(sprintf('Checking <info>[%s]</info> package <info>%s</info>...', $configKey, $package), $IO);

                if (! array_key_exists($package, $lockVersions)) {
                    self::log(sprintf('Package <info>%s</info> not found in the lock file', $package), $IO);

                    continue;
                }

                // Skip complex ranges for now
                if (
                    str_contains($version, ' ')
                    || str_contains($version, ',')
                    || str_contains($version, '|')
                    || str_contains($version, '@')
                    || str_contains($version, ' as ')
                    || str_ends_with($version, '-dev')
                    || str_starts_with($version, 'dev-')
                ) {
                    self::log(sprintf('Skipping complex range <info>%s</info>', $version), $IO);

                    continue;
                }

                $lockVersion = $lockVersions[$package];
                if (
                    str_contains($lockVersion, '@')
                    || str_starts_with($lockVersion, 'dev-')
                    || str_ends_with($lockVersion, '-dev')
                ) {
                    self::log(sprintf('Skipping dev locked version <info>%s</info>', $lockVersion), $IO);

                    continue;
                }

//                if (mb_ltrim($version, '^v') === mb_ltrim($lockVersion, '^v')) {
//                    self::log(sprintf('Skipping same version <info>%s</info>', $version), $IO);
//
//                    continue;
//                }

//                if (mb_ltrim($version, '^v') === mb_ltrim($lockVersion, '^v')) {
//                    self::log(sprintf('Skipping same version <info>%s</info>', $version), $IO);
//
//                    continue;
//                }

                $lockVersion = (string) preg_replace('#^v(?<version>.*)#', '\1', $lockVersion);

                if (is_numeric($version)) {
                    // Just by checking if the version is numeric
                    // we guarantee that $version is a string
                    // with numbers and dots.
                    // is_numeric($version);

                    $manipulator->addLink($configKey, $package, $lockVersion, true);

                    self::log(
                        sprintf(
                            'Expanding <info>%s</info>%s package from version (<info>%s</info>) to (<info>%s</info>)',
                            $package,
                            $isDev ? ' dev' : '',
                            $version,
                            $lockVersion
                        ),
                        $IO
                    );

                    continue;
                }

//                $constraintPrefix = '~';
                $constraintPrefix = '^';
//                    str_starts_with($version, '~') ? '~' : '^';

                $lockVersionWithPrefix = $constraintPrefix . $lockVersion;

                $manipulator->addLink($configKey, $package, $lockVersionWithPrefix, true);

                self::log(
                    sprintf(
                        'Updating <info>%s</info>%s package from version (<info>%s</info>) to (<info>%s</info>)',
                        $package,
                        $isDev ? ' dev' : '',
                        $version,
                        $lockVersionWithPrefix
                    ),
                    $IO
                );
            }
        }

        return $manipulator->getContents();
    }

    /**
     * @throws Throwable
     */
    private static function updateLockContentHash(string $composerLockFile, string $contentHash): void
    {
        $lockFile = new JsonFile($composerLockFile);

        /** @var array<string,scalar> $lockData */
        $lockData = $lockFile->read();

        $lockData['content-hash'] = $contentHash;

        $lockFile->write($lockData);
    }
}
