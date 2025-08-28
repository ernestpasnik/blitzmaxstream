# BlitzMax Stream

A PHP library for reading and writing binary data, inspired by [BlitzMax](https://blitzmax.org/docs/en/language/data_types/). Useful for working with binary file formats, including map files for CS2D.

---

## Installation

Install via Composer:

```bash
composer require ernestpasnik/blitzmaxstream
```

---

## Features

* Read/write integers, floats, doubles, bytes, strings, and lines.
* Supports **little-endian** and **big-endian** formats.
* Safe buffer management with offset checking.
* Null-terminated string support (C-style strings).
* Stream utilities: skip bytes, seek position, check remaining bytes, clear buffer.

---

## API Documentation

### Reading Data

| Method                       | Description                                                              |
| ---------------------------- | ------------------------------------------------------------------------ |
| `readByte($offset = null)`   | Reads a single byte from the buffer.                                     |
| `readShort($offset = null)`  | Reads a 16-bit integer.                                                  |
| `readInt($offset = null)`    | Reads a 32-bit integer.                                                  |
| `readLong($offset = null)`   | Reads a 64-bit integer.                                                  |
| `readFloat($offset = null)`  | Reads a 32-bit float.                                                    |
| `readDouble($offset = null)` | Reads a 64-bit float (double).                                           |
| `readLine($offset = null)`   | Reads until a newline character (`\n`), ignoring carriage return (`\r`). |
| `readString($length)`        | Reads a string of fixed length.                                          |
| `readStringNT($length)`      | Reads a null-terminated string of given length, skips the null byte.     |

### Writing Data

| Method                  | Description                                    |
| ----------------------- | ---------------------------------------------- |
| `writeByte($value)`     | Writes a single byte.                          |
| `writeShort($value)`    | Writes a 16-bit integer.                       |
| `writeInt($value)`      | Writes a 32-bit integer.                       |
| `writeLong($value)`     | Writes a 64-bit integer.                       |
| `writeFloat($value)`    | Writes a 32-bit float.                         |
| `writeDouble($value)`   | Writes a 64-bit float (double).                |
| `writeLine($value)`     | Writes a line followed by CRLF (`\r\n`).       |
| `writeString($value)`   | Writes a string.                               |
| `writeStringNT($value)` | Writes a string followed by a null terminator. |

### Utilities

| Method                   | Description                                  |
| ------------------------ | -------------------------------------------- |
| `setPosition($position)` | Sets the buffer position.                    |
| `skipBytes($count)`      | Skips a number of bytes.                     |
| `position()`             | Returns the current offset.                  |
| `remaining()`            | Returns the number of bytes left to read.    |
| `size()`                 | Returns total buffer size.                   |
| `close()`                | Returns the buffer up to the current offset. |
| `clear()`                | Clears and resets the buffer.                |

---

## Usage Example

```php
<?php
require_once 'vendor/autoload.php';

// Create buffer and write some data
$buf = new BlitzMaxStream();
$buf->writeInt(123)
    ->writeStringNT("Hello BlitzMax")
    ->writeFloat(9.81);

// Read data back
$s = new BlitzMaxStream($buf->close());
echo $s->readInt(), "\n";          // 123
echo $s->readStringNT(50), "\n";   // Hello BlitzMax
echo $s->readFloat(), "\n";        // 9.81
```

---

## License

This package is licensed under the [MIT License](LICENSE).
