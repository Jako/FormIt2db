<?php
/**
 * Db2FormIt Hook
 *
 * @package formit2db
 * @subpackage hook
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var fiHooks $hook
 */

use TreehillStudio\FormIt2db\Snippets\Db2FormIt;

$corePath = $modx->getOption('formit2db.core_path', null, $modx->getOption('core_path') . 'components/formit2db/');
/** @var FormIt2db $formit2db */
$formit2db = $modx->getService('formit2db', 'FormIt2db', $corePath . 'model/formit2db/', [
    'core_path' => $corePath
]);

$snippet = new Db2FormIt($modx, $hook, $scriptProperties);
if ($snippet instanceof TreehillStudio\FormIt2db\Snippets\Db2FormIt) {
    return $snippet->execute();
}
return 'TreehillStudio\FormIt2db\Snippets\Db2FormIt class not found';