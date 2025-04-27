<?php if (isset($_SESSION['pesan'])): ?>
    <div class="alert <?= $_SESSION['hasil'] ? 'alert-success' : 'alert-error'; ?>">
        <i class="<?= $_SESSION['hasil'] ? 'fas fa-check-circle' : 'fas fa-times-circle'; ?>"></i>
        <?= htmlspecialchars($_SESSION['pesan']); ?>
    </div>
    <?php unset($_SESSION['pesan'], $_SESSION['hasil']); ?>
<?php endif; ?>
