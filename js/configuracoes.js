document.addEventListener('DOMContentLoaded', function() {
    const configBtn = document.getElementById('configBtn');
    const configMenu = document.getElementById('configMenu');
    const deleteAccountBtn = document.getElementById('deleteAccountBtn');
    const deleteModal = document.getElementById('deleteModal');
    const cancelDelete = document.getElementById('cancelDelete');
    const confirmDelete = document.getElementById('confirmDelete');

    if (configBtn && configMenu) {
        configBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            configMenu.style.display = configMenu.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', function() {
            configMenu.style.display = 'none';
        });

        configMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    if (deleteAccountBtn && deleteModal) {
        deleteAccountBtn.addEventListener('click', function() {
            configMenu.style.display = 'none';
            deleteModal.style.display = 'flex';
        });
    }

    if (cancelDelete && deleteModal) {
        cancelDelete.addEventListener('click', function() {
            deleteModal.style.display = 'none';
        });
    }

    if (confirmDelete) {
        confirmDelete.addEventListener('click', function() {
            window.location.href = 'excluir-conta.php';
        });
    }

    if (deleteModal) {
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                deleteModal.style.display = 'none';
            }
        });
    }
});