<?php
declare(strict_types=1);

class BlitzMaxStream
{
    private int $offset = 0;
    private string $buffer;
    private int $length;
    private int $endianness;

    public const LITTLE_ENDIAN = 0;
    public const BIG_ENDIAN = 1;

    public function __construct(string $buffer = '', int $endianness = self::LITTLE_ENDIAN)
    {
        $this->buffer     = $buffer;
        $this->length     = strlen($buffer);
        $this->endianness = $endianness;
    }

    private function applyOffset(?int $offset): void
    {
        if ($offset !== null) {
            $this->offset += $offset;
        }
    }

    private function ensureReadable(int $bytes): void
    {
        if ($this->offset + $bytes > $this->length) {
            throw new \RuntimeException("Attempt to read past end of buffer at offset {$this->offset}");
        }
    }

    private function readUnpack(string $format, int $size, ?int $offset = null): int|float
    {
        $this->applyOffset($offset);
        $this->ensureReadable($size);
        $value = unpack($format, substr($this->buffer, $this->offset, $size))[1];
        $this->offset += $size;
        return $value;
    }

    private function writePack(string $format, int $size, int|float $value): self
    {
        $d = pack($format, $value);
        $this->buffer = substr_replace($this->buffer, $d, $this->offset, $size);
        $this->offset += $size;
        $this->length = strlen($this->buffer);
        return $this;
    }

    // ---- READERS ----
    public function readByte(?int $offset = null): int
    {
        $this->applyOffset($offset);
        $this->ensureReadable(1);
        return ord($this->buffer[$this->offset++]);
    }

    public function readShort(?int $offset = null): int
    {
        return $this->readUnpack(
            $this->endianness === self::LITTLE_ENDIAN ? 'v' : 'n', 2, $offset
        );
    }

    public function readInt(?int $offset = null): int
    {
        return $this->readUnpack(
            $this->endianness === self::LITTLE_ENDIAN ? 'V' : 'N', 4, $offset
        );
    }

    public function readLong(?int $offset = null): int
    {
        return $this->readUnpack(
            $this->endianness === self::LITTLE_ENDIAN ? 'P' : 'J', 8, $offset
        );
    }

    public function readFloat(?int $offset = null): float
    {
        return $this->readUnpack(
            $this->endianness === self::LITTLE_ENDIAN ? 'g' : 'G', 4, $offset
        );
    }

    public function readDouble(?int $offset = null): float
    {
        return $this->readUnpack(
            $this->endianness === self::LITTLE_ENDIAN ? 'e' : 'E', 8, $offset
        );
    }

    public function readLine(?int $offset = null): string
    {
        $this->applyOffset($offset);
        $line = '';
        while ($this->offset < $this->length) {
            $n = $this->readByte();
            if ($n === 10) break;      // LF
            if ($n !== 13) $line .= chr($n); // ignore CR
        }
        return $line;
    }

    public function readString(int $length): string
    {
        $this->ensureReadable($length);
        $str = substr($this->buffer, $this->offset, $length);
        $this->offset += $length;
        return $str;
    }

    /** Read string with null terminator (C-style) */
    public function readStringNT(int $length): string
    {
        $str = $this->readString($length);
        $this->offset++; // skip null terminator
        return $str;
    }

    // ---- WRITERS ----
    public function writeByte(int $value): self
    {
        $this->buffer[$this->offset++] = chr($value & 0xFF);
        $this->length = max($this->length, $this->offset);
        return $this;
    }

    public function writeShort(int $value): self
    {
        return $this->writePack(
            $this->endianness === self::LITTLE_ENDIAN ? 'v' : 'n', 2, $value
        );
    }

    public function writeInt(int $value): self
    {
        return $this->writePack(
            $this->endianness === self::LITTLE_ENDIAN ? 'V' : 'N', 4, $value
        );
    }

    public function writeLong(int $value): self
    {
        return $this->writePack(
            $this->endianness === self::LITTLE_ENDIAN ? 'P' : 'J', 8, $value
        );
    }

    public function writeFloat(float $value): self
    {
        return $this->writePack(
            $this->endianness === self::LITTLE_ENDIAN ? 'g' : 'G', 4, $value
        );
    }

    public function writeDouble(float $value): self
    {
        return $this->writePack(
            $this->endianness === self::LITTLE_ENDIAN ? 'e' : 'E', 8, $value
        );
    }

    public function writeLine(string $value): self
    {
        $this->writeString($value);
        return $this->writeByte(13)->writeByte(10);
    }

    public function writeString(string $value): self
    {
        $len = strlen($value);
        $this->buffer = substr_replace($this->buffer, $value, $this->offset, $len);
        $this->offset += $len;
        $this->length = strlen($this->buffer);
        return $this;
    }

    public function writeStringNT(string $value): self
    {
        return $this->writeString($value)->writeByte(0);
    }

    // ---- UTILITIES ----
    public function setPosition(int $position): void
    {
        $this->offset = max(0, $position);
    }

    public function skipBytes(int $count): void
    {
        $this->offset += $count;
    }

    public function remaining(): int
    {
        return $this->length - $this->offset;
    }

    public function close(): string
    {
        return substr($this->buffer, 0, $this->offset);
    }

    public function position(): int
    {
        return $this->offset;
    }

    public function size(): int
    {
        return $this->length;
    }

    public function clear(): void
    {
        $this->offset = 0;
        $this->buffer = '';
        $this->length = 0;
    }
}
