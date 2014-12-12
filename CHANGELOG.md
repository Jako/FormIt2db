Changelog
================================================================================

- 1.0
    - Initial public release as package for MODX Revolution
- 1.0.1
    - Add: Param ignoreFields to db2formit
    - Change: Paramname/fieldname of db2formit are compared with the REQUEST array instead of GET array
    - Bugfix: Wrong parameter names in db2formit code (now paramname/fieldname as documented)
- 1.1
    - Add: Param autoPackage - Autocreate the xPDO Package with packagename and tablename
    - Add: Param tablename - tablename for autocreation of the xPDO Package
- 1.1.1
    - Add: Log xPDO Package Errors in MODX Error Log
- 1.1.2
    - Add: autoPackage parameter: Package generation is executed in FormIt2db too. Before it was only active in db2FormIt.