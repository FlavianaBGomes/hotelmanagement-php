# Sistema de Gestão de Hotéis - Flaviana Bataliotti Gomes

Este é um sistema completo de gestão de hotéis desenvolvido em PHP com interface CSS estilizada no tema roxo anos 90. O sistema permite o controle completo de reservas em hotéis através de funcionalidades CRUD (Create, Read, Update, Delete) para quartos, hóspedes, reservas e estadias.

### RF1: CRUD para cadastro de quartos
- **Criar**: Adicionar novos quartos com número, tipo, capacidade e preço por noite
- **Ler**: Visualizar lista de todos os quartos cadastrados
- **Atualizar**: Editar informações de quartos existentes
- **Deletar**: Remover quartos do sistema

### RF2: CRUD para cadastro de hóspedes
- **Criar**: Cadastrar novos hóspedes com nome, sobrenome, documento, email e telefone
- **Ler**: Visualizar lista de todos os hóspedes cadastrados
- **Atualizar**: Editar informações de hóspedes existentes
- **Deletar**: Remover hóspedes do sistema

### RF3: CRUD para cadastro de reservas
- **Criar**: Criar novas reservas relacionando hóspedes com datas de início e fim
- **Ler**: Visualizar lista de todas as reservas com informações do hóspede
- **Atualizar**: Editar reservas existentes (datas, status, hóspede)
- **Deletar**: Cancelar/remover reservas do sistema
- **Status**: Controle de status das reservas (confirmada, cancelada, concluída)

### RF4: Registrar estadias
- **Criar**: Registrar estadias relacionando quartos e reservas
- **Check-in**: Registrar data e hora de entrada do hóspede
- **Check-out**: Registrar data e hora de saída do hóspede
- **Controle**: Visualizar estadias ativas e finalizadas

## Estrutura do Banco de Dados

### Tabela: quartos
- `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
- `numero` (VARCHAR(10), NOT NULL, UNIQUE)
- `tipo` (VARCHAR(50), NOT NULL)
- `capacidade` (INT, NOT NULL)
- `preco_noite` (DECIMAL(10,2), NOT NULL)

### Tabela: hospedes
- `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
- `nome` (VARCHAR(100), NOT NULL)
- `sobrenome` (VARCHAR(100), NOT NULL)
- `documento` (VARCHAR(50), UNIQUE)
- `email` (VARCHAR(100), UNIQUE)
- `telefone` (VARCHAR(20))

### Tabela: reservas
- `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
- `hospede_id` (INT, NOT NULL, FOREIGN KEY)
- `data_inicio` (DATE, NOT NULL)
- `data_fim` (DATE, NOT NULL)
- `status` (ENUM: 'confirmada', 'cancelada', 'concluida')

### Tabela: estadias
- `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
- `reserva_id` (INT, NOT NULL, FOREIGN KEY)
- `quarto_id` (INT, NOT NULL, FOREIGN KEY)
- `data_checkin` (DATETIME, NOT NULL)
- `data_checkout` (DATETIME)


## Tema Visual - Roxo Anos 90

O sistema utiliza um esquema de cores inspirado nos anos 90 com tons de roxo:

- **Gelado**: #a0d2eb
- **Freeze Purple**: #e5eaf5
- **Roxo Médio**: #d0bdf4
- **Dor Roxa**: #8458B3
- **Roxo Intenso**: #a28089

### Características do Design:
- Gradientes coloridos característicos dos anos 90
- Efeitos de neon e brilho no título principal
- Botões com bordas arredondadas e efeitos hover
- Animações suaves e transições
- Layout responsivo para desktop e mobile
- Scrollbar personalizada
- Cards com efeitos de hover especiais

## Tecnologias Utilizadas

- **Backend**: PHP 8.1
- **Banco de Dados**: MySQL 8.0
- **Frontend**: HTML5, CSS3
- **Arquitetura**: MVC (Model-View-Controller)
- **Padrão**: PDO para conexão com banco de dados
- **Segurança**: Sanitização de dados com htmlspecialchars

## Instalação e Configuração

### Pré-requisitos:
- PHP 8.1 ou superior
- MySQL 8.0 ou superior
- Servidor web (Apache/Nginx) ou PHP built-in server

### Passos de Instalação:

1. **Configurar o banco de dados:**
   ```sql
   mysql -u root -p < database_setup.sql
   ```

2. **Configurar conexão (config/database.php):**
   ```php
   private $host = 'localhost';
   private $db_name = 'hotel_management';
   private $username = 'root';
   private $password = '';
   ```

3. **Iniciar servidor:**
   ```bash
   php -S localhost:8000
   ```

4. **Acessar o sistema:**
   ```
   http://localhost:8000
   ```


### Página Principal
A página inicial apresenta um dashboard com cards para acessar cada módulo do sistema:
- Quartos: Gerenciamento de quartos do hotel
- Hóspedes: Cadastro e controle de hóspedes
- Reservas: Controle de reservas
- Estadias: Registro de check-ins e check-outs

### Gerenciamento de Quartos
1. Acesse "Quartos" no menu principal
2. Preencha o formulário com: número, tipo, capacidade e preço
3. Clique em "Criar" para adicionar o quarto
4. Use "Editar" para modificar quartos existentes
5. Use "Deletar" para remover quartos (com confirmação)

### Gerenciamento de Hóspedes
1. Acesse "Hóspedes" no menu principal
2. Preencha: nome, sobrenome, documento, email e telefone
3. Clique em "Criar" para cadastrar o hóspede
4. Use as ações "Editar" e "Deletar" conforme necessário

### Gerenciamento de Reservas
1. Acesse "Reservas" no menu principal
2. Selecione um hóspede cadastrado
3. Defina as datas de início e fim da reserva
4. Escolha o status (confirmada, cancelada, concluída)
5. Clique em "Criar" para registrar a reserva

### Registro de Estadias
1. Acesse "Estadias" no menu principal
2. Selecione uma reserva existente
3. Escolha o quarto para a estadia
4. Registre a data/hora de check-in
5. Opcionalmente registre o check-out (pode ser feito posteriormente)

## Recursos de Segurança

- Sanitização de dados de entrada com `htmlspecialchars()`
- Uso de prepared statements (PDO) para prevenir SQL injection
- Validação de dados no frontend e backend
- Confirmação antes de operações de exclusão

## Responsividade

O sistema é totalmente responsivo, adaptando-se a diferentes tamanhos de tela:
- Desktop: Layout completo com sidebar e cards
- Tablet: Layout adaptado com navegação otimizada
- Mobile: Interface simplificada com navegação vertical

---

*Desenvolvido em 2025 - Todos os direitos reservados*

