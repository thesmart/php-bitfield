<?php
/**
 * @author    John Smart <smartj@gmail.com>
 * @link      https://github.com/thesmart/php-bitfield
 */

require 'bootstrap.php';

use bitfield\Bitfield;

class BitfieldTest extends \PHPUnit_Framework_TestCase {

	public function testDefault() {
		$bf = new Bitfield(array(
			'a','b','c'
		));
		$this->assertTrue($bf->isOff('a'));
		$this->assertTrue($bf->isOff('b'));
		$this->assertTrue($bf->isOff('c'));

		$this->assertFalse($bf->isOn('a'));
		$this->assertFalse($bf->isOn('b'));
		$this->assertFalse($bf->isOn('c'));

		$this->assertFalse($bf->isOn('d'));
		$this->assertFalse($bf->isOff('d'));

		$this->assertEquals(array(), $bf->getOptionsOn());
		$this->assertEquals(0, $bf->getValue());

		$bfClone	= new Bitfield();
		$bfClone->unserialize($bf->serialize());
		$this->assertEquals($bf->getValue(), $bfClone->getValue());

		$this->assertEquals('0', (string)$bfClone);
	}

	public function testMutate() {
		$bf = new Bitfield(array(
			'a','b','c','d','e','f','g','h'
		));

		$bf->on('h');
		$bf->on('e');

		$this->assertTrue($bf->isOff('a'));
		$this->assertTrue($bf->isOff('b'));
		$this->assertTrue($bf->isOn('e'));
		$this->assertTrue($bf->isOn('h'));

		$this->assertFalse($bf->isOff('e'));
		$this->assertFalse($bf->isOff('h'));

		$this->assertEquals(array('e','h'), $bf->getOptionsOn());
		$this->assertEquals(144, $bf->getValue());

		$bfClone	= new Bitfield();
		$bfClone->unserialize($bf->serialize());
		$this->assertEquals($bf->getValue(), $bfClone->getValue());
		$this->assertEquals(144, $bfClone->getValue());

		$this->assertEquals('10010000', (string)$bfClone);
	}
}