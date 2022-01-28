<?php
class Pessoa{
    private $pdo;
    public function __construct($dbname, $host, $user, $pass){
        try{
            $this->pdo = new PDO('mysql:dbname='.$dbname.';host='.$host, $user, $pass);
            return $this->pdo;
        }catch(PDOException $e){
            echo 'Erro no banco '.$e->getMessage();
        }catch(Exception $e){
            echo 'Erro '.$e->getMessage();
        }
    }

    public function buscarDados(){
        $query = 'SELECT id, nome, telefone, email FROM pessoa ORDER BY id;';
        $cmd = $this->pdo->query($query);
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function cadastrarPessoa($nome, $telefone, $email){
        $query = 'SELECT id FROM pessoa WHERE email = :e';
        $cmd = $this->pdo->prepare($query);
        $cmd->bindValue(':e', $email);
        $cmd->execute();
        //verificando se email ja está cadastrado no intuito de impedir duplicidade de dados
        if($cmd->rowCount() > 0){
            return false;
        }else{
            $query = 'INSERT INTO pessoa(nome, telefone, email) VALUES (:n, :t, :e);';
            $cmd = $this->pdo->prepare($query);
            $cmd->bindValue(':n', $nome);
            $cmd->bindValue(':t', $telefone);
            $cmd->bindValue(':e', $email);
            $cmd->execute();
            return true;
        }
    }

    public function excluirPessoa($id){
        $query = 'DELETE FROM pessoa WHERE id = :id;';
        $cmd = $this->pdo->prepare($query);
        $cmd->bindValue(':id', $id);
        $cmd->execute();        
    }

    public function buscarPessoa($id){
        $dadosPessoa = array();
        $query = 'SELECT nome, telefone, email FROM pessoa WHERE id = :id';
        $cmd = $this->pdo->prepare($query);
        $cmd->bindValue(':id', $id);
        $cmd->execute();
        $dadosPessoa = $cmd->fetch(PDO::FETCH_ASSOC);
        return $dadosPessoa;
    }

    public function atualizarDados($id, $nome, $telefone, $email){
        $query = 'SELECT id FROM pessoa WHERE email = :e';
        $cmd = $this->pdo->prepare($query);
        $cmd->bindValue(':e', $email);
        $cmd->execute();
        $idResultado = $cmd->fetch(PDO::FETCH_ASSOC);
        
        if($idResultado['id'] != $id && $cmd->rowCount() > 0){
            return false;
        }else{
            $query = 'UPDATE pessoa SET nome = :n, telefone = :t, email = :e WHERE id = :id;';
            $cmd = $this->pdo->prepare($query);
            $cmd->bindValue(':n', $nome);
            $cmd->bindValue(':t', $telefone);
            $cmd->bindValue(':e', $email);
            $cmd->bindvalue(':id', $id);
            $cmd->execute();
            return true;
        }
    }
}