<?php
/**
 * @author    John Smart <smartj@gmail.com>
 * @link      https://github.com/thesmart/php-bitfield
 */

namespace bitfield;

/**
 * Map an array of options to a binary bitfield as a string or integer, and back again.
 */
class Bitfield implements \Serializable {

	/**
	 * Defined options mapped to bitfield.
	 * key: string option value
	 * value: int binary column
	 *
	 * @var    array
	 * @access protected
	 */
	protected $options;

	/**
	 * @var    int
	 * @access protected
	 */
	protected $value;

	/**
	 * Class constructor.
	 *
	 * @param array $options	Optional options this Bitfield will represent
	 * @param int $bitfield		Optional bitfield value to init from.
	 */
	public function __construct(array $options = array(), $bitfield = 0) {
		$this->options = array_flip(array_values($options));
		$this->setValue($bitfield);
	}

	/**
	 * @return int
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Set the value of the bitfield using an integer or binary string
	 *
	 * @param string|int $value
	 *
	 * @return void
	 * @access public
	 */
	public function setValue($value) {
		if (is_string($value)) {
			$value = bindec($value);
		}

		$this->value	= $value;
	}

	/**
	 * Get all the options that are on
	 * @return array
	 */
	public function getOptionsOn() {
		$onOptions	= array();
		foreach ($this->options as $option => $column) {
			if ($this->isOn($option)) {
				$onOptions[]	= $option;
			}
		}
		return $onOptions;
	}

	/**
	 * Is an option on?
	 * @param string $option
	 * @return boolean
	 */
	public function isOn($option) {
		if (!isset($this->options[$option])) {
			// no set, not on OR off
			return false;
		}

		$pow2	= pow(2, $this->options[$option]);
		return ($this->value & $pow2) === $pow2;
	}

	/**
	 * Is an option off?
	 * @param string $option
	 * @return boolean
	 */
	public function isOff($option) {
		if (!isset($this->options[$option])) {
			// no set, not on OR off
			return false;
		}

		return !$this->isOn($option);
	}

	/**
	 * Turn an option on
	 * @param string $option
	 */
	public function on($option) {
		if (!isset($this->options[$option])) {
			// do nothing
			return;
		}

		$pow2	= pow(2, $this->options[$option]);
		$this->value = $this->value | $pow2;
	}

	/**
	 * Turn an option off
	 * @param string $option
	 */
	public function off($option) {
		if (!isset($this->options[$option])) {
			// do nothing
			return;
		}

		$pow2	= pow(2, $this->options[$option]);
		$this->value = $this->value & (~$pow2);
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return decbin($this->value);
	}

	/**
	 * @return string
	 * @access public
	 */
	public function serialize() {
		$box	= array(
			'options'		=> $this->options,
			'value'			=> $this->value
		);
		return serialize($box);
	}

	/**
	 * @param string $serialized
	 * @return void
	 */
	public function unserialize($serialized) {
		$box	= unserialize($serialized);
		if (!isset($box['options']) || !isset($box['value'])) {
			return;
		}

		$this->options	= $box['options'];
		$this->value	= $box['value'];
	}
}