<?php
/**
 * Created by : PhpStorm
 * Web: https://www.kaadon.com
 * User: ipioo
 * Date: 2022/1/14 22:44
 */


require_once('vendor/autoload.php');

use Usdtcloud\web3chain\chain;


use \Usdtcloud\web3chain\erc\Tx;

/**
 * $ChainId 链 ID
 * $host 链 的连接
 * $contractAddress  合约地址
 * $Address  转账FORM的地址
 * $privateKey  转账FORM的私钥
 */

$host            = 'https://http-testnet.hecochain.com';
$contractAddress = '0x04f535663110a392a6504839beed34e019fdb4e0';
$Address         = '0xBd327D018bAb0157FCA4C7E8935594286f13229B';
$privateKey      = 'f8a79df67a6e35e37521e175d76d30ac6d0a25c93ecd63d4f2ee12a813de93f5';


$TX              = new Tx($host, $contractAddress, $Address, $privateKey);
//合约余额
$balance = $TX->token()->balanceOf('Bd327D018bAb0157FCA4C7E8935594286f13229B');
//ht余额
$tokenBalance = $TX->GethRPC->eth_getBalance('0x55cBF53065f1fbC22121A5F7de4837dF1AF6E04B');

$to              = "0xff6a5579a9D145646CF5c94FE38a9CA349B1afa2";

try {
    $TXDATA = $TX->rawTokenSign($to, 2);
    $hash = $TX->GethRPC->eth_sendRawTransaction($TXDATA);
    var_dump($hash);

    $Receipt = $TX->GethRPC->eth_getTransactionReceipt($hash);
    var_dump($Receipt);

} catch (\Exception $e) {
    var_dump($e->getMessage());
}

//HT转账
/**
 * $to 接受转账的地址
 * $value 转账数量 (目前仅支持真整数(int))
 * $gasPrice = 1 (单位价格)
 * $gasLimit = '210000'
 */
//$TXDATA = $TX->rawSign($to, 39);
//try {
//    $TX->GethRPC->eth_sendRawTransaction($TXDATA);
//} catch (\Exception $e) {
//    var_dump($e->getMessage());
//}
