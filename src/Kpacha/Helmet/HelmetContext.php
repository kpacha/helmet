<?php

namespace Kpacha\Helmet;

use Behat\Behat\Context\ClosuredContextInterface;
use Behat\Behat\Context\TranslatedContextInterface;
use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Kpacha\Helmet\Plugin\Plugin;

/**
 * Description of Helmet
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class HelmetContext extends BehatContext
{

    /**
     *
     * @var Kpacha\Helmet\Plugin\Plugin
     */
    private $plugin;

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
        $pluginClassName = 'Kpacha\Helmet\Plugin\\' . ucfirst($arg1);
        $this->plugin = new $pluginClassName;
        if (!$this->plugin->isInstalled()) {
            throw new Exception("The extension [$arg1] is not installed");
        }
    }

    /**
     * @Given /^the "([^"]*)" command line binary is installed$/
     */
    public function theCommandLineBinaryIsInstalled($arg1)
    {
        $this->plugin = new Plugin($arg1);
        if (!$this->plugin->isInstalled()) {
            throw new Exception("The command line binary [$arg1] is not installed");
        }
    }

    /**
     * @Given /^the following profile:$/
     */
    public function theFollowingProfile(TableNode $table)
    {
        $this->plugin->addProfiles($table);
    }

    /**
     * @When /^I launch a "([^"]*)" attack with:$/
     */
    public function iLaunchAAttackWith($attackType, PyStringNode $string)
    {
        $this->plugin->launchAttackWith($attackType, $string);
    }

    /**
     * @Then /^it should pass with regexp:$/
     */
    public function itShouldPassWithRegexp(PyStringNode $string)
    {
        if (!$this->matchRegex($string)) {
            throw new \Exception(
                    "Actual output is:\n" . $this->plugin->getOutput() . "\nAnd does not contain : " . $string->getRaw()
            );
        }
    }

    /**
     * @Then /^it should pass with exactly:$/
     */
    public function itShouldPassWithExactly(PyStringNode $string)
    {
        if ($string->getRaw() !== $this->plugin->getOutput()) {
            throw new \Exception(
                    "Actual output is:\n" . $this->plugin->getOutput() . "\nAnd is not : " . $string->getRaw()
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
                    "Actual output is:\n" . $this->plugin->getOutput() . "\nAnd contains : " . $string->getRaw()
            );
        }
    }

    /**
     * @Then /^the output should contain "([^"]*)"$/
     */
    public function theOutputShouldContain($arg1)
    {
        if (strpos($this->plugin->getOutput(), $arg1) === false) {
            throw new \Exception(
                    "Actual output is:\n" . $this->plugin->getOutput() . "\nAnd does not contain : " . $arg1
            );
        }
    }
    
    private function matchRegex(PyStringNode $string)
    {
        $pattern = '@' . trim($string->getRaw(), '/') . '@';
        return preg_match($pattern, $this->plugin->getOutput());
    }

}
