<?php
/**
 * file: log.php
 * author: Samuel Taylor
 * 
 * Contains implementation for the Log class
 */

/**
 * Log
 *
 * A simple class for logging with log levels inspired by android.util.Log. As
 * of right now, it writes to a few files in /var/log/tmlm/.
 */
class Log {
    private static $logFileBase = "/var/log/tmlm/tmlm.";

    /**
     * logToFile
     *
     * Logs a given message (along with the date/time) at a given log level.
     *
     * Parameters:
     *  level: a string representing the level at which to log. "e" and "w"
     *  correspond with error and warning, respectively. Everything else is
     *  logged to info.
     *
     *  msg: a string to be logged
     *
     * Return value: true if the logging was successful, false otherwise.
     */
    private static function logToFile($level, $msg) {
        $suffix = "info";
        switch ($level) {
        case "e":
            $suffix = "err";
            break;
        case "w":
            $suffix = "warn";
        }// Otherwise, suffix will remain "info"
        $f = fopen(self::$logFileBase . $suffix, "a");
        if (!$f) { return false; }
        if (fwrite($f, date("Y-m-d H:i:s") . PHP_EOL .
            $msg . PHP_EOL .
            "====" . PHP_EOL . PHP_EOL)) {
            return true;
        } else { return false; }
    }

    /**
     * e, w, and i
     *
     * These functions log a message at the error, warning, and information
     * levels, respectively.
     *
     * Parameters:
     *  msg: a string to be logged
     *
     * Return value: true if the logging was successful, false otherwise
     */
    public static function e($msg) { return self::logToFile("e", $msg); }
    public static function w($msg) { return self::logToFile("w", $msg); }
    public static function i($msg) { return self::logToFile("i", $msg); }
}
?>

