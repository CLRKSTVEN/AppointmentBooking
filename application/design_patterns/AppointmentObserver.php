<?php
// Observer Pattern for Appointment Notifications
interface Observer {
    public function update($message);
}

class PatientNotifier implements Observer {
    public function update($message) {
        // Notify patient (e.g., email/SMS)
        return "Patient notification: $message";
    }
}

class DoctorNotifier implements Observer {
    public function update($message) {
        // Notify doctor (e.g., email/SMS)
        return "Doctor notification: $message";
    }
}

class HospitalAppointmentSubject {
    private $observers = [];
    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }
    public function notify($message) {
        foreach ($this->observers as $observer) {
            $observer->update($message);
        }
    }
}
