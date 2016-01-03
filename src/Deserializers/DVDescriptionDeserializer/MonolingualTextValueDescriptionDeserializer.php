<?php

namespace SMW\Deserializers\DVDescriptionDeserializer;

use SMW\DataValues\MonolingualTextValue;
use SMW\Query\Language\ThingDescription;
use SMW\Query\Language\ValueDescription;
use SMW\Query\Language\Conjunction;
use SMW\Query\Language\SomeProperty;
use SMW\DataValueFactory;
use SMW\DIProperty;
use InvalidArgumentException;

/**
 * @private
 *
 * @license GNU GPL v2+
 * @since 2.3
 *
 * @author mwjames
 */
class MonolingualTextValueDescriptionDeserializer extends DescriptionDeserializer {

	/**
	 * @since 2.3
	 *
	 * {@inheritDoc}
	 */
	public function isDeserializerFor( $serialization ) {
		return $serialization instanceof MonolingualTextValue;
	}

	/**
	 * @since 2.3
	 *
	 * @param string $value
	 *
	 * @return Description
	 * @throws InvalidArgumentException
	 */
	public function deserialize( $value ) {

		if ( !is_string( $value ) ) {
			throw new InvalidArgumentException( 'value needs to be a string' );
		}

		if ( $value === '' ) {
			$this->addError( wfMessage( 'smw_novalues' )->text() );
			return new ThingDescription();
		}

		$subdescriptions = array();
		$dataValues = $this->dataValue->getValuesFromString( $value );

		foreach ( $dataValues as $dataValue ) {

			$value = $dataValue->getWikiValue();

			$comparator = SMW_CMP_EQ;
			$this->prepareValue( $value, $comparator );

			// Value can change during the prepare step therefore re-initialize
			$dataValue =  DataValueFactory::getInstance()->newPropertyObjectValue(
				$dataValue->getProperty(),
				$value
			);

			if ( !$dataValue->isValid() ) {
				$this->addError( $dataValue->getErrors() );
				continue;
			}

			$subdescriptions[] = $this->newSubdescription( $dataValue, $comparator );
		}

		return $this->getFinalDescriptionFor( $subdescriptions );
	}

	private function getFinalDescriptionFor( $subdescriptions ) {

		$count = count( $subdescriptions );

		if ( $count == 0 ) {
			return new ThingDescription();
		}

		if ( $count == 1 ) {
			return  reset( $subdescriptions );
		}

		return new Conjunction( $subdescriptions );
	}

	private function newSubdescription( $dataValue, $comparator ) {

		$description = new ValueDescription(
			$dataValue->getDataItem(),
			$dataValue->getProperty(),
			$comparator
		);

		if ( $dataValue->getWikiValue() === '+' || $dataValue->getWikiValue() === '?' ) {
			$description = new ThingDescription();
		}

		return new SomeProperty(
			$dataValue->getProperty(),
			$description
		);
	}

}
