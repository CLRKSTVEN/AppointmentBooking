<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login_model extends CI_Model
{
  /**
   * Authenticate user by username & password.
   * Expects table: users (id, staff_id, username, password_hash, role, status)
   * and staff: (staff_id, first_name, last_name, position_title, office_id, address_id, ...).
   */
  public function authenticate($username, $password)
  {
    if (empty($username) || empty($password)) {
      return null;
    }

    $this->db->select('u.*, s.first_name, s.middle_name, s.last_name, s.position_title');
    $this->db->from('users u');
    $this->db->join('staff s', 's.staff_id = u.staff_id', 'left');
    $this->db->where('u.username', $username);
    $this->db->where('u.status', 1); // only active accounts

    $query = $this->db->get();
    $user  = $query->row();

    if (!$user) {
      return null;
    }

    // Check password (hashed)
    if (!password_verify($password, $user->password_hash)) {
      return null;
    }

    return $user;
  }

  public function create_user($staff_id, $username, $plain_password, $role = 'staff')
  {
    $data = [
      'staff_id'      => (int) $staff_id,
      'username'      => $username,
      'password_hash' => password_hash($plain_password, PASSWORD_DEFAULT),
      'role'          => $role,
      'status'        => 1,
      'created_at'    => date('Y-m-d H:i:s'),
    ];

    return $this->db->insert('users', $data);
  }
}
