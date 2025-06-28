<?php
require_once '../config/database.php';
require_once '../models/Reserva.php';
require_once '../models/Hospede.php';

$database = new Database();
$db = $database->getConnection();
$reserva = new Reserva($db);
$hospede = new Hospede($db);

$message = '';

// Processar ações
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $reserva->hospede_id = $_POST['hospede_id'];
                $reserva->data_inicio = $_POST['data_inicio'];
                $reserva->data_fim = $_POST['data_fim'];
                $reserva->status = $_POST['status'];
                
                if ($reserva->create()) {
                    $message = "Reserva criada com sucesso!";
                } else {
                    $message = "Erro ao criar reserva.";
                }
                break;
                
            case 'update':
                $reserva->id = $_POST['id'];
                $reserva->hospede_id = $_POST['hospede_id'];
                $reserva->data_inicio = $_POST['data_inicio'];
                $reserva->data_fim = $_POST['data_fim'];
                $reserva->status = $_POST['status'];
                
                if ($reserva->update()) {
                    $message = "Reserva atualizada com sucesso!";
                } else {
                    $message = "Erro ao atualizar reserva.";
                }
                break;
                
            case 'delete':
                $reserva->id = $_POST['id'];
                if ($reserva->delete()) {
                    $message = "Reserva deletada com sucesso!";
                } else {
                    $message = "Erro ao deletar reserva.";
                }
                break;
        }
    }
}

// Buscar reserva para edição
$edit_reserva = null;
if (isset($_GET['edit'])) {
    $reserva->id = $_GET['edit'];
    if ($reserva->readOne()) {
        $edit_reserva = $reserva;
    }
}

// Listar todas as reservas
$stmt = $reserva->read();

// Listar hóspedes para o select
$hospedes_stmt = $hospede->read();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Reservas - Sistema de Gestão de Hotéis</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Sistema de Gestão de Hotéis</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Início</a></li>
                <li><a href="quartos.php">Quartos</a></li>
                <li><a href="hospedes.php">Hóspedes</a></li>
                <li><a href="reservas.php" class="active">Reservas</a></li>
                <li><a href="estadias.php">Estadias</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="container">
            <h2>Gerenciar Reservas</h2>
            
            <?php if ($message): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            <!-- Formulário para criar/editar reserva -->
            <div class="form-section">
                <h3><?php echo $edit_reserva ? 'Editar Reserva' : 'Nova Reserva'; ?></h3>
                <form method="POST">
                    <input type="hidden" name="action" value="<?php echo $edit_reserva ? 'update' : 'create'; ?>">
                    <?php if ($edit_reserva): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_reserva->id; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="hospede_id">Hóspede:</label>
                        <select id="hospede_id" name="hospede_id" required>
                            <option value="">Selecione um hóspede</option>
                            <?php while ($hospede_row = $hospedes_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $hospede_row['id']; ?>" 
                                        <?php echo ($edit_reserva && $edit_reserva->hospede_id == $hospede_row['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($hospede_row['nome'] . ' ' . $hospede_row['sobrenome']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="data_inicio">Data de Início:</label>
                        <input type="date" id="data_inicio" name="data_inicio" value="<?php echo $edit_reserva ? $edit_reserva->data_inicio : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="data_fim">Data de Fim:</label>
                        <input type="date" id="data_fim" name="data_fim" value="<?php echo $edit_reserva ? $edit_reserva->data_fim : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select id="status" name="status" required>
                            <option value="confirmada" <?php echo ($edit_reserva && $edit_reserva->status == 'confirmada') ? 'selected' : ''; ?>>Confirmada</option>
                            <option value="cancelada" <?php echo ($edit_reserva && $edit_reserva->status == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                            <option value="concluida" <?php echo ($edit_reserva && $edit_reserva->status == 'concluida') ? 'selected' : ''; ?>>Concluída</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn"><?php echo $edit_reserva ? 'Atualizar' : 'Criar'; ?></button>
                    <?php if ($edit_reserva): ?>
                        <a href="reservas.php" class="btn btn-secondary">Cancelar</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Lista de reservas -->
            <div class="table-section">
                <h3>Reservas Cadastradas</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Hóspede</th>
                            <th>Data Início</th>
                            <th>Data Fim</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nome'] . ' ' . $row['sobrenome']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['data_inicio'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['data_fim'])); ?></td>
                            <td>
                                <span class="status status-<?php echo $row['status']; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="reservas.php?edit=<?php echo $row['id']; ?>" class="btn btn-small">Editar</a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar esta reserva?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-small btn-danger">Deletar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Sistema de Gestão de Hotéis - Flaviana Bataliotti Gomes</p>
    </footer>
</body>
</html>

