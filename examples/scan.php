<?php
/**
 * Created by : PhpStorm
 * Web: https://www.kaadon.com
 * User: ipioo
 * Date: 2021/12/18 03:23
 */


require_once ('vendor/autoload.php');
use Usdtcloud\web3chain\chain;
$ERC20 = new chain("https://http-mainnet.hecochain.com");

$ERC20->setContractAddress('0x258f6738f238af6427dc3309495491e2ef0eaba9');
$ERC20->setScanNumber(11720231 );

/**
 *查询一个区块
 */
$data = $ERC20->scanBlock();
var_dump($data);
///**
// *查询N个区块
// */
for ($i = 1;$i < 100 ;$i++){
    $data = $ERC20->scanBlockNum();
    foreach ($data['data'] as $datum) {
        var_dump($datum['hash']);

        if ($datum['status'] && $datum['z_number'] !== $datum['number'] ){
            var_dump($datum['number'] . '-' . $datum['z_number']);
        }
    }
    echo '\n';
}

