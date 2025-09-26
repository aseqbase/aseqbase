<?php
namespace MiMFa\Library;
/**
 * A powerful library to compute and mathematics process
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#math See the Library Documentation
 */
class Math
{
    public static $Biggest        = INF;
    public static $Smallest       = -INF;


    /**
     * @param array Free args
     */
    public static function IsEven()
    {
        foreach(Convert::ToIteration(func_get_args()) as $key=>$val)
            if($val == 0 || $val%2 != 0)
                return false;
        return true;
    }

    /**
     * @param array Free args
     */
    public static function IsOdd()
    {
        foreach(Convert::ToIteration(func_get_args()) as $key=>$val)
            if($val == 0 || $val%2 == 0)
                return false;
        return true;
    }

    /**
     * @param array Free args
     */
    public static function Values()
    {
        return Convert::ToSequence(func_get_args());
    }

    /**
     * @param array Free args
     */
    public static function Count()
    {
        return count(self::Values(func_get_args()));
    }

    /**
     * @param array Free args
     */
    public static function Frequent()
    {
        $arr = [];
        foreach(Convert::ToIteration(func_get_args()) as $key=>$val)
        {
            $ind = array_key_first(array_filter($arr, function($k,$v) use($val){ return $v == $val;}, ARRAY_FILTER_USE_BOTH));
            if($ind) $arr[$val]=1;
            else $arr[$ind] += 1;
        }
        return $arr;
    }

    /**
     * @param array Free args
     */
    public static function Sort()
    {
        $arr = self::Values(func_get_args());
        sort($arr);
        return $arr;
    }

    /**
     * @param array Free args
     */
    public static function Distinct()
    {
        return array_unique(self::Values(func_get_args()));
    }

    /**
     * @param array Free args
     * @return float|int
     */
    public static function Sum()
    {
        return array_sum(self::Values(func_get_args()));
    }

    /**
     * @param array Free args
     * @return float|int
     */
    public static function Mean()
    {
        $arr = self::Values(func_get_args());
        return array_sum($arr)/count($arr);
    }

    /**
     * Summary of Sum
     * @param array Free args
     * @return float|int
     */
    public static function Median()
    {
        $arr = self::Sort(func_get_args());
        $c = count($arr);
        if(self::IsOdd($c)) return $arr[floor($c/2)];
        $cd = floor($c/2);
        return self::Mean($arr[$cd-1],$arr[$cd]);
    }

    /**
     * @param array Free args
     * @return float|int
     */
    public static function Mode()
    {
        $arr = self::Modes(func_get_args());
        return self::Mean($arr);
    }

    /**
     * @param array Free args
     * @return array
     */
    public static function Modes()
    {
        $arr = self::Frequent(func_get_args());
        $max = self::Maximum(array_values($arr));
        $mds = [];
        $length = count($arr);
        foreach ($arr as $key=>$value)
            if($value === $max)
                array_push($mds,$key);
        return $mds;
    }

    /**
     * @param array Free args
     * @return float|int
     */
    public static function Range()
    {
        return self::Maximum(func_get_args()) - self::Minimum(func_get_args());
    }

    /**
     * @param array Free args
     */
    public static function Minimum()
    {
        $res = self::$Biggest;
        foreach(Convert::ToIteration(func_get_args()) as $key=>$val)
            $res = min($res, $val);
        return $res;
    }

    /**
     * @param array Free args
     */
    public static function Maximum()
    {
        $res = self::$Smallest;
        foreach(Convert::ToIteration(func_get_args()) as $key=>$val)
            $res = max($res, $val);
        return $res;
    }

    /**
     * @param array Free args
     */
    public static function ABS()
    {
        foreach(Convert::ToIteration(func_get_args()) as $key=>$val)
            yield abs($val);
    }

    /**
     * @param array Free args
     */
    public static function Sigma($i=0, $k=100, $func = null)
    {
        $func = $func??function($ind){ return $ind; };
        $d = 0;
        for (; $i < $k; $i++)
            $d += $func($i);
        return $d;
    }

