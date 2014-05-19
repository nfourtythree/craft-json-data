<?php

namespace Craft;

class Json_dataTwigExtension extends \Twig_Extension
{
    protected $env;

    public function getName()
    {
        return 'JSON Data';
    }

    public function getFilters()
    {
        return array('json_data' => new \Twig_Filter_Method($this, 'jsonData'));
    }

    public function getFunctions()
    {
        return array('json_data' => new \Twig_Function_Method($this, 'jsonData'));
    }

    public function initRuntime(\Twig_Environment $env)
    {
        $this->env = $env;
    }

    public function jsonData()
    {
        $fields = func_get_args();
        $entries = array_shift($fields);

        $fields = $this->_prepFields($fields);

        $json_data = array();

        foreach ($entries as $entry) {
            $tmp = array();
            foreach ($fields as $field => $value) {

                // Check to see if we are dealing with some kind of relationship
                if (is_array($value)) {
                    if (!empty($entry->{$field})) {
                        foreach ($entry->{$field} as $sub_key => $sub_entry) {
                            $attributes = $this->_traverseAttributes($sub_entry, $value);
                            $tmp[$field][$sub_key] = $attributes;
                        }
                    }
                } else {
                     $tmp[$field] = $entry->{$field};
                }
            }
            $json_data[] = $tmp;
        }

        return json_encode($json_data, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
    }

    private function _traverseAttributes($entry, $attr)
    {
        $attributes = array();
        foreach ($attr as $attribute_key => $attribute) {
            if (is_string($attribute) and isset($entry->{$attribute})) {
                $attributes[$attribute] = $entry->{$attribute};
            } elseif (is_string($attribute_key) and is_array($attribute) and isset($entry->{$attribute_key})) {
                $sub_attr = array();
                foreach($entry->{$attribute_key} as $k => $e) {
                    $sub_attr[$k] = $this->_traverseAttributes($e, $attribute);
                }
                $attributes[$attribute_key] = $sub_attr;
            }
        }

        return $attributes;
    }

    private function _prepFields($fields)
    {
        $new_fields = array();
        foreach ($fields as $key => $field) {
            if (strpos($field, ".")) {
                $params = explode(".", $field);
                $field = array_shift($params);
                if (count($params) == 1) {
                    $attribute = $params[0];
                    $new_fields[$field][] = $attribute;
                } else {
                    if ((count($params) % 2) === 0) {
                        while (count($params)) {
                            $k = array_shift($params);
                            $v = array_shift($params);
                            $new_fields[$field][$k][] = $v;
                        }
                    } else {
                        while(count($params)) {
                            $k = array_shift($params);
                            $sk = array_shift($params);
                            $v = array_shift($params);
                            $new_fields[$field][$k][$sk][] = $v;
                        }
                    }
                }
            } else {
                $new_fields[$field] = $field;
            }
        }
        return $new_fields;
    }
}