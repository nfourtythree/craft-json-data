<?php
namespace Craft;

class JsonDataPlugin extends BasePlugin
{
    function getName()
    {
         return Craft::t('JSON Data');
    }

    function getVersion()
    {
        return '0.1';
    }

    function getDeveloper()
    {
        return 'Nathaniel Hammond';
    }

    function getDeveloperUrl()
    {
        return 'http://n43.co.uk';
    }

    public function addTwigExtension()
    {
        Craft::import('plugins.jsonData.twigextensions.JsonDataTwigExtension');

        return new JsonDataTwigExtension();
    }
}