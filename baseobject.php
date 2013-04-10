<?php
/*
* Abstract helper class to allow create class properties
* 
* From: http://php.net/manual/en/language.oop5.properties.php
*/
abstract class PropertiesObject {
	public function __get($name) {
		if (method_exists($this, ($method = '_get_'.$name)))
			return $this->$method();
		else 
			return;
	}
	
	public function __isset($name) {
		if (method_exists($this, ($method = '_isset_'.$name))) 
			return $this->$method();
		else
			return;
	}

	public function __set($name, $value) {
		if (method_exists($this, ($method = '_set_'.$name)))
			$this->$method($value);
	}

	public function __unset($name) {
		if (method_exists($this, ($method = '_unset_'.$name))) 
			$this->$method();
	}
}

/*
 * An abstract class to allow cached properties
 * defined in a more readable way.
 */
abstract class CachedPropertiesObject {
	protected static $CACHED_PROPERTIES = null;
	private $cached_values = array();
	
	public function __get($name) {
		if (is_array(self::$CACHED_PROPERTIES) &&
			array_search($name, self::$CACHED_PROPERTIES) !== false) {
			
			if (!array_key_exists($name, $this->cached_values)) {
				if (method_exists($this, ($method = 'cached_'.$name)))
					$this->cached_values[$name] = $this->$method();
				else
					throw new Exception('Invalid property: '.$name);
			}
			return $this->cached_values[$name];
		}
		throw new Exception('Invalid property: '.$name);
	}
}