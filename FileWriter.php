<?php

/**
 *
 * Class FileWriter
 *
 * Provides methods for writing content to a file with different modes.
 */
class FileWriter {

	private string $filename;

	/**
	 * FileWriter constructor.
	 *
	 * @param string $filename The name of the file to write to.
	 */
	public function __construct(string $filename) {
		$this->filename = $filename;
		$this->ensureDirectoryExists(dirname($filename));
		$this->createIfNeeded();
	}

	/**
	 * Ensures that the directory for the given path exists.
	 *
	 * @param string $directory The directory path.
	 * @return bool True if directory exists or is created, false otherwise.
	 */
	private function ensureDirectoryExists(string $directory): bool {
		return is_dir($directory) || mkdir($directory, 0755, true);
	}

	/**
	 * Creates the file if it does not exist.
	 *
	 * @param string $content The content to write.
	 * @return bool True on success, false on failure.
	 * @throws Exception If file already exists.
	 */
	public function create(string $content = ''): bool {
		if (file_exists($this->filename)) {
			throw new Exception("File '{$this->filename}' already exists.");
		}

		if (!$this->ensureDirectoryExists(dirname($this->filename))) {
			return false;
		}

		return $this->writeToFile($content, 'w');
	}

	/**
	 * Creates a new file with the given content if it doesn't exist.
	 *
	 * @return bool True if file exists or is created, false otherwise.
	 */
	private function createIfNeeded(): bool {
		return file_exists($this->filename) || $this->writeToFile('', 'w');
	}

	/**
	 * Overwrites the file with the given content.
	 *
	 * @param string $content The content to write.
	 * @return bool True on success, false on failure.
	 */
	public function overwrite(string $content): bool {
		if (!$this->createIfNeeded()) {
			return false;
		}
		return $this->writeToFile($content, 'w');
	}

	/**
	 * Appends the content to the end of the file.
	 *
	 * @param string $content The content to append.
	 * @return bool True on success, false on failure.
	 */
	public function append(string $content): bool {
		if (!$this->createIfNeeded()) {
			return false;
		}
		return $this->writeToFile($content, 'a');
	}

	/**
	 * Prepends the content to the beginning of the file.
	 *
	 * @param string $content The content to prepend.
	 * @return bool True on success, false on failure.
	 */
	public function prepend(string $content): bool {
		if (!$this->createIfNeeded()) {
			return false;
		}

		// Read current content
		$currentContent = file_get_contents($this->filename);
		if ($currentContent === false) {
			return false;
		}

		return $this->writeToFile($content . $currentContent, 'w');
	}

	/**
	 * Writes content to the file using specified mode.
	 *
	 * @param string $content The content to write.
	 * @param string $mode The file write mode ('w' for overwrite, 'a' for append).
	 * @return bool True on success, false on failure.
	 */
	private function writeToFile(string $content, string $mode): bool {
		return file_put_contents($this->filename, $content, LOCK_EX) !== false;
	}
}


// Example usage:
$filename = 'example/newfile.txt';
$fileWriter = new FileWriter($filename);

try {
	// Create a new file (throws exception if file exists)
	$fileWriter->create();
} catch (Exception $e) {
	echo "Error: " . $e->getMessage() . "\n";
}

// Overwrite file (create if it doesn't exist)
$fileWriter->overwrite("This will overwrite the file.\n");

// Append to file (create if it doesn't exist)
$fileWriter->append("This will be appended to the file.\n");

// Prepend to file (create if it doesn't exist)
$fileWriter->prepend("This will be prepended to the file.\n");
