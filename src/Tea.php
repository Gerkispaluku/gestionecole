<?php

namespace PHPMaker2023\gestion_ECOLE;

/**
 * Class for TEA encryption/decryption
 */
class Tea
{
    // Convert long to string
    private static function long2str($v, $w)
    {
        $len = count($v);
        $s = [];
        for ($i = 0; $i < $len; $i++) {
            $s[$i] = pack("V", $v[$i]);
        }
        if ($w) {
            return substr(join('', $s), 0, $v[$len - 1]);
        } else {
            return join('', $s);
        }
    }

    // Convert string to long
    private static function str2long($s, $w)
    {
        $v = unpack("V*", $s . str_repeat("\0", (4 - strlen($s) % 4) & 3));
        $v = array_values($v);
        if ($w) {
            $v[count($v)] = strlen($s);
        }
        return $v;
    }

    // Encrypt
    public static function encrypt($str, $key = "")
    {
        if ($str == "") {
            return "";
        }
        $key = $key ?: Config("RANDOM_KEY");
        $v = self::str2long($str, true);
        $k = self::str2long($key, false);
        $cntk = count($k);
        if ($cntk < 4) {
            for ($i = $cntk; $i < 4; $i++) {
                $k[$i] = 0;
            }
        }
        $n = count($v) - 1;
        $z = $v[$n];
        $y = $v[0];
        $delta = 0x9E3779B9;
        $q = floor(6 + 52 / ($n + 1));
        $sum = 0;
        while (0 < $q--) {
            $sum = self::int32($sum + $delta);
            $e = $sum >> 2 & 3;
            for ($p = 0; $p < $n; $p++) {
                $y = $v[$p + 1];
                $mx = self::int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ self::int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
                $z = $v[$p] = self::int32($v[$p] + $mx);
            }
            $y = $v[0];
            $mx = self::int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ self::int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
            $z = $v[$n] = self::int32($v[$n] + $mx);
        }
        return self::base6EncodeUrl(self::long2str($v, false));
    }

    // Decrypt
    public static function decrypt($str, $key = "")
    {
        $str = self::base64DecodeUrl($str);
        if ($str == "") {
            return "";
        }
        $key = $key ?: Config("RANDOM_KEY");
        $v = self::str2long($str, false);
        $k = self::str2long($key, false);
        $cntk = count($k);
        if ($cntk < 4) {
            for ($i = $cntk; $i < 4; $i++) {
                $k[$i] = 0;
            }
        }
        $n = count($v) - 1;
        $z = $v[$n];
        $y = $v[0];
        $delta = 0x9E3779B9;
        $q = floor(6 + 52 / ($n + 1));
        $sum = self::int32($q * $delta);
        while ($sum != 0) {
            $e = $sum >> 2 & 3;
            for ($p = $n; $p > 0; $p--) {
                $z = $v[$p - 1];
                $mx = self::int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ self::int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
                $y = $v[$p] = self::int32($v[$p] - $mx);
            }
            $z = $v[$n];
            $mx = self::int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ self::int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
            $y = $v[0] = self::int32($v[0] - $mx);
            $sum = self::int32($sum - $delta);
        }
        return self::long2str($v, true);
    }

    // Convert integer
    private static function int32($n)
    {
        while ($n >= 2147483648) {
            $n -= 4294967296;
        }
        while ($n <= -2147483649) {
            $n += 4294967296;
        }
        return (int)$n;
    }

    // Base64 encode for URL
    public static function base6EncodeUrl($string) {
        return str_replace(["+", "/", "="], ["-", "_", ""], base64_encode($string));
    }

    // Base64 decode for URL
    public static function base64DecodeUrl($string) {
        return base64_decode(str_replace(["-", "_"], ["+", "/"], $string));
    }
}
