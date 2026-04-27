<?php include __DIR__ . '/../layouts/header.php'; ?>
<section class="card glass">
    <h1>Leaderboard</h1>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Player</th>
                    <th>Highest Earnings</th>
                    <th>Total Deposits</th>
                    <th>Total Withdrawals</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars((string)$row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>₹<?= htmlspecialchars((string)$row['highest_earnings'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>₹<?= htmlspecialchars((string)$row['total_deposits'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>₹<?= htmlspecialchars((string)$row['total_withdrawals'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
