<?php
if (!defined('rinder')) { die('Direct access not permitted'); }

include_once __DIR__ . "/shared/header.php";
include_once __DIR__ . "/shared/navbar.php";
?>

<main class="container">
    <h1>👥 Gebruikers Beheren</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="admin-nav">
        <a href="/EduLearn/public/admin" class="btn">⬅️ Terug naar Dashboard</a>
    </div>

    <div class="users-table">
        <h2>Alle Gebruikers</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Naam</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Aangemaakt</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <span class="role-badge role-<?= htmlspecialchars($user['role']) ?>">
                            <?= ucfirst(htmlspecialchars($user['role'])) ?>
                        </span>
                    </td>
                    <td><?= isset($user['created_at']) ? date('d-m-Y', strtotime($user['created_at'])) : 'Onbekend' ?></td>
                    <td class="actions">
                        <?php if ($user['role'] !== 'admin' && $user['id'] != $_SESSION['user']['id']): ?>
                            <form method="POST" action="/EduLearn/public/admin/promote" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn-small btn-promote" onclick="return confirm('Weet je zeker dat je deze gebruiker wilt promoveren tot administrator?')">
                                    ⬆️ Promoveer tot Admin
                                </button>
                            </form>
                        <?php endif; ?>
                        
                        <?php if ($user['id'] != $_SESSION['user']['id']): ?>
                            <form method="POST" action="/EduLearn/public/admin/delete-user" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn-small btn-delete" onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt.')">
                                    🗑️ Verwijder
                                </button>
                            </form>
                        <?php else: ?>
                            <span class="current-user">👤 Jezelf</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<style>
.admin-nav {
    margin-bottom: 20px;
}

.users-table {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f8f9fa;
    font-weight: bold;
    color: #495057;
}

tr:hover {
    background-color: #f8f9fa;
}

.role-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: bold;
    text-transform: uppercase;
}

.role-student {
    background-color: #e3f2fd;
    color: #1976d2;
}

.role-teacher {
    background-color: #f3e5f5;
    color: #7b1fa2;
}

.role-admin {
    background-color: #ffebee;
    color: #c62828;
}

.actions {
    white-space: nowrap;
}

.btn-small {
    padding: 6px 12px;
    font-size: 0.85em;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin: 2px;
    text-decoration: none;
    display: inline-block;
}

.btn-promote {
    background-color: #28a745;
    color: white;
}

.btn-promote:hover {
    background-color: #218838;
}

.btn-delete {
    background-color: #dc3545;
    color: white;
}

.btn-delete:hover {
    background-color: #c82333;
}

.current-user {
    color: #6c757d;
    font-style: italic;
}

.success {
    background: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #c3e6cb;
    margin-bottom: 20px;
}

.error {
    background: #f8d7da;
    color: #721c24;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #f5c6cb;
    margin-bottom: 20px;
}
</style>

<?php include_once __DIR__ . "/shared/footer.php"; ?>
