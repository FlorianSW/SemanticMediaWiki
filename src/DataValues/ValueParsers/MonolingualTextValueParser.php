<?php

namespace SMW\DataValues\ValueParsers;

use SMW\DataValues\MonolingualTextValue;
use SMW\DataValueFactory;
use SMW\Localizer;
use SMWDataValue as DataValue;

/**
 * @private
 *
 * @license GNU GPL v2+
 * @since 2.4
 *
 * @author mwjames
 */
class MonolingualTextValueParser implements ValueParser {

	/**
	 * @var MonolingualTextValue
	 */
	private $monolingualTextValue;

	/**
	 * @var array
	 */
	private $errors = array();

	/**
	 * @since 2.4
	 *
	 * @return array
	 */
	public function getErrors() {
		return $this->errors;
	}

	/**
	 * @since 2.4
	 *
	 * @param string $userValue
	 *
	 * @return DataValue[]
	 */
	public function parse( $userValue ) {

		$text = $userValue;
		$dataValues = array();

		$languageCode = mb_substr( strrchr( $userValue, "@" ), 1 );

		// Remove the language code and marker from the text
		if ( $languageCode !== '' ) {
			$text = substr_replace( $userValue, '', ( mb_strlen( $languageCode ) + 1 ) * -1 );
		}

		foreach ( MonolingualTextValue::getPropertyDataItems() as $property ) {

			$value = null;

			if ( $property->getKey() === '_LCODE' && $languageCode !== '' ) {
				$value = Localizer::getLanguageCodeByBCP47( $languageCode );
			} elseif( $property->getKey() === '_LTEXT' ) {
				$value = $text;
			}

			if ( $value === null ) {
				continue;
			}

			$dataValue = DataValueFactory::getInstance()->newPropertyObjectValue(
				$property,
				$value
			);

			$dataValues[] = $dataValue;
		}

		return $dataValues;
	}

}
