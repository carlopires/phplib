<?php
class CLI {
	private $program_name = null;
	private $program_version = null;
	private $program_cmdline = null;

	public $init_vars = null;
	public $vars = null;

	public function __construct($name, $version = null, $cmdline = null, $init_vars = null) {
		$this->program_name = $name;
		$this->program_version = $version;
		$this->program_cmdline = $cmdline;

		$this->init_vars = $init_vars;
	}

	public function run($options) {
		$pname = $this->program_name;
		$pversion = $this->program_version;

		if ($pname && $pversion)
			print "$pname v$pversion\n";
		else if ($pname)
			print "$pname\n";

		if (!$this->parse_options($options))
			$this->print_how_to_use($options);
	}

	public function print_how_to_use($options = null) {
		if ($this->program_cmdline) {
			print "Use as:\n";
			print "\t$this->program_cmdline\n\n";
		}

		if (is_array($options) && count($options) > 0) {
			print "Options:\n";
			foreach($options as $op)
				print sprintf("\t%s: %s\n", $op[0], $op[1]);
		}
		exit;
	}

	private function get_vars() {
		if ($this->vars)
			return $this->vars;

		if ($this->init_vars) {
			$init_vars = $this->init_vars;
			$this->vars = $init_vars($this);
			return $this->vars;
		}

		return array();
	}

	private function parse_options($options) {
		$argc = $GLOBALS['argc'];
		$argv = $GLOBALS['argv'];

		$found_option = false;

		foreach($options as $op) {
			if (array_search($op[0], $argv)) {
				$op[2]($this->get_vars());
				$found_option = true;
			}
		}

		return $found_option;
	}
}

