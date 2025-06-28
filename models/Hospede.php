<?php
require_once '../config/database.php';

class Hospede {
    private $conn;
    private $table_name = "hospedes";

    public $id;
    public $nome;
    public $sobrenome;
    public $documento;
    public $email;
    public $telefone;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Criar hóspede
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nome=:nome, sobrenome=:sobrenome, documento=:documento, email=:email, telefone=:telefone";

        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->sobrenome = htmlspecialchars(strip_tags($this->sobrenome));
        $this->documento = htmlspecialchars(strip_tags($this->documento));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefone = htmlspecialchars(strip_tags($this->telefone));

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":sobrenome", $this->sobrenome);
        $stmt->bindParam(":documento", $this->documento);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefone", $this->telefone);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Ler todos os hóspedes
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nome, sobrenome";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Ler um hóspede específico
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->nome = $row['nome'];
            $this->sobrenome = $row['sobrenome'];
            $this->documento = $row['documento'];
            $this->email = $row['email'];
            $this->telefone = $row['telefone'];
            return true;
        }
        return false;
    }

    // Atualizar hóspede
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nome=:nome, sobrenome=:sobrenome, documento=:documento, email=:email, telefone=:telefone 
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->sobrenome = htmlspecialchars(strip_tags($this->sobrenome));
        $this->documento = htmlspecialchars(strip_tags($this->documento));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefone = htmlspecialchars(strip_tags($this->telefone));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":sobrenome", $this->sobrenome);
        $stmt->bindParam(":documento", $this->documento);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefone", $this->telefone);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Deletar hóspede
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

