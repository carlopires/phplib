<?php
/*
* ParseIf - A PHP paser for IF clauses
* Carlo Pires <carlopires@gmail.com> 
* 
* This class parses the following construction 
* (inspired by Django templates):
*  
* {% if <php condition> %}
* {% else %}
* {% endif %}
* 
* Example:
*  
*    $v = file_get_contents('select-customer.sql');
*
*    $p = new ParserIf(array(
*        'include_ids' => true,
*        'include_description' => true,
*    ));
*
*    print $p->parse($v);
*    
*    // Variable names can also start with '$'
*    // for instance:
*    $p = new ParserIf(array(
*        '$include_ids' => true,
*        '$include_description' => true,
*    ));
*     
*  
* The contents of select-customer.sql could be:
*    
*    select
*    {% if $include_ids %}
*        ID,
*    {% else %}
*        NUMBER,
*    {% endif %}
*        NAME,
*    {% if $include_description %}
*        DESCRIPTION,
*    {% endif %}
*        EMAIL
*    from
*        customers;
*        
*
*/
class ParserIf {
	private $variables;
	private $debug;

	public function __construct($variables = null, $debug = true) {
		$this->variables = $variables;
		$this->debug = $debug;
	}

	function parseif($text) {
		$start = strpos($text, '{% if ');
		if ($start !== false) {
			$end = null;
			$n = 0;
			$pos = $start+1;
			while (true) {
				$next_if = strpos($text, '{% if ', $pos);
				$next_endif = strpos($text, '{% endif %}', $pos);
					
				if ($next_endif === false)
					break;
					
				if ($next_if !== false && $next_if < $next_endif) {
					$n++;
					$pos = $next_if+1;
				} else {
					$end = $next_endif;

					if ($n == 0)
						break;
					else {
						$n--;
						$pos = $next_endif+1;
					}
				}
			}

			if ($end) {
				// extracts the condition
				$pos = strpos($text, '%}', $start+1);
				if ($pos !== false ) {
						
					$before = substr($text, 0, $start-1);
					$condition = substr($text, $start+6, $pos-($start+6));
						
					$valid = substr($text, $pos+2, $end-$pos-2);
					if (($else = strpos($valid, '{% else %}')) !== false) {
						$invalid = substr($valid, $else+10);
						$valid = substr($valid, 0, $else-1);
					} else
						$invalid = '';
						
					$after = substr($text, $end+11);
						
					return array(
							'before' => $before,
							'condition' => $condition,
							'valid' => $valid,
							'invalid' => $invalid,
							'after' => $after,
					);
				}
			}
		}

		return $text;
	}

	function parse($text, $variables=null) {
		// set global variables
		if (is_array($this->variables))
			foreach($this->variables as $name => $value) {
				if ($name[0] == '$')
					$name = substr($name, 1);
				$$name = $value;
			}

		// set local variables
		if (is_array($variables))
			foreach($variables as $name => $value) {
				if ($name[0] == '$')
					$name = substr($name, 1);
				$$name = $value;
			}

		// disable output if no debug
		if (!$this->debug)
			$err_level = error_reporting(0);

		// parse ifs
		while (is_array($parsed = $this->parseif($text))) {
			try {
				$valid = eval('return ' . $parsed['condition'] . ';');
			} catch (Exception $e) {
				$valid = false;
			}
				
			$text = $parsed['before'] .
			$parsed[($valid ? 'valid' : 'invalid')] .
			$parsed['after'];
		}

		// reset error output if no debug
		if (!$this->debug)
			error_reporting($err_level);

		// return parsed text
		return $parsed;
	}
}