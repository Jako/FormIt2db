<?php
/**
 * FormIt2db/db2FormIt
 *
 * Copyright 2013-2015 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * The snippets bases on the code in the following thread in MODX forum
 * http://forums.modx.com/thread/?thread=32560
 *
 * @package formit2db
 * @subpackage db2formit snippet
 *
 * db2FormIt snippet
 */
$prefix = $modx->getOption('prefix', $scriptProperties, $modx->getOption(xPDO::OPT_TABLE_PREFIX), true);
$packagename = $modx->getOption('packagename', $scriptProperties, '', true);
$classname = $modx->getOption('classname', $scriptProperties, '', true);
$tablename = $modx->getOption('tablename', $scriptProperties, '', true);
$where = $modx->fromJson($modx->getOption('where', $scriptProperties, '', true));
$paramname = $modx->getOption('paramname', $scriptProperties, '', true);
$fieldname = $modx->getOption('fieldname', $scriptProperties, $paramname, true);
$arrayFormat = $modx->getOption('arrayFormat', $scriptProperties, 'csv', true);
$arrayFields = $modx->fromJson($modx->getOption('arrayFields', $scriptProperties, '[]', true));
$ignoreFields = $modx->fromJson($modx->getOption('ignoreFields', $scriptProperties, '[]', true));
$notFoundRedirect = (integer)$modx->getOption('notFoundRedirect', $scriptProperties, '0', true);
$autoPackage = (boolean)$modx->getOption('autoPackage', $scriptProperties, false);

$packagepath = $modx->getOption($packagename . '.core_path', null, $modx->getOption('core_path') . 'components/' . $packagename . '/');
$modelpath = $packagepath . 'model/';

if ($autoPackage) {
    $schemapath = $modelpath . 'schema/';
    $schemafile = $schemapath . $packagename . '.mysql.schema.xml';
    $manager = $modx->getManager();
    $generator = $manager->getGenerator();
    if (!file_exists($schemafile)) {

        if (!is_dir($packagepath)) {
            mkdir($packagepath, 0777);
        }
        if (!is_dir($modelpath)) {
            mkdir($modelpath, 0777);
        }
        if (!is_dir($schemapath)) {
            mkdir($schemapath, 0777);
        }
        // Use this to create a schema from an existing database
        if (!$generator->writeSchema($schemafile, $packagename, 'xPDOObject', $prefix, true)) {
            $modx->log(modX::LOG_LEVEL_ERROR, 'Could not generate XML schema', '', 'db2FormIt Hook');
        }
    }
    $generator->parseSchema($schemafile, $modelpath);
    $modx->log(modX::LOG_LEVEL_WARN, 'autoPackage parameter active', '', 'db2FormIt Hook');
    $modx->addPackage($packagename, $modelpath, $prefix);
    $classname = $generator->getClassName($tablename);
} else {
    $modx->addPackage($packagename, $modelpath, $prefix);
}

if ($fieldname) {
    if (is_array($where)) {
        $where[$fieldname] = $modx->request->getParameters(array($paramname), 'REQUEST');
    } else {
        $where = array($fieldname => $modx->request->getParameters(array($paramname), 'REQUEST'));
    }
}

if (is_array($where)) {
    if ($dataobject = $modx->getObject($classname, $where)) {
        $formFields = $dataobject->toArray();
        foreach ($formFields as $field => $value) {
            if (in_array($field, $ignoreFields)) {
                unset($formFields[$field]);
            }
            if (in_array($field, $arrayFields)) {
                switch ($arrayFormat) {
                    case 'json': {
                        $formFields[$field] = $value;
                        break;
                    }
                    case 'csv' :
                    default : {
                        $formFields[$field] = json_encode(explode(',', $value));
                        break;
                    }
                }
            }
        }
        $hook->setValues($formFields);
    } else {
        if ($notFoundRedirect) {
            $modx->sendRedirect($modx->makeUrl($notFoundRedirect));
        }
    }
} else {
    if ($notFoundRedirect) {
        $modx->sendRedirect($modx->makeUrl($notFoundRedirect));
    }
}

return true;