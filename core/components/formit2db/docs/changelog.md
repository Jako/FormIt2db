# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.2.0] - 2022-02-01

### Changed

- Code refactoring
- Full MODX 3 compatibility

## [1.1.5] - 2019-08-19

### Changed

- PHP/MODX version check
- Normalized/refactored code 

## [1.1.4] - 2015-12-30

### Changed

- Bugfix: Wrong usage of $modx->request->getParameters

## [1.1.3] - 2015-01-17

### Changed

- Bugfix: notFoundRedirect is triggered right
- Bugfix: Don't check/log xPDO Package Errors in db2FormIt hook
- Build by Git-Package-Management

## [1.1.2] - 2014-12-08

### Added

- Add: autoPackage parameter: Package generation is executed in FormIt2db too. Before it was only active in db2FormIt.

## [1.1.1] - 2014-12-07

### Added

- Add: Log xPDO Package Errors in MODX Error Log

## [1.1] - 2013-09-29

### Added

- Param autoPackage - Autocreate the xPDO Package with packagename and tablename
- Param tablename - tablename for autocreation of the xPDO Package

## [1.0.1] - 2013-04-12

### Added

- Param ignoreFields to db2FormIt

### Changed

- Paramname/fieldname of db2FormIt are compared with the REQUEST array instead of GET array
- Bugfix: Wrong parameter names in db2FormIt code (now paramname/fieldname as documented)

## [1.0.0] - 2013-01-03

### Added

- Initial release for MODX Revolution
