<?php
namespace Craft;

class JsonDataService extends BaseApplicationComponent
{
    public function encode($entries, $fields)
    {
        // crude validation to start with
        if ($entries and $fields) {

            $fields = $this->_prepFields($fields);

            $json_data = array();

            if (is_array($entries)) {
                foreach ($entries as $entry) {
                    $json_data[] = $this->_fieldData($entry, $fields);
                }
            } else if ($entries instanceof EntryModel) {
                $json_data = $this->_fieldData($entries, $fields);
            }

            return json_encode($json_data, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
        } else {
            return false;
        }
    }

    private function _fieldData($entry, $fields)
    {
        $return = array();
        foreach ($fields as $field => $value) {
            // Check to see if we are dealing with some kind of relationship
            if (is_array($value)) {
                if (!empty($entry->{$field})) {
                    foreach ($entry->{$field} as $sub_key => $sub_entry) {
                        $attributes = $this->_traverseAttributes($sub_entry, $value);
                        $return[$field][$sub_key] = $attributes;

                        foreach ($return[$field][$sub_key] as $key => &$v) {
                            $v = $this->_parseData($key, $v);
                        }
                    }
                }
            } else {
                $return[$field] = $entry->{$field};
            }
        }

        return $return;
    }

    private function _parseData($field, $data)
    {
        $fieldData = craft()->fields->getFieldByHandle($field);

        if ($fieldData["type"] == "something") {
            // do ALL THE field types
        } else {
            return $data;
        }
    }

    private function _traverseAttributes($entry, $attr)
    {
        $attributes = array();
        foreach ($attr as $attribute_key => $attribute) {
            if (preg_match("/^getUrl\((.*?)\)/i", $attribute, $matches)) {
                if (is_array($matches) and isset($matches[1]) and $transformHandle = trim(str_replace(array("'",'"'), "", $matches[1]))) {
                    $attributes[$transformHandle] = $entry->getUrl($transformHandle);
                }
            }
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