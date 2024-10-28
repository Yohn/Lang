# Yohns\Lang\Text

Should be in working order, I'll test it more later.

It uses the [strtr](https://php.net/strtr) function to do fast find and replace of the strings. I did a few benchmark tests for find and replace of the strings on [ChatGPT Scripts - Benchmarks](https://github.com/Yohn/ChatGPT-Scripts/blob/main/benchmarks/find-and-replace.php)


```php
use Yohns\Lang\Text;

include 'vendor/autoload.php';

new Text('lib/Lang', 'es');

echo Text::L('Hello'); // Hola
echo Text::L('Hello [name]', ['[name]' => 'Yohn']); // Hola Yohn

```

---
## Class Yohns\Lang\Text

This class is responsible for handling language translations.
It loads language files from a specified directory and provides
functionality to retrieve phrases in the specified language.
It supports dynamic addition of phrases to the English language file
if they do not already exist.





## Methods

| Name | Description |
|------|-------------|
|[L](#textl)|Retrieves the translated phrase for the given phrase key.|
|[__construct](#text__construct)|Text constructor.|
|[getAll](#textgetall)|Retrieves all loaded texts.|
|[reload](#textreload)|Reloads the text for the current language.|
|[set](#textset)|Sets a new phrase or updates an existing one in the language text.|




### Text::L

**Description**

```php
public static L (string $phrase, array $ary)
```

Retrieves the translated phrase for the given phrase key.

If the phrase does not exist in the loaded language text,
it updates the English default language file with a new entry.

**Parameters**

* `(string) $phrase`
: The key of the phrase to retrieve.
* `(array) $ary`
: Optional replacements for placeholders in the phrase.

**Return Values**

`string|\Text`

> The translated phrase or the Text instance if updating.


<hr />


### Text::__construct

**Description**

```php
public __construct (string $dir, string $lingo)
```

Text constructor.



**Parameters**

* `(string) $dir`
: The directory where translations are located.
* `(string) $lingo`
: The language identifier, default is 'en'.

**Return Values**

`void`


**Throws Exceptions**


`\Exception`
> if the specified language directory is not readable
or the specified language file does not exist.

<hr />


### Text::getAll

**Description**

```php
public static getAll (void)
```

Retrieves all loaded texts.



**Parameters**

`This function has no parameters.`

**Return Values**

`mixed`

> An array containing all loaded texts or null if not loaded.


<hr />


### Text::reload

**Description**

```php
public static reload (void)
```

Reloads the text for the current language.



**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### Text::set

**Description**

```php
public static set (string $key, mixed $value)
```

Sets a new phrase or updates an existing one in the language text.



**Parameters**

* `(string) $key`
: The key of the text to set.
* `(mixed) $value`
: The value to associate with the key.

**Return Values**

`void`


<hr />
