<?php
/**
 * Define an url field.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2020
 * @license MIT
 */

namespace Laramore\Fields;

class Url extends Uri
{
    /**
     * The url must be defined with http or https.
     *
     * @var bool
     */
    protected $secured;

    /**
     * Return the protocol pattern.
     *
     * @return string
     */
    public function getProtocolPattern(): string
    {
        return $this->getConfig($this->secured ? 'patterns.secured_protocol' : 'patterns.protocol');
    }
}
