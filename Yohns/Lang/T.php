<?php

namespace Yohns\Lang;

use Yohns\Core\Config;

/**
 * Class Language
 * Handles language translation and management.
 *
 *  // Example usage:
 *  echo Language::translate(
 *	  'my-account.settings.edit.profile',
 *	  'Editing Detail for %d day%s which will also update %d location%s within %d day%s. Thanks %s!',
 *	  ['1', '', '5', 's', 'Friend!']
 *  );
 */
class T {
	private static array $translations = [];

	/**
	 * Translate a phrase.
	 *
	 * @param string $key
	 * @param string $phrase
	 * @param array $args
	 * @return string
	 */
	public static function _(string $key, string $phrase, array $findReplace = []): string {
		self::loadTranslations();

		// Replace placeholders in the phrase
		$translated = vsprintf($phrase, $args);

		// Add translation to the internal array if not already present
		if (!isset(self::$translations[$key])) {
			self::$translations[$key] = $phrase;
			self::saveTranslationsToFile();
		}

		return $translated;
	}

	/**
	 * Load translations from the translation file.
	 */
	private static function loadTranslations(): void {
		$file = self::TRANSLATION_FILE;
		if (file_exists($file)) {
			self::$translations = include $file;
			ksort(self::$translations); // Sort translations by key
		}
	}

	/**
	 * Save translations to the translation file.
	 */
	private static function saveTranslationsToFile(): void {
		$file = self::TRANSLATION_FILE;
		$content = "<?php\nreturn [\n";

		// Generate content for each translation
		foreach (self::$translations as $key => $phrase) {
			$escapedPhrase = str_replace("'", "\'", $phrase);
			$content .= "\t'$key' => '$escapedPhrase',\n";
		}
		$content .= "];\n";
		// Write content to file
		file_put_contents($file, $content);
	}
}
