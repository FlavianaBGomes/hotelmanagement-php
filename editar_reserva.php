<?php

    require_once("cabecalho.php");

    function retornaHospedes(){
        require("config/conexao.php");
        try{
            $sql = "SELECT * FROM hospedes ORDER BY nome, sobrenome";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } catch (Exception $e){
            die("Erro ao consultar hóspedes: ". $e->getMessage());
        }
    }

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

    function alterarReserva($hospede_id, $data_inicio, $data_fim, $status, $id){
        require("config/conexao.php");
        try{
            $sql = "UPDATE reservas SET hospede_id = ?, data_inicio = ?, data_fim = ?, status = ? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$hospede_id, $data_inicio, $data_fim, $status, $id])){
                header('location: reservas.php?edicao=true');
            } else {
                header('location: reservas.php?edicao=false');
            }
        } catch (Exception $e){
            die("Erro ao alterar a reserva: ".$e->getMessage());
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $hospede_id = $_POST['hospede_id'];
        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];
        $status = $_POST['status'];
        $id = $_POST['id'];
        alterarReserva($hospede_id, $data_inicio, $data_fim, $status, $id);
    } else {
        $reserva = consultaReserva($_GET['id']);
    }

    $hospedes = retornaHospedes();

?>

<h2>Alterar Reserva</h2>

<form method="post">

    <input type="hidden" name="id" value="<?= $reserva['id'] ?>" >
                        
    <div class="mb-3">
        <label for="hospede_id" class="form-label">Hóspede</label>
        <select id="hospede_id" name="hospede_id" class="form-control" required="">
            <option value="">Selecione um hóspede</option>
            <?php foreach($hospedes as $h): ?>
                <option value="<?= $h['id'] ?>" <?= ($reserva['hospede_id'] == $h['id']) ? 'selected' : '' ?>>
                    <?= $h['nome'] . ' ' . $h['sobrenome'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="data_inicio" class="form-label">Data de Início</label>
        <input value="<?= $reserva['data_inicio'] ?>" type="date" id="data_inicio" name="data_inicio" class="form-control" required="">
    </div>

    <div class="mb-3">
        <label for="data_fim" class="form-label">Data de Fim</label>
        <input value="<?= $reserva['data_fim'] ?>" type="date" id="data_fim" name="data_fim" class="form-control" required="">
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-control" required="">
            <option value="confirmada" <?= ($reserva['status'] == 'confirmada') ? 'selected' : '' ?>>Confirmada</option>
            <option value="cancelada" <?= ($reserva['status'] == 'cancelada') ? 'selected' : '' ?>>Cancelada</option>
            <option value="concluida" <?= ($reserva['status'] == 'concluida') ? 'selected' : '' ?>>Concluída</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Enviar</button>
    <a href="reservas.php" class="btn btn-secondary">Cancelar</a>
</form>


<?php 
    require_once("rodape.php");

?>

