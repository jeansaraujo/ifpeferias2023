<?php
    if(isset($_GET['excluir'])){
        $excluir = $_GET['excluir'];
        $sqldelete = $conexao->PREPARE("DELETE FROM usuarios WHERE id=:EXCLUIR");
        $sqldelete->bindParam(":EXCLUIR",$excluir);
        $res_del=$sqldelete->execute();
        if($res_del==1){
            print"
                <script>
                    alert('Exclusão com sucesso');
                </script>";
        }
    }
?>