<?php
/**
 * Created by : PhpStorm
 * Web: https://www.kaadon.com
 * User: ipioo
 * Date: 2021/12/18 00:38
 */

namespace Usdtcloud\web3chain;


use Exception;
use PHP\Math\BigInteger\BigInteger;
use Usdtcloud\web3chain\erc\Erc20php;
use Usdtcloud\web3chain\erc\Utils;

class chain extends Erc20php
{
    private $scanNumber = 0;
    private $blockNewNumber = 3;
    private $contractAddress = '';
    public $decimal = 18;
    public $address;

    public $type = [
        "Transfer" => '0xa9059cbb'//转账
    ];
    public function __construct(string $host,$chainId = 1)
    {
        parent::__construct($host,$chainId);
    }

    public function scanBlock(int $scanNumber = null)
    {
        if (!empty($scanNumber)) $this->scanNumber = $scanNumber;

        if ($this->blockNewNumber - $this->scanNumber <= 2) {
            $this->blockNewNumber = $this->blockNumber();
            if ($this->blockNewNumber - $this->scanNumber <= 2) {
                throw new Exception('尚有区块为挂起状态,请稍后再试!');
            }
        }
        $BlockData = $this->BlockByNumber($this->scanNumber);
        $transactions = $BlockData->transactions;
        $data = [];
        foreach ($transactions as $transaction) {

            $input = $transaction['input'];
            $row = [];
            $row["from"] = $transaction['from'];
            if (substr($input, 0, 10) == '0xa9059cbb' && $transaction['to'] == $this->contractAddress) {
//                var_dump($transaction);
                $row["hash"] = $transaction['hash'];
                $row["from"] = $transaction['from'];
                $row["to"] = '0x' . substr($input, 34, 40);
                $row["z_number"] = (float)((new BigInteger('0x' . substr($input, 74, 64)))->divide($this->decimal)->toString());
                $info = $this->chain->eth_getTransactionReceipt($transaction['hash']);
                if ($info->status == "0x1") {
                    $row["number"] = (float)((new BigInteger($info->logs[0]['data']))->divide($this->decimal)->toString());
                    $row["status"] = 1;
                } else {
                    $row["status"] = 0;
                    $row["number"] = 0;
                }
                array_push($data, $row);
            }
        }
        $this->scanNumber++;
        return [
            'block' => $this->scanNumber,
            'data' => $data
        ];

    }

    public function scanBlockNum(int $num = null, int $scanNumber = null)
    {
        $data = [];
        if (empty($num) || $num > 10) $num = 10;
        $data['data'] = [];
        for ($i = 0; $i < $num; $i++) {
            $raws = $this->scanBlock($scanNumber);
            if (count($raws['data']) > 0) {
                foreach ($raws['data'] as $raw) {
                    array_push($data['data'], $raw);
                }
            }
            if ($i == $num - 1) {
                $data['block'] = $raws['block'];
            }
        }
        return $data;
    }

    public function transactionHash(string $hash = null)
    {
        if (empty($hash)) {
            throw new Exception('交易hash不能为空!');
        }

        $data = [];
        $transfer = $this->chain->eth_getTransactionReceipt($hash);

        $data['status'] = $transfer->status == "0x1" ? 1 : 0;
        $Transaction = $this->Transaction($hash);

        $row = [];
        $row["type"] = substr($Transaction->input, 0, 10);
        $row["contract"] = $Transaction->to;
        $row["hash"] = $Transaction->hash;
        $row["from"] = $Transaction->from;
        $row["to"] = '0x' . substr($Transaction->input, 34, 40);
        $row["z_number"] = (float)(new BigInteger('0x' . substr($Transaction->input, 74, 64)))->divide($this->decimal)->toString();
        $row["number"] = (float)(new BigInteger($transfer->logs[0]['data']))->divide($this->decimal)->toString();
        $data['data'] = $row;

        return $data;

    }

    /**
     * @return int
     */
    public function getScanNumber()
    {
        return $this->scanNumber;
    }

    /**
     * @param string $contractAddress
     */
    public function setContractAddress(string $contractAddress)
    {
        Utils::isAddress($contractAddress);
        $this->contractAddress = $contractAddress;
        $this->decimal = pow(10, $this->contract($this->contractAddress)->decimals());
        return $this;
    }

    /**
     * @param int $blockNumber
     */
    public function setScanNumber(int $blockNumber)
    {
        if ($blockNumber !== $this->scanNumber) $this->scanNumber = $blockNumber;
        return $this;
    }

    /**
     * @param int $blockNewNumber
     */
    public function setBlockNewNumber($blockNewNumber)
    {
        $this->blockNewNumber = $blockNewNumber;
        return $this;
    }



}