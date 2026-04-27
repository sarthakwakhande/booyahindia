<?php include __DIR__ . '/../layouts/header.php'; ?>
<section class="card">
    <h1>Wallet</h1>
    <p>Available Balance: <strong>₹<?= htmlspecialchars(number_format((float)$balance, 2), ENT_QUOTES, 'UTF-8') ?></strong></p>
    <p>Current UPI: <strong><?= htmlspecialchars($upi, ENT_QUOTES, 'UTF-8') ?></strong></p>

    <form class="form-grid" method="post" action="/wallet/upi">
        <label>Update UPI ID
            <input type="text" name="upi_id" placeholder="name@bank" required>
        </label>
        <button class="btn" type="submit">Save UPI</button>
    </form>
</section>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
