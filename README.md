helmet
======

[![Build Status](https://api.travis-ci.org/kpacha/helmet.png?branch=master)](https://travis-ci.org/kpacha/helmet) [![Coverage Status](https://coveralls.io/repos/kpacha/helmet/badge.png?branch=master)](https://coveralls.io/r/kpacha/helmet?branch=master)

Behaviour-driven security test framework based on Behat and inspired by [Gauntlt](http://gauntlt.org/)

##What does it do?

Helmet is a simple framework for your security tests. With Helmet you can express your attacks as a Gherkin feature.

##Installation

Add Helmet to your project. Open your composer.json file and add

    "require": {
        "kpacha/helmet": "*"
    },
    "repositories": [
        {"type": "vcs", "url": "https://github.com/kpacha/helmet"}
    ],
    "config": {
        "bin-dir": "bin/"
    }

If you haven't allready done so, get Composer:

    curl -s http://getcomposer.org/installer | php

And install the new required lib

    php composer.phar update kpacha/helmet

##How to use

Init your project

    bin/helmet --init
    
and a folder called 'features' will be created in your root path. Check out the collection of examples at the _features/_ directory.

Place your attack files into the _features/_ folder and a _FeatureContext.php_ in _features/bootstrap/_. Your _FeatureContext.php_ file should look like this:

    <?php

    use Kpacha\Helmet\HelmetContext;

    /**
     * Features context.
     */
    class FeatureContext extends HelmetContext
    {

    }

Let's run your attacks! type 

    bin/helmet

and you'll get a nice report...


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/kpacha/helmet/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

