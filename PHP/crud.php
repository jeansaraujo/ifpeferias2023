<?php
    // CRUD - Insert(create)
    $user = "root";
    $pass = "";
    $aleatorio = random_int(1,100);
    $nome = "Usuario".$aleatorio;
    $email = "email".$aleatorio."@ifdadepressao.com";
    $senha = "123456";
    // conexao realizada
    $conexao = new PDO('mysql:host=localhost;dbname=ifpeferias2023', $user, $pass);
    // insert
    $sqlinsert = $conexao->PREPARE("INSERT INTO usuarios(nome,email,senha) VALUES (:NOME,:EMAIL,:SENHA)");
    $sqlinsert->bindParam(":NOME",$nome);
    $sqlinsert->bindParam(":EMAIL",$email);
    $sqlinsert->bindParam(":SENHA",$senha);    
    //$sqlinsert->execute();
    //seleção
    $sqlselect = $conexao->PREPARE("select * from usuarios");    
    $sqlselect->execute();
    //$resultado = $sqlselect->fetchAll(PDO::FETCH_ASSOC); // retorna como array
    $resultado = $sqlselect->fetchAll(PDO::FETCH_OBJ); // retorna como objeto
    /**
        *foreach($resultado as $res){
        *    print $res['nome']." / ".$res['email']." / ".$res['senha']."<br>";
        *}
    */
        foreach($resultado as $res){
            print $res->nome." / ".$res->email." / ".$res->senha."<br>";
        }
    //update
    /
    $sqlupdate = $conexao->PREPARE("UPDATE usuarios SET senha = :SENHA");
    $sqlupdate->bindParam(":SENHA",$senha);///
    $sqlupdate->execute();
    // Querendo usar uma informação externa ... bindParam    
    //nova seleção
    $sqlselect = $conexao->PREPARE("select * from usuarios");    
    $sqlselect->execute();
    $resultado = $sqlselect->fetchAll(PDO::FETCH_OBJ); // retorna como objeto
    foreach($resultado as $res){
        print $res->nome." / ".$res->email." / ".$res->senha."<br>";
    }

    
?>