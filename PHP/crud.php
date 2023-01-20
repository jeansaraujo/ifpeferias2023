<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
<?php
    include_once("sql/conexao.php");
    include_once("sql/delete.php");
    define("PAGINAS",4);
    $sqltodos = $conexao->PREPARE("SELECT count(*) as qte FROM usuarios"); 
    $sqltodos->execute();
    $sqltodosarray = $sqltodos->fetchAll(PDO::FETCH_ASSOC);
    $registros = $sqltodosarray[0]["qte"];
    $valorOFFSET = "0";    
    //print INTVAL($registros/PAGINAS);
?>
<style>
    table{
        width:100%;
        border:1px solid black;
        text-align:center;
    }
    thead{
        font-weight:bold;
    }
    #lesquerdo{
        width:50%;
        height:100vh;
        float:left;
        background:lightgray;
    }
    #ldireito{
        width:50%;
        height:100vh;
        float:right;
    }
    [type]{
        width:100%;        
    }
    label{
        display:block;       
        border-bottom: 2px darkgray dashed;
        color:red;
        margin-bottom:2px;
    }
    [class='fa-sharp fa-solid fa-eye']{
        color:blue;
        font-size:15px;
    }
    [class='fa-sharp fa-solid fa-pen']{
        color:orange;
        font-size:15px;
    }
    [class='fa-sharp fa-solid fa-trash']{
        color:red;
        font-size:15px;
    }
</style>
<div id="lesquerdo">
    <form method="POST" enctype="multipart/form-data">
        <label>Nome</label>
        <input type="text" name="nome" id="nome"> 
        <label>Email</label>
        <input type="text" name="email" id="email">
        <label>Senha</label>
        <input type="password" name="senha" id="senha">
        <label>Foto</label>
        <input type="file" name="foto" id="foto">
        <button type="submit">
            Cadastrar
        </button>
    </form>
