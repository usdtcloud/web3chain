<?php
/**
 * Created by : PhpStorm
 * Web: https://www.kaadon.com
 * User: ipioo
 * Date: 2021/12/18 03:23
 */


require_once ('vendor/autoload.php');
use \Usdtcloud\web3chain\chain;
$ERC20 = new chain("https://http-mainnet.hecochain.com");

$ERC20->setContractAddress('0xa71edc38d189767582c38a3145b5873052c3e47a');
$ERC20->setScanNumber(10954784 );

/**
 *查询一个区块
 */
$data = $ERC20->scanBlock();

/**
 *查询N个区块
 */
$data = $ERC20->scanBlockNum();

var_dump($data);