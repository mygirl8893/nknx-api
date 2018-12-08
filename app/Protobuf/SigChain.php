<?php
/**
 * Auto generated from message.proto at 2018-12-08 16:38:56
 */
namespace App\Protobuf;


class SigChain extends \ProtobufMessage
{
    /* Field index constants */
    const NONCE = 1;
    const DATASIZE = 2;
    const DATAHASH = 3;
    const BLOCKHASH = 4;
    const SRCPUBKEY = 5;
    const DESTPUBKEY = 6;
    const ELEMS = 7;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::NONCE => array(
            'name' => 'Nonce',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::DATASIZE => array(
            'name' => 'DataSize',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::DATAHASH => array(
            'name' => 'DataHash',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::BLOCKHASH => array(
            'name' => 'BlockHash',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::SRCPUBKEY => array(
            'name' => 'SrcPubkey',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::DESTPUBKEY => array(
            'name' => 'DestPubkey',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::ELEMS => array(
            'name' => 'Elems',
            'repeated' => true,
            'type' => 'App\Protobuf\SigChainElem'
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
        $this->values[self::NONCE] = null;
        $this->values[self::DATASIZE] = null;
        $this->values[self::DATAHASH] = null;
        $this->values[self::BLOCKHASH] = null;
        $this->values[self::SRCPUBKEY] = null;
        $this->values[self::DESTPUBKEY] = null;
        $this->values[self::ELEMS] = array();
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
     * Sets value of 'Nonce' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setNonce($value)
    {
        return $this->set(self::NONCE, $value);
    }

    /**
     * Returns value of 'Nonce' property
     *
     * @return integer
     */
    public function getNonce()
    {
        $value = $this->get(self::NONCE);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'DataSize' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setDataSize($value)
    {
        return $this->set(self::DATASIZE, $value);
    }

    /**
     * Returns value of 'DataSize' property
     *
     * @return integer
     */
    public function getDataSize()
    {
        $value = $this->get(self::DATASIZE);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'DataHash' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setDataHash($value)
    {
        return $this->set(self::DATAHASH, $value);
    }

    /**
     * Returns value of 'DataHash' property
     *
     * @return string
     */
    public function getDataHash()
    {
        $value = $this->get(self::DATAHASH);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'BlockHash' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setBlockHash($value)
    {
        return $this->set(self::BLOCKHASH, $value);
    }

    /**
     * Returns value of 'BlockHash' property
     *
     * @return string
     */
    public function getBlockHash()
    {
        $value = $this->get(self::BLOCKHASH);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'SrcPubkey' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setSrcPubkey($value)
    {
        return $this->set(self::SRCPUBKEY, $value);
    }

    /**
     * Returns value of 'SrcPubkey' property
     *
     * @return string
     */
    public function getSrcPubkey()
    {
        $value = $this->get(self::SRCPUBKEY);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'DestPubkey' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setDestPubkey($value)
    {
        return $this->set(self::DESTPUBKEY, $value);
    }

    /**
     * Returns value of 'DestPubkey' property
     *
     * @return string
     */
    public function getDestPubkey()
    {
        $value = $this->get(self::DESTPUBKEY);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Appends value to 'Elems' list
     *
     * @param \SigChainElem $value Value to append
     *
     * @return null
     */
    public function appendElems(App\Protobuf\SigChainElem $value)
    {
        return $this->append(self::ELEMS, $value);
    }

    /**
     * Clears 'Elems' list
     *
     * @return null
     */
    public function clearElems()
    {
        return $this->clear(self::ELEMS);
    }

    /**
     * Returns 'Elems' list
     *
     * @return \SigChainElem[]
     */
    public function getElems()
    {
        return $this->get(self::ELEMS);
    }

    /**
     * Returns 'Elems' iterator
     *
     * @return \ArrayIterator
     */
    public function getElemsIterator()
    {
        return new \ArrayIterator($this->get(self::ELEMS));
    }

    /**
     * Returns element from 'Elems' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \SigChainElem
     */
    public function getElemsAt($offset)
    {
        return $this->get(self::ELEMS, $offset);
    }

    /**
     * Returns count of 'Elems' list
     *
     * @return int
     */
    public function getElemsCount()
    {
        return $this->count(self::ELEMS);
    }
}
