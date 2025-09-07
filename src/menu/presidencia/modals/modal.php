<style>
    #modal_criar_task_presidencia{
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

    .ident{
        display: flex;
        align-items: baseline;
        margin-left: 50px;
        gap: 1.5em;

    }
    .ident h1 h2{
        font-size: 3em;
    }
    .ident span{
        font-size: 0.8em; ;
    }

    .btn-c{
        margin-top: 0.5em;
        margin-right: 0.5em;
    }
    .btn-fechar{
        display: flex;
        justify-content: flex-end;
        font-size: 1em;
        margin-top: 0; 
        background-color: transparent;
        border: none;
        cursor: pointer;
        color: #999999;
    }
    .btn-fechar:hover{
        color: rgb(245, 77, 77);
    }

    .modal-footer{   
        display: flex;
        justify-content: center;

    }

    .linha1, .linha4{
        display: flex;
        padding: 10px;
        justify-content: space-between;
        text-align: left;
        width: 80%;
        margin-left: 50px;
        padding: 10px 0px;
        /* border: 1px solid red; */
    }
    .linha2{
        display: flex;
        flex-direction: column;
        width: 80%;
        margin-left: 50px;
        text-align: left;
        padding: 10px 0px;
    /* border: 1px solid blue; */

    }
    .linha2 input{
        padding: 10px;
        border-radius: 5px;
        background-color: #999999;
    }
    .linha2 textarea{
        resize: none;
        border-radius: 5px;
        background-color: #999999;
    }

    label{
        margin-bottom: 4px;
        font-weight: 500;
    }
    .formulario select  {
        padding: 10px;
        width: 200px;
        background-color: #999999;
        border-radius: 5px;
        margin-top: 4px;
        
    }
    #iprazo{
        margin-top: 4px;
        padding: 10px;
        width: 180px;
        border-radius: 5px;
        background-color: #999999;
    }
    .btn{
        display: flex;
        gap: 5rem;
        padding: 2em;
        justify-content: center;
        align-items: center;

    }

    .btn-verm{
        background-color: rgba(255, 0, 0, 0.5);
        font-weight: bold;
        text-decoration: none;
        border-radius: 5px;
        border: none;  
        color: white;
        cursor: pointer;
        padding: 1em 4em;
    }
    .btn-azul{
        background-color: rgba(0, 0, 255, 0.5);
        font-weight: bold;
        text-decoration: none;
        border-radius: 5px;
        border: none;  
        color: white;
        cursor: pointer;
        padding: 1em 4em;
    }

    .btn-verm:hover{
        background-color: rgba(255, 0, 0, 0.85);
    }
    .btn-azul:hover{
        background-color: rgba(0, 0, 255, 0.85);
    }


</style>
  
    <div id="modal_criar_task_presidencia" class="section-modal">
            <!--Ícone fechar-->
        <div class="modal-header">
            <!-- header do modal -->
            <div class="ident">
                <h2>Título da Task</h2>
                <span>ID da Task</span>
            </div>
            <div class="btn-c">
                <button class="btn-fechar">&#10006</button>
            </div>


        </div>
        <!-- Linha 1 task -->

            <div class="linha1">
                <div class="formulario">
                    <form action="">
                        <label for="itipo">Tipo de Task</label> <br>
                        <select name="tipo" id="itipo">
                            <option value="" selected disabled>Selecione:</option>
                            <option value="tipo1">Tipo 1</option>
                            <option value="tipo2">Tipo 2</option>
                            <option value="tipo3">Tipo 3</option>
                        </select>
                    </form>
                </div>


                <div class="formulario">
                    <form action="">
                        <label for="iproj">Projeto</label><br>
                        <select name="projeto" id="iproj">
                            <option value="" selected disabled>Selecione:</option>
                            <option value="proj1">Projeto 1</option>
                            <option value="proj2">Projeto 2</option>
                            <option value="proj3">Projeto 3</option>
                        </select>
                    </form>
                </div>
            </div>
            <br>

                <!-- Linha 2 e 3 -->
            <div class="linha2">
                    <label for="ititulo">Título da Task</label>
                    <input type="text" name="titulo" id="ititulo" placeholder="Informe o Título:"><br>
                    <label for="idesc">Descrição</label>
                    <textarea name="desc" id="idesc" cols="300" rows="10" placeholder="Informe a Descrição"></textarea>
            </div>
            <br>

                <!-- Linha 4 e 5 -->
        <div class="linha4">
            <div class="col1-1">
                <div class="formulario">
                    <form action="">
                        <label for="idiretor">Diretor Responsável</label><br>
                        <select name="diretor" id="idiretor">
                            <option value="" selected disabled>Selecione:</option>
                            <option value="dir1">Diretor 1</option>
                            <option value="dir2">Diretor 2</option>
                            <option value="dir3">Diretor 3</option>
                        </select>
                    </form>
                </div>
                <br>
                <div class="formulario">
                    <form>
                        <label for="istatus">Status</label><br>
                        <select name="status" id="istatus">
                            <option value="" selected disabled>Selecione:</option>
                            <option value="stat1">Status 1</option>
                            <option value="stat2">Status 2</option>
                            <option value="stat3">Status 3</option>
                        </select>
                    </form>
                </div>

            </div>
                <div class="col1-2">
                    <div class="formulario">
                        <form action="">
                            <label for="iprazo">Prazo</label><br>
                            <input type="date" name="prazo" id="iprazo" ><br>
                        </form>
                    </div>
                    <br>
                    <div class="formulario">
                        <form action="">
                            <label for="icat">Categoria</label><br>
                            <select name="cat" id="icat">
                                <option value="" selected disabled>Selecione:</option>
                                <option value="cat1">Categoria 1</option>
                                <option value="cat2">Categoria 2</option>
                                <option value="cat3">Categoria 3</option>
                            </select>
                        </form><br>
                    </div>
                </div>
        </div>



        <!-- footer do modal -->
        <div class="modal-footer">
            <div class="btn">
                <button type="submit" class="btn-azul">Atualizar</button>
                <button type="reset" class="btn-verm">Cancelar</button>
            </div>
        </div>

    </div>



    
</body>
</html>