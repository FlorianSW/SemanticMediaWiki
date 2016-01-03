<?php

namespace SMW\Tests\Deserializers\DVDescriptionDeserializer;

use SMW\Deserializers\DVDescriptionDeserializer\MonolingualTextValueDescriptionDeserializer;
use SMW\DataValues\MonolingualTextValue;
use SMW\DIProperty;

/**
 * @covers \SMW\Deserializers\DVDescriptionDeserializer\MonolingualTextValueDescriptionDeserializer
 * @group semantic-mediawiki
 *
 * @license GNU GPL v2+
 * @since 2.4
 *
 * @author mwjames
 */
class MonolingualTextValueDescriptionDeserializerTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$this->assertInstanceOf(
			'\SMW\Deserializers\DVDescriptionDeserializer\MonolingualTextValueDescriptionDeserializer',
			new MonolingualTextValueDescriptionDeserializer()
		);
	}

	public function testIsDeserializerForTimeValue() {

		$dataValue = $this->getMockBuilder( '\SMW\DataValues\MonolingualTextValue' )
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		$instance = new MonolingualTextValueDescriptionDeserializer();

		$this->assertTrue(
			$instance->isDeserializerFor( $dataValue )
		);
	}

	public function testNonStringThrowsException() {

		$recordValue = $this->getMockBuilder( '\SMW\DataValues\MonolingualTextValue' )
			->disableOriginalConstructor()
			->getMock();

		$instance = new MonolingualTextValueDescriptionDeserializer();
		$instance->setDataValue( $recordValue );

		$this->setExpectedException( 'InvalidArgumentException' );
		$instance->deserialize( array() );
	}

	/**
	 * @dataProvider valueProvider
	 */
	public function testDeserialize( $value, $decription ) {

		$instance = new MonolingualTextValueDescriptionDeserializer();
		$instance->setDataValue( new MonolingualTextValue() );

		$this->assertInstanceOf(
			$decription,
			$instance->deserialize( $value )
		);
	}

	public function valueProvider() {

		#0
		$provider[] = array(
			'Jan;1970',
			'\SMW\Query\Language\SomeProperty'
		);

		#1
		$provider[] = array(
			'Jan@en',
			'\SMW\Query\Language\Conjunction'
		);

		#2
		$provider[] = array(
			'?',
			'\SMW\Query\Language\SomeProperty'
		);

		#3
		$provider[] = array(
			'',
			'\SMW\Query\Language\ThingDescription'
		);

		return $provider;
	}

}
