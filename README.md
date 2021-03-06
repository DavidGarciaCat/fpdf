# FPDF

## Set-up the library

### Before we start

You will need to download and install `composer`:

(NOTE: this project is not related with `composer` team so they can change the way to download and use it. Please refer to [getcomposer.org](https://getcomposer.org/download/) to get latest updates about how to get `composer`)

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

Now that you have the `composer.phar` file, you can move it to your project folder, in order to use it as part of it:

```bash
mv composer.phar /path/to/your/project/root/composer.phar

php composer.phar -V
```

(NOTE: your `composer.phar` file should be added into your `.gitignore` file, due it's not a file related with your project)

Or you can move it as a global system binary (the how-to-install process is based on this method):

```bash
mv composer.phar /usr/bin/composer

composer -V
```

### Install

Just include your package in your `composer.json` file:

```json
{
    "require": {
        "david-garcia/fpdf": "^1.1"
    }
}
```

Then run the `composer` command to install the dependency:

```bash
composer install
```

Alternatively you can just `require` the package to deal with all of it automatically:

```bash
composer require david-garcia/fpdf
```

### Usage

Step 1. Include the composer autoload:

```php
require "vendor/autoload.php";
```

Step 2. Include the `use` statements for `FPDF`:

```php
use DavidGarciaCat\FPDF\FPDF;
```

Step 3. Create a PDF file:

```php
$fpdf = new new FPDF();
$fpdf->output();
```

### Scripts

FPDF allows to build and implement stripts to expand and enhance the experience working with the library.

This project is implementing the currently published Scripts in FPDF website as Design Pattern Decorators.
Just set-up the main library as a Facade and then inject it in any Decorator constructor: 

```php
use DavidGarciaCat\FPDF\FPDF;
use DavidGarciaCat\FPDF\Script\BookmarkDecorator;

$fpdf = new BookmarkDecorator(new FPDF());
```

## What is FPDF?

FPDF is a PHP class which allows to generate PDF files with pure PHP, that is to say without using the PDFlib library. F from FPDF stands for Free: you may use it for any kind of usage and modify it to suit your needs.

FPDF has other advantages: high level functions. Here is a list of its main features:

- Choice of measure unit, page format and margins
- Page header and footer management
- Automatic page break
- Automatic line break and text justification
- Image support (JPEG, PNG and GIF)
- Colors
- Links
- TrueType, Type1 and encoding support
- Page compression

FPDF requires no extension (except Zlib to enable compression and GD for GIF support). The latest version requires at least PHP 5.1, however this project is based on PHP 5.5 or newer versions.

Please browse [www.fpdf.org](http://www.fpdf.org/) for Tutorials and Documentation. The tutorials will give you a quick start. The complete online documentation is available OnLine and you can downlaod it in multiple languages. It is strongly advised to read the FAQ which lists the most common questions and issues.

A Script section is available and provides some useful extensions (such as bookmarks, rotations, tables, barcodes...).

## ToDo

- **Provide all FPDF Scripts natively**  
FPDF has several scripts, but we need to download and include all of them manually, in order to use them. This project already moved the FPDF code to `namespaces` and is starting to include other FPDF Scripts, however the real goal is to include all these functionalities natively, as part of the project.
- **Scrutinizer CI Code coverage**  
Base FPDF source code, downloadable at [www.fpdf.org](http://www.fpdf.org/)'s website, does not include automated tests, so there's no code coverage. This project wants to provide tests, in order to enhance the code quality and offer the expected warranties that all of us want for a project like this.
- **Scrutinizer CI Code score**  
Base FPDF and FPDF Scripts are probably not designed in the right way, and code score is really poor due a high complexity and some missed checks. This project wants to improvide the code quality.
