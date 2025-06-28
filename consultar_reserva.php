<?php

    require_once("cabecalho.php");

    function consultaReserva($id){
        require("config/conexao.php");
        try{
            $sql = "SELECT r.*, h.nome, h.sobrenome 
                    FROM reservas r
                    LEFT JOIN hospedes h ON r.hospede_id = h.id
                    WHERE r.id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $reserva = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$reserva){
                die("Erro ao consultar o registro!");
            } else{
                return $reserva;
            }
        } catch(Exception $e){
            die("Erro ao consultar reserva: " . $e->getMessage());
        }
    }

    function excluirReserva($id){
        require("config/conexao.php");
        try{
            $sql = "DELETE FROM reservas WHERE id=?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$id])){
                header('location: reservas.php?exclusao=true');
            } else {
                header('location: reservas.php?exclusao=false');
            }
        } catch (Exception $e){
            die("Erro ao excluir a reserva: ".$e->getMessage());
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $id = $_POST['id'];
        excluirReserva($id);
    } else {
        $reserva = consultaReserva($_GET['id']);
    }

?>

<h2>Consultar Reserva</h2>

<form method="post">

    <input type="hidden" name="id" value="<?= $reserva['id'] ?>" >
                        
    <div class="mb-3">
        <p>Hóspede: <b> <?= $reserva['nome'] . ' ' . $reserva['sobrenome'] ?> </b> </p>
    </div>

    <div class="mb-3">
        <p>Data de Início: <b> <?= date('d/m/Y', strtotime($reserva['data_inicio'])) ?> </b> </p>
    </div>

    <div class="mb-3">
        <p>Data de Fim: <b> <?= date('d/m/Y', strtotime($reserva['data_fim'])) ?> </b> </p>
    </div>

    <div class="mb-3">
        <p>Status: <b> <?= ucfirst($reserva['status']) ?> </b> </p>
    </div>

    <div class="mb-3">
        <p class="text-danger">Deseja excluir esse registro?</p>
        <button type="submit" class="btn btn-danger">Excluir</button>
        <a href="reservas.php" class="btn btn-secondary">Voltar</a>
    </div>
</form>


<?php 
    require_once("rodape.php");

?>

