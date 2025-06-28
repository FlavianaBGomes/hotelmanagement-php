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

    function inserirReserva($hospede_id, $data_inicio, $data_fim, $status){
        require("config/conexao.php");
        try{
            $sql = "INSERT INTO reservas (hospede_id, data_inicio, data_fim, status) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$hospede_id, $data_inicio, $data_fim, $status])){
                header('location: reservas.php?cadastro=true');
            } else {
                header('location: reservas.php?cadastro=false');
            }
        } catch (Exception $e){
            die("Erro ao inserir a reserva: ".$e->getMessage());
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $hospede_id = $_POST['hospede_id'];
        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];
        $status = $_POST['status'];
        inserirReserva($hospede_id, $data_inicio, $data_fim, $status);
    }

    $hospedes = retornaHospedes();

?>

<h2>Nova Reserva</h2>

<form method="post">
                        
    <div class="mb-3">
        <label for="hospede_id" class="form-label">Hóspede</label>
        <select id="hospede_id" name="hospede_id" class="form-control" required="">
            <option value="">Selecione um hóspede</option>
            <?php foreach($hospedes as $h): ?>
                <option value="<?= $h['id'] ?>"><?= $h['nome'] . ' ' . $h['sobrenome'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="data_inicio" class="form-label">Data de Início</label>
        <input type="date" id="data_inicio" name="data_inicio" class="form-control" required="">
    </div>

    <div class="mb-3">
        <label for="data_fim" class="form-label">Data de Fim</label>
        <input type="date" id="data_fim" name="data_fim" class="form-control" required="">
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-control" required="">
            <option value="confirmada">Confirmada</option>
            <option value="cancelada">Cancelada</option>
            <option value="concluida">Concluída</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Enviar</button>
    <a href="reservas.php" class="btn btn-secondary">Cancelar</a>
</form>


<?php 
    require_once("rodape.php");

?>

