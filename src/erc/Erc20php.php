<?php
/**
 * Created by : PhpStorm
 * Web: https://www.kaadon.com
 * User: ipioo
 * Date: 2021/12/17 20:48
 */

namespace Usdtcloud\web3chain\erc;


use Exception;
use FurqanSiddiqui\Ethereum\ERC20\ERC20;
use FurqanSiddiqui\Ethereum\Ethereum;
use FurqanSiddiqui\Ethereum\RPC\GethRPC;
use FurqanSiddiqui\Ethereum\RPC\Models\Block;

class Erc20php
{
    public $contract;
    public $chain;

    public function __construct(string $host)
    {
        if (empty($host)){
            throw new Exception('Host 不能为空!');
        }

        $eth = new Ethereum();
        $GethRPC = new GethRPC($eth, "https://http-mainnet.hecochain.com");
        $erc20 = new ERC20($eth);
        $erc20->useRPCClient($GethRPC);
        $this->chain = $GethRPC;
        $this->contract = $erc20;
    }

    public function contract(string $contractAddress)
    {
        if (empty($contractAddress)){
            throw new Exception('合约地址不能为空!');
        }
        $this->contract = $this->contract->token($contractAddress);
        return $this->contract;
    }

    public function blockNumber()
    {
        return $this->chain->eth_blockNumber();
    }

    public function Transaction(string $hash)
    {
        return $this->chain->eth_getTransaction($hash);
    }

    public function BlockByNumber($height)
    {
        $height = $height ? "0x" . dechex($height) : "latest";
        $block = $this->chain->call("eth_getBlockByNumber", [$height, true]);
        if (is_null($block)) {
            return null; // Block not found/Out of range
        }

        if (!is_array($block)) {
            throw new Exception("eth_getBlockByNumber", "Object", gettype($block));
        }
        return new Block($block);}
}