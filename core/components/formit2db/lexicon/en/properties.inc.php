<?php
/**
 * Properties Lexicon Entries for FormIt2db/db2FormIt package.
 */
$_lang['formit2db.formit2db.prefix'] = 'Table prefix of the xPDO package';
$_lang['formit2db.formit2db.packagename'] = 'Package name of the xPDO package';
$_lang['formit2db.formit2db.classname'] = 'Class name of the xPDO object';
$_lang['formit2db.formit2db.tablename'] = 'Table name of the MySQL table (only used if autoPackage is enabled)';
$_lang['formit2db.formit2db.where'] = 'JSON encoded xPDO where clause to update a row instead of creating a new one';
$_lang['formit2db.formit2db.paramname'] = 'Requested POST param to update a row instead of creating a new one';
$_lang['formit2db.formit2db.fieldname'] = 'xPDO fieldname the POST param is compared with to update a row instead of creating a new one';
$_lang['formit2db.formit2db.arrayFormat'] = 'Format to transform form fields that contains array data (i.e. checkboxes) into';
$_lang['formit2db.formit2db.arrayFields'] = 'JSON encoded array of form fields that contains array data i.e. ["field_1", "field_2"]';
$_lang['formit2db.formit2db.removeFields'] = 'JSON encoded array of form fields not saved in the xPDO object i.e. ["field_1", "field_2"]';
$_lang['formit2db.formit2db.autoPackage'] = 'Use the autocreated xPDO Package';

$_lang['formit2db.db2formit.prefix'] = $_lang['formit2db.formit2db.prefix'];
$_lang['formit2db.db2formit.packagename'] = $_lang['formit2db.formit2db.packagename'];
$_lang['formit2db.db2formit.classname'] = $_lang['formit2db.formit2db.classname'];
$_lang['formit2db.db2formit.tablename'] = $_lang['formit2db.formit2db.tablename'];
$_lang['formit2db.db2formit.where'] = 'JSON encoded xPDO where clause to retreive an existing row';
$_lang['formit2db.db2formit.paramname'] = 'Requested REQUEST param to retreive an existing row';
$_lang['formit2db.db2formit.fieldname'] = 'xPDO fieldname the REQUEST param is compared with to retreive an existing row';
$_lang['formit2db.db2formit.arrayFormat'] = 'Format to transform form fields that contains array data (i.e. checkboxes) from';
$_lang['formit2db.db2formit.arrayFields'] = 'JSON encoded array of database fields that are transformed into arrays i.e. ["field_1", "field_2"]';
$_lang['formit2db.db2formit.ignoreFields'] = 'JSON encoded array of database fields that are not retreived into FormIt i.e. ["field_1", "field_2"]';
$_lang['formit2db.db2formit.notFoundRedirect'] = 'ID of the MODX resource the user is redirected to, if the requested row is not found';
$_lang['formit2db.db2formit.autoPackage'] = 'Autocreate the xPDO Package with packagename and tablename';
