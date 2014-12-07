<?php
/*
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
 * FormIt2db/db2FormIt snippet.
 */
$properties = array(
	array(
		'name' => 'prefix',
		'desc' => 'prop_formit2db.prefix',
		'type' => 'textfield',
		'options' => '',
		'value' => '',
		'lexicon' => 'formit2db:properties',
	),
	array(
		'name' => 'packagename',
		'desc' => 'prop_formit2db.packagename',
		'type' => 'textfield',
		'options' => '',
		'value' => '',
		'lexicon' => 'formit2db:properties',
	),
	array(
		'name' => 'classname',
		'desc' => 'prop_formit2db.classname',
		'type' => 'textfield',
		'options' => '',
		'value' => '',
		'lexicon' => 'formit2db:properties',
	),
	array(
		'name' => 'tablename',
		'desc' => 'prop_db2formit.tablename',
		'type' => 'textfield',
		'options' => '',
		'value' => '',
		'lexicon' => 'formit2db:properties',
	),
	array(
		'name' => 'where',
		'desc' => 'prop_formit2db.where',
		'type' => 'textfield',
		'options' => '',
		'value' => '',
		'lexicon' => 'formit2db:properties',
	),
	array(
		'name' => 'paramname',
		'desc' => 'prop_formit2db.paramname',
		'type' => 'textfield',
		'options' => '',
		'value' => '',
		'lexicon' => 'formit2db:properties',
	),
	array(
		'name' => 'fieldname',
		'desc' => 'prop_formit2db.fieldname',
		'type' => 'textfield',
		'options' => '',
		'value' => '',
		'lexicon' => 'formit2db:properties',
	),
	array(
		'name' => 'arrayFormat',
		'desc' => 'prop_db2formit.arrayFormat',
		'type' => 'list',
		'options' => array(
			array('text' => 'CSV', 'value' => 'csv'),
			array('text' => 'JSON', 'value' => 'json')
		),
		'value' => 'csv',
		'lexicon' => 'formit2db:properties',
	),
	array(
		'name' => 'arrayFields',
		'desc' => 'prop_db2formit.arrayFields',
		'type' => 'textfield',
		'options' => '',
		'value' => '[]',
		'lexicon' => 'formit2db:properties',
	),
	array(
		'name' => 'removeFields',
		'desc' => 'prop_formit2db.removeFields',
		'type' => 'textfield',
		'options' => '',
		'value' => '[]',
		'lexicon' => 'formit2db:properties',
	),
	array(
		'name' => 'autoPackage',
		'desc' => 'prop_formit2db.autoPackage',
		'type' => 'combo-boolean',
		'options' => '',
		'value' => FALSE,
		'lexicon' => 'formit2db:properties',
	)
);

return $properties;