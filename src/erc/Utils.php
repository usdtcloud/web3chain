<?php
/**
 * Created by : PhpStorm
 * Web: https://www.kaadon.com
 * User: ipioo
 * Date: 2021/12/17 21:17
 */

namespace Usdtcloud\web3chain\erc;


use Exception;
use kornrunner\Ethereum\Contract;
use PHP\Math\BigInteger\BigInteger;
use Web3p\EthereumUtil\Util;

class Utils extends Util
{
    public static function isAddress(string $address = null)
    {
        if (preg_match('/^0x[a-f0-9]{40}$/i', $address)){
            return true;
        }else{
            throw new Exception('地址不符合要求!');
        }
    }

    public static function weiValue($num,$decimals = 18 )
    {
        try {
            /*执行主体*/
           $BigInteger =  (new BigInteger($num,16))->multiply((new BigInteger(10))->pow($decimals))->getValue();
            return base_convert($BigInteger,10,16);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public static function getValue($value)
    {
        if (is_string($value)) {
            // remove gaps between hex digits
            $value = preg_replace('/\s|0x/', '', $value);
        } elseif (is_numeric($value)) {
            $value = base_convert($value,10,16);
        } elseif ($value === null) {
            $value = '';
        } else {
            throw new Exception('OctetString: unrecognized input type!');
        }

        if (strlen($value) % 2 != 0) {
            // transform values like 1F2 to 01F2
            $value = '0'.$value;
        }
        return $value;
    }

    public static function hexAmount(float $amount,$decimals = 18) {
        return '0x' . static::weiValue($amount,$decimals);
    }

    public static function getTransferData(string $toAddress, string $hexAmount): string {
        return sprintf('0x%s%s%s',
            str_pad('a9059cbb', 32, '0', STR_PAD_RIGHT),
            str_pad(static::sanitizeAddress($toAddress), 32, '0', STR_PAD_LEFT),
            str_pad(static::sanitizeHex($hexAmount), 64, '0', STR_PAD_LEFT)
        );
    }

    public static function sanitizeAddress(string $address): string {
        $address = static::sanitizeHex($address);
        if (strlen($address) !== 40) {
            throw new Exception('Invalid address provided');
        }

        return $address;
    }

    public static function sanitizeHex(string $hex): string {
        if (stripos($hex, '0x') === 0) {
            $hex = substr($hex, 2);
        }

        $length = strlen($hex);
        if (($length == 0)
            || (trim($hex, '0..9A..Fa..f') !== '')) {
            throw new Exception('Invalid hex provided');
        }

        return $hex;
    }


}