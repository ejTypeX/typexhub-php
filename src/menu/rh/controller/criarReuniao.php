<?php
    ob_start(); // ob_start para evitar problemas com headers

    session_start(); // Iniciar sessão
    require_once __DIR__ . '/../../../include/conexao.php';
    require_once __DIR__ . '/../repository/reuniaoRepository.php';

    // Só processa quando for POST (evita avisos de array indefinido quando a página é acessada via GET)
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        // redireciona de volta para o formulário se não for POST
        header('Location: ../../criarReuniao.php');
        exit;
    }

    try{
        // usar null coalescing para evitar avisos
        $motivo = trim($_POST['reuniao_motivo'] ?? '');
        $dataReuniao = trim($_POST['reuniao_data'] ?? '');
        $localReuniao = trim($_POST['reuniao_local'] ?? '');
        $horas = trim($_POST['reuniao_horas'] ?? '');
        $descricao = trim($_POST['reuniao_descricao'] ?? '');
        $statusReuniao = trim($_POST['reuniao_status'] ?? 'Programado');

        if($motivo === ''){
            throw new Exception("Motivo é obrigatório");
        }

        if($dataReuniao === ''){
            throw new Exception("Data da reunião é obrigatória");
        }

        if($localReuniao === ''){
            throw new Exception("Local da reunião é obrigatório");
        }

        if($horas === ''){
            throw new Exception("Horário é obrigatório");
        }

        $reuniao = new stdClass();
        $reuniao->motivo = $motivo;
        $reuniao->data_reuniao = $dataReuniao;
        $reuniao->local_reuniao = $localReuniao;
        $reuniao->horas = $horas;
        $reuniao->descricao = $descricao;
        $reuniao->status_reuniao = $statusReuniao;

        // chama a função do repositório passando a conexão PDO
        $success = insertNewReuniao($pdo, $reuniao);

        if ($success) {
            $_SESSION['flash_success'] = 'Reunião criada com sucesso.';
            $response['success'] = true;
            $response['message'] = 'Reunião criada com sucesso.';
            header('Location: ../criarReuniao.php?success=' . urlencode($response['message']));
            exit;
        } else {
            throw new Exception('Falha ao inserir reunião');
        }

    } catch(Exception $e){
        // registra mensagem de erro e redireciona de volta
        $_SESSION['flash_error'] = $e->getMessage();
        header('Location: ../../criarReuniao.php?error=' . urlencode($e->getMessage()));
        exit;
    }

?>
