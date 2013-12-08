<?php

namespace Kpacha\Tests\Helmet;

use Kpacha\Helmet\PluginGuesser;

/**
 * Description of PluginGuesserTest
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class PluginGuesserTest extends \PHPUnit_Framework_TestCase
{

    private $guesser;

    public function setUp()
    {
        $this->guesser = new PluginGuesser;
    }

    /**
     * @expectedException     Exception
     */
    public function testGetPluginThrowsExceptionForUnknownPlugins()
    {
        $this->guesser->getPlugin('unknown');
    }

    public function testGetGenericPlugin()
    {
        $binary = 'binary';
        $plugin = $this->guesser->getPlugin('plugin', $binary);
        $this->assertInstanceOf('Kpacha\Helmet\Plugin\Plugin', $plugin);
    }

    /**
     * @dataProvider    getPluginProvider
     */
    public function testGetPlugin($pluginName)
    {
        $plugin = $this->guesser->getPlugin($pluginName);
        $this->assertInstanceOf('Kpacha\Helmet\Plugin\\' . ucfirst($pluginName), $plugin);
    }
    
    public function getPluginProvider()
    {
        return array(array('curl'), array('nmap'));
    }
}
