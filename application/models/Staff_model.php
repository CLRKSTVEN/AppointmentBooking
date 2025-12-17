<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Staff_model extends CI_Model
{
    private $table = 'staff';

    public function count_active(): int
    {
        return (int) $this->db->where('is_active', 1)->from($this->table)->count_all_results();
    }

    public function recent(int $limit = 5): array
    {
        return $this->db
            ->order_by('created_at', 'DESC')
            ->limit($limit)
            ->get($this->table)
            ->result();
    }
}
