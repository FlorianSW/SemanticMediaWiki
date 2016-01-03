<?php

namespace SMW\Tests\DataValues\ValueParsers;

use SMW\DataValues\ValueParsers\MonolingualTextValueParser;

/**
 * @covers \SMW\DataValues\ValueParsers\MonolingualTextValueParser
 * @group semantic-mediawiki
 *
 * @license GNU GPL v2+
 * @since 2.4
 *
 * @author mwjames
 */
class MonolingualTextValueParserTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$this->assertInstanceOf(
			'\SMW\DataValues\ValueParsers\MonolingualTextValueParser',
			new MonolingualTextValueParser()
		);
	}

	/**
	 * @dataProvider fullStringProvider
	 */
	public function testFullParsableString( $value, $text, $languageCode ) {

		$instance = new MonolingualTextValueParser();
		$dataValues = $instance->parse( $value );

		$this->assertCount(
			2,
			$dataValues
		);

		$this->assertEquals(
			$text,
			$dataValues[0]->getWikiValue()
		);

		$this->assertEquals(
			$languageCode,
			$dataValues[1]->getWikiValue()
		);
	}

	public function testParsableStringWithMissingLanguageCode() {

		$instance = new MonolingualTextValueParser();
		$dataValues = $instance->parse( 'FooBar' );

		$this->assertCount(
			1,
			$dataValues
		);

		$this->assertEquals(
			'FooBar',
			$dataValues[0]->getWikiValue()
		);
	}

	public function fullStringProvider() {

		$provider[] = array(
			'Foo@EN',
			'Foo',
			'en'
		);

		$provider[] = array(
			'testWith@example.org@zh-hans',
			'testWith@example.org',
			'zh-Hans'
		);

		return $provider;
	}

}
