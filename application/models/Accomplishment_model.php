<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Accomplishment_model extends CI_Model
{
    private $table = 'accomplishments';

    public function count_all(): int
    {
        return (int) $this->db->from($this->table)->count_all_results();
    }

    public function count_for_staff(int $staffId): int
    {
        if ($staffId <= 0) {
            return 0;
        }

        return (int) $this->db
            ->where('staff_id', $staffId)
            ->from($this->table)
            ->count_all_results();
    }

    public function count_public_for_staff(int $staffId): int
    {
        if ($staffId <= 0) {
            return 0;
        }

        return (int) $this->db
            ->where('staff_id', $staffId)
            ->where('is_public', 1)
            ->from($this->table)
            ->count_all_results();
    }

    public function recent(int $limit = 5): array
    {
        return $this->db
            ->select('a.*, s.first_name, s.last_name')
            ->from($this->table . ' a')
            ->join('staff s', 's.staff_id = a.staff_id', 'left')
            ->order_by('a.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }

    public function recent_for_staff(int $staffId, int $limit = 10): array
    {
        if ($staffId <= 0) {
            return [];
        }

        return $this->db
            ->where('staff_id', $staffId)
            ->order_by('created_at', 'DESC')
            ->limit($limit)
            ->get($this->table)
            ->result();
    }

    public function get_for_staff(int $staffId): array
    {
        if ($staffId <= 0) {
            return [];
        }

        return $this->db
            ->where('staff_id', $staffId)
            ->order_by('start_date', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function all_with_staff(): array
    {
        return $this->db
            ->select('a.*, s.first_name, s.last_name')
            ->from($this->table . ' a')
            ->join('staff s', 's.staff_id = a.staff_id', 'left')
            ->order_by('a.created_at', 'DESC')
            ->get()
            ->result();
    }

    public function categories_for_staff(int $staffId): array
    {
        if ($staffId <= 0) {
            return [];
        }

        $this->db->distinct();
        return $this->db
            ->select('category')
            ->where('staff_id', $staffId)
            ->where('category IS NOT NULL', null, false)
            ->order_by('category')
            ->get($this->table)
            ->result();
    }

    public function create(array $payload)
    {
        if (!isset($payload['created_at'])) {
            $payload['created_at'] = date('Y-m-d H:i:s');
        }

        $this->db->insert($this->table, $payload);
        return $this->db->insert_id();
    }

    public function find(int $id, int $staffId)
    {
        if ($id <= 0 || $staffId <= 0) {
            return null;
        }

        return $this->db
            ->where('id', $id)
            ->where('staff_id', $staffId)
            ->get($this->table)
            ->row();
    }

    public function update(int $id, int $staffId, array $payload): bool
    {
        if ($id <= 0 || $staffId <= 0) {
            return false;
        }

        return $this->db
            ->where('id', $id)
            ->where('staff_id', $staffId)
            ->update($this->table, $payload);
    }

    public function delete(int $id, int $staffId): bool
    {
        if ($id <= 0 || $staffId <= 0) {
            return false;
        }

        return $this->db
            ->where('id', $id)
            ->where('staff_id', $staffId)
            ->delete($this->table);
    }
}
