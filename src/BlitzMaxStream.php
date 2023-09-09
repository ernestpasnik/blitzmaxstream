<?php

class BlitzMaxStream
{
    private $offset;
    private $buffer;
    private $length;
    private $endianness;

    const LITTLE_ENDIAN = 0;
    const BIG_ENDIAN = 1;

    public function __construct($buff = null, $endianness = self::LITTLE_ENDIAN)
    {
        $this->offset = 0;
        if ($buff !== null) {
            $this->buffer = $buff;
            $this->length = strlen($buff);
        } else {
            $this->buffer = '';
            $this->length = 0;
        }
        $this->endianness = $endianness;
    }

    public function readByte($offset = null)
    {
        if ($offset !== null) {
            $this->offset += $offset;
        }
        $value = ord($this->buffer[$this->offset]);
        $this->offset += 1;
        return $value;
    }

    public function readShort($offset = null)
    {
        if ($offset !== null) {
            $this->offset += $offset;
        }
        if ($this->endianness === self::LITTLE_ENDIAN) {
            $value = unpack('v', substr($this->buffer, $this->offset, 2))[1];
        } else {
            $value = unpack('n', substr($this->buffer, $this->offset, 2))[1];
        }
        $this->offset += 2;
        return $value;
    }

    public function readInt($offset = null)
    {
        if ($offset !== null) {
            $this->offset += $offset;
        }
        if ($this->endianness === self::LITTLE_ENDIAN) {
            $value = unpack('V', substr($this->buffer, $this->offset, 4))[1];
        } else {
            $value = unpack('N', substr($this->buffer, $this->offset, 4))[1];
        }
        $this->offset += 4;
        return $value;
    }

    public function readLong($offset = null)
    {
        if ($offset !== null) {
            $this->offset += $offset;
        }
        if ($this->endianness === self::LITTLE_ENDIAN) {
            $value = unpack('P', substr($this->buffer, $this->offset, 8))[1];
        } else {
            $value = unpack('J', substr($this->buffer, $this->offset, 8))[1];
        }
        $this->offset += 8;
        return $value;
    }

    public function readFloat($offset = null)
    {
        if ($offset !== null) {
            $this->offset += $offset;
        }
        if ($this->endianness === self::LITTLE_ENDIAN) {
            $value = unpack('g', substr($this->buffer, $this->offset, 4))[1];
        } else {
            $value = unpack('G', substr($this->buffer, $this->offset, 4))[1];
        }
        $this->offset += 4;
        return $value;
    }

    public function readDouble($offset = null)
    {
        if ($offset !== null) {
            $this->offset += $offset;
        }
        if ($this->endianness === self::LITTLE_ENDIAN) {
            $value = unpack('e', substr($this->buffer, $this->offset, 8))[1];
        } else {
            $value = unpack('E', substr($this->buffer, $this->offset, 8))[1];
        }
        $this->offset += 8;
        return $value;
    }

    public function readLine($offset = null)
    {
        if ($offset !== null) {
            $this->offset += $offset;
        }
        $value = '';
        while (true) {
            $n = $this->readByte();
            if ($n === null || $n === 10) {
                break;
            } elseif ($n !== 13) {
                $value .= chr($n);
            }
        }
        return $value;
    }

    public function readString($length)
    {
        $value = substr($this->buffer, $this->offset, $length);
        $this->offset += $length;
        return $value;
    }

    public function readStringNT($length)
    {
        $value = $this->readString($length);
        $this->offset += 1;
        return $value;
    }

    public function writeByte($value)
    {
        $this->buffer[$this->offset] = chr($value);
        $this->offset += 1;
        return $this->buffer;
    }

    public function writeShort($value)
    {
        if ($this->endianness === self::LITTLE_ENDIAN) {
            $d = pack('v', $value);
        } else {
            $d = pack('n', $value);
        }
        $this->buffer = substr_replace($this->buffer, $d, $this->offset, 2);
        $this->offset += 2;
        return $this->buffer;
    }

    public function writeInt($value)
    {
        if ($this->endianness === self::LITTLE_ENDIAN) {
            $d = pack('V', $value);
        } else {
            $d = pack('N', $value);
        }
        $this->buffer = substr_replace($this->buffer, $d, $this->offset, 4);
        $this->offset += 4;
        return $this->buffer;
    }

    public function writeLong($value)
    {
        if ($this->endianness === self::LITTLE_ENDIAN) {
            $d = pack('P', $value);
        } else {
            $d = pack('J', $value);
        }
        $this->buffer = substr_replace($this->buffer, $d, $this->offset, 8);
        $this->offset += 8;
        return $this->buffer;
    }

    public function writeFloat($value)
    {
        if ($this->endianness === self::LITTLE_ENDIAN) {
            $d = pack('g', $value);
        } else {
            $d = pack('G', $value);
        }
        $this->buffer = substr_replace($this->buffer, $d, $this->offset, 4);
        $this->offset += 4;
        return $this->buffer;
    }

    public function writeDouble($value)
    {
        if ($this->endianness === self::LITTLE_ENDIAN) {
            $d = pack('e', $value);
        } else {
            $d = pack('E', $value);
        }
        $this->buffer = substr_replace($this->buffer, $d, $this->offset, 8);
        $this->offset += 8;
        return $this->buffer;
    }

    public function writeLine($value)
    {
        $length = strlen($value);
        for ($i = 0; $i < $length; $i++) {
            $this->buffer[$this->offset + $i] = $value[$i];
        }
        $this->offset += $length;
        $this->writeByte(13);
        $this->writeByte(10);
        return $this;
    }

    public function writeString($value)
    {
        $length = strlen($value);
        for ($i = 0; $i < $length; $i++) {
            $this->buffer[$this->offset + $i] = $value[$i];
        }
        $this->offset += $length;
        return $this->buffer;
    }

    public function writeStringNT($value)
    {
        $this->writeString($value);
        $this->writeByte(0);
        return $this->buffer;
    }

    public function setPosition($position)
    {
        $this->offset = $position;
    }

    public function skipBytes($count)
    {
        $this->offset += $count;
        return $this->offset;
    }

    public function remaining()
    {
        return $this->length - $this->offset;
    }

    public function close()
    {
        return substr($this->buffer, 0, $this->offset);
    }

    public function position()
    {
        return $this->offset;
    }

    public function size()
    {
        return $this->length;
    }

    public function clear()
    {
        $this->offset = 0;
        $this->buffer = '';
        $this->length = 0;
    }
}
