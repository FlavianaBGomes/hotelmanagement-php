<?php
require_once '../config/database.php';

class Reserva {
    private $conn;
    private $table_name = "reservas";

    public $id;
    public $hospede_id;
    public $data_inicio;
    public $data_fim;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Criar reserva
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET hospede_id=:hospede_id, data_inicio=:data_inicio, data_fim=:data_fim, status=:status";

        $stmt = $this->conn->prepare($query);

        $this->hospede_id = htmlspecialchars(strip_tags($this->hospede_id));
        $this->data_inicio = htmlspecialchars(strip_tags($this->data_inicio));
        $this->data_fim = htmlspecialchars(strip_tags($this->data_fim));
        $this->status = htmlspecialchars(strip_tags($this->status));

        $stmt->bindParam(":hospede_id", $this->hospede_id);
        $stmt->bindParam(":data_inicio", $this->data_inicio);
        $stmt->bindParam(":data_fim", $this->data_fim);
        $stmt->bindParam(":status", $this->status);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Ler todas as reservas
    public function read() {
        $query = "SELECT r.*, h.nome, h.sobrenome 
                  FROM " . $this->table_name . " r
                  LEFT JOIN hospedes h ON r.hospede_id = h.id
                  ORDER BY r.data_inicio DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Ler uma reserva específica
    public function readOne() {
        $query = "SELECT r.*, h.nome, h.sobrenome 
                  FROM " . $this->table_name . " r
                  LEFT JOIN hospedes h ON r.hospede_id = h.id
                  WHERE r.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->hospede_id = $row['hospede_id'];
            $this->data_inicio = $row['data_inicio'];
            $this->data_fim = $row['data_fim'];
            $this->status = $row['status'];
            return true;
        }
        return false;
    }

    // Atualizar reserva
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET hospede_id=:hospede_id, data_inicio=:data_inicio, data_fim=:data_fim, status=:status 
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->hospede_id = htmlspecialchars(strip_tags($this->hospede_id));
        $this->data_inicio = htmlspecialchars(strip_tags($this->data_inicio));
        $this->data_fim = htmlspecialchars(strip_tags($this->data_fim));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":hospede_id", $this->hospede_id);
        $stmt->bindParam(":data_inicio", $this->data_inicio);
        $stmt->bindParam(":data_fim", $this->data_fim);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Deletar reserva
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>

