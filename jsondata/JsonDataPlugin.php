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
        return 'nfourtythree';
    }

    function getDeveloperUrl()
    {
        return 'http://n43.me';
    }

    public function addTwigExtension()
    {
        Craft::import('plugins.jsondata.twigextensions.JsonDataTwigExtension');

        return new JsonDataTwigExtension();
    }
}
