<?php
/**
 * Resolve creating db tables
 *
 * THIS RESOLVER IS AUTOMATICALY GENERATED, NO CHANGES WILL APPLY
 *
 * @package formit2db
 * @subpackage build
 */

if ($object->xpdo) {
    /** @var modX $modx */
    $modx =& $object->xpdo;

    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modelPath = $modx->getOption('formit2db.core_path');

            if (empty($modelPath)) {
                $modelPath = '[[++core_path]]components/formit2db/model/';
            }

            if ($modx instanceof modX) {
                $modx->addExtensionPackage('formit2db', $modelPath);
            }

            break;
        case xPDOTransport::ACTION_UNINSTALL:
            if ($modx instanceof modX) {
                $modx->removeExtensionPackage('formit2db');
            }

            break;
    }
}
return true;