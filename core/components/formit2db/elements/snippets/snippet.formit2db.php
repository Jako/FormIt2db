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
 * @subpackage formit2db snippet
 */
$prefix = $modx->getOption('prefix', $scriptProperties, $modx->getOption(xPDO::OPT_TABLE_PREFIX), TRUE);
$packagename = $modx->getOption('packagename', $scriptProperties, '', TRUE);
$classname = $modx->getOption('classname', $scriptProperties, '', TRUE);
$tablename = $modx->getOption('tablename', $scriptProperties, '', TRUE);
$where = $modx->fromJson($modx->getOption('where', $scriptProperties, '', TRUE));
$paramname = $modx->getOption('paramname', $scriptProperties, '', TRUE);
$fieldname = $modx->getOption('fieldname', $scriptProperties, $paramname, TRUE);
$arrayFormat = $modx->getOption('arrayFormat', $scriptProperties, 'csv', TRUE);
$arrayFields = $modx->fromJson($modx->getOption('arrayFields', $scriptProperties, '[]', TRUE));
$removeFields = $modx->fromJson($modx->getOption('removeFields', $scriptProperties, '[]', TRUE));
$autoPackage = (boolean) $modx->getOption('autoPackage', $scriptProperties, FALSE);

$packagepath = $modx->getOption('core_path') . 'components/' . $packagename . '/';
$modelpath = $packagepath . 'model/';

$modx->addPackage($packagename, $modelpath, $prefix);
if ($autoPackage) {
	$manager = $modx->getManager();
	$generator = $manager->getGenerator();
	$classname = $generator->getClassName($tablename);
}

if ($fieldname) {
	if (is_array($where)) {
		$where[$fieldname] = $_POST[$paramname];
	} else {
		$where = array($fieldname => $_POST[$paramname]);
	}
}

if (is_array($where)) {
	$dataobject = $modx->getObject($classname, $where);
	if (empty($dataobject)) {
		$dataobject = $modx->newObject($classname);
	}
} else {
	$dataobject = $modx->newObject($classname);
}

if (!is_object($dataobject) || !($dataobject instanceof xPDOObject)) {
	$errorMsg = 'Failed to create object of type: ' . $classname;
	$hook->addError('error_message', $errorMsg);
	return FALSE;
}

$formFields = $hook->getValues();
foreach ($formFields as $field => $value) {
	if (!in_array($field, $removeFields)) {
		if (in_array($field, $arrayFields)) {
			switch ($arrayFormat) {
				case 'json': {
						$value = json_encode($value);
						break;
					}
				case 'csv' :
				default : {
						$value = implode(',', $value);
						break;
					}
			}
		}
		$dataobject->set($field, $value);
	}
}

if (!$dataobject->save()) {
	$errorMsg = 'Failed to save object of type: ' . $classname;
	$hook->addError('error_message', $errorMsg);
	return FALSE;
}
return TRUE;
?>