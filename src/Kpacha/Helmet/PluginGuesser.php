<?php

namespace Kpacha\Helmet;

/**
 * Description of PluginGuesser
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class PluginGuesser
{

    public function getPlugin($pluginName, $args = null)
    {
        $pluginClassName = 'Kpacha\Helmet\Plugin\\' . ucfirst($pluginName);
        if (!class_exists($pluginClassName)) {
            throw new \Exception("The plugin [$pluginClassName] is not installed");
        }
        if($args === null) {
            $args = $pluginName;
        }
        return new $pluginClassName($args);
    }

}
