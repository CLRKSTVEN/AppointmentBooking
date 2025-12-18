<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);
        $this->load->database();
    }

    /**
    * Show + handle profile photo update for the currently logged in user.
    * URL: /Page/changeDP
    */
    public function changeDP()
    {
        $this->_require_login();

        $staffId = (int) $this->session->userdata('staff_id');
        $profile = $this->_staff_profile($staffId);

        if (!$profile) {
            $this->session->set_flashdata('error', 'Profile not found. Please try again.');
            return redirect('dashboard');
        }

        // Handle upload
        if ($this->input->method() === 'post') {
            if (empty($_FILES['photo']['name'])) {
                $this->session->set_flashdata('upload_error', 'Please choose an image to upload.');
                return redirect('Page/changeDP');
            }

            $uploadPath = FCPATH . 'upload/profile/';
            if (!is_dir($uploadPath)) {
                @mkdir($uploadPath, 0755, true);
            }

            $config = [
                'upload_path'   => $uploadPath,
                'allowed_types' => 'gif|jpg|jpeg|png',
                'max_size'      => 2048,
                'file_name'     => 'staff_' . $staffId . '_' . time(),
            ];

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('photo')) {
                $this->session->set_flashdata('upload_error', $this->upload->display_errors('', ''));
                return redirect('Page/changeDP');
            }

            $uploadData = $this->upload->data();
            $filename = $uploadData['file_name'];

            $this->db->where('staff_id', $staffId)->update('staff', ['photo' => $filename]);
            $this->session->set_userdata(['photo' => $filename, 'avatar' => $filename]);
            $this->session->set_flashdata('upload_success', 'Profile photo updated.');
            return redirect('Page/changeDP');
        }

        if (empty($profile->photo)) {
            $profile->photo = 'avatar.png';
        }

        $data = [
            'profile' => $profile,
            'upload_error' => $this->session->flashdata('upload_error'),
            'upload_success' => $this->session->flashdata('upload_success'),
        ];

        $this->load->view('profile_change_dp', $data);
    }

    /**
    * Display the current user's profile (or another staff if id is supplied and allowed).
    * URL: /Page/staffprofile?id=#
    */
    public function staffprofile()
    {
        $this->_require_login();

        $staffId = (int) $this->input->get('id', TRUE);
        if ($staffId <= 0) {
            $staffId = (int) $this->session->userdata('staff_id');
        }

        if ($staffId <= 0) {
            $this->session->set_flashdata('error', 'Profile is missing. Please contact an administrator.');
            return redirect('dashboard');
        }

        $profile = $this->_staff_profile($staffId);
        if (!$profile) {
            $this->session->set_flashdata('error', 'Profile not found.');
            return redirect('dashboard');
        }

        $isOwnProfile = ($staffId === (int)$this->session->userdata('staff_id'));

        // Handle profile update (only for the owner of the profile)
        if ($isOwnProfile && $this->input->method() === 'post') {
            $this->form_validation->set_rules('first_name', 'First name', 'required|trim');
            $this->form_validation->set_rules('middle_name', 'Middle name', 'trim');
            $this->form_validation->set_rules('last_name', 'Last name', 'required|trim');
            $this->form_validation->set_rules('suffix', 'Suffix', 'trim');
            $this->form_validation->set_rules('position_title', 'Position', 'trim');
            $this->form_validation->set_rules('office_id', 'Office', 'integer');
            $this->form_validation->set_rules('short_bio', 'Short bio', 'trim');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', validation_errors('', ''));
                return redirect('Page/staffprofile');
            }

            $officeId = (int)$this->input->post('office_id', TRUE);
            $payload = [
                'first_name'     => $this->input->post('first_name', TRUE),
                'middle_name'    => $this->input->post('middle_name', TRUE),
                'last_name'      => $this->input->post('last_name', TRUE),
                'suffix'         => $this->input->post('suffix', TRUE),
                'position_title' => $this->input->post('position_title', TRUE) ?: null,
                'office_id'      => $officeId > 0 ? $officeId : null,
                'short_bio'      => $this->input->post('short_bio', TRUE),
                'updated_at'     => date('Y-m-d H:i:s'),
            ];

            $this->db->where('staff_id', $staffId)->update('staff', $payload);

            // Refresh session display name
            $fullName = trim(
                ($payload['first_name'] ?? '') . ' ' .
                (!empty($payload['middle_name']) ? substr($payload['middle_name'], 0, 1) . '. ' : '') .
                ($payload['last_name'] ?? '') . ' ' .
                ($payload['suffix'] ?? '')
            );
            if ($fullName === '') {
                $fullName = (string)$this->session->userdata('username');
            }
            $this->session->set_userdata(['full_name' => $fullName]);
            $this->session->set_flashdata('success', 'Profile updated.');
            return redirect('Page/staffprofile');
        }

        if (empty($profile->photo)) {
            $profile->photo = 'avatar.png';
        }

        $data = [
            'profile' => $profile,
            'is_own_profile' => $isOwnProfile,
            'positions' => $this->_position_options(),
            'offices' => $this->_offices(),
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
        ];

        $this->load->view('profile_view', $data);
    }

    /**
    * Alias for student profile requests. For now it uses the same view.
    */
    public function studentsprofile()
    {
        return $this->staffprofile();
    }

    /**
    * Simple lock screen: end the session and redirect to login with a message.
    */
    public function lockScreen()
    {
        $this->_require_login();
        $this->session->set_flashdata('auth_error', 'Session locked. Please sign in again.');
        $this->session->sess_destroy();
        redirect('login');
    }

    public function bdayToday()
    {
        $this->_require_login();
        $this->session->set_flashdata('error', 'Birthday celebrants page is not available yet.');
        redirect('dashboard');
    }

    public function bdayMonth()
    {
        $this->_require_login();
        $this->session->set_flashdata('error', 'Birthday celebrants page is not available yet.');
        redirect('dashboard');
    }

    private function _require_login()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    private function _staff_profile(int $staffId)
    {
        if ($staffId <= 0) {
            return null;
        }

        $this->db->select('s.*, u.username, u.role');
        $this->db->from('staff s');
        $this->db->join('users u', 'u.staff_id = s.staff_id', 'left');

        if ($this->db->table_exists('offices')) {
            $this->db->select('o.name as office_name');
            $this->db->join('offices o', 'o.id = s.office_id', 'left');
        }

        $this->db->where('s.staff_id', $staffId);
        return $this->db->get()->row();
    }

    private function _offices(): array
    {
        if (!$this->db->table_exists('offices')) {
            return [];
        }
        return $this->db->get('offices')->result();
    }

    private function _position_options(): array
    {
        return [
            'Administrator',
            'Booking Officer',
            'Receptionist',
            'Doctor',
            'Nurse',
            'Counselor',
            'Therapist',
            'Support Staff',
            'Client',
        ];
    }
}
