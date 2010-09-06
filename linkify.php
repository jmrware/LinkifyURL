<?php
/* File:		linkify.php
 * Version:		20100906_0300
 * Copyright:	(c) 2010 Jeff Roberson - http://jmrware.com
 * MIT License:	http://www.opensource.org/licenses/mit-license.php
 *
 * Summary: This script linkifys http URLs on a page.
 *
 * Usage:	See example page: linkify.html
 */
$text = file_get_contents('linkify.html');
preg_match('/^(.*?<body[^>]*>)(.*)$/si', $text, $matches);
$text = $matches[2];

function linkify($text) {
	$url_pattern = '/
# url_pattern: Match http(s)\/ftp(s) URL that is not already linkified.
  (\()                     # $1: URL delimited by (parentheses).
  ((?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$&\'()*+,;=%]+)  # $2: URL.
  (\))                     # $3: ")" end delimiter.
| (\[)                     # $4: URL delimited by [square brackets].
  ((?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$&\'()*+,;=%]+)  # $5: URL.
  (\])                     # $6: "]" end delimiter.
| (\{)                     # $7: URL delimited by {curly brackets}.
  ((?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$&\'()*+,;=%]+)  # $8: URL.
  (\})                     # $9: "}" end delimiter.
| (<|&(?:lt|\#60|\#x3c);)  # $10: URL delimited by <angle brackets>.
  ((?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$&\'()*+,;=%]+)  # $11: URL.
  (>|&(?:gt|\#62|\#x3e);)  # $12: ">" end delimiter. (HTML entities too).
| (                        # $13: Prefix proving URLs not already linked.
    (?: ^                  # Can be a beginning of line\/ string or
    | [^=\s\'"\]]          # a non-equals, non-quote, followed by
    ) \s*[\'"]?            # optional whitespace and optional quote;
  | [^=\s]\s+              # or... a non-equals sign followed by whitespace.
  )                        # End $13. Non-prelinkified-proof prefix.
  ( \b                     # $14: Match other URLs.
    (?:ht|f)tps?:\/\/      # Required literal http URL prefix
    [A-Za-z0-9\-._~:\/?#[\]@!$\'()*+,;=%]* # Unroll-the-loop. (normal*)
    (?: (?!(?:&(?:gt|apos|quot|\#0*(?:34|39|62)|\#x0*(?:22|27|3e));))
      &                    # Match "&" if not an ending HTML entity (special).
      [A-Za-z0-9\-._~:\/?#[\]@!$\'()*+,;=%]* # more normal*
    )*                     # Unroll-the-loop (special normal*)*.
    [A-Za-z0-9\-_~\/#[\]@$()*+;=%] # Last char can\'t be [.:?!&\',]
  )                        # End $14. Other URLs.
/imx';
$url_replace = '$1$4$7$10$13<a href="$2$5$8$11$14">$2$5$8$11$14</a>$3$6$9$12';
	return preg_replace($url_pattern, $url_replace, $text);
}
echo($matches[1]. linkify($text));
?>