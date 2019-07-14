<?php
/**
 * FormIt2db/db2FormIt
 *
 * Copyright 2013-2019 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * The snippets bases on the code in the following thread in MODX forum
 * http://forums.modx.com/thread/?thread=32560
 *
 * @package formit2db
 * @subpackage db2formit snippet
 *
 * db2FormIt snippet
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var fiHooks $hook
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

// $joins = $modx->getOption('joins', $scriptProperties, '[]', true);
$joins = $modx->fromJson($modx->getOption('joins', $scriptProperties, '[]', true));

// Debug joins
/*
echo '<pre>';
var_dump($joins);
echo '</pre>';
*/

// Clear join Array and just maintain names
$joinCriteria = [];

foreach ($joins as $join) {
    $className = key($join) !== 0 ? key($join) : $join[0];
    $joinCriteria[$className] = [];
}


// AutoPackage
if ($autoPackage) {
    $schemapath = $modelpath . 'schema/';
    $schemafile = $schemapath . $packagename . '.mysql.schema.xml';
    $manager = $modx->getManager();
    /** @var xPDOGenerator_mysql|xPDOGenerator_sqlsrv|xPDOGenerator_sqlite $generator */
    $generator = $manager->getGenerator();
    $newFolderPermissions = $modx->getOption('new_folder_permissions', null, 0755);
    if (!file_exists($schemafile)) {
        if (!is_dir($packagepath)) {
            mkdir($packagepath, $newFolderPermissions);
        }
        if (!is_dir($modelpath)) {
            mkdir($modelpath, $newFolderPermissions);
        }
        if (!is_dir($schemapath)) {
            mkdir($schemapath, $newFolderPermissions);
        }
        //Use this to create a schema from an existing database
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
    if ($requestParams = $modx->request->getParameters(array($paramname), 'REQUEST')) {
        $where = (is_array($where)) ? array_merge($where, $requestParams) : $requestParams;
    }
}



// if (is_array($where)) {
    
    if(!$joins) {
        $dataobject = $modx->getObject($classname, $where);
    } else {
        $dataobject = $modx->getCollectionGraph($classname, $joinCriteria, $where);
    }
    

 
    if ($dataobject) {
        
        if ($joins && empty($dataobject)) {
            $errorMsg = 'Failed to create object of type: ' . $classname;
            $hook->addError('error_message', $errorMsg);
            $modx->log(modX::LOG_LEVEL_ERROR, $errorMsg, '', 'db2FormIt Hook');
            return false;
        } elseif ( !$joins && (!is_object($dataobject) || !($dataobject instanceof xPDOObject)) ) {
            $errorMsg = 'Failed to create object of type: ' . $classname;
            $hook->addError('error_message', $errorMsg);
            $modx->log(modX::LOG_LEVEL_ERROR, $errorMsg, '', 'db2FormIt Hook');
            return false;
        }
        
        if (empty($dataobject) && $notFoundRedirect) {
            $modx->sendRedirect($modx->makeUrl($notFoundRedirect));
        }
        
    
        // -- handle joins 
        if($joins) 
        {
        /* V1 - getCollectionGraph */
            foreach ($dataobject as $obj)
            {
                if (is_object($obj))
                {
                    $formFields = $obj->toArray();         
                    
                    foreach($joinCriteria as $jClassName => $value) {
                        $relObj = $obj->getFKDefinition($jClassName);
                        $relCardinality = $relObj['cardinality'];
                        
                        // One
                        switch($relCardinality) {
                            case 'one':
                                $formFields = array_merge($formFields, $obj->toArray($jClassName.'.'));
                                break;
                            case 'many':
                                $i = 0;
                                foreach($obj->$jClassName as $subObj) 
                                {
                                    //print_r($rl->toArray()) ;
                                    $formFields = array_merge($formFields, $subObj->toArray($jClassName.'.'.$i.'.'));
                                }
                                break;
                        }
                    }
                }
            }
     
        
        /* V2 - loop individually */
        
        /*
        $joinObjs = [];
        $joinSettings = [];
        */
        
        /*
        foreach ($joins as $join) {
            // key => value or single value?
            $className = key($join) !== 0 ? key($join) : $join[0];
            $relObj = $dataobject->getFKDefinition($className);
            
            if($relObj) {
                $relClass = $relObj['class'];
                $relCardinality = $relObj['cardinality'];
                
                $joinObj = [];
                
                switch ($relCardinality) {
                    case 'one' :
                        $joinObj = $dataobject->getOne($relClass,$paramname);
                        $joinObj = $joinObj->toArray($relClass.'.');
                        break;
                    case 'many' :
                        $joinObj = $dataobject->getMany($relClass);
                        break;
                }
                
                
                $formFields = array_merge($formFields, $joinObj);
            }
        }
        
        */
        
        }
        // END handle joins --
        
        foreach ($formFields as $field => $value) {
            if (in_array($field, $ignoreFields)) {
                unset($formFields[$field]);
            }
            if (in_array($field, $arrayFields)) {
                switch ($arrayFormat) {
                    case 'json':
                        $formFields[$field] = $value;
                        break;
                    case 'csv' :
                    default :
                        $formFields[$field] = json_encode(explode(',', $value));
                        break;
                }
            }
        }
        
        $hook->setValues($formFields);
    } else {
        if ($notFoundRedirect) {
            $modx->sendRedirect($modx->makeUrl($notFoundRedirect));
    }
}

return true;