<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nome;
    public $email;
    public $matricula;
    public $senha;
    public $pontos;
    public $is_admin;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criar() {
        $query = "INSERT INTO " . $this->table_name . " (nome, email, matricula, senha) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->nome);
        $stmt->bindParam(2, $this->email);
        $stmt->bindParam(3, $this->matricula);
        $this->senha = password_hash($this->senha, PASSWORD_DEFAULT);
        $stmt->bindParam(4, $this->senha);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function autenticar() {
        $query = "SELECT id, nome, senha, is_admin FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && password_verify($this->senha, $row['senha'])) {
            $this->id = $row['id'];
            $this->nome = $row['nome'];
            $this->is_admin = $row['is_admin'];  // Certifique-se de que está obtendo corretamente do banco de dados
            return true;
        } else {
            return false;
        }
    }

    public function atualizarPontos($pontosAdicionais) {
        $query = "UPDATE " . $this->table_name . " SET pontos = pontos + ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $pontosAdicionais);
        $stmt->bindParam(2, $this->id);

        return $stmt->execute();
    }

    public function obterRanking() {
        $query = "SELECT nome, pontos FROM " . $this->table_name . " ORDER BY pontos DESC, nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizar() {
        $query = "UPDATE " . $this->table_name . " SET nome = :nome, email = :email, senha = :senha WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':senha', $this->senha);
        $stmt->bindParam(':id', $this->id);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
}
?>
