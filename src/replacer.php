<?php

namespace Hikouki\Stigma;

class Replacer
{
    /**
     * Execute
     * @return boolean
     */
    public static function execute(&$object, $target, $replace)
    {
        $plain_object = Ser::maybeUn($object);

        if (is_array($plain_object)) {
            $replaced = false;
            foreach ($plain_object as &$value) {
                if ($this->execute($value, $target, $replace) && !$replaced) {
                    $replaced = true;
                }
            }
            $object = Ser::maybe($plain_object);
            return $replaced;
        } else {
            if (strpos($object, $target)) {
                $object = str_replace($target, $replace, $object);
                return true;
            }
            return false;
        }
    }
}
