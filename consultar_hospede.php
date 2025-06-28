<?php

    require_once("cabecalho.php");

    function consultaHospede($id){
        require("config/conexao.php");
        try{
            $sql = "SELECT * FROM hospedes WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $hospede = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$hospede){
                die("Erro ao consultar o registro!");
            } else{
                return $hospede;
            }
        } catch(Exception $e){
            die("Erro ao consultar hóspede: " . $e->getMessage());
        }
    }

    function excluirHospede($id){
        require("config/conexao.php");
        try{
            $sql = "DELETE FROM hospedes WHERE id=?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$id])){
                header('location: hospedes.php?exclusao=true');
            } else {
                header('location: hospedes.php?exclusao=false');
            }
        } catch (Exception $e){
            die("Erro ao excluir o hóspede: ".$e->getMessage());
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $id = $_POST['id'];
        excluirHospede($id);
    } else {
        $hospede = consultaHospede($_GET['id']);
    }

?>

<h2>Consultar Hóspede</h2>

<form method="post">

    <input type="hidden" name="id" value="<?= $hospede['id'] ?>" >
                        
    <div class="mb-3">
        <p>Nome: <b> <?= $hospede['nome'] . ' ' . $hospede['sobrenome'] ?> </b> </p>
    </div>

    <div class="mb-3">
        <p>Documento: <b> <?= $hospede['documento'] ?> </b> </p>
    </div>

    <div class="mb-3">
        <p>E-mail: <b> <?= $hospede['email'] ?> </b> </p>
    </div>

    <div class="mb-3">
        <p>Telefone: <b> <?= $hospede['telefone'] ?> </b> </p>
    </div>

    <div class="mb-3">
        <p class="text-danger">Deseja excluir esse registro?</p>
        <button type="submit" class="btn btn-danger">Excluir</button>
        <a href="hospedes.php" class="btn btn-secondary">Voltar</a>
    </div>
</form>


<?php 
    require_once("rodape.php");

?>

