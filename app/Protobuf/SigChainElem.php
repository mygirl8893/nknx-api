<?php
/**
 * Auto generated from message.proto at 2018-12-08 16:38:56
 */
namespace App\Protobuf;
/**
 * SigChainElem message
 */
class SigChainElem extends App\Protobuf\ProtobufMessage
{
    /* Field index constants */
    const ADDR = 1;
    const NEXTPUBKEY = 2;
    const MINING = 3;
    const SIGALGO = 4;
    const SIGNATURE = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::ADDR => array(
            'name' => 'Addr',
            'required' => false,
            'type' => App\Protobuf\ProtobufMessage::PB_TYPE_STRING,
        ),
        self::NEXTPUBKEY => array(
            'name' => 'NextPubkey',
            'required' => false,
            'type' => App\Protobuf\ProtobufMessage::PB_TYPE_STRING,
        ),
        self::MINING => array(
            'name' => 'Mining',
            'required' => false,
            'type' => App\Protobuf\ProtobufMessage::PB_TYPE_BOOL,
        ),
        self::SIGALGO => array(
            'name' => 'SigAlgo',
            'required' => false,
            'type' => App\Protobuf\ProtobufMessage::PB_TYPE_INT,
        ),
        self::SIGNATURE => array(
            'name' => 'Signature',
            'required' => false,
            'type' => App\Protobuf\ProtobufMessage::PB_TYPE_STRING,
        ),
    );

    /**
     * Constructs new message container and clears its internal state
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Clears message values and sets default ones
     *
     * @return null
     */
    public function reset()
    {
        $this->values[self::ADDR] = null;
        $this->values[self::NEXTPUBKEY] = null;
        $this->values[self::MINING] = null;
        $this->values[self::SIGALGO] = null;
        $this->values[self::SIGNATURE] = null;
    }

    /**
     * Returns field descriptors
     *
     * @return array
     */
    public function fields()
    {
        return self::$fields;
    }

    /**
     * Sets value of 'Addr' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAddr($value)
    {
        return $this->set(self::ADDR, $value);
    }

    /**
     * Returns value of 'Addr' property
     *
     * @return string
     */
    public function getAddr()
    {
        $value = $this->get(self::ADDR);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'NextPubkey' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setNextPubkey($value)
    {
        return $this->set(self::NEXTPUBKEY, $value);
    }

    /**
     * Returns value of 'NextPubkey' property
     *
     * @return string
     */
    public function getNextPubkey()
    {
        $value = $this->get(self::NEXTPUBKEY);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'Mining' property
     *
     * @param boolean $value Property value
     *
     * @return null
     */
    public function setMining($value)
    {
        return $this->set(self::MINING, $value);
    }

    /**
     * Returns value of 'Mining' property
     *
     * @return boolean
     */
    public function getMining()
    {
        $value = $this->get(self::MINING);
        return $value === null ? (boolean)$value : $value;
    }

    /**
     * Sets value of 'SigAlgo' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setSigAlgo($value)
    {
        return $this->set(self::SIGALGO, $value);
    }

    /**
     * Returns value of 'SigAlgo' property
     *
     * @return integer
     */
    public function getSigAlgo()
    {
        $value = $this->get(self::SIGALGO);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'Signature' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setSignature($value)
    {
        return $this->set(self::SIGNATURE, $value);
    }

    /**
     * Returns value of 'Signature' property
     *
     * @return string
     */
    public function getSignature()
    {
        $value = $this->get(self::SIGNATURE);
        return $value === null ? (string)$value : $value;
    }
}
