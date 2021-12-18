<?php
/**
 * Created by : PhpStorm
 * Web: https://www.kaadon.com
 * User: ipioo
 * Date: 2021/12/17 21:17
 */

namespace Usdtcloud\web3chain\erc;


use Exception;
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
}