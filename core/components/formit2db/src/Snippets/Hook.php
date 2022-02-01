<?php
/**
 * Abstract Hook
 *
 * @package formit2db
 * @subpackage snippet
 */

namespace TreehillStudio\FormIt2db\Snippets;

use fiHooks;
use modX;
use siHooks;
use xPDO;

/**
 * Class Hook
 */
abstract class Hook extends Snippet
{
    /**
     * A reference to the fiHooks instance
     * @var fiHooks|siHooks $hook
     */
    protected $hook;

    /**
     * Creates a new Hook instance.
     *
     * @param modX $modx
     * @param fiHooks|siHooks $hook
     * @param array $properties
     */
    public function __construct(modX $modx, $hook, $properties = [])
    {
        parent::__construct($modx, $properties);

        $this->hook = &$hook;
    }

    /**
     * @param string $packagename
     * @return string
     */
    protected function createAutopackage($packagename)
    {
        $packagepath = $this->modx->getOption($packagename . '.core_path', null, $this->modx->getOption('core_path') . 'components/' . $packagename . '/');
        $modelpath = $packagepath . 'model/';

        $schemapath = $modelpath . 'schema/';
        $schemafile = $schemapath . $packagename . '.mysql.schema.xml';
        $manager = $this->modx->getManager();
        $generator = $manager->getGenerator();
        if (!file_exists($schemafile)) {
            // Create the schema folder
            $this->modx->cacheManager->writeTree($schemapath);
            // Create a schema from an existing database table - due to xPDOGenerator restrictions, all tables with the prefix property are added to the scheme
            if (!$generator->writeSchema($schemafile, $packagename, 'xPDOObject', $this->getProperty('prefix'), true)) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not generate XML schema', '', 'FormIt2db Hook');
            }
        }

        if ($this->formit2db->getOption('modxversion') == 2) {
            $generator->parseSchema($schemafile, $modelpath);
        } else {
            // This is wrong
            $generator->parseSchema($schemafile, $modelpath);
        }
        $this->modx->log(xPDO::LOG_LEVEL_WARN, 'autoPackage parameter active', '', 'FormIt2db Hook');
        $this->modx->addPackage($packagename, $modelpath, $this->getProperty('prefix'));
        return $generator->getClassName($this->getProperty('tablename'));
    }
}
