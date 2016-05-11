<?php

namespace Hikouki\Stigma;

use JBZoo\Utils\Ser;

class Replacer
{
    /**
     * Execute
     * @return boolean
     */
    public static function execute(&$object, $target, $replace)
    {
        $plainObject = Ser::maybeUn($object);

        if (is_array($plainObject)) {
            $replaced = false;
            foreach ($plainObject as &$value) {
                if (static::execute($value, $target, $replace) && !$replaced) {
                    $replaced = true;
                }
            }
            $object = Ser::maybe($plainObject);
            return $replaced;
        } else {
            if (strpos($object, $target) !== false) {
                $object = str_replace($target, $replace, $object);
                return true;
            }
            return false;
        }
    }
}
