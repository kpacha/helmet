<?php

namespace Kpacha\Tests\Helmet\Plugin;

use Kpacha\Helmet\Plugin\Plugin;

/**
 * Description of PluginTest
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class PluginTest extends \PHPUnit_Framework_TestCase
{

    private $plugin;

    public function testIsInstalled()
    {
        $plugin = new Plugin('ls');
        $this->assertTrue($plugin->isInstalled());
    }

    public function testIsNotInstalled()
    {
        $plugin = new Plugin('unknownBinary');
        $this->assertFalse($plugin->isInstalled());
    }

    /**
     * @dataProvider launchAttackProvider
     */
    public function testLaunchAttack($profiles, $argumentsRaw)
    {
        $outputRaw = "some multi-line\noutput result to parse";
        $output = array(
            'status' => 0,
            'output' => explode("\n", $outputRaw)
        );
        $plugin = $this->getMock('Kpacha\Helmet\Plugin\Plugin', array('exec'), array('ls'));
        $plugin->expects($this->once())->method('exec')->will($this->returnValue($output));

        if (count($profiles)) {
            $mockedProfiles = $this->getMock('Behat\Gherkin\Node\TableNode', array('getRows'));
            $mockedProfiles->expects($this->once())->method('getRows')->will($this->returnValue($profiles));
            $plugin->addProfiles($mockedProfiles);
        }

        $arguments = $this->getMock('Behat\Gherkin\Node\PyStringNode', array('getRaw'));
        $arguments->expects($this->any())->method('getRaw')->will($this->returnValue($argumentsRaw));

        $plugin->launchAttackWith('attackType', $arguments);

        $this->assertEquals($outputRaw, $plugin->getOutput());
    }

    public function launchAttackProvider()
    {
        return array(
            array(array(), '-a'),
            array(array(array('name', 'value'), array('key', 'valueToPut')), '-a <key>')
        );
    }
}
