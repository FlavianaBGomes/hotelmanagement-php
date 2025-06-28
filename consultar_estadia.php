<?php

    require_once("cabecalho.php");

    function consultaEstadia($id){
        require("config/conexao.php");
        try{
            $sql = "SELECT e.*, r.data_inicio, r.data_fim, h.nome, h.sobrenome, q.numero as quarto_numero
                    FROM estadias e
                    LEFT JOIN reservas r ON e.reserva_id = r.id
                    LEFT JOIN hospedes h ON r.hospede_id = h.id
                    LEFT JOIN quartos q ON e.quarto_id = q.id
                    WHERE e.id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $estadia = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$estadia){
                die("Erro ao consultar o registro!");
            } else{
                return $estadia;
            }
        } catch(Exception $e){
            die("Erro ao consultar estadia: " . $e->getMessage());
        }
    }

    function excluirEstadia($id){
        require("config/conexao.php");
        try{
            $sql = "DELETE FROM estadias WHERE id=?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$id])){
                header('location: estadias.php?exclusao=true');
            } else {
                header('location: estadias.php?exclusao=false');
            }
        } catch (Exception $e){
            die("Erro ao excluir a estadia: ".$e->getMessage());
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $id = $_POST['id'];
        excluirEstadia($id);
    } else {
        $estadia = consultaEstadia($_GET['id']);
    }

?>

<h2>Consultar Estadia</h2>

<form method="post">

    <input type="hidden" name="id" value="<?= $estadia['id'] ?>" >
                        
    <div class="mb-3">
        <p>Hóspede: <b> <?= $estadia['nome'] . ' ' . $estadia['sobrenome'] ?> </b> </p>
    </div>

    <div class="mb-3">
        <p>Quarto: <b> Quarto <?= $estadia['quarto_numero'] ?> </b> </p>
    </div>

    <div class="mb-3">
        <p>Check-in: <b> <?= date('d/m/Y H:i', strtotime($estadia['data_checkin'])) ?> </b> </p>
    </div>

    <div class="mb-3">
        <p>Check-out: <b> 
            <?php if ($estadia['data_checkout']): ?>
                <?= date('d/m/Y H:i', strtotime($estadia['data_checkout'])) ?>
            <?php else: ?>
                <span class="text-warning">Ainda hospedado</span>
            <?php endif; ?>
        </b> </p>
    </div>

    <div class="mb-3">
        <p>Período da Reserva: <b> <?= date('d/m/Y', strtotime($estadia['data_inicio'])) ?> a <?= date('d/m/Y', strtotime($estadia['data_fim'])) ?> </b> </p>
    </div>

    <div class="mb-3">
        <p class="text-danger">Deseja excluir esse registro?</p>
        <button type="submit" class="btn btn-danger">Excluir</button>
        <a href="estadias.php" class="btn btn-secondary">Voltar</a>
    </div>
</form>


<?php 
    require_once("rodape.php");

?>

