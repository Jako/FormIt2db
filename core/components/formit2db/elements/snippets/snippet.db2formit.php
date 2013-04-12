<?php
/*
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
 * @subpackage db2formit snippet
 */
$prefix = $modx->getOption('prefix', $scriptProperties, $modx->getOption(xPDO::OPT_TABLE_PREFIX), true);
$packagename = $modx->getOption('packagename', $scriptProperties, '', true);
$classname = $modx->getOption('classname', $scriptProperties, '', true);
$where = $modx->fromJson($modx->getOption('where', $scriptProperties, '', true));
$paramname = $modx->getOption('paramname', $scriptProperties, '', true);
$fieldname = $modx->getOption('fieldname', $scriptProperties, $paramname, true);
$arrayFormat = $modx->getOption('arrayFormat', $scriptProperties, 'csv', true);
$arrayFields = $modx->fromJson($modx->getOption('arrayFields', $scriptProperties, '[]', true));
$ignoreFields = $modx->fromJson($modx->getOption('ignoreFields', $scriptProperties, '[]', true));
$notFoundRedirect = intval($modx->getOption('notFoundRedirect', $scriptProperties, '0'), true);

$packagepath = $modx->getOption('core_path') . 'components/' . $packagename . '/';
$modelpath = $packagepath . 'model/';

$modx->addPackage($packagename, $modelpath, $prefix);

if ($fieldname) {
	if (is_array($where)) {
		$where[$fieldname] = $_REQUEST[$paramname];
	} else {
		$where = array($fieldname => $_REQUEST[$paramname]);
	}
}

if (is_array($where)) {
	if ($dataobject = $modx->getObject($classname, $where)) {
		if (!is_object($dataobject) || !($dataobject instanceof xPDOObject)) {
			$errorMsg = 'Failed to create object of type: ' . $classname;
			$hook->addError('error_message', $errorMsg);
			return false;
		}
		if (empty($dataobject) && $notFoundRedirect) {
			$modx->sendRedirect($modx->makeUrl($notFoundRedirect));
		}
		$formFields = $dataobject->toArray();
		foreach ($formFields as $field => $value) {
			if (in_array($field, $ignoreFields)) {
				unset($formFields[$field]);
			}
			if (in_array($field, $arrayFields)) {
				switch ($arrayFormat) {
					case 'json': {
							$formFields[$field] = json_decode($value, true);
							break;
						}
					case 'csv' :
					default : {
							$formFields[$field] = explode(',', $value);
							break;
						}
				}
			}
		}
		$hook->setValues($formFields);
	}
} else {
	if ($notFoundRedirect) {
		$modx->sendRedirect($modx->makeUrl($notFoundRedirect));
	}
}

return true;
?>