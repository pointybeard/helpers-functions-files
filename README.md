# PHP Helpers: File Functions

-   Version: v1.0.0
-   Date: April 07 2020
-   [Release notes](https://github.com/pointybeard/helpers-functions-files/blob/master/CHANGELOG.md)
-   [GitHub repository](https://github.com/pointybeard/helpers-functions-files)

A collection of helpful functions related to creating and modifying files and directories

## Installation

This library is installed via [Composer](http://getcomposer.org/). To install, use `composer require pointybeard/helpers-functions-files` or add `"pointybeard/helpers-functions-files": "~1.0"` to your `composer.json` file.

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

### Requirements

This library makes use of the [pointybeard/helpers-exceptions-readabletrace](https://github.com/pointybeard/helpers-exceptions-readabletrace), [pointybeard/helpers-functions-flags](https://github.com/pointybeard/pointybeard/helpers-functions-flags), and [pointybeard/helpers-functions-cli](https://github.com/pointybeard/helpers-functions-cli) packages. They are installed automatically via composer.

To include all the [PHP Helpers](https://github.com/pointybeard/helpers) packages on your project, use `composer require pointybeard/helpers` or add `"pointybeard/helpers": "~1.2"` to your composer file.

## Usage

This library is a collection of helpful functions related to creating and modifying files and directories. They are included by the vendor autoloader automatically. The functions have a namespace of `pointybeard\Helpers\Functions\Files`

The following functions are provided:

-   `realise_directory(string $path, int $flags = null): void`
-   `create_symbolic_link(string $target, ?string $destination, int $flags = null): void`

Example usage:

```php
<?php

declare(strict_types=1);
include __DIR__.'/vendor/autoload.php';

use pointybeard\Helpers\Functions\Files;

// Make a test directory and make sure that's where we're working from
Files\realise_directory('test/dest/', Files\FLAG_FORCE);

// Creating a symbolic link requires the cwd to be the destination for the link
chdir('./test');

/*** Example 1: Create a symbolic link in ./test ***/
Files\create_symbolic_link('../README.md', null, Files\FLAG_FORCE);
var_dump(file_exists('README.md'), is_link('README.md'));
// bool(true)
// bool(true)

/*** Example 2: Try to create the same symbolic link again, but this time without ***/
// using Files\FLAG_FORCE
try {
    Files\create_symbolic_link('../README.md');
} catch (Files\Exceptions\Symlink\DestinationExistsException $ex) {
    var_dump('ERROR: '.$ex->getMessage());
}
// string(46) "ERROR: Symbolic link README.md already exists."

/*** Example 3: Attempt to make a link to a file that doesn't exist ***/
try {
    Files\create_symbolic_link('../NOTAFILE');
} catch (Files\Exceptions\Symlink\TargetMissingException $ex) {
    var_dump('ERROR: '.$ex->getMessage());
}
// string(55) "ERROR: Symbolic link target ../NOTAFILE does not exist."

/*** Example 4: Attempt to create a symbolic link where we don't have permissions to ***/
try {
    // Go somewhere we're not supposed to
    chdir('/');
    Files\create_symbolic_link(realpath(__DIR__.'/LICENCE'), 'naughty');
} catch (Files\Exceptions\Symlink\CreationFailedException $ex) {
    var_dump('ERROR: '.$ex->getMessage());
}
//string(160) "ERROR: Symbolic link '/naughty' could not be created. Returned:
//  Failed to run command. Returned: ln: failed to create symbolic link 'naughty': Permission denied"

```

## Support

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/pointybeard/helpers-functions-files/issues), or better yet, fork the library and submit a pull request.

## Contributing

We encourage you to contribute to this project. Please check out the [Contributing documentation](https://github.com/pointybeard/helpers-functions-files/blob/master/CONTRIBUTING.md) for guidelines about how to get involved.

## License

"PHP Helpers: File Functions" is released under the [MIT License](http://www.opensource.org/licenses/MIT).
