<?php
class Database {
    private $host = 'localhost';
    private $db = 'carelink_db';
    private $user = 'root';
    private $pass = '';
    protected $conn;

    public function connect() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}

class Patient extends Database {
    public function getAllPatients() {
        $stmt = $this->connect()->prepare("SELECT * FROM patients");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class Appointment extends Database {
    // Create Appointment (Prepared Statement)
    public function create($patient_id, $doctor_id, $date, $status) {
        $query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, status) VALUES (:p_id, :d_id, :date, :status)";
        $stmt = $this->connect()->prepare($query);
        return $stmt->execute([
            ':p_id' => $patient_id, 
            ':d_id' => $doctor_id, 
            ':date' => $date, 
            ':status' => $status
        ]);
    }

    // Read with Search and Filter
    public function getAppointments($search = '', $status = '') {
        $query = "SELECT a.appointment_id, p.first_name, p.last_name, d.full_name as doctor, a.appointment_date, a.status 
                  FROM appointments a 
                  JOIN patients p ON a.patient_id = p.patient_id 
                  JOIN doctors d ON a.doctor_id = d.doctor_id 
                  WHERE (p.first_name LIKE :search OR p.last_name LIKE :search)";
        
        if (!empty($status)) {
            $query .= " AND a.status = :status";
        }
        $query .= " ORDER BY a.appointment_date ASC";

        $stmt = $this->connect()->prepare($query);
        $params = [':search' => "%$search%"];
        if (!empty($status)) {
            $params[':status'] = $status;
        }
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update Appointment Status
    public function updateStatus($id, $status) {
        $stmt = $this->connect()->prepare("UPDATE appointments SET status = :status WHERE appointment_id = :id");
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    // Delete Appointment
    public function delete($id) {
        $stmt = $this->connect()->prepare("DELETE FROM appointments WHERE appointment_id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Dashboard Statistics
    public function getDashboardStats() {
        $stmt = $this->connect()->prepare("SELECT COUNT(*) as total, SUM(IF(status='Pending',1,0)) as pending FROM appointments");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>