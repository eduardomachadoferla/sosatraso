<?php 
include('config/base.php');
include('config/conexao.php');
include('includes/header.php');

$sql2 = 'SELECT * FROM alunos';
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute();
$turmas = $stmt2->fetchAll();

$css = ['geral.css', 'index.css', 'estilo.css'];

unset($_SESSION['ALUNO']);
?>

<div class="bg-white w-full max-w-md mx-auto rounded-lg drop-shadow-lg mt-22 px-4 md:px-10 py-10 h-auto">
    <form action="autorizacao.php" method="post" id="cadastroForm" class="flex flex-col justify-center items-center mt-10">
        
        <!-- Campo de pesquisa de aluno -->
        <div class="w-full">
            <input 
                class="border border-gray-400 rounded-md p-3 mb-5 w-full" 
                type="text" 
                id="nome" 
                name="nome" 
                placeholder="Nome Completo" 
                required 
                onkeyup="buscarNomes()">
            
            <input type="hidden" id="turma" name="turma">
            <div id="sugestoes" class="sugestoes"></div>
        </div>

        <!-- Motivo do atraso -->
        <select 
            class="border border-gray-400 rounded-md p-3 mb-5 w-full" 
            id="motivo_atraso" 
            name="motivo_atraso" 
            required 
            onchange="mostrarCaixaTexto()">
            
            <option value="">Motivo do Atraso</option>
            <option value="Perdi o horário">Perdi o horário</option>
            <option value="Chuva">Chuva</option>
            <option value="Imprevisto com o meio de transporte">Imprevisto com o meio de transporte</option>
            <option value="Outro">Outro</option>
        </select>

        <!-- Outro motivo -->
        <div id="outro_motivo" class="w-full mb-5 hidden">
            <input 
                class="border border-gray-400 rounded-md p-3 w-full" 
                type="text" 
                id="outro_text" 
                name="outro_text" 
                placeholder="Especifique o Motivo">
        </div>

        <!-- Botão -->
        <div class="text-center">
            <button 
                class="bg-marista text-white px-6 py-2 rounded-lg drop-shadow-lg mt-6"  
                type="submit">
                GERAR BILHETE
            </button>
        </div>
    </form>
</div>

<!-- Script JS -->
<script>
function buscarNomes() {
    var nome = document.getElementById("nome").value;
    if (nome.length < 2) {
        document.getElementById("sugestoes").innerHTML = "";
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "buscar_alunos.php?nome=" + nome, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("sugestoes").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

function selecionarNome(nome) {
    var words = nome.split("|");
    document.getElementById("nome").value = words[0];
    document.getElementById("turma").value = words[1];
    document.getElementById("sugestoes").innerHTML = "";
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById("outro_motivo").classList.add("hidden");
    document.getElementById("outro_text").removeAttribute("required");
});

function mostrarCaixaTexto() {
    const selectElement = document.getElementById("motivo_atraso");
    const outroMotivoDiv = document.getElementById("outro_motivo");
    const outroTextInput = document.getElementById("outro_text");

    if (selectElement.value === "Outro") {
        outroMotivoDiv.classList.remove("hidden");
        outroTextInput.setAttribute("required", "required");
    } else {
        outroMotivoDiv.classList.add("hidden");
        outroTextInput.removeAttribute("required");
    }
}
</script>
</body>
</html>
