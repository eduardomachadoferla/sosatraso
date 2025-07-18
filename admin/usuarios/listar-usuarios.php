<?php
session_start(); // ESSENCIAL para trabalhar com $_SESSION
if (isset($_SESSION['mensagem'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['mensagem']) . '</div>';
    unset($_SESSION['mensagem']);
}

include('../../config/conexao.php');

// Redirecionamento se não estiver autenticado
if (!isset($_SESSION['login']['auth'])) {
    header("Location: " . BASE_ADMIN . 'login.php');
    exit();
}

include('../include/header.php');

// Configuração da paginação
$limit = 20;
$paginaAtual = isset($_GET['pagination']) ? (int)$_GET['pagination'] : 1;
$offset = ($paginaAtual - 1) * $limit;

// Pegando nome da pesquisa por GET ou POST
$busca_nome = '';
if (isset($_POST['busca_nome'])) {
    $busca_nome = trim($_POST['busca_nome']);
    // Redireciona para GET com o parâmetro para evitar resubmissão
    header("Location: ?pagination=1&busca_nome=" . urlencode($busca_nome));
    exit();
} elseif (isset($_GET['busca_nome'])) {
    $busca_nome = trim($_GET['busca_nome']);
}

// Filtro SQL para busca por nome
$filtro_nome = "";
$params = [];

if (!empty($busca_nome)) {
    $filtro_nome = "WHERE nome LIKE :nome";
    $params[':nome'] = "%" . $busca_nome . "%";
}

// Consulta principal com limite e offset para paginação
$sqlUsuarios = "SELECT * FROM usuarios $filtro_nome ORDER BY nome ASC LIMIT $offset, $limit";
$stmtUsuarios = $pdo->prepare($sqlUsuarios);
$stmtUsuarios->execute($params);
$dataUsuarios = $stmtUsuarios->fetchAll();

// Consulta para contar total de registros e calcular total de páginas
$sqlTotal = "SELECT COUNT(*) as total FROM usuarios $filtro_nome";
$stmtTotal = $pdo->prepare($sqlTotal);
$stmtTotal->execute($params);
$totalRegistros = $stmtTotal->fetch()['total'];
$totalPaginas = ceil($totalRegistros / $limit);
?>

<!-- HTML da página -->
<?php
if (isset($_SESSION['msg_sucesso'])) {
    echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">'
         . htmlspecialchars($_SESSION['msg_sucesso']) .
         '</div>';
    unset($_SESSION['msg_sucesso']);
}

if (isset($_SESSION['msg_erro'])) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">'
         . htmlspecialchars($_SESSION['msg_erro']) .
         '</div>';
    unset($_SESSION['msg_erro']);
}
?>

<div class="bg-white w-6xl mx-auto p-6 rounded-lg">
    <p class="text-2xl mx-auto text-center font-black text-marista mb-6">GERENCIAMENTO USUÁRIOS</p>

    <!-- Formulário de busca -->
    <form method="post" class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-center">
        <input type="text" name="busca_nome" placeholder="Buscar usuário pelo nome"
               value="<?php echo htmlspecialchars($busca_nome); ?>"
               class="border border-gray-400 rounded-md p-3 w-64">

        <button type="submit" class="bg-marista text-white px-6 py-2 rounded-lg drop-shadow-lg">Buscar</button>
        <a href="../adicionar_usuario.php" class="bg-marista text-white px-6 py-2 rounded-lg drop-shadow-lg">Adicionar Usuário</a>
    </form>

    <div id="resultados">
        <table class="table w-full text-left">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Setor</th>
                    <th>Permissão</th>

                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dataUsuarios)) {
                    foreach ($dataUsuarios as $usuario) { ?>
                        <tr>
                            <td class="flex items-center gap-2">
                                <a href="../usuarios/editar-usuarios.php?id=<?php echo $usuario['id']; ?>">
                                    <img src="../imagems/edit.svg" alt="editar" class="w-5 h-5 hover:scale-110 transition-transform duration-200">
                                </a>
                                <?php echo htmlspecialchars($usuario['nome']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['setor']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['permissao']); ?></td>


                        </tr>
                <?php }
                } else { ?>
                    <tr><td colspan="5">Nenhum usuário encontrado!</td></tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <div class="mt-8">
            <nav class="isolate inline-flex -space-x-px rounded-md shadow-xs mx-auto justify-center flex" aria-label="Pagination">
                <?php
                $queryBase = "?busca_nome=" . urlencode($busca_nome) . "&pagination=";
                $prev = $paginaAtual - 1;
                $next = $paginaAtual + 1;
                ?>
                <a href="<?php echo ($prev >= 1) ? $queryBase . $prev : '#'; ?>"
                   class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50">
                    <span class="sr-only">Anterior</span>
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                    </svg>
                </a>

                <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>
                    <a href="<?php echo $queryBase . $i; ?>"
                       class="<?php echo ($i == $paginaAtual) ? 'bg-marista' : 'bg-marista2'; ?> relative z-10 inline-flex items-center px-4 py-2 text-sm font-semibold text-white">
                        <?php echo $i; ?>
                    </a>
                <?php } ?>

                <a href="<?php echo ($next <= $totalPaginas) ? $queryBase . $next : '#'; ?>"
                   class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50">
                    <span class="sr-only">Próxima</span>
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </a>
            </nav>
        </div>
    </div>
</div>
