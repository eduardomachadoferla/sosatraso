<?php
session_start();
include('../config/conexao.php');

$css = ['geral.css', 'index.css', 'estilo.css'];
include('../includes/header.php');
?>

</head>
<body>

<div class="bg-white w-full max-w-md mx-auto rounded-lg drop-shadow-lg mt-24 px-4 md:px-10 py-10 h-auto">

    <!-- LOGO DA ESCOLA (opcional) -->
    <div class="flex justify-center mb-6">
        <img src="../imagems/logo.png" alt="Logo da Escola" class="h-16 md:h-20">
    </div>

    <h3 class="text-center text-xl font-semibold mb-4">ACESSO RESTRITO</h3>

    <!-- MENSAGEM DE ERRO -->
    <?php if (isset($_SESSION['error'])) { ?>
        <div class="text-red-600 text-center font-medium mb-4">
            <?php 
            echo $_SESSION['error']; 
            unset($_SESSION['error']);
            ?>
        </div>
    <?php } ?>

    <!-- FORMULÁRIO -->
    <form action="logar.php" method="post" id="cadastroForm" class="flex flex-col items-center space-y-4">

        <!-- Setor -->
        <input 
            type="text" 
            class="border border-gray-400 rounded-md p-3 w-full" 
            id="setor" 
            name="setor" 
            placeholder="Setor" 
            required>

        <!-- Senha -->
        <input 
            type="password" 
            class="border border-gray-400 rounded-md p-3 w-full" 
            id="senha" 
            name="senha" 
            placeholder="Senha" 
            required>

        <!-- BOTÕES -->
        <div class="flex flex-col md:flex-row items-center justify-center w-full gap-4 mt-4">
            <a href="<?php echo BASE_URL; ?>" class="bg-marista text-white px-6 py-2 rounded-lg drop-shadow-lg w-full text-center">Voltar à Home</a>
            <button type="submit" class="bg-marista text-white px-6 py-2 rounded-lg drop-shadow-lg w-full">
                ACESSAR RELATÓRIO
            </button>
        </div>

    </form>

</div>

</body>
</html>
