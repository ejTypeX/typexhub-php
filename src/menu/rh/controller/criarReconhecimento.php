<?php
    ob_start(); // ob_start para evitar problemas com headers

    session_start(); // Iniiciar sessão
    require_once __DIR__ . '/../../../include/conexao.php';
    require_once __DIR__ . '/../repository/reconhecimentoRepository.php';

    

    try{
        $member_id = trim($_POST['member_id'] ?? '');
        $director_id = trim($_POST['director_id'] ?? '');
        $feito = trim($_POST['feito'] ?? '');
        $reconhecimento = trim($_POST['reconhecimento'] ?? '');
        $dataReconhecimento = trim($_POST['data_reconhecimento'] ?? '');

        echo''. $member_id .''. $director_id ;

        if(!$member_id){
            throw new Exception("Membro é obrigatório");
        }

        //if(!$director_id){
          //  throw new Exception("Diretor é obrigatório");
        //}

        if(!$feito || $feito === ''){
            throw new Exception("Feito é obrigatório");
        }


        if(!$dataReconhecimento || $dataReconhecimento === ''){
            throw new Exception("A data é obrigatório");
        }

        if(!$reconhecimento || $reconhecimento === ''){
            throw new Exception("Habilidade é obrigatória");
        }

        $ObjReconhecimento = new stdClass();
        $ObjReconhecimento->data_reconhecimento = $dataReconhecimento;
        $ObjReconhecimento->diretor_id = null;
        $ObjReconhecimento->feito = $feito;
        $ObjReconhecimento->membro_id = $member_id;
        $ObjReconhecimento->reconhecimento = $reconhecimento;
        


        // chama a função do repositório passando a conexão PDO
        $success = insertNewreconhecimento($pdo, $ObjReconhecimento);

        if ($success) {
            $_SESSION['flash_success'] = 'Reconhecimento criado com sucesso.';
            $response['success'] = true;
            $response['message'] = 'Reconhecimento criado com sucesso.';
            header('Location: ../criarReconhecimento.php?success=' . urlencode($response['message']));
            exit;
        } else {
            throw new Exception('Falha ao inserir reconhecimento');
        }

    } catch(Exception $e){
        // registra mensagem de erro e redireciona de volta
        $_SESSION['flash_error'] = $e->getMessage();
        header('Location: ../criarReconhecimento.php?error=' . urlencode($e->getMessage()));
        exit;
    }




?>