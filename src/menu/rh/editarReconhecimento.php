<?php 

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

include "../../include/header.php";
include "../../include/conexao.php";
include "./repository/reconhecimentoRepository.php";

$rec_id = $_GET["id"];

$reconhecimento = findOneById($pdo, $rec_id);

$diretoria = selectAllDirector($pdo);
$membro = selectAllMember($pdo)

?>

<style>
    .rh-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 2rem;
        min-height: calc(100vh - 100px);
    }

    .alert {
        width: 100%;
        max-width: 600px;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        font-family: 'Inter', sans-serif;
        font-weight: 500;
    }

    .alert-success {
        background-color: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
    }

    .alert-error {
        background-color: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }

    .page-navigation {
        width: 100%;
        max-width: 600px;
        display: flex;
        justify-content: flex-end;
        margin-bottom: 1rem;
    }

    .form-wrapper {
        background-color: var(--bg-container-color);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px;
        margin-top: 2rem;
    }

    .form-title {
        font-family: 'Inter', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: #FFFFFF;
        margin-bottom: 2rem;
        text-align: center;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        font-weight: 500;
        color: #D9D9D9;
        margin-bottom: 0.5rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 12px 16px;
        border: 2px solid transparent;
        border-radius: 8px;
        background-color: #1E1E1E;
        color: #D9D9D9;
        font-size: 1rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #D9D9D9;
        background-color: #2A2A2A;
    }

    .form-group select {
        cursor: pointer;
    }

    .form-group select option {
        background-color: #1E1E1E;
        color: #D9D9D9;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .checkbox-group input[type="checkbox"] {
        width: auto;
        margin: 0;
        accent-color: #D9D9D9;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 120px;
    }

    .btn-primary {
        background-color: #393D46;
        color: white;
    }

    .btn-primary:hover {
        background-color: #006d71;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background-color: transparent;
        color: #D9D9D9;
        border: 2px solid #D9D9D9;
    }

    .btn-secondary:hover {
        background-color: #D9D9D9;
        color: #1E1E1E;
    }

    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }

    .file-input-wrapper input[type=file] {
        position: absolute;
        left: -9999px;
    }

    .file-input-label {
        padding: 12px 16px;
        border: 2px dashed #D9D9D9;
        border-radius: 8px;
        background-color: #1E1E1E;
        color: #D9D9D9;
        cursor: pointer;
        text-align: center;
        transition: all 0.3s ease;
        display: block;
    }

    .file-input-label:hover {
        border-color: #FFFFFF;
        background-color: #2A2A2A;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-wrapper {
            margin: 1rem;
            padding: 1.5rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
    }
</style>

<div class="rh-container">
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_GET['success']) ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>
    
    <div class="page-navigation">
        <a href="listaReconhecimento.php" class="btn btn-secondary">Ver reconhecimentos Cadastrados</a>
    </div>
    
    <div class="form-wrapper">
        <h1 class="form-title">Cadastrar Novo reconhecimento</h1>
        
        <form action="./controller/editarReconhecimento.php" method="post" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-group">
                    <label for="member_id">Memebro *</label>
                    <select name="member_id" id="member_id">
                        <?php foreach($membro as $mem): ?>
                            <option
                                <?= $reconhecimento['membro_id'] == $mem['id'] ? 'selected' : '' ?> 
                                value="<?= $mem['id'] ?>"><?= $mem['nome'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="membro_cpf">Diretor*</label>
                    <select name="director_id" id="derector_id">
                        <?php foreach($diretoria as $dir): ?>
                            <option 
                                <?= $reconhecimento['diretor_id'] == $dir['dir_id'] ? 'selected' : '' ?>
                                value="<?= $dir['dir_id'] ?>"><?= $dir['dir_nome'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>             
                
                <div class="form-group">
                    <label for="feito">Feito*</label>
                    <textarea id="feito" name="feito" required ><?= htmlspecialchars($reconhecimento['feito']) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="reconhecimento">Reconhecimento*</label>
                    <textarea id="reconhecimento" name="reconhecimento" required><?= htmlspecialchars($reconhecimento['reconhecimento']) ?></textarea>
                </div>
            
                <div class="form-group">
                    <label for="data_reconhecimento">Data *</label>
                    <input type="date" id="data_reconhecimento" name="data_reconhecimento" required value="<?= htmlspecialchars($reconhecimento['data_reconhecimento']) ?>">
                </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Alterar</button>
            </div>
            <input type="hidden" id="id" name="id" value="<?= $rec_id ?>">
        </form>
    </div>
</div>

<script>

// Atualizar label do arquivo selecionado
document.getElementById('usr_foto').addEventListener('change', function() {
    const label = document.querySelector('.file-input-label');
    const fileName = this.files[0]?.name;
    
    if (fileName) {
        label.textContent = `Arquivo selecionado: ${fileName}`;
    } else {
        label.textContent = 'Clique para selecionar uma imagem';
    }
});

// Máscara para documento (CPF)
document.getElementById('membro_cpf').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    
    if (value.length <= 11) {
        // CPF
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    } else {
        // CNPJ
        value = value.replace(/^(\d{2})(\d)/, '$1.$2');
        value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
        value = value.replace(/(\d{4})(\d)/, '$1-$2');
    }
    
    this.value = value;
});
</script>

</body>
</html>