<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$db   = 'adote_um_pet';
$user = 'root';              
$pass = '';                  
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$status_message = null;
$status_type = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo_animal'] ?? null;
    $nome = $_POST['nome_animal'] ?? null;
    $idade = (int)($_POST['idade'] ?? 0);
    $porte = $_POST['porte'] ?? null;
    $cores = $_POST['cores'] ?? null; 
    $observacoes = $_POST['observacoes'] ?? '';

    if ($tipo && $nome && $idade > 0 && $porte && $cores) {
        try {s
            $pdo = new PDO($dsn, $user, $pass, $options);
            
            $sql = "INSERT INTO adocoes (tipo_animal, nome_animal, idade, porte, cores, observacoes) 
                    VALUES (?, ?, ?, ?, ?, ?)";
                    
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$tipo, $nome, $idade, $porte, $cores, $observacoes]);

            $status_message = "🎉 Sucesso! O animal **" . htmlspecialchars($nome) . "** foi registrado e já está na lista.";
            $status_type = 'success';
            
        } catch (\PDOException $e) {
            $status_message = "❌ Erro no Banco de Dados (SALVAMENTO). Detalhe: " . $e->getMessage();
            $status_type = 'error';
        }
    } else {
        $status_message = "⚠️ Aviso: Dados insuficientes para salvar. Apenas a lista será exibida.";
        $status_type = 'warning';
    }
}

$adocoes = [];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    $stmt = $pdo->query('SELECT tipo_animal, nome_animal, idade, porte, cores, observacoes, data_registro FROM adocoes ORDER BY id DESC');

    $adocoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (\PDOException $e) {
    if ($status_type !== 'error') {
        $status_message = "❌ Erro ao buscar registros: Verifique a conexão com o MySQL e a tabela. Detalhe: " . $e->getMessage();
        $status_type = 'error';
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Adoção - Adote um Pet!</title>
    <link rel="stylesheet" href="styles.css">
    <style>
      
        .status-message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
        }
        .status-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-error, .status-warning {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
       
        .animal-card {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border-left: 5px solid #007bff;
        }
        .cor-detail {
            display: inline-block;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            margin-right: 5px;
            vertical-align: middle;
            border: 1px solid #333;
        }
        .link-cadastro {
            display: block;
            margin-bottom: 25px;
            text-align: center;
            font-size: 1.1em;
            color: #28a745;
            text-decoration: none;
            font-weight: bold;
        }
        .link-cadastro:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <main class="container">
        <h1>🐾 Lista de Animais Disponíveis 🐾</h1>
        <p>Dados diretamente do seu banco de dados `adote_um_pet`.</p>

        <?php
        if ($status_message) {
            echo "<div class='status-message status-$status_type'>" . nl2br(htmlspecialchars($status_message)) . "</div>";
        }
        ?>

        <a href="index.html" class="link-cadastro">← Voltar para o Formulário de Cadastro</a>

        
        <section id="lista-adocoes" style="padding-top: 10px;">
            <h2 style="font-size: 1.8em; color: #007bff; text-align: center; margin-bottom: 20px;">Total de Registros: <?php echo count($adocoes); ?></h2>
            
            <?php if (empty($adocoes)):?>
                <p style="color: #6c757d; font-style: italic; text-align: center;">Nenhum animal registrado ainda. Cadastre um animal no formulário!</p>
            <?php else:?>
                <?php foreach ($adocoes as $animal): 

                    $cores_map = [
                        'Preto' => '#343a40', 'Branco' => '#f8f9fa', 'Marrom' => '#795548', 
                        'Caramelo' => '#d2b48c', 'Cinza' => '#6c757d'
                    ];
                    $cor_hex = $cores_map[$animal['cores']] ?? '#ccc';
                ?>
                    <div class="animal-card">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 1.2em; font-weight: bold; color: #007bff;"><?php echo htmlspecialchars($animal['nome_animal']); ?></span>
                            <span style="font-size: 0.8em; font-style: italic; color: #666;">(<?php echo htmlspecialchars($animal['tipo_animal']); ?>)</span>
                        </div>
                        <div style="margin-top: 8px; font-size: 0.9em;">
                            <p>
                                <strong>Idade:</strong> <?php echo htmlspecialchars($animal['idade']); ?> anos | 
                                <strong>Porte:</strong> <?php echo htmlspecialchars($animal['porte'] ?? 'N/A'); ?>
                            </p>
                            <p style="margin-top: 4px;">
                                <strong>Cor:</strong> 
                                <span class="cor-detail" style="background-color: <?php echo $cor_hex; ?>;"></span>
                                <?php echo htmlspecialchars($animal['cores']); ?>
                            </p>
                            <p style="margin-top: 8px; color: #333;"><strong>Obs:</strong> <?php echo htmlspecialchars($animal['observacoes'] ?: 'Nenhuma'); ?></p>
                            <p style="margin-top: 4px; font-size: 0.75em; color: #999;">Registrado em: <?php echo date('d/m/Y H:i', strtotime($animal['data_registro'])); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </section>

    </main>
</body>
</html>