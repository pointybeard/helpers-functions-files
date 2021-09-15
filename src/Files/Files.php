<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Functions\Files;

use pointybeard\Helpers\Functions\Cli;
use pointybeard\Helpers\Functions\Flags;

const FLAG_FORCE = 0x0001;

/*
 * Create a symbolic link of target file or directory at specified destination
 *
 * @param  string  $target      path to target file or directory
 * @param  ?string $name        name of the link. This is optional. If ommitted, basename($target) is assumed
 * @param  int  $flags          null or FLAG_FORCE to force creation
 *
 * @return void
 *
 * @throws Exceptions\Symlink\DestinationExistsException
 * @throws Exceptions\Symlink\TargetMissingException
 * @throws Exceptions\Symlink\CreationFailedException
 */
if (!function_exists(__NAMESPACE__."\create_symbolic_link")) {
    function create_symbolic_link(string $target, ?string $name = null, int $flags = null): void
    {
        $name = $name ?? basename($target);
        $target = rtrim($target, '/');

        if (false == Flags\is_flag_set($flags, FLAG_FORCE) && true == file_exists(getcwd()."/{$name}")) {
            throw new Exceptions\Symlink\DestinationExistsException(getcwd()."/{$name}");
        }

        if (false == realpath($target)) {
            throw new Exceptions\Symlink\TargetMissingException($target);
        }

        $old_error_handler = set_error_handler(function (int $number, string $message) {
            throw new \Exception("error $number: '$message'");
        });

        try {
            //symlink($target, $name);
            Cli\run_command(sprintf(
                'ln -v -s %s %s %s',
                true == Flags\is_flag_set($flags, FLAG_FORCE)
                ? '-f'
                : null,
                $target,
                $name
            ), $output, $error);
        } catch (Throwable | Cli\Exceptions\RunCommandFailedException $ex) {
            throw new Exceptions\Symlink\CreationFailedException(
                sprintf(
                    '%s/%s',
                    rtrim(getcwd(), '/'),
                    null == $name
                    ? basename($target)
                    : $name
                ),
                $ex->getMessage()
            );
        } finally {
            set_error_handler($old_error_handler);
        }
    }
}

/*
 * Create all directories in a given path
 *
 * @param string $path    path to realise. Any directories in the path (i.e. parents) will be created as well
 * @param  int  $flags    null or FLAG_FORCE to avoid throwing AlreadyExistsException
 *
 * @return void
 *
 * @throws Exceptions\Directory\AlreadyExistsException
 * @throws Exceptions\Directory\UnableToCreateDirectoryException
 * @throws Exceptions\Directory\CreationFailedException
 */
if (!function_exists(__NAMESPACE__.'\realise_directory')) {
    function realise_directory(string $path, int $flags = null): void
    {
        if (false == Flags\is_flag_set($flags, FLAG_FORCE) && true == is_dir($path)) {
            throw new Exceptions\Directory\AlreadyExistsException($path);
        }

        $old_error_handler = set_error_handler(function (int $number, string $message) {
            throw new \Exception("error $number: '$message'");
        });

        try {
            Cli\run_command(sprintf(
                'mkdir --parents --verbose "%s"',
                $path
            ));

            if (false == is_dir($path)) {
                throw new Exceptions\Directory\CreationFailedException($path, 'directory could not be created for some unknown reason.');
            }
        } catch (Cli\Exceptions\RunCommandFailedException $ex) {
            throw new Exceptions\Directory\CreationFailedException($path, $ex->getError());
        } finally {
            set_error_handler($old_error_handler);
        }
    }
}
