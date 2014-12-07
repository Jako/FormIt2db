<?php
/**
 * FormIt2db/db2FormIt
 *
 * Copyright 2013-2014 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package formit2db
 * @subpackage build
 *
 * Resolvers for the FormIt2db/db2FormIt package.
 */
$resolvers = array();

/* create the resolvers array */
if (file_exists($sources['resolvers'] . 'resolve.remove_tables.php')) {
    $resolvers[] = array(
        'type' => 'php',
        'resolver' => array(
            'source' => $sources['resolvers'] . 'resolve.remove_tables.php'
        )
    );
}
if (is_dir($sources['source_assets'])) {
    $resolvers[] = array(
        'type' => 'file',
        'resolver' => array(
            'source' => $sources['source_assets'],
            'target' => "return MODX_ASSETS_PATH . 'components/';")
    );
}
if (is_dir($sources['source_core'])) {
    $resolvers[] = array(
        'type' => 'file',
        'resolver' => array(
            'source' => $sources['source_core'],
            'target' => "return MODX_CORE_PATH . 'components/';")
    );
}
if (file_exists($sources['resolvers'] . 'resolve.tables.php')) {
    $resolvers[] = array(
        'type' => 'php',
        'resolver' => array(
            'source' => $sources['resolvers'] . 'resolve.tables.php')
    );
}
return $resolvers;
