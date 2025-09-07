<style>
    .section-opcoes {
        background-color: #454545;
        border-radius: 12px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        color: white;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;

    }

    .modal-header h2 {
        margin: 0;
        font-size: 1.5rem;
    }

    .close-btn {
        background: none;
        border: none;
        color: #bbb;
        font-size: 2rem;
        cursor: pointer;
        line-height: 1;
    }

    .close-btn:hover {
        color: rgb(245, 77, 77);
    }


    .body {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        padding: 1rem 1.5rem;
    }


    .body button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 1rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: bold;
        color: white;
        cursor: pointer;
        gap: 1.5rem;
    }


    .edit-btn {
        background-color: #00a89d;
    }
    .edit-btn:hover{
        background-color: #00a89da6;
        
    }

    .delete-btn {
        background-color: #cf2c34;
    }
    .delete-btn:hover {
        background-color: #cf2c34a6;
    }


    .modal-footer {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
        
    }

    .separador {
        width: 100%;
        border: none;
        border-top: 1px solid #555;
        margin-bottom: 1rem;
    }


    .cancel-btn {
        width: 80%;
        padding: 1rem;
        background-color: #6c6c6c;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: bold;
        color: white;
        cursor: pointer;
    }

    .cancel-btn:hover {
        background-color: #555;
    }
</style>

<dialog id="opcoes_task_presidencia"> 
    <div id="opcoes" class="section-opcoes">
        <div class="modal-header">
            <h2>Opções</h2>
            <button class="close-modal" type="button" data-modal="modal_opcoes_task_presidencia">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    
        <div class="modal-body">
            <button class="edit-btn">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Editar</span>
            </button>
            <button class="delete-btn">
                <i class="fa-solid fa-trash-can"></i>
                <span>Excluir</span>
            </button>
        </div>
    
        <div class="modal-footer">
            <hr class="separador">
            <button class="cancel-btn">Cancelar</button>
        </div>
    </div>
</dialog>