<?php
/**
 * Created by : PhpStorm
 * Web: https://www.kaadon.com
 * User: ipioo
 * Date: 2022/1/16 02:17
 */

namespace Usdtcloud\web3chain\erc;


use Cassandra\Varint;
use FurqanSiddiqui\Ethereum\ERC20\ERC20;
use FurqanSiddiqui\Ethereum\Ethereum;
use FurqanSiddiqui\Ethereum\RPC\GethRPC;
use kornrunner\Ethereum\Transaction;

class Tx
{
    public $GethRPC;
    public $ERC20;
    public $Decimals = 6;
    public $ChainId = "256";
    public $Address;
    public $privateKey;
    public $TokenAddress;

    public function __construct(string $host,$contractAddress,$Address,$privateKey,$ChainId = 256)
    {
        if (empty($host)) {
            throw new Exception('Host 不能为空!');
        }

        $eth     = new Ethereum();
        $GethRPC = new GethRPC($eth, $host);
        $erc20   = new ERC20($eth);
        $erc20->useRPCClient($GethRPC);
        $this->GethRPC    = $GethRPC;
        $this->ERC20 = $erc20;
        $this->TokenAddress = strtolower($contractAddress);
        $this->Address = strtolower($Address);
        $this->privateKey = $privateKey;
        $this->ChainId = $ChainId;
        $this->setDecimals();
    }

    public function token()
    {
       return $this->ERC20->token($this->TokenAddress);
    }

    public function setDecimals()
    {
        $this->Decimals = $this->token()->decimals();
    }


    public function rawSign($to, $value, $gasPrice = 1, $gasLimit = '2100000')
    {
        $nonce = (string) $this->GethRPC->eth_getTransactionCount($this->Address);
        $gasPrice    = Utils::weiValue($gasPrice, 10);
        $gasLimit    = Utils::weiValue($gasLimit, 0);
        $to          = Utils::getValue($to);
        $value       = Utils::weiValue($value);
        $transaction = new Transaction($nonce, $gasPrice, $gasLimit, $to, $value);
        return '0x' . $transaction->getRaw($this->privateKey, $this->ChainId);
    }

    public function rawTokenSign($to, $value, $gasPrice = 1, $gasLimit = '2100000')
    {
        $blockNum = $this->GethRPC->eth_blockNumber();
        $nonce = (string) $this->GethRPC->eth_getTransactionCount($this->Address,$blockNum);

        $gasPrice    = Utils::weiValue($gasPrice, 11);
        $gasLimit    = Utils::weiValue($gasLimit, 0);
        $to          = (string) Utils::getValue($to);
        $hexAmount   = Utils::hexAmount($value, $this->Decimals);
        $data        = Utils::getTransferData($to, $hexAmount);
        $transaction =  new Transaction($nonce, $gasPrice, $gasLimit, $this->TokenAddress, '', $data);
        return '0x' . $transaction->getRaw($this->privateKey, $this->ChainId);
    }
}