</div>
<div id="ldireito">
    <?php
        // CRUD - Insert(create)
        if(isset($_POST['senha'])||isset($_POST['email'])){
            print "Code Inserção";
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            $foto = $_FILES['foto'];
            // insert
            $nomefoto = time().$foto["name"];
            $sqlinsert = $conexao->PREPARE("INSERT INTO usuarios(nome,email,senha,foto) VALUES (:NOME,:EMAIL,:SENHA,:FOTO)");
            $sqlinsert->bindParam(":NOME",$nome);
            $sqlinsert->bindParam(":EMAIL",$email);
            $sqlinsert->bindParam(":SENHA",$senha);    
            $sqlinsert->bindParam(":FOTO",$nomefoto);
            if(!is_dir("img")){
                mkdir("img");
            }      
            $destino = "img".DIRECTORY_SEPARATOR.$nomefoto;
            move_uploaded_file($foto["tmp_name"],$destino);
            $resultado = $sqlinsert->execute();
            //print "<br>".$resultado;
        }
        //seleção
        $valorLIMIT = PAGINAS;
        if(isset($_GET['paginacao'])){
            $paginacao = $_GET['paginacao'];
            $valorOFFSET = $paginacao * PAGINAS;
            $sql = "select * from usuarios LIMIT ".$valorLIMIT." OFFSET ".$valorOFFSET;
            $sqlselect = $conexao->PREPARE($sql); 
            $sqlselect->execute();
        }
        else{
            $sql = "select * from usuarios LIMIT ".$valorLIMIT." OFFSET ".$valorOFFSET;
            $sqlselect = $conexao->PREPARE($sql); 
            $sqlselect->execute();
        }                
    ?>
        <table>
            <thead>
                <tr>
                    <td>Id</td>
                    <td>Nome</td>
                    <td>Email</td>
                    <td>Ações</td>
                </tr>
            </thead>  
            <tbody>  
                   
    <?php    
        //$resultado = $sqlselect->fetchAll(PDO::FETCH_ASSOC); // retorna como array
        $resultado = $sqlselect->fetchAll(PDO::FETCH_OBJ); // retorna como objeto        
            foreach($resultado as $res){
                print"
                <tr>
                    <td>".$res->id."</td>
                    <td>".$res->nome."</td>
                    <td>".$res->email."</td>                  
                    <td>
                        <a href='#modalPessoa".$res->id."' data-bs-toggle='modal' data-bs-target='#modalPessoa".$res->id."'".">
                            <i class='fa-sharp fa-solid fa-eye'></i>
                        </a> 
                        <a href='alterar.php?id=".$res->id."'><i class='fa-sharp fa-solid fa-pen'></i></a>
                        <a href='?excluir=".$res->id."'><i class='fa-sharp fa-solid fa-trash'></i></a>
                    </td>
                </tr>
            ";               
            }
    ?>
        </tbody>            
    </table>
    <div class="d-flex justify-content-center">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php 
                    if($valorOFFSET != 0){
                        print "<li class='page-item'><a class='page-link' href='?paginacao=0'>Primeiro</a></li>";
                    }
                    $fim = intval($registros/PAGINAS);
                    $inicio = 0;
                    $exato = ($registros/PAGINAS - intval($registros/PAGINAS))==0?true:false;                   
                    if($exato == true){
                        for($inicio;$inicio<$fim;$inicio++){
                            if(!isset($paginacao)){
                                if($inicio == 0){
                                    print "
                                        <li class='page-item'><a class='page-link bg-primary text-white' href='?paginacao=".$inicio."'>".($inicio+1)."</a></li>
                                    ";
                                }
                                else{
                                    print "
                                        <li class='page-item'><a class='page-link' href='?paginacao=".$inicio."'>".($inicio+1)."</a></li>
                                    ";
                                }
                            }
                            else{
                                if($paginacao == $inicio){
                                    print "
                                        <li class='page-item'><a class='page-link bg-primary text-white' href='?paginacao=".$inicio."'>".($inicio+1)."</a></li>
                                    ";
                                }
                                else{
                                    print "
                                        <li class='page-item'><a class='page-link' href='?paginacao=".$inicio."'>".($inicio+1)."</a></li>
                                    ";
                                }
                            }
                        }
                    }
                    else{
                        for($inicio;$inicio<=$fim;$inicio++){
                            if(!isset($paginacao)){
                                if($inicio == 0){
                                    print "
                                        <li class='page-item'><a class='page-link bg-primary text-white' href='?paginacao=".$inicio."'>".($inicio+1)."</a></li>
                                    ";
                                }
                                else{
                                    print "
                                        <li class='page-item'><a class='page-link' href='?paginacao=".$inicio."'>".($inicio+1)."</a></li>
                                    ";
                                }
                            }
                            else{
                                if($paginacao == $inicio){
                                    print "
                                        <li class='page-item'><a class='page-link bg-primary text-white' href='?paginacao=".$inicio."'>".($inicio+1)."</a></li>
                                    ";
                                }
                                else{
                                    print "
                                        <li class='page-item'><a class='page-link' href='?paginacao=".$inicio."'>".($inicio+1)."</a></li>
                                    ";
                                }
                            }
                        }
                    }
                    //print $valorOFFSET. "/ ".$fim;
                    if(isset($_GET["paginacao"])){
                        $paginacao = $_GET["paginacao"];
                            if($exato == true){
                                if($paginacao != ($fim-1)){
                                    print "
                                        <li class='page-item'><a class='page-link' href='?paginacao=".($fim-1)."'>Último</a></li>
                                    ";
                                ;}
                            }
                            else{
                                if($paginacao != $fim){
                                    print "
                                        <li class='page-item'><a class='page-link' href='?paginacao=".$fim."'>Último</a></li>
                                    ";
                                }
                            }
                    }
                    else{
                        if($exato == true){
                            print "
                                <li class='page-item'><a class='page-link' href='?paginacao=".($fim-1)."'>Último</a></li>
                            ";
                        }
                        else{
                            
                            print "
                                <li class='page-item'><a class='page-link' href='?paginacao=".$fim."'>Último</a></li>
                            ";
                            
                        }
                    }
                ?>
            </ul>
        </nav>
    </div>
</div>

<!-- Button trigger modal 
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#aulaphp">
  Launch static backdrop modal
</button>
-->

<?php
    //var_dump($resultado);
    foreach($resultado as $res){
    print "
        <div class='modal fade' id='modalPessoa".$res->id."'"."data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='staticBackdropLabel' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                <div class='modal-header'>
                    <h1 class='modal-title fs-5' id='staticBackdropLabel'>Mais Informações de ".$res->nome."</h1>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    <img class='img-fluid w-50' src='img/".$res->foto."'>
                    <p>".$res->email."</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                </div>
                </div>
            </div>
            </div>
    ";
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>