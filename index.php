<?php
# vim: set ts=2 sw=2 sts=2:

# Copyright (c) 2012 Benjamin Althues <benjamin@babab.nl>
#
# Permission to use, copy, modify, and distribute this software for any
# purpose with or without fee is hereby granted, provided that the above
# copyright notice and this permission notice appear in all copies.
#
# THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
# WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
# MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
# ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
# WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
# ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
# OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.

if (!empty($_GET['cmd']))
  $cmd = htmlentities($_GET['cmd']);
else
  $cmd = '';

if (!empty($_GET['q']))
  $q = htmlentities($_GET['q']);
else
  $q = '';

$content = '';

switch ($cmd) {
default:
  $content .= "<strong>Did not recognize '@$cmd' as a valid command!"
    . "</strong><hr>";
case 'help':
  $content .= "<h1>Help</h1>"
    . "<strong>Here are some commands</strong>\n"
    . "@help            - Show this help information\n"
    . "@help &lt;command&gt;  - Show help for command\n"
    . "#&lt;favorite&gt;      - Go to &lt;favorite&gt;\n"
    . "\n"
    . "<strong>Duckduckgo.com bang syntax search</strong>\n"
    . "!yt &lt;string&gt;     - Search YouTube\n"
    . "!g &lt;string&gt;      - Search Google\n"
    . "!i &lt;string&gt;      - Search Google Images\n"
    . "\n"
    . "<a href=\"https://duckduckgo.com/?q=!bang\">"
    . "Learn more on duckduckgo</a>";
  break;
case 'unknown':
  $content .= '';
}

?><!doctype html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <script type="text/javascript" src="/js/lib/jquery-1.7.1.min.js"></script>
  </head>
  <body>
    <div id="container">
      <p id="s0">enter query</p>
      <p id="s1">press enter to submit</p>
      <form method="get" action="/parse/">
        <input type="text" id="q" name="q" value="<?php echo $q ?>">
      </form>
      <br>

      <div id="menu">
        <small>type '@help' to get started</small>
      </div><!-- #menu -->

      <div id="content">
        <pre><?php echo $content ?></pre>
      </div><!-- #content -->
    </div><!-- #container -->

    <script type="text/javascript" src="/js/main.js"></script>
    <?php if (!empty($cmd)): ?>
      <script type="text/javascript">
        $("#content").show();
        $("#menu").hide();
      </script>
    <?php endif ?>

  </body>
</html>