<?php

namespace Kpacha\Helmet;

use Behat\Behat\Context\ClosuredContextInterface;
use Behat\Behat\Context\TranslatedContextInterface;
use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Description of Helmet
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class HelmetContext extends BehatContext
{

    /**
     * @var Kpacha\Helmet\Plugin\Plugin
     */
    protected $plugin;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }

    /**
     * @Given /^"([^"]*)" is installed$/
     */
    public function isInstalled($arg1)
    {
        return $this->checkPluginIsInstalled($arg1);
    }

    /**
     * @Given /^the "([^"]*)" command line binary is installed$/
     */
    public function theCommandLineBinaryIsInstalled($arg1)
    {
        return $this->checkPluginIsInstalled('plugin', $arg1);
    }

    protected function checkPluginIsInstalled($pluginName, $argument = null)
    {
        if (!$this->getPlugin($pluginName, $argument)->isInstalled()) {
            throw new \Exception("[$pluginName - $argument] is not installed");
        }
        return true;
    }

    protected function getPlugin($pluginName = null, $argument = null)
    {
        if ($this->plugin === null) {
            $pluginGuesser = new PluginGuesser;
            $this->plugin = $pluginGuesser->getPlugin($pluginName, $argument);
        }
        return $this->plugin;
    }

    /**
     * @Given /^the following profile:$/
     */
    public function theFollowingProfile(TableNode $table)
    {
        $this->getPlugin()->addProfiles($table);
    }

    /**
     * @When /^I launch a "([^"]*)" attack with:$/
     */
    public function iLaunchAAttackWith($attackType, PyStringNode $string)
    {
        $this->getPlugin()->launchAttackWith($attackType, $string);
    }

    /**
     * @Then /^it should pass with regexp:$/
     */
    public function itShouldPassWithRegexp(PyStringNode $string)
    {
        if (!$this->matchRegex($string)) {
            throw new \Exception(
                "Actual output is:\n" . $this->getPlugin()->getOutput() . "\nAnd does not contain : " . $string->getRaw()
            );
        }
    }

    /**
     * @Then /^it should pass with exactly:$/
     */
    public function itShouldPassWithExactly(PyStringNode $string)
    {
        if ($string->getRaw() !== $this->getPlugin()->getOutput()) {
            throw new \Exception(
                "Actual output is:\n" . $this->getPlugin()->getOutput() . "\nAnd is not : " . $string->getRaw()
            );
        }
    }

    /**
     * @Then /^the output should not match:$/
     */
    public function theOutputShouldNotMatch(PyStringNode $string)
    {
        if ($this->matchRegex($string)) {
            throw new \Exception(
                "Actual output is:\n" . $this->getPlugin()->getOutput() . "\nAnd contains : " . $string->getRaw()
            );
        }
    }

    /**
     * @Then /^the output should contain "([^"]*)"$/
     */
    public function theOutputShouldContain($arg1)
    {
        if (strpos($this->getPlugin()->getOutput(), $arg1) === false) {
            throw new \Exception(
                "Actual output is:\n" . $this->getPlugin()->getOutput() . "\nAnd does not contain : " . $arg1
            );
        }
    }

    private function matchRegex(PyStringNode $string)
    {
        $pattern = '@' . trim($string->getRaw(), '/') . '@';
        return preg_match($pattern, $this->getPlugin()->getOutput());
    }
}
