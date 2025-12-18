<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Messages extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);
        $this->load->database();
    }

    public function index()
    {
        $this->_require_login();
        $this->_ensure_table();

        $currentStaffId = (int) $this->session->userdata('staff_id');
        if ($currentStaffId <= 0) {
            $this->session->set_flashdata('error', 'You need a staff profile to use messaging.');
            return redirect('dashboard');
        }

        $role = strtolower((string) $this->session->userdata('role'));
        $recipients = $this->_recipient_options($role, $currentStaffId);

        $withId = (int) $this->input->get('with', true);
        if ($withId <= 0 && !empty($recipients)) {
            $withId = (int) ($recipients[0]->staff_id ?? 0);
        }

        $thread = [];
        $withProfile = null;
        if ($withId > 0) {
            $withProfile = $this->_staff_profile($withId);
            $thread = $this->_thread_between($currentStaffId, $withId);
            // Mark incoming messages as read
            $this->db->where('receiver_staff_id', $currentStaffId)
                ->where('sender_staff_id', $withId)
                ->where('is_read', 0)
                ->update('messages', ['is_read' => 1]);
        }

        $data = [
            'recipients' => $recipients,
            'current_staff_id' => $currentStaffId,
            'with_id' => $withId,
            'with_profile' => $withProfile,
            'thread' => $thread,
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
        ];

        $this->load->view('messages', $data);
    }

    public function send()
    {
        $this->_require_login();
        $this->_ensure_table();

        $currentStaffId = (int) $this->session->userdata('staff_id');
        if ($currentStaffId <= 0) {
            $this->session->set_flashdata('error', 'You need a staff profile to use messaging.');
            return redirect('messages');
        }

        $this->form_validation->set_rules('receiver_staff_id', 'Recipient', 'required|integer');
        $this->form_validation->set_rules('body', 'Message', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('', ''));
            return redirect('messages');
        }

        $receiverId = (int) $this->input->post('receiver_staff_id', TRUE);
        $body = trim((string) $this->input->post('body', TRUE));

        if ($receiverId <= 0 || $receiverId === $currentStaffId) {
            $this->session->set_flashdata('error', 'Please choose a valid recipient.');
            return redirect('messages');
        }

        $payload = $this->_store_message($currentStaffId, $receiverId, $body);

        if ($this->input->is_ajax_request()) {
            return $this->_json_success(['message' => $payload]);
        }

        $this->session->set_flashdata('success', 'Message sent.');
        return redirect('messages?with=' . $receiverId);
    }

    public function send_ajax()
    {
        $this->_require_login();
        $this->_ensure_table();

        $currentStaffId = (int) $this->session->userdata('staff_id');
        if ($currentStaffId <= 0) {
            return $this->_json_error('You need a staff profile to use messaging.', 400);
        }

        $receiverId = (int) $this->input->post('receiver_staff_id', TRUE);
        $body = trim((string) $this->input->post('body', TRUE));

        if ($receiverId <= 0 || $receiverId === $currentStaffId || $body === '') {
            return $this->_json_error('Invalid recipient or empty message.', 400);
        }

        $payload = $this->_store_message($currentStaffId, $receiverId, $body);
        return $this->_json_success(['message' => $payload]);
    }

    public function unread_count()
    {
        $this->_require_login();
        $this->_ensure_table();

        $currentStaffId = (int) $this->session->userdata('staff_id');
        if ($currentStaffId <= 0) {
            return $this->_json_success(['count' => 0]);
        }

        $count = (int) $this->db
            ->where('receiver_staff_id', $currentStaffId)
            ->where('is_read', 0)
            ->count_all_results('messages');

        return $this->_json_success(['count' => $count]);
    }

    public function thread()
    {
        $this->_require_login();
        $this->_ensure_table();

        $currentStaffId = (int) $this->session->userdata('staff_id');
        $withId = (int) $this->input->get('with', true);
        if ($currentStaffId <= 0 || $withId <= 0) {
            return $this->_json_error('Invalid thread.', 400);
        }

        $thread = $this->_thread_between($currentStaffId, $withId);
        return $this->_json_success(['thread' => $thread]);
    }

    private function _recipient_options(string $role, int $currentStaffId)
    {
        // Clients: show staff. Staff: show clients.
        $this->db
            ->select('s.staff_id, s.first_name, s.last_name, s.position_title, u.role')
            ->from('staff s')
            ->join('users u', 'u.staff_id = s.staff_id', 'left')
            ->where('s.staff_id !=', $currentStaffId)
            ->where('s.is_active', 1);

        if ($role === 'client') {
            $this->db->where('u.role', 'staff');
        } else {
            $this->db->where('u.role', 'client');
        }

        $this->db->order_by('s.first_name', 'ASC');
        return $this->db->get()->result();
    }

    private function _thread_between(int $a, int $b): array
    {
        if ($a <= 0 || $b <= 0) {
            return [];
        }

        $this->db->group_start()
            ->group_start()
            ->where('sender_staff_id', $a)
            ->where('receiver_staff_id', $b)
            ->group_end()
            ->or_group_start()
            ->where('sender_staff_id', $b)
            ->where('receiver_staff_id', $a)
            ->group_end()
            ->group_end()
            ->order_by('created_at', 'ASC');

        return $this->db->get('messages')->result();
    }

    private function _store_message(int $senderId, int $receiverId, string $body): array
    {
        $now = date('Y-m-d H:i:s');
        $payload = [
            'sender_staff_id'   => $senderId,
            'receiver_staff_id' => $receiverId,
            'body'              => $body,
            'is_read'           => 0,
            'created_at'        => $now,
        ];

        $this->db->insert('messages', $payload);
        $payload['id'] = $this->db->insert_id();
        return $payload;
    }

    private function _staff_profile(int $staffId)
    {
        if ($staffId <= 0) {
            return null;
        }

        $this->db->select('s.*, u.role');
        $this->db->from('staff s');
        $this->db->join('users u', 'u.staff_id = s.staff_id', 'left');
        $this->db->where('s.staff_id', $staffId);
        return $this->db->get()->row();
    }

    private function _ensure_table()
    {
        // Lightweight runtime migration to create messages table if missing
        $this->db->query(
            "CREATE TABLE IF NOT EXISTS messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                sender_staff_id INT NOT NULL,
                receiver_staff_id INT NOT NULL,
                body TEXT NOT NULL,
                is_read TINYINT(1) NOT NULL DEFAULT 0,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_sender (sender_staff_id),
                INDEX idx_receiver (receiver_staff_id),
                INDEX idx_pair (sender_staff_id, receiver_staff_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }

    private function _json_error(string $msg, int $code = 400)
    {
        $this->output
            ->set_status_header($code)
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => false, 'error' => $msg]));
    }

    private function _json_success(array $data = [])
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array_merge(['success' => true], $data)));
    }

    private function _require_login()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }
}
