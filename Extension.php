<?php

namespace Bolt\Extension\WMC\ParentTheme;

use Bolt\Application;
use Bolt\BaseExtension;
use Bolt\Helpers\Arr;
use Bolt\Config;

class Extension extends BaseExtension
{
    public function initialize() {
        $config = $this->app['config'];
        $end = $config->getWhichEnd($config->get('general/branding/path'));

        // only run on frontend
        if ($end == 'frontend') {
            $parent = $config->get('theme/parent');

            if ($parent) {
                $this->loadThemeConfig($config, $parent, $config->get('general/theme'));
                $this->app['twig.path'] = $config->get('twigpath');
            }
        }
    }

    private function loadThemeConfig(Config $config, $theme, $childTheme)
    {
        $themePath = $this->app['resources']->getPath('themebase') . '/' . $theme;

        if (file_exists($themePath)) {
            $configFile = $themePath . '/config.yml';

            // insert parent path right after child path.
            $twigPath = $config->get('twigpath');
            if ($twigPath) {
                $childThemePath = $this->app['resources']->getPath('themebase') . '/' . $childTheme;
                $key = array_search($childThemePath, $twigPath);
                if ($key !== false) {
                    array_splice($twigPath, $key, 1, array($childThemePath, $themePath));
                }
                $config->set('twigpath', $twigPath);
            }

            if (file_exists($configFile)) {
                $themeConfig = self::mergeConfigFile($configFile);

                if ($themeConfig) {
                    // load parent theme config, but without overwriting, because child prevails
                    $config->set('theme', Arr::mergeRecursiveDistinct($themeConfig, $config->get('theme')));

                    // multiple levels allowed
                    if (!empty($themeConfig['parent'])) {
                        $this->loadThemeConfig($config, $themeConfig['parent'], $theme);
                    }
                }
            }
        }
    }

    public function getName()
    {
        return "ParentTheme";
    }

    private static function mergeConfigFile($file)
    {
        $yamlparser = new \Symfony\Component\Yaml\Parser();

        return $yamlparser->parse(file_get_contents($file) . "\n");
    }
}
