<?php

namespace Yohns\Lang;

use Exception;
/**
 * Class Text
 *
 * This class is responsible for handling language translations.
 * It loads language files from a specified directory and provides
 * functionality to retrieve phrases in the specified language.
 * It supports dynamic addition of phrases to the English language file
 * if they do not already exist.
 */
class Text {

	private static array $text = [];
	private static array $textDefault = [];
	private static string $lingo;
	private static string $langFile;
	private static string $directory;

	/**
	 * Text constructor.
	 *
	 * @param string $dir The directory where translations are located.
	 * @param string $lingo The language identifier, default is 'en'.
	 *
	 * @throws Exception if the specified language directory is not readable
	 *         or the specified language file does not exist.
	 */
	public function __construct(string $dir, string $lingo='en') {
		self::$lingo = $lingo;
		self::$directory = $dir;
		if (!is_dir(self::$directory) || !is_readable(self::$directory)) {
			$d = self::$directory;
			throw new Exception("Directory does not exist or is not readable: $d");
		}
		if(str_starts_with($lingo, 'WebId:')) {
			// I need to figure out a way to load the websites specific language here..
			// maybe have their new phrases in the db and then load them dynamically self::set()
			throw new Exception("Web languages are not supported yet.");
		}
		self::$langFile = self::$directory.'/'.self::$lingo.'.php';
		if(!is_file(self::$langFile)) {
			throw new Exception("Lang file not found: ".self::$langFile);
		}
		self::$text[$lingo] = include self::$langFile;
		if($lingo != 'en'){
			// have the default here too..
			self::$textDefault = include self::$directory.'/en.php';
		}
	}

	/**
	 * Retrieves the translated phrase for the given phrase key.
	 *
	 * If the phrase does not exist in the loaded language text,
	 * it updates the English default language file with a new entry.
	 *
	 * @param string $phrase The key of the phrase to retrieve.
	 * @param array $ary Optional replacements for placeholders in the phrase.
	 *
	 * @return string|Text The translated phrase or the Text instance if updating.
	 */
	public static function L(string $phrase, array $ary = []): string|Text {
		if(array_key_exists($phrase, self::$text)){
			return count($ary) > 0 ? strtr(self::$text[$phrase], $ary) : self::$text[$phrase];
		} else if(!array_key_exists($phrase, self::$textDefault)){
			return count($ary) > 0 ? strtr(self::$text[$phrase], $ary) : self::$textDefault[$phrase];
		} else {
			// add to default en file..
			return self::updateDefault($phrase, $ary);
		}
	}

	/**
	 * Updates the default English language file by adding a new phrase.
	 *
	 * @param string $phrase The new phrase to add.
	 * @param array $ary The replacements for the phrase.
	 *
	 * @return string The translated phrase after updating the English file.
	 *
	 * @throws Exception if the language file format is invalid.
	 */
	private static function updateDefault(string $phrase, array $ary): string {
		// Open the specified config file for reading
		$english = self::$directory.'/en.php';
		$fileContent = trim(file_get_contents($english));
		// Ensure the last line is ];
		if(!str_ends_with(trim($fileContent), '];')){
			throw new Exception("Language file ".$english." format invalid, does not end with ];");
		}
		// removed the ending ]; from  he file so we can add the new info
		// after [; gets removed, we trim the whitespace
		$trimmedContents = trim(trim($fileContent, '];'));
		if(!str_ends_with($trimmedContents, ',')){
			$trimmedContents .= ',';
		}
		$addSlashes = strtr($phrase, ["'", "\'"]);
		$trimmedContents .= "\n\t'{$phrase}' => '{$addSlashes}',";
		$trimmedContents .= "\n];";
		// Write back to the file
		file_put_contents($english, $trimmedContents);
		return strtr($phrase, $ary);
	}

	/**
	 * Retrieves all loaded texts.
	 *
	 * @return mixed An array containing all loaded texts or null if not loaded.
	 */
	public static function getAll(): mixed {
		return self::$text ?? null;
	}

	/**
	 * Sets a new phrase or updates an existing one in the language text.
	 *
	 * @param string $key The key of the text to set.
	 * @param mixed $value The value to associate with the key.
	 */
	public static function set(string $key, mixed $value): void {
		self::$text[$key] = $value;
		// I might use this for the website customization to the language..?
	}

	/**
	 * Reloads the text for the current language.
	 */
	public static function reload(): void {
		self::$text = [];
		(new self(self::$lingo));
	}
}
