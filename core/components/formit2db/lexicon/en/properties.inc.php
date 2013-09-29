<?php
/**
 * FormIt2db/db2FormIt
 * 
 * Copyright 2013 by Thomas Jakobi <thomas.jakobi@partout.info>
 * 
 * The snippets bases on the code in the following thread in MODX forum 
 * http://forums.modx.com/thread/?thread=32560 
 * 
 * FormIt2db/db2FormIt is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * FormIt2db/db2FormIt is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more 
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * FormIt2db/db2FormIt; if not, write to the Free Software Foundation, Inc., 
 * 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package formit2db
 * @subpackage lexicon
 *
 * FormIt2db/db2FormIt
 */
$_lang['prop_formit2db.prefix'] = 'Table prefix of the xPDO package';
$_lang['prop_formit2db.packagename'] = 'Package name of the xPDO package';
$_lang['prop_formit2db.classname'] = 'Class name of the xPDO object';
$_lang['prop_formit2db.tablename'] = 'Table name of the MySQL table (only used if autoPackage is enabled)';
$_lang['prop_formit2db.where'] = 'JSON encoded xPDO where clause - to update a row instead of creating a new one';
$_lang['prop_formit2db.paramname'] = 'Requested POST param - to update a row instead of creating a new one';
$_lang['prop_formit2db.fieldname'] = 'xPDO fieldname the POST param is compared with - to update a row instead of creating a new one';
$_lang['prop_formit2db.arrayFormat'] = 'Format to transform form fields that contains array data (i.e. checkboxes) into';
$_lang['prop_formit2db.arrayFields'] = 'JSON encoded array of form fields that contains array data i.e. ["field_1", "field_2"]';
$_lang['prop_formit2db.removeFields'] = 'JSON encoded array of form fields not saved in the xPDO object i.e. ["field_1", "field_2"]';
$_lang['prop_formit2db.autoPackage'] = 'Use the autocreated xPDO Package';

$_lang['prop_db2formit.prefix'] = $_lang['prop_formit2db.prefix'];
$_lang['prop_db2formit.packagename'] = $_lang['prop_formit2db.packagename'];
$_lang['prop_db2formit.classname'] = $_lang['prop_formit2db.classname'];
$_lang['prop_db2formit.tablename'] = $_lang['prop_formit2db.tablename'];
$_lang['prop_db2formit.where'] = 'JSON encoded xPDO where clause - to retreive an existing row';
$_lang['prop_db2formit.paramname'] = 'Requested REQUEST param - to retreive an existing row';
$_lang['prop_db2formit.fieldname'] = 'xPDO fieldname the REQUEST param is compared with - to retreive an existing row';
$_lang['prop_db2formit.arrayFormat'] = 'Format to transform form fields that contains array data (i.e. checkboxes) from';
$_lang['prop_db2formit.arrayFields'] = 'JSON encoded array of database fields that are transformed into arrays i.e. ["field_1", "field_2"]';
$_lang['prop_db2formit.ignoreFields'] = 'JSON encoded array of database fields that are not retreived into FormIt i.e. ["field_1", "field_2"]';
$_lang['prop_db2formit.notFoundRedirect'] = 'ID of the MODX resource the user is redirected to, if the requested row is not found';
$_lang['prop_db2formit.autoPackage'] = 'Autocreate the xPDO Package with packagename and tablename';
