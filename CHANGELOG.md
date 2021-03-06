# CHANGELOG

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## v1.2.0 [2017-12-11]

### Added

- Added support for `SectorDecorator`, used by `DiagramDecorator`
- Added support for `DiagramDecorator`

## v1.1.4 [2017-12-11]

### Fixed

- Fix checks and calls to use internal getters

### Change

- Changed copy for `FPDF Exception` message

## v1.1.3 [2017-12-11]

### Fixed

- Fix decorator implementation

## v1.1.2 [2017-12-11]

### Fixed

- Add missed `return` on `FPDFFacade`

## v1.1.1 [2017-12-11]

### Fixed

- Return the parent output for `output()` method
- Return the parent output for `pageNo()` method

## v1.1.0 [2017-12-11]

### Added

- Build a FPDF Interface with the public methods
- Build a FPDF Facade, used to call any public method and to extend it with Decorators
- Build a FPDF Bookmarks Decorator to enhance the user experience

### Changed

- Updated README file to include an example about:
  - How to download `composer`
  - How to use Decorators to enhance the FPDF base functionalities

## v1.0.0 [2017-12-08]

### Added

- Include `FPDF 1.81` library, downloaded from [www.fpdf.org](http://www.fpdf.org)
- Include custom `FPDF Exception` to manage them internally
- Include `composer.json` file with all project details
- Include MIT License
- Include README
- Apply PSR-1 and PSR-2 standards through StyleCI
- Apply file / folder permissions
- Apply suggestions from SensioLabs Insight and Scrutinizer CI
