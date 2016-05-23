<?php
/**
 * Created by PhpStorm.
 * User: Siebe
 * Date: 15/05/2016
 * Time: 17:15
 */

/**
 * Helper function for flash message
 *
 * @param $message
 */
function flash($message)
{
	session()->flash('message', $message);
}

/**
 * Get an array of all words in a string
 *
 * @param $string
 *
 * @return array
 */
function stringToWordArray($string)
{
	$newString = preg_replace('/[^A-Za-z 0-9\-]/', ' ', $string);
	$newArray = explode(" ", $newString);
	return $newArray;
}

/**
 * Check if the given word is in the array to ignore it
 *
 * @param $wordToCheck
 *
 * @return bool
 */
function checkIfWordIsIgnored($wordToCheck)
{
	$ignoredWords = \App\Word::get();
	//dump($ignoredWords);
	foreach ($ignoredWords as $row)
	{
		if($row->word == $wordToCheck)
		{
			return true;
		}
	}
	return false;
}