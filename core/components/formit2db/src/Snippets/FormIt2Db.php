<?php
/**
 * FormIt2db Snippet
 *
 * @package formit2db
 * @subpackage hook
 */

namespace TreehillStudio\FormIt2db\Snippets;

use xPDO;
use xPDOObject;

class FormIt2db extends Hook
{
    /**
     * Get default snippet properties.
     *
     * @return array
     */
    public function getDefaultProperties(): array
    {
        return [
            'prefix' => $this->modx->getOption(xPDO::OPT_TABLE_PREFIX),
            'packagename' => '',
            'classname' => '',
            'tablename' => '',
            'where::associativeJson' => '',
            'paramname' => '',
            'fieldname' => '',
            'arrayFormat' => 'csv',
            'arrayFields::associativeJson' => '[]',
            'removeFields::associativeJson' => '[]',
            'autoPackage::bool' => false,
        ];
    }

    /**
     * Execute the snippet and return the result.
     *
     * @return string
     */
    public function execute(): string
    {
        $prefix = $this->getProperty('prefix');
        $packagename = $this->getProperty('packagename');
        $classname = $this->getProperty('classname');
        $where = $this->getProperty('where');

        $packagepath = $this->modx->getOption($packagename . '.core_path', null, $this->modx->getOption('core_path') . 'components/' . $packagename . '/');
        $modelpath = $packagepath . 'model/';

        if ($this->getProperty('autoPackage')) {
            $classname = $this->createAutopackage($packagename); // CAUTION: the classname can be different after creating the autopackage
        } else {
            $this->modx->addPackage($packagename, $modelpath, $prefix);
        }

        if ($this->getProperty('fieldname')) {
            if ($requestParams = $this->modx->request->getParameters([$this->getProperty('paramname')], 'POST')) {
                $where = (is_array($where)) ? array_merge($where, $requestParams) : $requestParams;
            }
        }

        if (is_array($where)) {
            $dataobject = $this->modx->getObject($classname, $where);
            if (empty($dataobject)) {
                $dataobject = $this->modx->newObject($classname);
            }
        } else {
            $dataobject = $this->modx->newObject($classname);
        }

        if (!is_object($dataobject) || !($dataobject instanceof xPDOObject)) {
            $errorMsg = 'Failed to create object of type: ' . $classname;
            $this->hook->addError('error_message', $errorMsg);
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $errorMsg, '', 'FormIt2db Hook');
            return false;
        }

        $formFields = $this->hook->getValues();
        foreach ($formFields as $field => $value) {
            if (!in_array($field, $this->getProperty('removeFields'))) {
                if (in_array($field, $this->getProperty('arrayFields'))) {
                    switch ($this->getProperty('arrayFormat')) {
                        case 'json':
                            $value = json_encode($value);
                            break;
                        case 'csv' :
                        default :
                            $value = implode(',', $value);
                            break;
                    }
                }
                $dataobject->set($field, $value);
            }
        }

        if (!$dataobject->save()) {
            $errorMsg = 'Failed to save object of type: ' . $classname;
            $this->hook->addError('error_message', $errorMsg);
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $errorMsg, '', 'FormIt2db Hook');
            return false;
        }
        return true;
    }
}
