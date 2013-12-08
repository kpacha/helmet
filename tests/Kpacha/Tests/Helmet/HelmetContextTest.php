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

    public function testProfileIsSet()
    {
        $profiles = $this->getMock('Behat\Gherkin\Node\TableNode');
        $plugin = $this->getMock('Kpacha\Helmet\Plugin\Plugin', array('addProfiles'), array(), '', false);
        $plugin->expects($this->once())
                ->method('addProfiles')
                ->with($this->equalTo($profiles));
        $this->mockPluginGetter($plugin);
        $this->subject->theFollowingProfile($profiles);
    }

    public function testAttackLaunch()
    {
        $attackType = 'someAttackType';
        $arguments = $this->getMock('Behat\Gherkin\Node\PyStringNode');
        $plugin = $this->getMock('Kpacha\Helmet\Plugin\Plugin', array('launchAttackWith'), array(), '', false);
        $plugin->expects($this->once())
                ->method('launchAttackWith')
                ->with($this->equalTo($attackType), $this->equalTo($arguments));
        $this->mockPluginGetter($plugin);
        $this->subject->iLaunchAAttackWith($attackType, $arguments);
    }

    public function testOutputIsCheckedWithRegex()
    {
        $arguments = $this->mockArgumentsAndPlugin("A text with some words.", "/(some)/", 1);
        $this->subject->itShouldPassWithRegexp($arguments);
    }

    /**
     * @expectedException     Exception
     */
    public function testOutputIsRejectedByRegex()
    {
        $arguments = $this->mockArgumentsAndPlugin("A text without any key words.", "/(some)/", 2);
        $this->subject->itShouldPassWithRegexp($arguments);
    }

    public function testOutputDoesNotMatch()
    {
        $arguments = $this->mockArgumentsAndPlugin("A text without any key words.", "/(some)/", 1);
        $this->subject->theOutputShouldNotMatch($arguments);
    }

    /**
     * @expectedException     Exception
     */
    public function testOutputMatches()
    {
        $arguments = $this->mockArgumentsAndPlugin("A text with some words.", "/(some)/", 2);
        $this->subject->theOutputShouldNotMatch($arguments);
    }

    public function testOutputIsEquals()
    {
        $arguments = $this->mockArgumentsAndPlugin("A text with some words.", "A text with some words.", 1);
        $this->subject->itShouldPassWithExactly($arguments);
    }

    /**
     * @expectedException     Exception
     */
    public function testOutputIsNotEquals()
    {
        $arguments = $this->mockArgumentsAndPlugin("A text with some words.", "A text with some other words.", 2);
        $this->subject->itShouldPassWithExactly($arguments);
    }

    public function testOutputContains()
    {
        $this->mockPluginOutput("A text with some words.", 1);
        $this->subject->theOutputShouldContain("some");
    }

    /**
     * @expectedException     Exception
     */
    public function testOutputDoesNotContain()
    {
        $this->mockPluginOutput("A text with some words.", 2);
        $this->subject->theOutputShouldContain("other");
    }

    protected function mockArgumentsAndPlugin($output, $argument, $times = 1)
    {
        $arguments = $this->getMock('Behat\Gherkin\Node\PyStringNode', array('getRaw'));
        $arguments->expects($this->exactly($times))->method('getRaw')->will($this->returnValue($argument));
        $this->mockPluginOutput($output, $times);
        return $arguments;
    }

    protected function mockPluginOutput($output, $times)
    {
        $plugin = $this->getMock('Kpacha\Helmet\Plugin\Plugin', array('getOutput'), array(), '', false);
        $plugin->expects($this->exactly($times))
                ->method('getOutput')
                ->will($this->returnValue($output));
        $this->mockPluginGetter($plugin, $times);
    }

    protected function mockPluginGetter($plugin, $times = 1)
    {
        $this->subject = $this->getMock('Kpacha\Helmet\HelmetContext', array('getPlugin'), array(array()));
        $this->subject->expects($this->exactly($times))
                ->method('getPlugin')
                ->will($this->returnValue($plugin));
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
