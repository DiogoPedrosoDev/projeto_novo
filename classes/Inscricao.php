<?php
class Inscricao {
    private $conn;
    private $table_name = "inscricoes";

    public $id;
    public $user_id;
    public $curso_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criar() {
        // Verificar se já há inscrição no mesmo horário
        $query = "SELECT * FROM " . $this->table_name . " i 
                  JOIN cursos c ON i.curso_id = c.id 
                  WHERE i.user_id = ? AND c.data = (SELECT data FROM cursos WHERE id = ?) 
                  AND ((c.horario_inicio BETWEEN (SELECT horario_inicio FROM cursos WHERE id = ?) AND (SELECT horario_fim FROM cursos WHERE id = ?)) 
                  OR (c.horario_fim BETWEEN (SELECT horario_inicio FROM cursos WHERE id = ?) AND (SELECT horario_fim FROM cursos WHERE id = ?)))";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->curso_id);
        $stmt->bindParam(3, $this->curso_id);
        $stmt->bindParam(4, $this->curso_id);
        $stmt->bindParam(5, $this->curso_id);
        $stmt->bindParam(6, $this->curso_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return false; // Conflito de horário detectado
        } else {
            // Nenhum conflito, proceder com a inscrição
            $query = "INSERT INTO " . $this->table_name . " (user_id, curso_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->user_id);
            $stmt->bindParam(2, $this->curso_id);

            return $stmt->execute();
        }
    }

    public function listarPorUsuario($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorCurso($curso_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE curso_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $curso_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
