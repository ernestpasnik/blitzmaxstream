# BlitzMax Stream

A handy tool for working with binary data used in [BlitzMax](https://blitzmax.org/docs/en/language/data_types/).

## Installation

```bash
composer require ernestpasnik/blitzmaxstream
```

## Documentation

### Read

| Method                     | Description                                                                    |
| -------------------------- | ------------------------------------------------------------------------------ |
| readByte($offset = null)   | Reads a single byte from the buffer.                                           |
| readShort($offset = null)  | Reads a 16-bit integer from the buffer.                                        |
| readInt($offset = null)    | Reads a 32-bit integer from the buffer.                                        |
| readLong($offset = null)   | Reads a 64-bit integer from the buffer.                                        |
| readFloat($offset = null)  | Reads a 32-bit floating-point number from the buffer.                          |
| readDouble($offset = null) | Reads a 64-bit floating-point number from the buffer.                          |
| readLine($offset = null)   | Reads a line of text from the buffer until a newline character is encountered. |
| readString($length)        | Reads a specified number of characters from the buffer as a string.            |
| readStringNT($length)      | Reads a null-terminated string from the buffer, excluding the null byte.       |

### Write

| Method                     | Description                                                                    |
| -------------------------- | ------------------------------------------------------------------------------ |
| writeByte($value)          | Writes a single byte to the buffer.                                            |
| writeShort($value)         | Writes a 16-bit integer to the buffer.                                         |
| writeInt($value)           | Writes a 32-bit integer to the buffer.                                         |
| writeLong($value)          | Writes a 64-bit integer to the buffer.                                         |
| writeFloat($value)         | Writes a 32-bit floating-point number to the buffer.                           |
| writeDouble($value)        | Writes a 64-bit floating-point number to the buffer.                           |
| writeLine($value)          | Writes a line of text followed by a newline character to the buffer.           |
| writeString($value)        | Writes a string to the buffer.                                                 |
| writeStringNT($value)      | Writes a null-terminated string to the buffer, including the null byte.        |

### Other

| Method                     | Description                                                                    |
| -------------------------- | ------------------------------------------------------------------------------ |
| setPosition($position)     | Sets the buffer position to the specified value.                               |
| skipBytes($count)          | Skips a specified number of bytes in the stream.                               |
| position()                 | Returns the current buffer position.                                           |
| remaining()                | Returns the number of bytes remaining in the buffer.                           |
| close()                    | Returns the portion of the buffer written to so far.                           |
| size()                     | Returns the size of the buffer.                                                |
| clear()                    | Clears and resets the buffer.                                                  |

## Usage example

This library can be used to read Unreal Software's CS2D map file, specification can be found [here](https://www.unrealsoftware.de/files_pub/cs2d_spec_map_format.txt).

```php
$mapData = file_get_contents('de_dust2.map');
$buffer = new BlitzMaxStream($mapData);

$header = [];
$header['header'] = $buffer->readLine();
$header['scrollMap'] = $buffer->readByte();
$header['useModifiers'] = $buffer->readByte();
$header['saveTileHeights'] = $buffer->readByte();
$header['use64pxTiles'] = $buffer->readByte();
$buffer->skipBytes(6);
$header['sysUptime'] = $buffer->readInt();
$header['authorUSGN'] = $buffer->readInt();
$header['daylightTime'] = $buffer->readInt();
$buffer->skipBytes(28);
$header['authorName'] = $buffer->readLine();
$header['programUsed'] = $buffer->readLine();
$buffer->skipBytes(16);
$header['infoString'] = $buffer->readLine();
$header['tilesetImage'] = $buffer->readLine();
$header['tileCount'] = $buffer->readByte();
$header['mapWidth'] = $buffer->readInt();
$header['mapHeight'] = $buffer->readInt();
$header['backgroundImage'] = $buffer->readLine();
$header['bgScrollX'] = $buffer->readInt();
$header['bgScrollY'] = $buffer->readInt();
$header['bgColorRed'] = $buffer->readByte();
$header['bgColorGreen'] = $buffer->readByte();
$header['bgColorBlue'] = $buffer->readByte();
$header['headerTest'] = $buffer->readLine();
```

## License

This package is licensed under the [MIT License](LICENSE).
