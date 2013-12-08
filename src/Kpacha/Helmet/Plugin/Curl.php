<?php

namespace Kpacha\Helmet\Plugin;

/**
 * Description of Curl
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class Curl extends Plugin
{
    const COMMAND = 'curl';

    public function __construct($command = self::COMMAND)
    {
        parent::__construct($command);
    }
}
