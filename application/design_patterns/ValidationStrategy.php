<?php
// Strategy Pattern for Appointment Validation
interface ValidationStrategy {
    public function validate($data);
}

class PatientValidation implements ValidationStrategy {
    public function validate($data) {
        // Example: validate patient appointment data
        return isset($data['patient_id']) && !empty($data['patient_id']);
    }
}

class DoctorValidation implements ValidationStrategy {
    public function validate($data) {
        // Example: validate doctor appointment data
        return isset($data['doctor_id']) && !empty($data['doctor_id']);
    }
}

class AppointmentValidator {
    private $strategy;
    public function __construct(ValidationStrategy $strategy) {
        $this->strategy = $strategy;
    }
    public function validate($data) {
        return $this->strategy->validate($data);
    }
}
