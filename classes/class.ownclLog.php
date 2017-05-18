<?php
require_once('./Services/Logging/classes/class.ilLog.php');

/**
 * Class ownclLog
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ownclLog extends ilLog {

	const OC_LOG = 'oc.log';
	/**
	 * @var swdrLog
	 */
	protected static $instance;


	/**
	 * @return swdrLog
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new self(ILIAS_LOG_DIR, self::OC_LOG);
		}

		return self::$instance;
	}


	function write($a_msg, $a_log_level = NULL) {
		parent::write($a_msg, $a_log_level);
	}


	/**
	 * @return mixed
	 */
	public function getLogDir() {
		return ILIAS_LOG_DIR;
	}


	/**
	 * @return string
	 */
	public function getLogFile() {
		return self::OC_LOG;
	}


	/**
	 * @return string
	 */
	public static function getFullPath() {
		$log = self::getInstance();

		return $log->getLogDir() . '/' . $log->getLogFile();
	}
}

?>
