<?php
namespace Megaforms\Vendor\Libs\Helpers;
use Megaforms\Vendor\Exceptions\ArgException;
use Megaforms\Vendor\Exceptions\FileException;


/**
 * Class Helpers
 * @since 1.0.0
 */
class CommonHelpers
{

    /**
     * Topic concerning this method
     * @link: http://stackoverflow.com/questions/173400/how-to-check-if-php-array-is-associative-or-sequential
     * @param array $array
     * @return bool
     */
    public static function isAssoc(array $array){
        return array_keys($array) !== range(0,count($array) -1);
    }
	/**
	 * Add or remove ".min" to path based on AMOFORMS_USE_MIN_JS
	 * @since 1.0.0
	 * @param string $path
	 * @return string
	 */
	public static function get_js_path($path)
	{
		$is_min = (strpos($path, '.min.js') !== FALSE);
		if (true) {
			if (!$is_min) {
				$path = str_replace('.js', '.min.js', $path);
			}
		} else {
			if ($is_min) {
				$path = str_replace('.min.js', '.js', $path);
			}
		}
		return $path;
	}

	/**
	 * Escape HTML-entities in string
	 * @since 1.0.0
	 * @param string $text
	 * @return string
	 */
	public static function escape($text)
	{
		return htmlspecialchars(htmlspecialchars_decode(trim((string)$text), ENT_QUOTES | ENT_HTML401), ENT_QUOTES | ENT_HTML401);
	}


	/**
	 * Un-escape HTML-entities in string
	 * @since 1.0.2
	 * @param string $text
	 * @return string
	 */
	public static function un_escape($text)
	{
		return trim(htmlspecialchars_decode((string)$text, ENT_QUOTES | ENT_HTML401));
	}

	/**
	 * Remove slashes before quotes in string or array.
	 * For array this function walk over all string values.
	 * @param string $string
	 * @return string|array
	 */
	public static function strip_slashes($string)
	{
		if (is_array($string)) {
			foreach ($string as $index => $str) {
				$string[$index] = self::strip_slashes($str);
			}
		} elseif (is_string($string)) {
			$string = stripslashes($string);
		}
		return $string;
	}

	/**
	 * Trim string values in array
	 * @since 2.15.0
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	public static function trim_values(array $array) {
		foreach ($array as $key => $item) {
			if (is_string($item)) {
				$array[$key] = trim($item);
			} elseif (is_array($item)) {
				$array[$key] = self::trim_values($item);
			}
		}

		return $array;
	}

    /**
     * Trim each side of a string
     * @since 1.0.0
     *
     * @param string $str
     *
     * @return string $tstr;
     */
    public static function strim($str){
        if(is_string($str) && strlen($str) > 0)
            return trim($str);
        else
            return '';
    }

	/**
	 * Array validator for empty values
	 * @since 1.0.0
	 * @param array  $array - array for checking
	 * @param array  $keys  - array of keys that should be checked: [id, settings => [email => [name, subject, to]]]
	 * @param string $prefix - internal parameter for building path of array keys
	 * @throws ArgException
	 */
	public static function validate_for_empty(array $array, array $keys, $prefix = '')
	{
		foreach ($keys as $key => $value) {
			if (is_array($value)) {
				$path = $prefix . "[$key]";
				if (empty($array[$key])) {
					throw new ArgException(
						ArgException::WRONG_PARAMETER,
						["Empty $path"]
					);
				}
				self::validate_for_empty($array[$key], $value, $path);
			} else {
				if (is_string($key) && is_string($value)) {
					$path = $prefix . "[$key][$value]";
					if (empty($array[$key][$value])) {
						throw new ArgException(
							ArgException::WRONG_PARAMETER,
							["Empty $path"]
						);
					}
				} else {
					$key = (string)$value;
					$path = $prefix . "[$key]";
					if (empty($array[$key])) {
						throw new ArgException(
                            ArgException::WRONG_PARAMETER,
                            ["Empty $path"]
                        );
					}
				}
			}
		}
	}

	/**
	 * Show exceptions only in debug mode
	 * @since 1.0.0
	 * @param \Exception $e
	 */
	public static function handle_exception(\Exception $e) {
		if (defined('WP_DEBUG') && WP_DEBUG) {
//			if (Router::instance()->is_ajax()) {
//				(new Libs\Http\Response\Ajax())
//					->set_message($e->getMessage())
//					->set('trace', $e->__toString())
//					->send();
//			} else {
			return
			"<pre>Exception: <b>" . $e->getMessage() . "</b>"
			. PHP_EOL . "\n".
			var_dump($e).
			"</pre>";
//			}
		}
		return "Error";
	}

	/**
	 * Load files from external sources
	 * @param $path
	 * @return string
	 * @throws FileException
     */
	public static function loadFile($path,$fileName){
		$libContent = file_get_contents($path);
		if(is_writable(PluginDataSet::load()->uploads_path)){
			$localPath = PluginDataSet::load()->uploads_path . $fileName;
			if(file_put_contents($localPath,$libContent)){
				return $localPath;
			}else{
				throw new FileException(
					FileException::WRITTING_ERROR,
					[$localPath]
				);
			}
		}else{
			throw new FileException(
				FileException::INACCESSIBLE_PATH,
				[$path]
			);
		}
	}
}
