<?php

require_once('../src/BlitzMaxStream.php');

function testReadWriteByte()
{
    $stream = new BlitzMaxStream();
    $value = 42;
    
    $stream->writeByte($value);
    $stream->setPosition(0);
    $result = $stream->readByte();
    
    if ($value === $result) {
        echo "testReadWriteByte: Passed\n";
    } else {
        echo "testReadWriteByte: Failed\n";
    }
}

function testReadWriteShort()
{
    $stream = new BlitzMaxStream();
    $value = 12345;
    
    $stream->writeShort($value);
    $stream->setPosition(0);
    $result = $stream->readShort();
    
    if ($value === $result) {
        echo "testReadWriteShort: Passed\n";
    } else {
        echo "testReadWriteShort: Failed\n";
    }
}

function testReadWriteInt()
{
    $stream = new BlitzMaxStream();
    $value = 987654321;
    
    $stream->writeInt($value);
    $stream->setPosition(0);
    $result = $stream->readInt();
    
    if ($value === $result) {
        echo "testReadWriteInt: Passed\n";
    } else {
        echo "testReadWriteInt: Failed\n";
    }
}

function testReadWriteLong()
{
    $stream = new BlitzMaxStream();
    $value = 1234567890123456789;
    
    $stream->writeLong($value);
    $stream->setPosition(0);
    $result = $stream->readLong();
    
    if ($value === $result) {
        echo "testReadWriteLong: Passed\n";
    } else {
        echo "testReadWriteLong: Failed\n";
    }
}

function testReadWriteFloat()
{
    $stream = new BlitzMaxStream();
    $value = PHP_FLOAT_EPSILON;
    
    $stream->writeFloat($value);
    $stream->setPosition(0);
    $result = $stream->readFloat();
    
    if ($value === $result) {
        echo "testReadWriteFloat: Passed\n";
    } else {
        echo "testReadWriteFloat: Failed\n";
    }
}

function testReadWriteDouble()
{
    $stream = new BlitzMaxStream();
    $value = 2.71828;
    
    $stream->writeDouble($value);
    $stream->setPosition(0);
    $result = $stream->readDouble();
    
    if ($value === $result) {
        echo "testReadWriteDouble: Passed\n";
    } else {
        echo "testReadWriteDouble: Failed\n";
    }
}

function testReadWriteString()
{
    $stream = new BlitzMaxStream();
    $value = "Hello, World!";
    
    $stream->writeString($value);
    $stream->setPosition(0);
    $result = $stream->readString(strlen($value));
    
    if ($value === $result) {
        echo "testReadWriteString: Passed\n";
    } else {
        echo "testReadWriteString: Failed\n";
    }
}

function testReadWriteStringNT()
{
    $stream = new BlitzMaxStream();
    $value = "Hello, World!";
    
    $stream->writeStringNT($value);
    $stream->setPosition(0);
    $result = $stream->readStringNT(strlen($value));
    
    if ($value === $result) {
        echo "testReadWriteStringNT: Passed\n";
    } else {
        echo "testReadWriteStringNT: Failed\n";
    }
}

function testReadWriteLine()
{
    $stream = new BlitzMaxStream();
    $value = "This is a test line.";
    
    $stream->writeLine($value);
    $stream->setPosition(0);
    $result = $stream->readLine();
    
    if ($value === $result) {
        echo "testReadWriteLine: Passed\n";
    } else {
        echo "testReadWriteLine: Failed\n";
    }
}

testReadWriteByte();
testReadWriteShort();
testReadWriteInt();
testReadWriteLong();
testReadWriteFloat();
testReadWriteDouble();
testReadWriteString();
testReadWriteStringNT();
testReadWriteLine();
