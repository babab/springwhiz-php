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
require_once 'inc/lib/dbhandler.php';
require_once 'inc/bookmark.php';

class command {

    public static $commandlist = array(
        'bm'        => 'bookmark',
        'bookmark'  => 'bookmark',
        'h'         => 'help',
        'help'      => 'help',
        'li'        => 'login',
        'login'     => 'login',
        'lo'        => 'logout',
        'logout'    => 'logout',
        'reg'       => 'register',
        'register'  => 'register',
        'unknown'   => 'help',
    );

    public static function getContent($cmd)
    {
        if (empty($cmd))
            return array('', '');

        $args = explode(' ', $cmd);

        if (array_key_exists($args[0], static::$commandlist))
            $command = static::$commandlist[$args[0]];
        else
            $command = '_doesnotexist';

        switch ($command) {
        case 'help':
            return array('help', file_get_contents('./html/help.html'));
            break;
        case 'bookmark':
            if (!isset($_SESSION['logged_in'])
                    || $_SESSION['logged_in'] == false) {
                return array('bookmark', 'You have to be logged in before '
                        . 'you can add bookmarks');
            }

            switch($args[1]) {
            case 'add':
                if (isset($args[3])) {
                    $bm = new bookmark;
                    if ($bm->add($_SESSION['username'], $args[2], $args[3]))
                        return array('bookmark', 'Succesfully added bookmark');
                    else
                        return false;
                }
                break;
            case 'list':
                $bm = new bookmark;
                if ($list = $bm->htmlList($_SESSION['username']))
                    return array('bookmark', $list);
                else
                    return false;
                break;
            }

            $_SESSION['error'] = 'Invalid syntax for bookmark command';
            return array('help', file_get_contents('./html/help.html'));
            break;
        case 'login':
            if (!empty($args[1]))
                $_SESSION['username_inp'] = $args[1];
            else
                $_SESSION['username_inp'] = '';

            return array('login', ' ');
            break;
        case 'logout':
            unset($_SESSION['username_inp']);
            unset($_SESSION['username']);
            unset($_SESSION['error']);
            unset($_SESSION['logged_in']);
            return array('logout', ' ');
            break;
        case 'register':
            $_SESSION['username'] = '';
            return array('register', ' ');
            break;
        default:
            return array('', "<span class=\"warn\">Warning </span> "
                . "Did not recognize '@$cmd' as a valid command!"
                . file_get_contents('./html/help.html'));
        }
    }
}
