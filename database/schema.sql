CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) UNIQUE NULL,
    phone VARCHAR(20) UNIQUE NULL,
    google_id VARCHAR(120) UNIQUE NULL,
    password_hash VARCHAR(255) NULL,
    upi_id VARCHAR(120) NULL,
    role ENUM('user','admin') NOT NULL DEFAULT 'user',
    status ENUM('active','banned') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_role_status (role, status)
);

CREATE TABLE wallets (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    balance DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    locked_balance DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_wallet_user (user_id),
    CONSTRAINT fk_wallet_user FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE tournaments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(190) NOT NULL,
    mode ENUM('1v1','4v4_clash_squad','battle_royale') NOT NULL,
    entry_fee DECIMAL(10,2) NOT NULL,
    prize_pool DECIMAL(12,2) NOT NULL,
    max_slots INT NOT NULL,
    joined_slots INT NOT NULL DEFAULT 0,
    start_at DATETIME NOT NULL,
    join_locked TINYINT(1) NOT NULL DEFAULT 0,
    room_id VARCHAR(100) NULL,
    room_password VARCHAR(100) NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tournament_start (start_at),
    CONSTRAINT fk_tournament_admin FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE tournament_entries (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    tournament_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_entry (tournament_id, user_id),
    INDEX idx_entry_user (user_id),
    CONSTRAINT fk_entry_tournament FOREIGN KEY (tournament_id) REFERENCES tournaments(id),
    CONSTRAINT fk_entry_user FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE transactions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    type ENUM('deposit','withdrawal','entry_fee','prize','admin_adjustment','redeem_purchase') NOT NULL,
    status ENUM('pending','success','failed','approved','rejected') NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    reference_id VARCHAR(120) NULL,
    metadata JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tx_user_type (user_id, type),
    INDEX idx_tx_status (status),
    CONSTRAINT fk_tx_user FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE withdrawals (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    upi_id VARCHAR(120) NOT NULL,
    status ENUM('pending','approved','rejected','paid') NOT NULL DEFAULT 'pending',
    admin_note VARCHAR(255) NULL,
    reviewed_by BIGINT UNSIGNED NULL,
    reviewed_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_withdrawal_status_created (status, created_at),
    CONSTRAINT fk_withdraw_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_withdraw_admin FOREIGN KEY (reviewed_by) REFERENCES users(id)
);

CREATE TABLE redeem_code_batches (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(190) NOT NULL,
    custom_price DECIMAL(10,2) NOT NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_batch_admin FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE redeem_codes (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    batch_id BIGINT UNSIGNED NOT NULL,
    code_value VARCHAR(190) UNIQUE NOT NULL,
    sold_to BIGINT UNSIGNED NULL,
    sold_at DATETIME NULL,
    revealed_at DATETIME NULL,
    is_visible_once TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_code_sold_to (sold_to),
    CONSTRAINT fk_code_batch FOREIGN KEY (batch_id) REFERENCES redeem_code_batches(id),
    CONSTRAINT fk_code_user FOREIGN KEY (sold_to) REFERENCES users(id)
);

CREATE TABLE admin_activity_logs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    admin_id BIGINT UNSIGNED NOT NULL,
    action VARCHAR(120) NOT NULL,
    target_type VARCHAR(60) NOT NULL,
    target_id BIGINT UNSIGNED NULL,
    payload JSON NULL,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_admin_log_admin_created (admin_id, created_at),
    CONSTRAINT fk_admin_log_user FOREIGN KEY (admin_id) REFERENCES users(id)
);

CREATE VIEW leaderboard AS
SELECT
    u.id,
    u.name,
    SUM(CASE WHEN t.type = 'prize' THEN t.amount ELSE 0 END) AS highest_earnings,
    SUM(CASE WHEN t.type = 'deposit' AND t.status = 'success' THEN t.amount ELSE 0 END) AS total_deposits,
    SUM(CASE WHEN t.type = 'withdrawal' AND t.status IN ('approved','paid') THEN t.amount ELSE 0 END) AS total_withdrawals
FROM users u
LEFT JOIN transactions t ON t.user_id = u.id
GROUP BY u.id, u.name;
