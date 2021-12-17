<?php
require_once ('vendor/autoload.php');
use \Usdtcloud\web3chain\chain;
$ERC20 = new chain("https://http-mainnet.hecochain.com");

$ERC20->setContractAddress('0xa71edc38d189767582c38a3145b5873052c3e47a');
$ERC20->setScanNumber(10954784 );

$data = $ERC20->scanBlockNum();
var_dump(json_encode($data,true));