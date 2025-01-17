<?php
// Verifica se a requisição é do tipo POST. Isso acontece quando o formulário é enviado.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtém o número total de questões, convertendo o valor pera inteiro.
    // Se não for fornecido, define o valor padrão como 10.
    $total_questions = intval($_POST['text_total_questions']) ?? 10;
    
    // Chama a função 'prepare_game' para preparar os dados do jogo com o número de questões definido.
    prepare_game($total_questions);
   
    // Redireciona para a página do jogo (route-game), para que o jogador possa começar a jogar.
    // Isso é feito através de um cabeçalho HTTP que muda a URL e recarrega a página.
    header('Location: index.php?route=game');
    exit; // A execução é interrompida aqui para evitar que o código abaixo seja executado.
}

// Função para preparar os dados do jogo com base no número total de questões fornecido.
function prepare_game($total_questions)
{
    // Acessa a variável global $capitals, que contém os dados das capitais.
    global $capitals;

    // Inicializa um array vazio para armazenar os IDs das capitais selecionadas aleatoriamente.
    $ids = [];
    // Continua até que tenhamos número desejado de questões.
    while(count($ids) < $total_questions) {
        // Gera um ID aleatório dentro do intervalo de indices do array $capitals.
        $id = rand(0, count($capitals) - 1);
        // Verifica se o ID ja foi selecionado. Se não, adiciona ao array kids.
        if(!in_array($id, $ids)) {
            $ids[] = $id;
        }
    }

    // Inicializa o array $questions para armazenar ás questões do jogo.
    $questions;
    // Para cada ID selecionado, cria uma questão.
    foreach($ids as $id) {
    
    // Inicializa un array para armazenar as respostas possíveis.
    $answers = [];
    // A primeira resposta é sempre o ID correto (capital correspondente ao pais).
    $answers[] = $id;
    // Preenche as outras respostas com IDs aleatórios que não sejam iguais do correto.
    while(count($answers) < 3) {
        $tmp = rand(0, count($capitals) - 1);
        // Verifica se resposta gerada já foi selecionada. Se não, adiciona a lista de respostas.
        if(!in_array($tmp, $answers)) {
            $answers[] = $tmp;
        }
    }
    
    // Embaralha as respostas para que a correte não fique sempre na mesma posição.
    shuffle($answers);

    // Adiciona a questão ao array $questions.
    // Cada questão contem o nome do pais, o ID da resposta correta e as 3 alternativas.
    $questions[] = [
        'question' => $capitals[$id][0],    // O nome do país.
        'correct_answer' => $id,            // O ID da resposta correta (capital).
        'answers' => $answers               // As 3 respostas possíveis (uma correta, duas erradas).
    ];
}

// Armazena as questões geradas na sessão para que possam ser acessadas em outras páginas.
$_SESSION['questions'] = $questions;

// Inicializa os dados do jogo na sessão, incluindo o número total de questões,
// O número da questão atual e as contagens de respostas corretas e erradas.
$_SESSION['game'] = [
    'total_questions' => $total_questions,  // Número total de questões definidas pelo usuário.
    'current_question' => 0,                // Começa com a primeira questão.
    'correct_answers' => 0,                 // Inicializa o número de respostas corretas.
    'incorrect_answers' => 0,               // Inicializa o número de respostas erradas.
];
}
?>

<!-- Início do código HTML para exibir o formulário na página de início -->
<div class="container">
    <div class="row">
        <!-- Titulo principal do jogo -->
        <h1>Quiz das Capitais</h1>
    <hr>

    <!-- Formulário para o usuário definir o número de questões e iniciar o jogo -->
    <div class="number">
        <form action="index.php?route=start" method="post">
            <!-- Pergunta sobre o número de questões, com um campo input de tipo number -->
            <h3><label for="text total questions" class="form-label">Número de questões:</label>
            <!-- 0 valor inicial é 10, o minimo è 1 e o máximo é 20. -->
            <input type="number" class="form-control" id="text_total_questions" name="text_total_questions" value="10" min="1" max="20"> </h3>
    </div>
        <!-- Botão para submeter o formulário e Iniciar o jogo -->
        <div>
          <button type="submit" class="btn">Iniciar</button>
        </div>
    </form>
    </div>
</div>