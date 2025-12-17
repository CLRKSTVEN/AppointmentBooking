<?php
// Controller for appointment notifications (AJAX)
// URL: /AppointmentNotify/ajax_pending_count, /ajax_pending_list, /ajax_mark_seen

defined('BASEPATH') or exit('No direct script access allowed');

class AppointmentNotify extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Accomplishment_model');
        $this->load->library('session');
    }

    // Count of pending appointments for staff
    public function ajax_pending_count()
    {
        $staff_id = (int)$this->session->userdata('staff_id');
        if ($staff_id <= 0) {
            echo json_encode(['count' => 0]);
            return;
        }
        $count = $this->db->where('status', 'pending')->where('staff_id', $staff_id)->count_all_results('accomplishments');
        echo json_encode(['count' => $count]);
    }

    // List of pending appointments for staff
    public function ajax_pending_list()
    {
        $staff_id = (int)$this->session->userdata('staff_id');
        $limit = (int)$this->input->get('limit', true) ?: 8;
        if ($staff_id <= 0) {
            echo json_encode(['data' => []]);
            return;
        }
        $rows = $this->db->where('status', 'pending')->where('staff_id', $staff_id)
            ->order_by('created_at', 'DESC')->limit($limit)->get('accomplishments')->result();
        $data = [];
        foreach ($rows as $row) {
            $data[] = [
                'title' => $row->title,
                'category' => $row->category,
                'client' => trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')),
                'start_date' => $row->start_date,
                'created_at' => $row->created_at,
                'id' => $row->id,
            ];
        }
        echo json_encode(['data' => $data]);
    }

    // Mark all as seen (optional, for badge reset)
    public function ajax_mark_seen()
    {
        // You can implement logic to mark as seen if you add a 'seen' column
        echo json_encode(['success' => true]);
    }
}
