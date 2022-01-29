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

use TreehillStudio\FormIt2Db\Snippets\Db2FormItSnippet;

$corePath = $modx->getOption('formit2db.core_path', null, $modx->getOption('core_path') . 'components/formit2db/');
/** @var FormIt2Db $formit2db */
$formit2db = $modx->getService('formit2db', 'FormIt2Db', $corePath . 'model/formit2db/', [
    'core_path' => $corePath
]);

$snippet = new Db2FormItSnippet($modx, $hook, $scriptProperties);
if ($snippet instanceof TreehillStudio\FormIt2Db\Snippets\Db2FormItSnippet) {
    return $snippet->execute();
}
return 'TreehillStudio\FormIt2Db\Snippets\Db2FormItSnippet class not found';