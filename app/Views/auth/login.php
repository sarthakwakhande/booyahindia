<?php include __DIR__ . '/../layouts/header.php'; ?>
<section class="card glass">
    <h1>Login</h1>
    <p>Use Google login or mobile OTP authentication.</p>
    <div class="actions">
        <a class="btn" href="/auth/google/redirect">Continue with Google</a>
    </div>
    <form method="post" action="/auth/otp/send" class="form-grid">
        <label>Mobile Number
            <input name="phone" placeholder="10-digit mobile" required>
        </label>
        <button class="btn" type="submit">Send OTP</button>
    </form>
</section>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
