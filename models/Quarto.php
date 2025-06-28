<?php
require_once '../config/database.php';

class Quarto {
    private $conn;
    private $table_name = "quartos";

    public $id;
    public $numero;
    public $tipo;
    public $capacidade;
    public $preco_noite;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Criar quarto
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET numero=:numero, tipo=:tipo, capacidade=:capacidade, preco_noite=:preco_noite";

        $stmt = $this->conn->prepare($query);

        $this->numero = htmlspecialchars(strip_tags($this->numero));
        $this->tipo = htmlspecialchars(strip_tags($this->tipo));
        $this->capacidade = htmlspecialchars(strip_tags($this->capacidade));
        $this->preco_noite = htmlspecialchars(strip_tags($this->preco_noite));

        $stmt->bindParam(":numero", $this->numero);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":capacidade", $this->capacidade);
        $stmt->bindParam(":preco_noite", $this->preco_noite);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Ler todos os quartos
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY numero";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Ler um quarto específico
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->numero = $row['numero'];
            $this->tipo = $row['tipo'];
            $this->capacidade = $row['capacidade'];
            $this->preco_noite = $row['preco_noite'];
            return true;
        }
        return false;
    }

    // Atualizar quarto
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET numero=:numero, tipo=:tipo, capacidade=:capacidade, preco_noite=:preco_noite 
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->numero = htmlspecialchars(strip_tags($this->numero));
        $this->tipo = htmlspecialchars(strip_tags($this->tipo));
        $this->capacidade = htmlspecialchars(strip_tags($this->capacidade));
        $this->preco_noite = htmlspecialchars(strip_tags($this->preco_noite));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":numero", $this->numero);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":capacidade", $this->capacidade);
        $stmt->bindParam(":preco_noite", $this->preco_noite);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Deletar quarto
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

