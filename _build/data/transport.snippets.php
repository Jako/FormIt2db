<?php
/**
 * FormIt2db/db2FormIt
 * 
 * Copyright 2013-2014 by Thomas Jakobi <thomas.jakobi@partout.info>
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
 * @subpackage build
 *
 * FormIt2db/db2FormIt package
 */
$snippets = array();

$snippets[1] = $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
	'id' => 1,
	'name' => 'FormIt2db',
	'description' => 'FormIt to DB hook.',
	'snippet' => getSnippetContent($sources['snippets'] . 'snippet.formit2db.php'),
		), '', true, true);
$properties = include $sources['properties'] . 'properties.formit2db.php';
$snippets[1]->setProperties($properties);
unset($properties);

$snippets[2] = $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
	'id' => 2,
	'name' => 'db2FormIt',
	'description' => 'DB to FormIt hook.',
	'snippet' => getSnippetContent($sources['snippets'] . 'snippet.db2formit.php'),
		), '', true, true);
$properties = include $sources['properties'] . 'properties.db2formit.php';
$snippets[2]->setProperties($properties);
unset($properties);

return $snippets;