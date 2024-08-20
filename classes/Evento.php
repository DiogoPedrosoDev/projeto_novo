<?php
class Evento {
    private $conn;
    private $table_name = "eventos";

    public $id;
    public $titulo;
    public $descricao;
    public $data;
    public $horario_inicio;
    public $horario_fim;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criar() {
        $query = "INSERT INTO " . $this->table_name . " (titulo, descricao, data, horario_inicio, horario_fim) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->titulo);
        $stmt->bindParam(2, $this->descricao);
        $stmt->bindParam(3, $this->data);
        $stmt->bindParam(4, $this->horario_inicio);
        $stmt->bindParam(5, $this->horario_fim);

        return $stmt->execute();
    }

    public function atualizar() {
        $query = "UPDATE " . $this->table_name . " SET titulo = ?, descricao = ?, data = ?, horario_inicio = ?, horario_fim = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->titulo);
        $stmt->bindParam(2, $this->descricao);
        $stmt->bindParam(3, $this->data);
        $stmt->bindParam(4, $this->horario_inicio);
        $stmt->bindParam(5, $this->horario_fim);
        $stmt->bindParam(6, $this->id);

        return $stmt->execute();
    }

    public function deletar() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        return $stmt->execute();
    }

    public function listarTodos() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>
