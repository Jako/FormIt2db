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
 * @subpackage formit2db snippet
 *
 * FormIt2db snippet
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
$removeFields = $modx->fromJson($modx->getOption('removeFields', $scriptProperties, '[]', true));
$autoPackage = (boolean)$modx->getOption('autoPackage', $scriptProperties, false);

$packagepath = $modx->getOption($packagename . '.core_path', null, $modx->getOption('core_path') . 'components/' . $packagename . '/');
$modelpath = $packagepath . 'model/';

/* TO DO
* 
* `file` setting for (multiple) file/s
* `req` setting checking and error handling
* multi file upload - including formit2file
* implement formit2file
* alias-field separator -- currently '_', automatically through naming convention and HTML behavior
*/

/* Naming convention for fields: 
* Alias prepended to field name, separated by point 
* Example name="Alias.field" 
*/

/* Joins must be JSON notification
* example: 
* &joins=`[
*   {"CommentImages" : {"type" : "file" , "req" : "false"} },
*   {"CommentThumbnail" : {"type" : "file"} },
*   ["SingleClass"]
*   ]`
*/

$joins = $modx->fromJson($modx->getOption('joins', $scriptProperties, '[]', true));
$aliasSeparator = '';

// Debug joins
/*
echo '<pre>';
var_dump($joins);
echo '</pre>';
*/

$packagepath = $modx->getOption($packagename . '.core_path', NULL, $modx->getOption('core_path') . 'components/' . $packagename . '/');
$modelpath = $packagepath . 'model/';

// Set LogLevel for debugging
// $modx->setLogLevel(4);

/*
$log_target = array(
    'target'=>'FILE',
    'options' => array(
        'filename'=>'my_custom.log'
    )
);
*/



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
        // Use this to create a schema from an existing database
        if (!$generator->writeSchema($schemafile, $packagename, 'xPDOObject', $prefix, true)) {
            $modx->log(modX::LOG_LEVEL_ERROR, 'Could not generate XML schema', '', 'FormIt2db Hook');
        }
    }
    $generator->parseSchema($schemafile, $modelpath);
    $modx->log(modX::LOG_LEVEL_WARN, 'autoPackage parameter active', '', 'FormIt2db Hook');
    $modx->addPackage($packagename, $modelpath, $prefix);
    $classname = $generator->getClassName($tablename);
} else {
    $modx->addPackage($packagename, $modelpath, $prefix);
}

if ($fieldname) {
    if ($requestParams = $modx->request->getParameters(array($paramname), 'POST')) {
        $where = (is_array($where)) ? array_merge($where, $requestParams) : $requestParams;
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
    $modx->log(modX::LOG_LEVEL_ERROR, $errorMsg, '', 'FormIt2db Hook');
    return false;
}



$formFields = $hook->getValues();

/*
echo '<pre>';
var_dump($formFields);
echo '</pre>';

die();
*/

// Create Array with data for joined Objects
$joinObjs = [];
$joinSettings = [];

foreach ($joins as $join) {
    // Group Join fields in one array
    $class = key($join) !== 0 ? key($join) : $join[0];
    $joinSettings[$class] = $join[$class];
    if (!array_key_exists($class, $formFields) && !is_array($formFields[$class]) ) {
        $tmpArray = [];
        foreach ($formFields as $field => $val) {
            $skey = $class.'_';
            if( strpos($field, $skey) !== false)  {
                $sfield = substr($field, strlen($skey));
                $tmpArray[$sfield] = $val;
                unset($formFields[$field]);
            }
        }
        $joinObjs[$class] = $tmpArray;

    } elseif (array_key_exists($class, $formFields) && is_array($formFields[$class]) ) {
        $joinObjs[$class] = $formFields[$class];
        unset($formFields[$class]);
    } elseif (array_key_exists($class, $formFields) && isJson($formFields[$class]) ) {
        $joinObjs[$class] = $modx->fromJson($formFields[$class]);
        unset($formFields[$class]);
    }
}


// Debug $formFields after join field removal
/*
echo '<pre>';
var_dump($formFields);
echo '</pre>';

echo '<pre>';
var_dump($joinObjs);
echo '</pre>';
 

die();
*/

// create $dataobject
foreach ($formFields as $field => $value) {
    if (!in_array($field, $removeFields)) {
        if (in_array($field, $arrayFields)) {
            $value = getArrayFormat($value);
        }
        $dataobject->set($field, $value);
    }
}


// add joined classes to $dataobject
foreach ($joinObjs as $className => $classData) {
    $relObj = $dataobject->getFKDefinition($className);
    
    if($joinSettings[$className]['type'] == 'file' && $classData['error'] == 4 ) {
        $hasData = false;
    } else {
        $hasData = array_filter($classData);
    }
    
    if ($relObj && $hasData) {
        $relClass = $relObj['class'];
        $relCardinality = $relObj['cardinality'];
        
        $addObj = $modx->newObject($relClass);
        
        foreach ($classData as $field => $value) {
            if (!empty($value) && isJson($value)) {
                $addObj->fromJson($value);
            } elseif (is_array($value)) {
                $value = getArrayFormat($value);
                $addObj->set($field, $value);
            } else {
                $addObj->set($field, $value);
            }
                
        }
    
        switch ($relCardinality) {
            case 'one' :
                $dataobject->addOne($addObj);
                break;
            case 'many' :
                $dataobject->addMany($addObj);
                break;
        }
        
        /* Debugging      
        $a = $addObj->toArray(); 
        echo 'ADD OBJ <pre>';
        print_r($a); 
        echo '</pre>';
        */
    } elseif (!$hasData) {        
    // } elseif (!$hasData && $joinSettings[$className]['req'] == true) {        
        // $errorMsg = 'Data for a required field is missing!';
        // $hook->addError('error_message', $errorMsg);
        $errorMsg = 'No submitted data for required field: ' . $className . '<br />. Skipping this one.';
        $modx->log(modX::LOG_LEVEL_ERROR, $errorMsg, '', 'FormIt2db Hook');

    } else {
        $errorMsg = 'Could not find related class: ' . $className . '<br /> Skipping this one.';
        $modx->log(modX::LOG_LEVEL_ERROR, $errorMsg, '', 'FormIt2db Hook');
    }                  
}


// Debug dataobject
/*
echo 'DATAOBJ';
$a = $dataobject->toArray(); 
        echo '<pre>';
        print_r($a); 
        echo '</pre>';


die();


$allFormFields = $hook->getValues();
        echo '<pre>';
        print_r($allFormFields); 
        echo '</pre>';

*/


if (!$dataobject->save()) {
    $errorMsg = 'Failed to save object of type: ' . $classname;
    $hook->addError('error_message', $errorMsg);
    $modx->log(modX::LOG_LEVEL_ERROR, $errorMsg, '', 'FormIt2db Hook');
    return false;
}

return true;


/* Functions */

function isJson($string) {
 json_decode($string);
 return (json_last_error() == JSON_ERROR_NONE);
}

function getArrayFormat($value) {
    switch ($arrayFormat) {
        case 'json':
            $value = json_encode($value);
            break;
        case 'csv' :
        default :
            $value = implode(',', $value);
            break;
        }
    return $value;
};


// Not needed
/*
function flatten_array(array $array) {
    $return = array(); 
    array_walk_recursive($array, function($a,$b) use (&$return) { $return[$b] = $a; }); 
    return $return; 
};
*/