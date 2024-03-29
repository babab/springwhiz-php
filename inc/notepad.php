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

class notepad extends sprwz {
    private $notepad;
    private $user_id;

    public function __construct($username, $fetch = true)
    {
        parent::__construct();

        if ($fetch) {
            $user = new user;
            $this->user_id = $user->id($username);
        }

        if ($fetch && !$this->notepad = $this->fetch($this->user_id)) {
            $q = "INSERT INTO _T_notepad (user_id, content)
                VALUES (
                    '".$this->db->escape($this->user_id)."',
                    '".'This is your notepad, edit and save notes and access'
                    . " them everywhere...'
                )";
            $this->db->query($q);
        }
    }

    public function update()
    {
        $q = "UPDATE _T_notepad SET content = "
                . "'".$this->db->escape($this->notepad)."'
                WHERE user_id = $this->user_id";
        $this->db->query($q);
    }

    public function authenticate_form()
    {
        if (empty($_POST))
            return false;

        if (!$_SESSION['logged_in'] || empty($this->user_id))
            return false;

        $this->requireValidToken();

        $_SESSION['error'] = '';

        $this->notepad = htmlentities($_POST['notepad']);

        if (!empty($this->notepad))
            $this->update();
    }

    public function html()
    {
        $html = '<h1>Notepad</h1>
                <form method="post"
                action="' . $this->base_url .'/query.php?m=notepad">
                <textarea id="notepad" name="notepad" rows="20">'
                  . $this->notepad . '</textarea>
                  <br><br>
                  <div class="center">
                   <input type="submit" id="submit" name="submit" value="save">
                  </div>
                  <input type="hidden" id="token" name="token"
                         value="'.$this->get_token().'">
                </form>';
        return $html;

    }

    public function fetch($user_id)
    {
        $q = "SELECT content FROM _T_notepad "
            . "WHERE user_id = $user_id ";

        if ($res = $this->db->qfetch_first($q))
            return $res['content'];
        else
            return false;
    }

    public function install()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS _T_notepad (
                notepad_id      INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id         INT(10) NOT NULL,
                content         TEXT NOT NULL
            ) ENGINE = InnoDB");

        $this->db->query("
            ALTER TABLE _T_notepad ADD FOREIGN KEY (user_id)
            REFERENCES _T_users(user_id)
            ON DELETE CASCADE ON UPDATE NO ACTION");
    }
}
