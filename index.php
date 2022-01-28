<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>CRUD PDO EASY</title>

    <style>
        .grid{
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 30px;
           margin-top: 15rem;
        }
    </style>
</head>
<body>
    
    <?php
        require_once __DIR__.'/Pessoa.php';
        $pessoa = new Pessoa('crudpdo', 'localhost', 'root', '');

        //Excluindo pessoa do registro
        if(isset($_GET['id'])){
            $id = addslashes($_GET['id']);
            $pessoa->excluirPessoa($id); 
            header('Location: index.php');
        }
        //End Excluindo pessoa do registro

        //Realizando busca de registro unico
        if(isset($_GET['id_up'])){
            $id = addslashes($_GET['id_up']);
            $p = $pessoa->buscarPessoa($id);
        }
        //End Realizando busca de registro unico
    ?>

    <div class="container grid" >
        <div class="card">
            <div class="card-body">  
                <?php
                    if(isset($_POST['btn-submit'])){  //Verificando se o btn de cadastrar foi clicado               
                        $nome = addslashes($_POST['nome']);
                        $telefone = addslashes($_POST['telefone']);
                        $email = addslashes($_POST['email']);
                        
                        if(!empty($nome) && !empty($telefone) && !empty($email)){
                            $status = $pessoa->cadastrarPessoa($nome, $telefone, $email);
                            if(!$status){
                                ?>
                                <div class="alert alert-danger" role="alert">
                                    Email já cadastrado. Tente Novamente
                                </div>
                                <?php
                                
                            }else{
                                header('Location: index.php');
                            }
                        }else{

                            ?>
                            <div class="alert alert-danger" role="alert">
                                 Preencha todos os campos!
                            </div>
                        <?php                          
                        }
                    }else if(isset($_POST['update-submit'])){ // verificando se o btn de atualizar foi clicado
                        $id = $_GET['id_up'];
                        $nome = addslashes($_POST['nome']);
                        $telefone = addslashes($_POST['telefone']);
                        $email = addslashes($_POST['email']);

                        if(!empty($nome) && !empty($telefone) && !empty($email)){    //Verificando se as variáveis possuem valores  
                            //A funcão "atualizarDados" retorna um valor booleano que é capturado pela variavel $status
                            //A verificação para esse valor está na classe "Pessoa"                      
                            $status = $pessoa->atualizarDados($id, $nome, $telefone, $email);
                            if(!$status){
                                ?>
                                 <div class="alert alert-danger" role="alert">
                                  E-mail existente! Tente novamente.
                                </div>
                                <?php
                             
                            }else{
                                header('Location: index.php');
                            }
                        }                        
                    }               
                    ?>              
                <form method="POST">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" class="form-control mt-2" value="<?php if(isset($_GET['id_up'])){ echo $p['nome']; } ?>"> 
                    <label for="nome">Telefone</label>
                    <input type="text" name="telefone" class="form-control mt-2" value="<?php if(isset($_GET['id_up'])){ echo $p['telefone']; } ?>"> 
                    <label for="nome">email</label>
                    <input type="text" name="email" class="form-control mt-2" value="<?php if(isset($_GET['id_up'])){ echo $p['email']; } ?>"> 
                    <button type="submit" class="btn btn-primary mt-2" name="<?php if(!isset($_GET['id_up'])){echo 'btn-submit';}else{echo 'update-submit';}?>" ><?php if(isset($_GET['id_up'])){echo 'Atualizar';}else{ echo 'Cadastrar';} ?></button>            
                </form>
            </div>
        </div>
        <table class="table ">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">NOME</th>
                    <th scope="col">TELEFONE</th>
                    <th scope="col">EMAIL</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $dados = $pessoa->buscarDados();
                    //Verificando se existem dados a serem exibidos
                    if(count($dados) > 0){
                        for($i = 0; $i < count($dados); $i++){
                            ?>     
                        <tr>
                            <?php                
                            foreach($dados[$i] as $key => $value){
                                ?>
                                    <td><?php echo $value ?></td>                              
                                    <?php
                            }
                            ?>
                        <td><a href="index.php?id=<?php echo $dados[$i]['id'];?>">Excluir</a>   <a href="index.php?id_up=<?php echo $dados[$i]['id'];?>">Editar</a></td>
                        </tr>
                        
                        <?php
                        }
                    }else{ // Caso não haja dados, será exibida essa msg
                    ?>
                        <td colspan="4" style="text-align: center;"> Ainda não há registros ... </td>
                    <?php
                    }
                    ?>     
            </tbody>
        </table>
    </div>
</body>
</html>