<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/BlitzMaxStream.php';

/**
 * Simple assertion function
 */
function assertEqual(mixed $expected, mixed $actual, string $name, float $epsilon = 0.0): void {
    $passed = $epsilon > 0 ? abs($expected - $actual) < $epsilon : $expected === $actual;
    echo $name . ': ' . ($passed ? "Passed\n" : "Failed (expected $expected, got $actual)\n");
}

// Define test cases
$tests = [
    ['name'=>'Byte',      'write'=>fn($s)=>$s->writeByte(42),                   'expected'=>42],
    ['name'=>'Short',     'write'=>fn($s)=>$s->writeShort(12345),               'expected'=>12345],
    ['name'=>'Int',       'write'=>fn($s)=>$s->writeInt(987654321),             'expected'=>987654321],
    ['name'=>'Long',      'write'=>fn($s)=>$s->writeLong(1234567890123456789), 'expected'=>1234567890123456789],
    ['name'=>'Float',     'write'=>fn($s)=>$s->writeFloat(3.14),               'expected'=>3.14, 'epsilon'=>0.00001],
    ['name'=>'Double',    'write'=>fn($s)=>$s->writeDouble(2.718281828459),    'expected'=>2.718281828459, 'epsilon'=>0.0000001],
    ['name'=>'String',    'write'=>fn($s)=>$s->writeString("Hello World"),     'expected'=>"Hello World"],
    ['name'=>'StringNT',  'write'=>fn($s)=>$s->writeStringNT("Test NT"),       'expected'=>"Test NT"],
    ['name'=>'Line',      'write'=>fn($s)=>$s->writeLine("This is a line"),    'expected'=>"This is a line"],
];

// Run all tests
foreach ($tests as $t) {
    $stream = new BlitzMaxStream();
    $t['write']($stream);
    $stream->setPosition(0);
    $epsilon = $t['epsilon'] ?? 0.0;

    // Determine which read method to call based on name
    $result = match($t['name']) {
        'Byte' => $stream->readByte(),
        'Short' => $stream->readShort(),
        'Int' => $stream->readInt(),
        'Long' => $stream->readLong(),
        'Float' => $stream->readFloat(),
        'Double' => $stream->readDouble(),
        'String' => $stream->readString(strlen($t['expected'])),
        'StringNT' => $stream->readStringNT(strlen($t['expected'])),
        'Line' => $stream->readLine(),
        default => null
    };

    assertEqual($t['expected'], $result, $t['name'], $epsilon);
}

// Sequential read/write example
$stream = new BlitzMaxStream();
$stream->writeByte(10)->writeShort(20)->writeInt(30);
$stream->setPosition(0);
echo "\nSequential read:\n";
echo "Byte: " . $stream->readByte() . "\n";
echo "Short: " . $stream->readShort() . "\n";
echo "Int: " . $stream->readInt() . "\n";
