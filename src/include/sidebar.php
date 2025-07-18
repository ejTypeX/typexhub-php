<?php

function getCurrentPage() {
    $uri = $_SERVER['REQUEST_URI'];
    $path = trim(parse_url($uri, PHP_URL_PATH), '/');
    
    $path = strtolower($path);
    
    return $path;
}

$currentPage = getCurrentPage();
?>

<aside class="sidebar" id="sidebar">
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        ◀
    </button>
    
    <div class="sidebar-header">
        <h2>TypeX Hub</h2>
        <span class="sidebar-subtitle">Sistema para EJs</span>
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item" data-tooltip="Dashboard">
                <a href="/dashboard" class="nav-link <?php echo ($currentPage === 'dashboard') ? 'active' : ''; ?>">
                    <span class="nav-icon">📊</span>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item" data-tooltip="Presidência">
                <a href="/presidencia" class="nav-link <?php echo ($currentPage === 'presidencia') ? 'active' : ''; ?>">
                    <span class="nav-icon">👑</span>
                    <span class="nav-text">Presidência</span>
                </a>
            </li>
            
            <li class="nav-item" data-tooltip="Finanças">
                <a href="/financas" class="nav-link <?php echo ($currentPage === 'financas') ? 'active' : ''; ?>">
                    <span class="nav-icon">💰</span>
                    <span class="nav-text">Finanças</span>
                </a>
            </li>
            
            <li class="nav-item" data-tooltip="Recursos Humanos">
                <a href="/rh" class="nav-link <?php echo ($currentPage === 'rh') ? 'active' : ''; ?>">
                    <span class="nav-icon">👥</span>
                    <span class="nav-text">Recursos Humanos</span>
                </a>
            </li>
            
            <li class="nav-item" data-tooltip="Projetos">
                <a href="/projetos" class="nav-link <?php echo ($currentPage === 'projetos') ? 'active' : ''; ?>">
                    <span class="nav-icon">📋</span>
                    <span class="nav-text">Projetos</span>
                </a>
            </li>
            
            <li class="nav-item" data-tooltip="Marketing">
                <a href="/marketing" class="nav-link <?php echo ($currentPage === 'marketing') ? 'active' : ''; ?>">
                    <span class="nav-icon">📢</span>
                    <span class="nav-text">Marketing</span>
                </a>
            </li>
            
            <li class="nav-item" data-tooltip="Infraestrutura">
                <a href="/infra" class="nav-link <?php echo ($currentPage === 'infra') ? 'active' : ''; ?>">
                    <span class="nav-icon">🔧</span>
                    <span class="nav-text">Infraestrutura</span>
                </a>
            </li>
            
            <li class="nav-item" data-tooltip="Usuários">
                <a href="/user" class="nav-link <?php echo ($currentPage === 'user') ? 'active' : ''; ?>">
                    <span class="nav-icon">👤</span>
                    <span class="nav-text">Usuários</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <a href="/auth/logout" class="logout-btn">
            <span class="nav-icon">🚪</span>
            <span class="nav-text">Sair</span>
        </a>
    </div>
</aside>
