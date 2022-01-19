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
}