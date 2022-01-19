<?php
/**
 * FormIt2db Snippet
 *
 * @package formit2db
 * @subpackage hook
 */

namespace TreehillStudio\FormIt2db\Snippets;

use xPDO;

class Db2FormItSnippet extends Hook
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
            'ignoreFields::associativeJson' => '[]',
            'notFoundRedirect::int' => 0,
            'autoPackage::bool' => false,
        ];
    }

    /**
     * Execute the snippet and return the result.
     *
     * @return string
     * @throws /Exception
     */
    public function execute(): string
    {
        $prefix = $this->getProperty('prefix');
        $packagename = $this->getProperty('packagename');
        $classname = $this->getProperty('classname');
        $where = $this->getProperty('where');
        $notFoundRedirect = $this->getProperty('notFoundRedirect');

        $packagepath = $this->modx->getOption($packagename . '.core_path', null, $this->modx->getOption('core_path') . 'components/' . $packagename . '/');
        $modelpath = $packagepath . 'model/';

        if ($this->getProperty('autoPackage')) {
            $schemapath = $modelpath . 'schema/';
            $schemafile = $schemapath . $packagename . '.mysql.schema.xml';
            $manager = $this->modx->getManager();
            $generator = $manager->getGenerator();
            $newFolderPermissions = $this->modx->getOption('new_folder_permissions', null, 0755);
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
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not generate XML schema', '', 'Db2FormIt Hook');
                }
            }
            $generator->parseSchema($schemafile, $modelpath);
            $this->modx->log(xPDO::LOG_LEVEL_WARN, 'autoPackage parameter active', '', 'Db2FormIt Hook');
            $this->modx->addPackage($packagename, $modelpath, $prefix);
            $classname = $generator->getClassName($this->getProperty('tablename'));
        } else {
            $this->modx->addPackage($packagename, $modelpath, $prefix);
        }

        if ($this->getProperty('fieldname')) {
            if ($requestParams = $this->modx->request->getParameters([$this->getProperty('paramname')], 'REQUEST')) {
                $where = (is_array($where)) ? array_merge($where, $requestParams) : $requestParams;
            }
        }

        if (is_array($where)) {
            if ($dataobject = $this->modx->getObject($classname, $where)) {
                $formFields = $dataobject->toArray();
                foreach ($formFields as $field => $value) {
                    if (in_array($field, $this->getProperty('ignoreFields'))) {
                        unset($formFields[$field]);
                    }
                    if (in_array($field, $this->getProperty('arrayFields'))) {
                        switch ($this->getProperty('arrayFormat')) {
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
                $this->hook->setValues($formFields);
            } else {
                if ($notFoundRedirect) {
                    $this->modx->sendRedirect($this->modx->makeUrl($notFoundRedirect));
                }
            }
        } else {
            if ($notFoundRedirect) {
                $this->modx->sendRedirect($this->modx->makeUrl($notFoundRedirect));
            }
        }
        return true;
    }
}
