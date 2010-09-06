/* <![CDATA[ */
/* File:        linkify.js
 * Version:     20100906_0900
 * Copyright:   (c) 2010 Jeff Roberson - http://jmrware.com
 * MIT License: http://www.opensource.org/licenses/mit-license.php
 *
 * Summary: This script linkifys http URLs on a page.
 *
 * Usage:   See demonstration page: linkify.html
 */
/* Here is a commented version of the regex used here (in PHP format):
# url_pattern: Match http(s)/ftp(s) URL that is not already linkified.
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
/imx
*/
function linkify(text) {
    var url_pattern = /(\()((?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$&'()*+,;=%]+)(\))|(\[)((?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$&'()*+,;=%]+)(\])|(\{)((?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$&'()*+,;=%]+)(\})|(<|&(?:lt|#60|#x3c);)((?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$&'()*+,;=%]+)(>|&(?:gt|#62|#x3e);)|((?:^|[^=\s'"\]])\s*['"]?|[^=\s]\s+)(\b(?:ht|f)tps?:\/\/[A-Za-z0-9\-._~:\/?#[\]@!$'()*+,;=%]*(?:(?!(?:&(?:gt|apos|quot|#0*(?:34|39|62)|#x0*(?:22|27|3e));))&[A-Za-z0-9\-._~:\/?#[\]@!$'()*+,;=%]*)*[A-Za-z0-9\-_~\/#[\]@$()*+;=%])/img;
    var url_replace = '$1$4$7$10$13<a href="$2$5$8$11$14">$2$5$8$11$14</a>$3$6$9$12';
    return text.replace(url_pattern, url_replace);
}
function prepare_linkification() {
    if (!document.getElementsByTagName) return;
    var elems = document.getElementsByTagName('*');
    for (var i = 0; i < elems.length; i++) {
        if (elems[i].className.match(/\blinkify\b/)) {
            elems[i].onclick = onclick_linkify;
            elems[i].title = 'Click to linkify URLs in this element.';
        }
    }
    elems = null;
}
function onclick_linkify() {
    this.onclick = null; // disable further clicks on this element.
    this.innerHTML = linkify(this.innerHTML);
    this.className = this.className.replace(/\blinkify\b/, 'linkified');
    this.setAttribute('title','All matching URLs here have been linkified.');
    analyse_links(this);
    return false;
}
function analyse_links(elem) {
    if (!document.getElementsByTagName) return false;
    var href;
    var re_paren = /\([^()[\]]*\)/;
    var re_brack = /\[[^()[\]]*\]/;
    var links = elem.getElementsByTagName('a');
    for (var i = 0; i < links.length; i++) {
        links[i].onclick = function() {alert("This is a dummy link.");return false;};
        href = links[i].getAttribute('href');
        while (href.search(re_paren) != -1) {
            href = href.replace(re_paren, "", href);
        }
        while (href.search(re_brack) != -1) {
            href = href.replace(re_brack, "", href);
        }
        if (href.search(/[()[\]]/) == -1) {
            links[i].title = 'This link has no unbalanced parentheses or square brackets.';
            links[i].className = 'balanced';
        } else {
            links[i].title = 'This link has unbalanced parentheses or square brackets.';
            links[i].className = 'unbalanced';
        }
    }
    links = null;
    return false;
}
window.onload = prepare_linkification;
/* ]]> */
