<?php
require_once '../config/database.php';

class Estadia {
    private $conn;
    private $table_name = "estadias";

    public $id;
    public $reserva_id;
    public $quarto_id;
    public $data_checkin;
    public $data_checkout;

    public function __construct($db) {
        $this->conn = $db;
    }


    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET reserva_id=:reserva_id, quarto_id=:quarto_id, data_checkin=:data_checkin, data_checkout=:data_checkout";

        $stmt = $this->conn->prepare($query);

        $this->reserva_id = htmlspecialchars(strip_tags($this->reserva_id));
        $this->quarto_id = htmlspecialchars(strip_tags($this->quarto_id));
        $this->data_checkin = htmlspecialchars(strip_tags($this->data_checkin));
        $this->data_checkout = htmlspecialchars(strip_tags($this->data_checkout));

        $stmt->bindParam(":reserva_id", $this->reserva_id);
        $stmt->bindParam(":quarto_id", $this->quarto_id);
        $stmt->bindParam(":data_checkin", $this->data_checkin);
        $stmt->bindParam(":data_checkout", $this->data_checkout);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT e.*, r.data_inicio, r.data_fim, h.nome, h.sobrenome, q.numero as quarto_numero
                  FROM " . $this->table_name . " e
                  LEFT JOIN reservas r ON e.reserva_id = r.id
                  LEFT JOIN hospedes h ON r.hospede_id = h.id
                  LEFT JOIN quartos q ON e.quarto_id = q.id
                  ORDER BY e.data_checkin DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Ler uma estadia específica
    public function readOne() {
        $query = "SELECT e.*, r.data_inicio, r.data_fim, h.nome, h.sobrenome, q.numero as quarto_numero
                  FROM " . $this->table_name . " e
                  LEFT JOIN reservas r ON e.reserva_id = r.id
                  LEFT JOIN hospedes h ON r.hospede_id = h.id
                  LEFT JOIN quartos q ON e.quarto_id = q.id
                  WHERE e.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->reserva_id = $row['reserva_id'];
            $this->quarto_id = $row['quarto_id'];
            $this->data_checkin = $row['data_checkin'];
            $this->data_checkout = $row['data_checkout'];
            return true;
        }
        return false;
    }

    // Atualizar estadia
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET reserva_id=:reserva_id, quarto_id=:quarto_id, data_checkin=:data_checkin, data_checkout=:data_checkout 
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->reserva_id = htmlspecialchars(strip_tags($this->reserva_id));
        $this->quarto_id = htmlspecialchars(strip_tags($this->quarto_id));
        $this->data_checkin = htmlspecialchars(strip_tags($this->data_checkin));
        $this->data_checkout = htmlspecialchars(strip_tags($this->data_checkout));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":reserva_id", $this->reserva_id);
        $stmt->bindParam(":quarto_id", $this->quarto_id);
        $stmt->bindParam(":data_checkin", $this->data_checkin);
        $stmt->bindParam(":data_checkout", $this->data_checkout);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Deletar estadia
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

