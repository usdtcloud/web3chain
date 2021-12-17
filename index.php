<?php

require_once ('vendor/autoload.php');

use FurqanSiddiqui\Ethereum\ERC20\ERC20;
use  FurqanSiddiqui\Ethereum\Ethereum;
use FurqanSiddiqui\Ethereum\Math\Integers;
use FurqanSiddiqui\Ethereum\RPC\GethRPC;

function object_array($array) {
    if(is_object($array)) {
        $array = (array)$array;
    } if(is_array($array)) {
        foreach($array as $key=>$value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}

$eth = new Ethereum;
$infura = new GethRPC($eth, "https://http-mainnet.hecochain.com");


$erc20 = new ERC20($eth);
$erc20->useRPCClient($infura);
$contract = '0x258f6738f238af6427dc3309495491e2ef0eaba9';
$hrc20 = $erc20->token($contract);
$decimals = $hrc20->decimals();
$decimal = pow(10,$decimals);
var_dump($decimal);
echo " <br>";
$zuixin1 = $infura->eth_blockNumber();
$zuixin = $infura->eth_getBlock("10945220")->transactions;

$a = "0x6597bf415b62dcb92c57f6c6dfc5c67ec5f57890866d035b32a4f5e6a6c86908";
$zuixin3 = $infura->eth_getTransactionReceipt($a);

if ($zuixin3->status == "0x1"){
    echo "=======================================";
    $xuixin4 = $infura->eth_getTransaction($a);
    var_dump($xuixin4->value/$decimal);
    var_dump(json_encode($xuixin4->raw(),true));
    echo "=======================================";
    echo " <br>";
}


$usdt = $erc20->token("0x258f6738f238af6427dc3309495491e2ef0eaba9");
echo " <br>";

var_dump($usdt->name());
echo " <br>";
var_dump($usdt->symbol());
echo " <br>";
var_dump($usdt->decimals());
echo " <br>";
var_dump($usdt->totalSupply());
echo " <br>";
var_dump($usdt->balanceOf($eth->getAccount("0xb1f594d5e65a1fdb82d2b3055124291e2867d79f")));
echo " <br>";
