<?php
namespace MiMFa\Library;
/**
 * A simple library to create default and standard Script parts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#Script See the Library Documentation
 */
class Script
{
    public static function Point($row, $index = 0) {
        $cc = is_array($row)? count($row):0;
        if ($cc > 3) {
            $res = [];
            $res[] = "{ ";
            $res[] = "label: ".self::Parameters($row[0]);
            $res[] = ",x:".self::Numbers($row[1]);
            $res[] = ",y:".self::Numbers($row[2]);
            $len = count($row);
            for ($i = 3; $i < $len; $i++) $res[] = ",{$row[$i]}";
            $res[] = " }";
            return join("", $res);
        }
        else if ($cc > 2)
            return join("", [
                "{ label: ", self::Parameters(first($row)),
                ",x:", self::Numbers($row[1]),
                ",y:", self::Numbers(last($row)),
                "}"
            ]);
        else if ($cc > 1)
            return join("", [
                "{x:", self::Numbers(first($row)),
                ",y:", self::Numbers(last($row)),
                "}"
            ]);
        else{
            return join("", [
                "{x:", self::Numbers($index),
                ",y:", self::Numbers($row),
                "}"
            ]);
        }
    }
    public static function Points($content) {
        return join(",", loop($content, function($i, $row) { return self::Point($row, $i); }));
    }
    public static function Parameters($arr) {
        return isEmpty($arr) ? "" :
            (is_iterable($arr) ? join("", ["[", join(", ", loop($arr, function($i, $o) { return self::Parameters($o);})), "]"]) :
                (is_string($arr) ? "`$arr`" :
                    (is_object($arr) ? json_encode($arr) :
                        Convert::ToString($arr))));
    }
    public static function Numbers($arr) {
        $isarr = is_iterable($arr);
        $val = $isarr ?
            (preg_replace("/,\s+,/m", ", 0,",
                (preg_replace("/,\s*$/m", ", 0",
                    (preg_replace("/^\s*,\s+/m", "0, ",
                        join(", ", is_array($arr) ? $arr : Convert::ToArray($arr)))))))) : $arr;
        return isEmpty($val) ? "0" : ($isarr ? "[" + $val + "]" : $val);
    }
}
?>