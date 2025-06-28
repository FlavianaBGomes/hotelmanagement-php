<?php
require_once '../config/database.php';
require_once '../models/Quarto.php';

$database = new Database();
$db = $database->getConnection();
$quarto = new Quarto($db);

$message = '';

// Processar ações
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $quarto->numero = $_POST['numero'];
                $quarto->tipo = $_POST['tipo'];
                $quarto->capacidade = $_POST['capacidade'];
                $quarto->preco_noite = $_POST['preco_noite'];
                
                if ($quarto->create()) {
                    $message = "Quarto criado com sucesso!";
                } else {
                    $message = "Erro ao criar quarto.";
                }
                break;
                
            case 'update':
                $quarto->id = $_POST['id'];
                $quarto->numero = $_POST['numero'];
                $quarto->tipo = $_POST['tipo'];
                $quarto->capacidade = $_POST['capacidade'];
                $quarto->preco_noite = $_POST['preco_noite'];
                
                if ($quarto->update()) {
                    $message = "Quarto atualizado com sucesso!";
                } else {
                    $message = "Erro ao atualizar quarto.";
                }
                break;
                
            case 'delete':
                $quarto->id = $_POST['id'];
                if ($quarto->delete()) {
                    $message = "Quarto deletado com sucesso!";
                } else {
                    $message = "Erro ao deletar quarto.";
                }
                break;
        }
    }
}

// Buscar quarto para edição
$edit_quarto = null;
if (isset($_GET['edit'])) {
    $quarto->id = $_GET['edit'];
    if ($quarto->readOne()) {
        $edit_quarto = $quarto;
    }
}

// Listar todos os quartos
$stmt = $quarto->read();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Quartos - Sistema de Gestão de Hotéis</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Sistema de Gestão de Hotéis</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Início</a></li>
                <li><a href="quartos.php" class="active">Quartos</a></li>
                <li><a href="hospedes.php">Hóspedes</a></li>
                <li><a href="reservas.php">Reservas</a></li>
                <li><a href="estadias.php">Estadias</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="container">
            <h2>Gerenciar Quartos</h2>
            
            <?php if ($message): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            <!-- Formulário para criar/editar quarto -->
            <div class="form-section">
                <h3><?php echo $edit_quarto ? 'Editar Quarto' : 'Novo Quarto'; ?></h3>
                <form method="POST">
                    <input type="hidden" name="action" value="<?php echo $edit_quarto ? 'update' : 'create'; ?>">
                    <?php if ($edit_quarto): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_quarto->id; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="numero">Número do Quarto:</label>
                        <input type="text" id="numero" name="numero" value="<?php echo $edit_quarto ? $edit_quarto->numero : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo">Tipo:</label>
                        <select id="tipo" name="tipo" required>
                            <option value="">Selecione o tipo</option>
                            <option value="Standard" <?php echo ($edit_quarto && $edit_quarto->tipo == 'Standard') ? 'selected' : ''; ?>>Standard</option>
                            <option value="Deluxe" <?php echo ($edit_quarto && $edit_quarto->tipo == 'Deluxe') ? 'selected' : ''; ?>>Deluxe</option>
                            <option value="Suite" <?php echo ($edit_quarto && $edit_quarto->tipo == 'Suite') ? 'selected' : ''; ?>>Suíte</option>
                            <option value="Presidential" <?php echo ($edit_quarto && $edit_quarto->tipo == 'Presidential') ? 'selected' : ''; ?>>Presidencial</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="capacidade">Capacidade:</label>
                        <input type="number" id="capacidade" name="capacidade" value="<?php echo $edit_quarto ? $edit_quarto->capacidade : ''; ?>" min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="preco_noite">Preço por Noite (R$):</label>
                        <input type="number" id="preco_noite" name="preco_noite" value="<?php echo $edit_quarto ? $edit_quarto->preco_noite : ''; ?>" step="0.01" min="0" required>
                    </div>
                    
                    <button type="submit" class="btn"><?php echo $edit_quarto ? 'Atualizar' : 'Criar'; ?></button>
                    <?php if ($edit_quarto): ?>
                        <a href="quartos.php" class="btn btn-secondary">Cancelar</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Lista de quartos -->
            <div class="table-section">
                <h3>Quartos Cadastrados</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Tipo</th>
                            <th>Capacidade</th>
                            <th>Preço/Noite</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['numero']); ?></td>
                            <td><?php echo htmlspecialchars($row['tipo']); ?></td>
                            <td><?php echo htmlspecialchars($row['capacidade']); ?></td>
                            <td>R$ <?php echo number_format($row['preco_noite'], 2, ',', '.'); ?></td>
                            <td>
                                <a href="quartos.php?edit=<?php echo $row['id']; ?>" class="btn btn-small">Editar</a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar este quarto?');">
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

