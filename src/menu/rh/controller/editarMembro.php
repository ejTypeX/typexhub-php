<?php
    ob_start(); // ob_start para evitar problemas com headers

    session_start(); // Iniiciar sessão
    require_once __DIR__ . '/../../../include/conexao.php';
    require_once __DIR__ . '/../repository/membroRepository.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        // redireciona de volta para o formulário se não for POST
        header('Location: ../../criarMembro.php');
        exit;
    } // Só processa quando for POST (evita avisos de array indefinido quando a página é acessada via GET)
   

    try{
        // usar null coalescing para evitar avisos
        $nome = trim($_POST['membro_nome'] ?? '');
        $cpf = trim($_POST['membro_cpf'] ?? '');
        $rg = trim($_POST['membro_rg'] ?? '');
        $email = trim($_POST['membro_email'] ?? '');
        $dataNascimento = trim($_POST['membro_data_nascimento'] ?? '');
        $habilidade = trim($_POST['membro_habilidade'] ?? '');
        $instagram = trim($_POST['membro_instagram'] ?? '');
        $github = trim($_POST['membro_github'] ?? '');
        $whatsapp = trim($_POST['membro_whatsapp'] ?? '');
        $linkedin = trim($_POST['membro_linkedin'] ?? '');
        $admissao = trim($_POST['membro_admissao'] ?? '');
        $ra = trim($_POST['membro_ra'] ?? '');
        $periodo = trim($_POST['membro_periodo'] ?? '');
        $cargo = trim($_POST['membro_cargo'] ?? '');
        $area = trim($_POST['membro_area'] ?? '');
        $coeficiente = trim($_POST['membro_coeficiente'] ?? '');
        $celular = trim($_POST['membro_celular'] ?? '');
        $endereco = trim($_POST['membro_endereco'] ?? '');

        if($nome === ''){
            throw new Exception("Nome é obrigatório");
        }

        if($cpf === ''){
            throw new Exception("Documento é obrigatório");
        }

        if($rg === ''){
            throw new Exception("RG é obrigatório");
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new Exception("Email inválido");
        }

        if($dataNascimento === ''){
            throw new Exception("Data de Nascimento é obrigatório");
        }

        if($habilidade === ''){
            throw new Exception("Habilidade é obrigatória");
        }

        $membro = new stdClass();
        $membro->nome = $nome;
        $membro->cpf = $cpf;
        $membro->rg = $rg;
        $membro->email = $email;
        $membro->data_nascimento = $dataNascimento;
        $membro->habilidade = $habilidade;
        $membro->instagram = $instagram;
        $membro->github = $github;
        $membro->whatsapp = $whatsapp;
        $membro->linkedin = $linkedin;
        $membro->admissao = $admissao ?: null;
        $membro->ra = $ra;
        $membro->periodo = $periodo ?: null;
        $membro->cargo = $cargo ?: null;
        $membro->area = $area ?: null;
        $membro->coeficiente = $coeficiente ?: null;
        $membro->celular = $celular ?: null;
        $membro->endereco = $endereco ?: null;

        // obtém o id do membro a ser editado
        $membroid = isset($_POST['membro_id']) ? (int)$_POST['membro_id'] : 0;
        
        if ($membroid <= 0) {
            throw new Exception('ID do membro inválido');
        }

        // chama a função do repositório passando a conexão PDO
        $success = updateMembro($pdo, $membroid, $membro);

        if ($success) {
            $_SESSION['flash_success'] = 'Membro alterado com sucesso.';
            $response['success'] = true;
            $response['message'] = 'Membro alterado com sucesso.';
            header('Location: ../editarMembro.php?id=' . $membroid . '&success=' . urlencode($response['message']));
            exit;
        } else {
            throw new Exception('Falha ao atualizar membro');
        }

    } catch(Exception $e){
        // registra mensagem de erro e redireciona de volta
        $_SESSION['flash_error'] = $e->getMessage();
        header('Location: ../../criarMembro.php?error=' . urlencode($e->getMessage()));
        exit;
    }




?>