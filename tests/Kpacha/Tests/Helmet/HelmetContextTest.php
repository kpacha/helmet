<?php

namespace Kpacha\Tests\Helmet;

/**
 * Description of HelmetContextTest
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class HelmetContextTest extends \PHPUnit_Framework_TestCase
{

    public function testCheckForInstalledPlugin()
    {
        $extension = 'installedExtension';
        $plugin = $this->mockPluginIsInstalledMethod(true);
        $this->mockPluginGuessing($extension, $plugin);

        $this->assertTrue($this->subject->isInstalled($extension));
    }

    /**
     * @expectedException     Exception
     */
    public function testThrowExceptionForNotInstalledPlugin()
    {
        $extension = 'unknown';
        $plugin = $this->mockPluginIsInstalledMethod(false);
        $this->mockPluginGuessing($extension, $plugin);

        $this->subject->isInstalled($extension);
    }

    public function testCheckForInstalledCommandLine()
    {
        $binary = 'installedCLBinary';
        $plugin = $this->mockPluginIsInstalledMethod(true);
        $this->mockGenericPluginGuessing($binary, $plugin);

        $this->assertTrue($this->subject->theCommandLineBinaryIsInstalled($binary));
    }

    /**
     * @expectedException     Exception
     */
    public function testThrowExceptionForNotInstalledCommandLine()
    {
        $binary = 'unknownCLBinary';
        $plugin = $this->mockPluginIsInstalledMethod(false);
        $this->mockGenericPluginGuessing($binary, $plugin);

        $this->subject->theCommandLineBinaryIsInstalled($binary);
    }

    protected function mockPluginGuessing($pluginName, $plugin)
    {
        $this->subject = $this->getMock('Kpacha\Helmet\HelmetContext', array('getPlugin'), array(array()));
        $this->subject->expects($this->once())
                ->method('getPlugin')
                ->with($this->equalTo($pluginName), $this->equalTo(null))
                ->will($this->returnValue($plugin));
    }

    protected function mockGenericPluginGuessing($binary, $plugin)
    {
        $this->subject = $this->getMock('Kpacha\Helmet\HelmetContext', array('getPlugin'), array(array()));
        $this->subject->expects($this->once())
                ->method('getPlugin')
                ->with($this->equalTo('plugin'), $this->equalTo($binary))
                ->will($this->returnValue($plugin));
    }

    protected function mockPluginIsInstalledMethod($return)
    {
        $plugin = $this->getMock('Kpacha\Helmet\Plugin\Plugin', array('isInstalled'), array(), '', false);
        $plugin->expects($this->once())
                ->method('isInstalled')
                ->will($this->returnValue($return));
        return $plugin;
    }

}
