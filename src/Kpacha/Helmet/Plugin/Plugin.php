<?php

namespace Kpacha\Helmet\Plugin;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class Plugin
{

    /**
     * @var string
     */
    private $command;

    /**
     * @var string
     */
    private $output;

    /**
     * @var TableNode 
     */
    private $profileTable;

    public function __construct($command = '')
    {
        $this->command = $command;
    }

    public function addProfiles(TableNode $table)
    {
        $this->profileTable = $table;
    }

    public function isInstalled()
    {
        $return = $this->exec("type $this->command");
        return $return['status'] === 0;
    }

    public function launchAttackWith($attackType, PyStringNode $string)
    {
        $return = $this->exec($this->getCommand($string));
        $this->output = trim(implode("\n", $return['output']));
    }

    public function getOutput()
    {
        return $this->output;
    }

    protected function exec($command)
    {
        $return = array('output' => null, 'status' => null);
        exec($command, $return['output'], $return['status']);
        return $return;
    }

    protected function getCommand(PyStringNode $string)
    {
        return $this->command . ' ' . $this->parseParameters($string);
    }

    protected function parseParameters(PyStringNode $string)
    {
        $rawParams = $string->getRaw();
        preg_match_all("/<(\w*)>/", $rawParams, $parameters);
        foreach ($parameters[1] as $parameter) {
            $rawParams = str_replace("<$parameter>", $this->getParameter($parameter), $rawParams);
        }
        return $rawParams;
    }

    protected function getParameter($name)
    {
        $value = null;
        $profiles = $this->profileTable->getRows();
        $keys = array_shift($profiles);
        foreach ($profiles as $profile) {
            $profile = array_combine($keys, $profile);
            if ($profile['name'] === $name) {
                $value = $profile['value'];
                break;
            }
        }
        return $value;
    }

}