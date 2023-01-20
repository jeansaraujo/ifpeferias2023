<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
<style>
    div{
        width:50%;
        margin:auto;
    }
    input{
        display:block;
    }
</style>

<?php
    include("sql/conexao.php");
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $dadoAlterar = $conexao->PREPARE("SELECT * FROM usuarios WHERE id =:ALTERAR");
        $dadoAlterar->bindParam(":ALTERAR",$id);
        $dadoAlterar->execute();
        $old = $dadoAlterar->fetchAll(PDO::FETCH_ASSOC);
        //var_dump($old);
    }

?>
<div>
    <form method="POST" enctype="multipart/form-data">
        <label>Nome</label>
        <input  type="hidden" name="id" id="id" value="<?php print $old[0]['id']?>"> 
        <input  class = "form-control" type="text" name="nome" id="nome" value="<?php print $old[0]['nome']?>"> 
        <label>Email</label>
        <input  class = "form-control" type="text" name="email" id="email" value="<?php print $old[0]['email']?>">
        <label>Senha</label>
        <input  class = "form-control" type="password" name="senha" id="senha" value="<?php print $old[0]['senha']?>">
        <label>Foto</label>
        <input  class = "form-control" type="file" name="foto" id="foto" src="img/<?php print $old[0]['foto']?>">
        <button class="btn btn-outline-primary mt-2" type="submit">
            Alterar
        </button>
    </form>
</div>


<?php
    if(isset($_POST["id"])&&isset($_POST["nome"])){
        $id = $_POST["id"];
        $nome = $_POST["nome"];
        $senha= $_POST["senha"];
        $email = $_POST["email"];
        $foto = $_FILES["foto"];
        $fotonome = time().$foto["name"];
        $destino = "img".DIRECTORY_SEPARATOR.$fotonome;
        move_uploaded_file($foto["tmp_name"],$destino);
        //update
        $sqlupdate = $conexao->PREPARE("UPDATE usuarios SET nome=:NOME,senha=:SENHA, email=:EMAIL, foto=:FOTO WHERE id =:ID");
        $sqlupdate->bindParam(":ID",$id);
        $sqlupdate->bindParam(":NOME",$nome);
        $sqlupdate->bindParam(":SENHA",$senha);
        $sqlupdate->bindParam(":EMAIL",$email);
        $sqlupdate->bindParam(":FOTO",$fotonome);
        $sqlupdate->execute();
        header("Location: http://localhost/crud.php");
    }
?>