document.addEventListener('DOMContentLoaded', () => {

    const openButtons = document.querySelectorAll('.open-modal');
    const closeButtons = document.querySelectorAll('.close-modal');

    openButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            
            if (!modal) return;
            if (button.classList.contains('button_editar')) {
                const dataset = button.dataset;
                const modalForm = modal.querySelector('form');
                if (modalForm) {
                    modalForm.querySelector('#id_titulo').value = dataset.titulo || '';
                    modalForm.querySelector('#id_desc').value = dataset.descricao || '';
                    modalForm.querySelector('#id_prazo').value = dataset.prazo || '';
                    const hiddenIdField = modalForm.querySelector('#task_id_hidden');
                    if (hiddenIdField) {
                        hiddenIdField.value = dataset.taskId || '';
                    }
                }
            }
            modal.showModal(); 
        });
    });

    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modal = button.closest('dialog'); 
            if (modal) {
                modal.close(); 
            }
        });
    });

    const dialogs = document.querySelectorAll('dialog');
    dialogs.forEach(dialog => {
        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) {
                dialog.close();
            }
        });
    });

});

function loadMoreTasks() {
        const tableBody = document.getElementById('tasksTableBody');
        const btnVerMais = document.getElementById('btnVerMaisTasks');
        const isExpanded = tableBody.classList.contains('expanded');
        
        if (!isExpanded) {
            tableBody.classList.add('expanded');
            btnVerMais.textContent = 'Ver menos';
        } else {
            tableBody.classList.remove('expanded');
            btnVerMais.textContent = 'Ver mais';
            
            document.getElementById('tasks_presidencia').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }
    }
