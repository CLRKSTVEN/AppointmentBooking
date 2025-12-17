<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Office_model extends CI_Model
{
    private $table = 'offices';

    public function count_all(): int
    {
        return (int) $this->db->from($this->table)->count_all_results();
    }
}
