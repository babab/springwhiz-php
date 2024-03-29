<?php
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

require_once 'inc/lib/sprwz.php';
require_once 'inc/lib/user.php';
require_once 'inc/lib/cookie_login.php';
require_once 'inc/bookmark.php';
require_once 'inc/notepad.php';

echo 'Installing users table<br>';
$user = new user;
$user->install();

echo 'Installing cookie_login table<br>';
$cookie = new cookie_login(null);
$cookie->install();

echo 'Installing bookmarks table<br>';
$bm = new bookmark;
$bm->install();

echo 'Installing notepad table<br>';
$notepad = new notepad(null, false);
$notepad->install();

echo 'Done';
