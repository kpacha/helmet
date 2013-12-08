<?php

namespace Kpacha\Helmet\Plugin;

/**
 * Description of Nmap
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class Nmap extends Plugin
{
    const COMMAND = 'nmap';

    public function __construct($command = self::COMMAND)
    {
        parent::__construct($command);
    }
}
