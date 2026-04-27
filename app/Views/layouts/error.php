<?php include __DIR__ . '/header.php'; ?>
<section class="card">
    <h1>Error</h1>
    <p><?= htmlspecialchars($message ?? 'Unknown error', ENT_QUOTES, 'UTF-8') ?></p>
</section>
<?php include __DIR__ . '/footer.php'; ?>
