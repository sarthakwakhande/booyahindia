<?php include __DIR__ . '/../layouts/header.php'; ?>
<section class="card">
    <h1>Upcoming Matches</h1>
    <div class="table-wrap">
        <table>
            <thead>
            <tr><th>Mode</th><th>Entry Fee</th><th>Prize Pool</th><th>Slots</th><th>Starts</th><th>Countdown</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($matches as $match): ?>
                <tr data-start="<?= htmlspecialchars($match['start_at'], ENT_QUOTES, 'UTF-8') ?>">
                    <td><?= htmlspecialchars($match['mode'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>₹<?= htmlspecialchars((string)$match['entry_fee'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>₹<?= htmlspecialchars((string)$match['prize_pool'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (int)$match['joined_slots'] ?>/<?= (int)$match['max_slots'] ?></td>
                    <td><?= htmlspecialchars($match['start_at'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="countdown">--:--:--</td>
                    <td>
                        <form method="post" action="/tournaments/join" class="inline-form">
                            <input type="hidden" name="tournament_id" value="<?= (int)$match['id'] ?>">
                            <button class="btn" type="submit">Join</button>
                        </form>
                        <form method="post" action="/tournaments/room" class="inline-form">
                            <input type="hidden" name="tournament_id" value="<?= (int)$match['id'] ?>">
                            <button class="btn btn-secondary" type="submit">Room</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