    /**
     * @param array Free args
     */
    public static function Phi($i=0, $k=100, $func = null)
    {
        $func = $func??function($ind){ return $ind; };
        $d = 0;
        for (; $i < $k; $i++)
            $d *= $func($i);
        return $d;
    }

    /**
     * @param array Free args
     */
    public static function Convergences()
    {
        $last = 1;
        foreach(Convert::ToIteration(func_get_args()) as $key=>$val)
        {
            foreach($val as $item)
                $last = ($last + (($item-$last) / $item))/2;
            yield $last;
        }
    }

    /**
     * @param array Free args
     */
    public static function Peaks()
    {
        $args = self::Values(func_get_args());
        $len = count($args) -1;
        for ($i = 1; $i < $len; $i++)
            if (abs($args[$i - 1]) < abs($args[$i]) && abs($args[$i + 1]) < abs($args[$i]))
                yield $args[$i];
    }

    /**
     * @param array Free args
     */
    public static function Valleys()
    {
        $args = self::Values(func_get_args());
        $len = count($args) -1;
        for ($i = 1; $i < $len; $i++)
            if (abs($args[$i - 1]) > abs($args[$i]) && abs($args[$i + 1]) > abs($args[$i]))
                yield $args[$i];
    }

    /**
     * @param array Free args
     */
    public static function Variance()
    {
        $mean = self::Mean(func_get_args());
        $res = 0;
        foreach(Convert::ToIteration(func_get_args()) as $key=>$val)
            $res += pow($val-$mean,2);
        return $res/count(func_get_args());
    }

    /**
     * @param array Free args
     */
    public static function STD()
    {
        return sqrt(self::Variance(func_get_args()));
    }

    /**
     * @param array Free args
     */
    public static function Logarithm() {
        if(count(func_get_args()) > 1) log(func_get_args()[0], func_get_args()[1]??10);
        else if(is_array(func_get_args()[0])) log(func_get_args()[0][0], func_get_args()[0][1]??10);
        else return log10(func_get_args()[0]);
    }

    /**
     * @param array Free args
     */
    public static function Power($x,$b=2)
    {
        return pow($x,$b);
    }

    /**
     * @param array Free args
     */
    public static function Radical($x,$b=2)
    {
        return pow($x,1/$b);
    }

    /**
     * @param array Free args
     */
    public static function Sin()
    {
        return sin(...func_get_args());
    }

    /**
     * @param array Free args
     */
    public static function Cos()
    {
        return cos(...func_get_args());
    }

    /**
     * @param array Free args
     */
    public static function Tan()
    {
        return tan(...func_get_args());
    }

    /**
     * @param array Free args
     */
    public static function Cot()
    {
        return 1/tan(...func_get_args());
    }

    /**
     * @param array Free args
     */
    public static function Decimals($num, $dec = 2)
    {
        $d = 10**$dec;
        return round(round($num * $d) / $d,$dec);
    }

    /**
     * @param array Free args
     */
    public static function Slice($slicesNumber, $x = 100, $y = 100, $minX=null, $minY = null)
    {
	    if ($minX == null) $minX = $x/10;
	    if ($minY == null) $minY = $y/10;

	    $xLength = 1;
	    $yLength = 1;
	    $xSize = $x / $xLength;
	    $ySize = $y / $yLength;

	    while ($xLength * $yLength < $slicesNumber)
	    {
		    if ($xSize < $minX && $ySize < $minY) return ["X"=>$minX,"Y"=>$minY];
		    else if ($ySize < $minY)
		    {
			    $yLength = max(1, $yLength - 1);
			    $xLength = min($slicesNumber, $xLength + 1);
		    }
		    else if ($xSize < $minX)
		    {
			    $xLength = max(1, $xLength - 1);
			    $yLength = min($slicesNumber, $yLength + 1);
		    }
		    else if ($xLength > $yLength)
		    {
			    $yLength = min($slicesNumber, $yLength + 1);
		    }
		    else
		    {
			    $xLength = min($slicesNumber, $xLength + 1);
		    }
		    $xSize = $x / $xLength;
		    $ySize = $y / $yLength;
	    }
	    return ["X"=>min($xSize, $x),"Y"=>min($ySize, $y)];
    }
}