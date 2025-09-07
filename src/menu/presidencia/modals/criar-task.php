<style>
    #modal_criar_task_presidencia {
        background-color: rgba(30, 30, 30, 1);
    }

    .section-modal {
        display: block; /* alterar para none ao final*/
        box-shadow: rgba(0, 0, 0, 0.5);
        color: white;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        max-width: 600px;
        align-items: center;
        max-height: 800px;
        border-radius: 12px;
    }

    .modal-header {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        justify-content: space-between;
        /* border: 1px solid red; */
        padding: 10px;
    }

    .ident {
        display: flex;
        align-items: baseline;
        margin-left: 50px;
        gap: 1.5em;
    }
    .ident h1 h2 {
        font-size: 3em;
    }
    .ident span {
        font-size: 0.8em;
    }

    .btn-c {
        margin-top: 0.5em;
        margin-right: 0.5em;
    }
    .btn-fechar {
        display: flex;
        justify-content: flex-end;
        font-size: 1em;
        margin-top: 0;
        background-color: transparent;
        border: none;
        cursor: pointer;
        color: #999999;
    }
    .btn-fechar:hover {
        color: rgb(245, 77, 77);
    }

    .modal-footer {
        display: flex;
        justify-content: center;
    }

    .linha1, .linha4 {
        display: flex;
        padding: 10px;
        justify-content: space-between;
        text-align: left;
        width: 80%;
        margin-left: 50px;
        padding: 10px 0px;
        /* border: 1px solid red; */
    }
    .linha2 {
        display: flex;
        flex-direction: column;
        width: 80%;
        margin-left: 50px;
        text-align: left;
        padding: 10px 0px;
        /* border: 1px solid blue; */
    }
    .linha2 input {
        padding: 10px;
        border-radius: 5px;
        background-color: #999999;
    }
    .linha2 textarea {
        resize: none;
        border-radius: 5px;
        background-color: #999999;
    }

    label {
        margin-bottom: 4px;
        font-weight: 500;
    }
    .formulario select {
        padding: 10px;
        width: 200px;
        background-color: #999999;
        border-radius: 5px;
        margin-top: 4px;
    }
    #iprazo {
        margin-top: 4px;
        padding: 10px;
        width: 180px;
        border-radius: 5px;
        background-color: #999999;
    }
    .btn {
        display: flex;
        gap: 5rem;
        padding: 2em;
        justify-content: center;
        align-items: center;
    }

    .btn-verm {
        background-color: rgba(255, 0, 0, 0.5);
        font-weight: bold;
        text-decoration: none;
        border-radius: 5px;
        border: none;
        color: white;
        cursor: pointer;
        padding: 1em 4em;
    }
    .btn-azul {
        background-color: rgba(0, 0, 255, 0.5);
        font-weight: bold;
        text-decoration: none;
        border-radius: 5px;
        border: none;
        color: white;
        cursor: pointer;
        padding: 1em 4em;
    }

    .btn-verm:hover {
        background-color: rgba(255, 0, 0, 0.85);
    }
    .btn-azul:hover {
        background-color: rgba(0, 0, 255, 0.85);
    }

</style>

<dialog id="modal_criar_task_presidencia">
    <div class="section-modal">
        <div class="modal-header">
            <div class="ident">
                <h2>Título da Task</h2>
                <span>ID da Task</span>
            </div>
            <button class="close-modal" type="button" data-modal="modal_criar_task_presidencia">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body">
            <form action="enviarTask.php" method="POST">
                <div class="select-group">
                    <label for="id_tipo">Tipo de Task</label> <br>
                    <select name="tipo" id="id_tipo">
                        <option value="" selected disabled>Selecione:</option>
                        <option value="tipo1">Tipo 1</option>
                        <option value="tipo2">Tipo 2</option>
                        <option value="tipo3">Tipo 3</option>
                    </select>
                </div>

                <div class="select-group">
                    <label for="id_projeto">Projeto</label><br>
                    <select name="projeto" id="id_projeto">
                        <option value="" selected disabled>Selecione:</option>
                        <option value="proj1">Projeto 1</option>
                        <option value="proj2">Projeto 2</option>
                        <option value="proj3">Projeto 3</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="id_titulo">Título da Task</label>
                    <input type="text" name="titulo" id="id_titulo" placeholder="Informe o Título:"><br>
                    <label for="id_desc">Descrição</label>
                    <textarea name="desc" id="id_desc" cols="300" rows="10" placeholder="Informe a Descrição"></textarea>
                </div>

                <div class="select-group">
                    <label for="id_diretor">Diretor Responsável</label>
                    <select name="diretor" id="id_diretor">
                        <option value="" selected disabled>Selecione:</option>
                        <option value="dir1">Diretor 1</option>
                        <option value="dir2">Diretor 2</option>
                        <option value="dir3">Diretor 3</option>
                    </select>
                </div>

                <div class="select-group">
                    <label for="id_status">Status</label>
                    <select name="status" id="id_status">
                        <option value="" selected disabled>Selecione:</option>
                        <option value="stat1">Status 1</option>
                        <option value="stat2">Status 2</option>
                        <option value="stat3">Status 3</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="id_prazo">Prazo</label>
                    <input type="date" name="prazo" id="id_prazo">
                </div>

                <div class="select-group">
                    <label for="id_categoria">Categoria</label>
                    <select name="cat" id="id_categoria">
                        <option value="" selected disabled>Selecione:</option>
                        <option value="cat1">Categoria 1</option>
                        <option value="cat2">Categoria 2</option>
                        <option value="cat3">Categoria 3</option>
                    </select>
                </div>
                
                <div class="modal-footer">
                    <div class="btn">
                        <button type="submit" class="btn-azul">Atualizar</button>
                        <button type="reset" class="btn-verm">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</dialog>