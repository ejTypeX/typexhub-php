function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    const toggleBtn = document.querySelector('.sidebar-toggle');
    
    sidebar.classList.toggle('collapsed');
    
    if (sidebar.classList.contains('collapsed')) {
        toggleBtn.innerHTML = '▶';
        mainContent.style.marginLeft = '70px';
        
        localStorage.setItem('sidebarCollapsed', 'true');
    } else {
        toggleBtn.innerHTML = '◀';
        mainContent.style.marginLeft = '280px';
        
        localStorage.setItem('sidebarCollapsed', 'false');
    }
}

function restoreSidebarState() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    const toggleBtn = document.querySelector('.sidebar-toggle');
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    
    if (isCollapsed) {
        sidebar.classList.add('collapsed');
        toggleBtn.innerHTML = '▶';
        mainContent.style.marginLeft = '70px';
    }
}

function handleResize() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (window.innerWidth <= 768) {
        mainContent.style.marginLeft = '0';
    } else if (sidebar.classList.contains('collapsed')) {
        mainContent.style.marginLeft = '70px';
    } else {
        mainContent.style.marginLeft = '280px';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    restoreSidebarState();
});

window.addEventListener('resize', handleResize);

document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
