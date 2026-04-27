<?php include __DIR__ . '/../layouts/header.php'; ?>
<section class="card glass">
    <h1>Admin Panel</h1>
    <div class="grid four">
        <div><h3>Users</h3><p><?= (int)$metrics['users'] ?></p></div>
        <div><h3>Live Matches</h3><p><?= (int)$metrics['live_matches'] ?></p></div>
        <div><h3>Pending Withdrawals</h3><p><?= (int)$metrics['pending_withdrawals'] ?></p></div>
        <div><h3>Redeem Codes</h3><p><?= (int)$metrics['redeem_codes_available'] ?></p></div>
    </div>

    <ul>
        <li>User management: view, edit, ban, reset password, adjust wallet.</li>
        <li>Role control with audit logs (User ↔ Admin).</li>
        <li>Tournament lifecycle management and join lock controls.</li>
        <li>Transaction monitor: deposits, withdrawals, purchases.</li>
    </ul>
</section>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
