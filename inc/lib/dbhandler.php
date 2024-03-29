<?php
/*
 * Copyright (c) 2012 Benjamin Althues <benjamin@babab.nl>
 *
 * Permission to use, copy, modify, and distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
 * ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
 * OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 */

class dbhandler {
    private $db_prefix;
    private $db_conn;
    private $db_query;
    private $db_res;

    public function __construct($db_name, $db_user, $db_pass, $db_prefix,
            $db_host = 'localhost', $db_port = 3306)
    {
        $this->db_prefix = $db_prefix;
        $this->db_conn = mysql_connect($db_host.':'.$db_port,
                                       $db_user, $db_pass);
        if (!$this->db_conn)
            sprwz::error("Connection error: ". mysql_error());
        if (!mysql_select_db($db_name, $this->db_conn)) {
            sprwz::error("Error connecting to database '" . $db_name .
                "': ". mysql_error());
        }
        return $this;
    }

    public function query($query)
    {
        $q = str_replace('_T_', $this->db_prefix, $query);
        if (!$this->db_query = mysql_query($q, $this->db_conn))
            sprwz::error("Query error: ". mysql_error());

        return $this;
    }

    public function fetch()
    {
        $this->db_res = array();

        if ($this->db_query) {
            while ($row = mysql_fetch_assoc($this->db_query))
                $this->db_res[] = $row;
        }

        if (!empty($this->db_res))
            return $this->db_res;
        else
            return false;
    }

    public function qfetch($query)
    {
        return $this->query($query)->fetch();
    }

    public function qfetch_first($query)
    {
        $rows = $this->query($query . ' LIMIT 1')->fetch();
        if (isset($rows[0]))
            return $rows[0];
        else
            return false;
    }

    public function escape($input)
    {
        return mysql_real_escape_string(strip_tags($input));
    }
}
