<?php

function test($name){
	return 'hello '.$name;
}

$secret = 'secret in top';

return [
	'hello' => test('Bob'),
	'and' => test('Chat'),
	'you' => test($secret),
];