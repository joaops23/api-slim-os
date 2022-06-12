<?php
namespace Model;

class OS extends \PDO{
    private $pdo;

    public function __construct(){
        $dados = json_decode(file_get_contents('../env.json'),true);
        $this->pdo = new \PDO("$dados[driver]:dbname=$dados[db];host=$dados[host]", "$dados[user]", "$dados[pass]");
    }

    public function getPdo(){
        return $this->pdo;
    }

    public function inserir($dados){
        $dados = json_decode($dados,true);
        // Verifica se foi enviado o campo "ordem", caso não gera um erro;
        $ordem = isset($dados['ordem']) ? trim($dados['ordem']) : throw new \exception("Ordem de serviço não encontrada, {$dados}");

        
        $valida = $this->pdo->prepare("select id from ordens where ordem = '{$ordem}'");
        $valida->execute();
        $retorno = $valida->fetch(\PDO::FETCH_ASSOC);
        
        
        if(!$retorno){
            //Caso não tenha encontrado uma ordem, o sistema irá incluir
            $this->pdo->beginTransaction();
            
            $stmt = $this->pdo->prepare("INSERT INTO ordens (ordem, descricao, status, data_inicio, data_entrega) VALUES(:ordem, :descricao, :status, :data_inicio, :data_entrega)");
            
            $stmt->bindParam(":ordem", $dados['ordem'], \PDO::PARAM_STR);
            $stmt->bindParam(":descricao", $dados['descricao'], \PDO::PARAM_STR);
            $stmt->bindParam(":status", $dados['status'], \PDO::PARAM_STR);
            $stmt->bindParam(":data_inicio", $dados['data_inicio'], \PDO::PARAM_STR);
            $stmt->bindParam(":data_entrega", $dados['data_fim'], \PDO::PARAM_STR);

            if($stmt->execute()){
                $id = $this->pdo->lastInsertId();
                $message = "success";
                $this->pdo->commit();
            }
            else{
                $message = "faile";
                $this->pdo->rollBack();
                throw new \Exception("Não cadastrado, erro interno");
            }
        } else {
            $id = 0;
            $message = "duplicate";
        }
        $return = ["message" => $message, "id" => $id];
        return json_encode($return);
    }

    public function alterar($table, $dados, $id){
        $params = "";

        $this->pdo->beginTransaction();

        foreach($dados as $dado => $val){
            $params .= " {$dado} = '$val'";
        }
        $stmt = $this->pdo->prepare("UPDATE {$table} SET {$params} WHERE id = :id");

        $stmt->bindParam(":id", $id, \PDO::PARAM_STR);
        
        if($stmt->execute()){
            $message = "success";
            $this->pdo->commit();
        } else{
            $this->pdo->rollback();
            throw new \Exception("Não cadastrado, Usuário não encontrado!");
        }

        $return = ['message' => $message];
        return json_encode($return);
    }

    public function excluir($table, $id){
        $this->pdo->beginTransaction();
        
        $stmt = $this->pdo->prepare("DELETE FROM {$table} WHERE id = :id ");
        
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);

        if($stmt->execute()){            
            $message = "success";
            $this->pdo->commit();
        }
        else{
            $message = "faile";
            $this->pdo->rollBack();
        }
         
        $return = ["message" => $message];
        return json_encode($return);
    }

    static function select($table, $cond = array(), $campos = "*"){
        
        $where = "";
        foreach($cond as $condicao => $val){
            $where .= " and ";
            $where .= $condicao ." ". addslashes($val);
        }
        
        $class = new OS();
        $pdo = $class->getPdo();
        
        $stmt = $pdo->query("select {$campos} FROM {$table} WHERE 1 {$where}");

        $return = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if(count($return) > 0 ){
            $message = "success";
        }
        else{
            $message = 'faile';
            $return = ['message' => $message];
        }

        return json_encode($return);
    }
}


?>