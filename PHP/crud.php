<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
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
    [class='fa-sharp fa-solid fa-plus']{
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
        // conexao realizada
        $user = "root";
        $pass = "";
        $conexao = new PDO('mysql:host=localhost;dbname=ifpeferias2023', $user, $pass);
        // CRUD - Insert(create)
        if(isset($_POST['nome'])||isset($_POST['senha'])||isset($_POST['email'])){
            print "Code Inserção";
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];  
            //print $nome.$senha.$email;      
            // insert
            $sqlinsert = $conexao->PREPARE("INSERT INTO usuarios(nome,email,senha) VALUES (:NOME,:EMAIL,:SENHA)");
            $sqlinsert->bindParam(":NOME",$nome);
            $sqlinsert->bindParam(":EMAIL",$email);
            $sqlinsert->bindParam(":SENHA",$senha);    
            $resultado = $sqlinsert->execute();
            //print "<br>".$resultado;
        }
        //seleção
        $sqlselect = $conexao->PREPARE("select * from usuarios");    
        $sqlselect->execute();
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
                    <td><i class='fa-sharp fa-solid fa-plus'></i><i class='fa-sharp fa-solid fa-pen'></i><i class='fa-sharp fa-solid fa-trash'></i></td>
                </tr>
            ";               
            }
    ?>
            </tbody>
        </table>
        
    
    <?php
        //update
        $sqlupdate = $conexao->PREPARE("UPDATE usuarios SET senha = :SENHA");
        $sqlupdate->bindParam(":SENHA",$senha);///
        $sqlupdate->execute();
        // Querendo usar uma informação externa ... bindParam    
        //nova seleção
        $sqlselect = $conexao->PREPARE("select * from usuarios");    
        //$sqlselect = $conexao->PREPARE("TRUNCATE usuarios");
        $sqlselect->execute();
        $resultado = $sqlselect->fetchAll(PDO::FETCH_OBJ); // retorna como objeto
       // foreach($resultado as $res){
       //     print $res->nome." / ".$res->email." / ".$res->senha."<br>";
       // }   
    ?>
</div>
