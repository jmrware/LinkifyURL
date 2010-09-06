<?php
/* File:		linkify.php
 * Version:		20100905_2300
 * Copyright:	(c) 2010 Jeff Roberson - http://jmrware.com
 * MIT License:	http://www.opensource.org/licenses/mit-license.php
 *
 * Summary: This script linkifys http URLs on a page.
 *
 * Usage:	See example page: linkify.html
 */
// Simplest replace handles URL delimited by parentheses.
$text = file_get_contents('linkify.html');
preg_match('/^(.*?<body[^>]*>)(.*)$/si', $text, $matches);
$text = $matches[2];

function linkify($text) {
	$pluck_URL_long = '/ # 20100905_pluck_URL: Match http\/ftp URL that is not already linkified.
  (\()					   # $1: URL delimited by (parentheses).
  ((?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$&\'()*+,;=%]+)  # $2: URL.
  (\))					   # $3: ")" end delimiter.
| (\[)					   # $4: URL delimited by [square brackets].
  ((?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$&\'()*+,;=%]+)  # $5: URL.
  (\])					   # $6: "]" end delimiter.
| (\{)					   # $7: URL delimited by {curly brackets}.
  ((?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$&\'()*+,;=%]+)  # $8: URL.
  (\})					   # $9: "}" end delimiter.
| (<|&(?:lt|\#60|\#x3c);)  # $10: URL delimited by <angle brackets>.
  ((?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$&\'()*+,;=%]+)  # $11: URL.
  (>|&(?:gt|\#62|\#x3e);)  # $12: ">" end delimiter. (HTML entities too).
| (						   # $13: Prefix proving URLs not already linked.
	(?: ^				   # Can be a beginning of line\/ string or
	| [^=\s\'"\]]		   # a non-equals, non-quote, followed by
	) \s*[\'"]?			   # optional whitespace and optional quote;
  | [^=\s]\s+			   # or... a non-equals sign followed by whitespace.
  )						   # End $13. Non-prelinkified-proof prefix.
  ( \b					   # $14: Match other URLs.
	(?:ht|f)tps?:\/\/	   # Required literal http URL prefix
	[A-Za-z0-9\-._~:\/?#[\]@!$\'()*+,;=%]* # Unroll-the-loop. (normal*)
	(?: (?!(?:&(?:gt|apos|quot|\#0*(?:34|39|62)|\#x0*(?:22|27|3e));))
	  &					   # Match "&" if not an ending HTML entity (special).
	  [A-Za-z0-9\-._~:\/?#[\]@!$\'()*+,;=%]* # more normal*
	)*					   # Unroll-the-loop (special normal*)*.
	[A-Za-z0-9\-_~\/#[\]@$()*+;=%] # Last char can\'t be [.:?!&\',]
  )						   # End $14. Other URLs.
/imx';
	$pluck_URL_short = '/(\()((?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$&\'()*+,;=%]+)(\))|(\[)((?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$&\'()*+,;=%]+)(\])|(\{)((?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$&\'()*+,;=%]+)(\})|(<|&(?:lt|\#60|\#x3c);)((?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$&\'()*+,;=%]+)(>|&(?:gt|\#62|\#x3e);)|((?:^|[^=\s\'"\]])\s*[\'"]?|[^=\s]\s+)(\b(?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$\'()*+,;=%]*(?:(?!(?:&(?:gt|apos|quot|\#0*(?:34|39|62)|\#x0*(?:22|27|3e));))&[A-Za-z0-9\-._~:\/?#[\]@!$\'()*+,;=%]*)*[A-Za-z0-9\-_~\/#[\]@$()*+;=%])/im';
	$text_long = preg_replace_callback($pluck_URL_long, "_linkify_callback", $text);
	$text_short = preg_replace_callback($pluck_URL_short, "_linkify_callback", $text);
	if ($text_long !== $text_short) exit("Error SHORT != LONG");
	return ($text_long);
}
function _linkify_callback($m) {
	for ($i = 0; $i <= 14; ++$i) if (!isset($m[$i])) $m[$i] = "";
	$url = $m[2] . $m[5] . $m[8] . $m[11] . $m[14];
	return $m[1] . $m[4] . $m[7] . $m[10] . $m[13] .
		'<a href="' . $url . '">' . $url . '</a>' . $m[3] . $m[6] . $m[9] . $m[12];
}
$text_long = linkify($text);
echo($matches[1]. $text_long);
?>