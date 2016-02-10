<?php

namespace Craft;

class JsonDataTwigExtension extends \Twig_Extension
{
    protected $env;

    public function getName()
    {
        return 'JSON Data';
    }

    public function getFilters()
    {
        return array('jsonData' => new \Twig_Filter_Method($this, 'jsonData'));
    }

    public function getFunctions()
    {
        return array('jsonData' => new \Twig_Function_Method($this, 'jsonData'));
    }

    public function initRuntime(\Twig_Environment $env)
    {
        $this->env = $env;
    }

    public function jsonData()
    {
        $fields = func_get_args();
        $entries = array_shift($fields);

        return craft()->jsonData->encode($entries, $fields);
    }
}