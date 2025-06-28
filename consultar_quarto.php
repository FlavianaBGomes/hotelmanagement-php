<?php

    require_once("cabecalho.php");

    function consultaQuarto($id){
        require("config/conexao.php");
        try{
            $sql = "SELECT * FROM quartos WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $quarto = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$quarto){
                die("Erro ao consultar o registro!");
            } else{
                return $quarto;
            }
        } catch(Exception $e){
            die("Erro ao consultar quarto: " . $e->getMessage());
        }
    }

    function excluirQuarto($id){
        require("config/conexao.php");
        try{
            $sql = "DELETE FROM quartos WHERE id=?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$id])){
                header('location: quartos.php?exclusao=true');
            } else {
                header('location: quartos.php?exclusao=false');
            }
        } catch (Exception $e){
            die("Erro ao excluir o quarto: ".$e->getMessage());
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $id = $_POST['id'];
        excluirQuarto($id);
    } else {
        $quarto = consultaQuarto($_GET['id']);
    }

?>

<h2>Consultar Quarto</h2>

<form method="post">

    <input type="hidden" name="id" value="<?= $quarto['id'] ?>" >
                        
    <div class="mb-3">
        <p>Número: <b> <?= $quarto['numero'] ?> </b> </p>
    </div>

    <div class="mb-3">
        <p>Tipo: <b> <?= $quarto['tipo'] ?> </b> </p>
    </div>

    <div class="mb-3">
        <p>Capacidade: <b> <?= $quarto['capacidade'] ?> pessoas </b> </p>
    </div>

    <div class="mb-3">
        <p>Preço por Noite: <b> R$ <?= number_format($quarto['preco_noite'], 2, ',', '.') ?> </b> </p>
    </div>

    <div class="mb-3">
        <p class="text-danger">Deseja excluir esse registro?</p>
        <button type="submit" class="btn btn-danger">Excluir</button>
        <a href="quartos.php" class="btn btn-secondary">Voltar</a>
    </div>
</form>


<?php 
    require_once("rodape.php");

?>

