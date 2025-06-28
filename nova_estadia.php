<?php

    require_once("cabecalho.php");

    function retornaReservas(){
        require("config/conexao.php");
        try{
            $sql = "SELECT r.*, h.nome, h.sobrenome 
                    FROM reservas r
                    LEFT JOIN hospedes h ON r.hospede_id = h.id
                    WHERE r.status = 'confirmada'
                    ORDER BY r.data_inicio DESC";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } catch (Exception $e){
            die("Erro ao consultar reservas: ". $e->getMessage());
        }
    }

    function retornaQuartos(){
        require("config/conexao.php");
        try{
            $sql = "SELECT * FROM quartos ORDER BY numero";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } catch (Exception $e){
            die("Erro ao consultar quartos: ". $e->getMessage());
        }
    }

    function inserirEstadia($reserva_id, $quarto_id, $data_checkin, $data_checkout){
        require("config/conexao.php");
        try{
            $sql = "INSERT INTO estadias (reserva_id, quarto_id, data_checkin, data_checkout) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$reserva_id, $quarto_id, $data_checkin, $data_checkout])){
                header('location: estadias.php?cadastro=true');
            } else {
                header('location: estadias.php?cadastro=false');
            }
        } catch (Exception $e){
            die("Erro ao inserir a estadia: ".$e->getMessage());
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $reserva_id = $_POST['reserva_id'];
        $quarto_id = $_POST['quarto_id'];
        $data_checkin = $_POST['data_checkin'];
        $data_checkout = $_POST['data_checkout'] ?: null;
        inserirEstadia($reserva_id, $quarto_id, $data_checkin, $data_checkout);
    }

    $reservas = retornaReservas();
    $quartos = retornaQuartos();

?>

<h2>Nova Estadia</h2>

<form method="post">
                        
    <div class="mb-3">
        <label for="reserva_id" class="form-label">Reserva</label>
        <select id="reserva_id" name="reserva_id" class="form-control" required="">
            <option value="">Selecione uma reserva</option>
            <?php foreach($reservas as $r): ?>
                <option value="<?= $r['id'] ?>">
                    <?= $r['nome'] . ' ' . $r['sobrenome'] . ' - ' . date('d/m/Y', strtotime($r['data_inicio'])) . ' a ' . date('d/m/Y', strtotime($r['data_fim'])) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="quarto_id" class="form-label">Quarto</label>
        <select id="quarto_id" name="quarto_id" class="form-control" required="">
            <option value="">Selecione um quarto</option>
            <?php foreach($quartos as $q): ?>
                <option value="<?= $q['id'] ?>">Quarto <?= $q['numero'] ?> - <?= $q['tipo'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="data_checkin" class="form-label">Data e Hora do Check-in</label>
        <input type="datetime-local" id="data_checkin" name="data_checkin" class="form-control" required="">
    </div>

    <div class="mb-3">
        <label for="data_checkout" class="form-label">Data e Hora do Check-out</label>
        <input type="datetime-local" id="data_checkout" name="data_checkout" class="form-control">
        <small class="form-text text-muted">Deixe em branco se o hóspede ainda não fez check-out</small>
    </div>

    <button type="submit" class="btn btn-primary">Enviar</button>
    <a href="estadias.php" class="btn btn-secondary">Cancelar</a>
</form>


<?php 
    require_once("rodape.php");

?>

