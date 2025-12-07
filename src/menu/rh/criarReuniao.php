<?php 

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

include "../../include/header.php";
include "../../include/conexao.php";
include "./repository/reuniaoRepository.php";

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
        <a href="listaReuniao.php" class="btn btn-secondary">Ver reunioes Cadastradas</a>
    </div>
    
    <div class="form-wrapper">
        <h1 class="form-title">Cadastrar Nova reuniao</h1>
        
        <form action="./controller/criarReuniao.php" method="post" enctype="multipart/form-data">
            <div class="form-grid">            
                
                <div class="form-group">
                    <label for="reuniao_data">Motivo*</label>
                    <input type="text" id="reuniao_data" name="reuniao_motivo" required>
                </div>

                <div class="form-group">
                    <label for="reuniao_data">Data*</label>
                    <input type="date" id="reuniao_data" name="reuniao_data" required></textarea>
                </div>
            
                <div class="form-group">
                    <label for="reuniao_local">Local Reunião*</label>
                    <input type="text" id="reuniao_local" name="reuniao_local" required>
                </div>

                <div class="form-group">
                    <label for="reuniao_horas">Hora reunião*</label>
                    <input type="text" id="reuniao_horas" name="reuniao_horas" required>
                </div>

                <div class="form-group">
                    <label for="reuniao_descricao">Local Reunião*</label>
                    <textarea id="reuniao_descricao" name="reuniao_descricao" required></textarea>
                </div>

                <div class="form-group">
                    <label for="reuniao_status">Status da Reunião*</label>
                    <select id="reuniao_status" name="reuniao_status" required>
                        <option value="Programado">Programado</option>
                        <option value="Concluído">Concluído</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>
        </form>
    </div>
</div>


</body>
</html>