# FPDF

## Set-up the library

### Before we start

Please make sure you have your `composer` available as a global system binary. If not then use `php composer.phar` instead of `composer`. 

### Install

Just include your package in your `composer.json` file:

```json
{
    "require": {
        "david-garcia/fpdf": "^1.0"
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
use DavidGarciaCat\FPDF\Script\FPDFFacade;
```

Step 3. Create a PDF file:

```php
$fpdf = new FPDFFacade(new FPDF());
$fpdf->output();
```

### Scripts

FPDF allows to build and implement stripts to expand and enhance the experience working with the library.

This project is implementing the currently published Scripts in FPDF website as Design Pattern Decorators.
Just set-up the main library as a Facade and then inject it in any Decorator constructor: 

```php
$fpdf = new FPDFFacade(new FPDF());
$fpdf = BookmarkDecorator($fpdf);
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
