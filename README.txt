/* File:        README for linkify.js and linkify.php
 * Version:     20100913_0900
 * Copyright:   (c) 2010 Jeff Roberson - http://jmrware.com
 * MIT License: see: http://www.opensource.org/licenses/mit-license.php
 */

Summary:  This project consists of 2 script files: linkify.js and linkify.php.
Each of these scripts contain a function called: "linkify()" which converts
all URLs (HTTP, HTTPS, FTP and FTPS)) in a passed string into equivalent HTML
link tags. i.e. Given the input string: 'Go http://test.com here!', the
function returns: 'Go <a href="http://test.com">http://test.com"<a> here!'.
The linkify function does not touch URLs that are already part of a HTML or
BBCode link. e.g. 

File Descriptions:
-----------
 linkify.js
-----------
Javascript file containing the linkify() function. It also has a few support
functions which allow the user to clink on elements of the linkify.html page
to linkify their contents interactively. Once linkified the script checks to
see if the resulting URL links contain any unbalanced parentheses or square
brackets and displays the ones which are unbalanced in red.

------------
 linkify.php
------------
PHP script file containing the linkify() function. It also has code which
reads the linkify.html file, linkifies all the contents of its <body>
element, then displays the converted web page.

------------
 linkify.rbl
------------
This is a RegexBuddy library file containing the regular expression
used by the scripts. This tool was used extensively during development.
Highly recommended. See: http://www.regexbuddy.com/.

-------------
 linkify.html
-------------
This web page contains many un-linked URLs which are used to test and
demonstrate the linkify.js and linkify.php scripts. If also provides
a detailed commented listing of the regular expression used.
