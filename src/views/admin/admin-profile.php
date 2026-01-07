<!DOCTYPE html>
<?php include __DIR__ . "/../layouts/header.php"; ?>
<?php include __DIR__ . "/../layouts/sidebar.php"; ?>


<html>
<head>
    <title>Admin Profile</title>
    <link rel="stylesheet" href="./css/profile.css">
</head>
<body>

<div class="profile-container">

    <h2>Admin Profile</h2>

    
    <div class="profile-card">
        <h3>Profile Information</h3>

        <p><strong>Name:</strong> <?= htmlspecialchars($admin['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($admin['email']) ?></p>
        <div class="action-row">
            <a href="index.php?action=admin-profile-edit" class="btn">Edit Profile</a>
        </div>
    </div>

   
    <div class="profile-card">
        <h3>Security Settings:</h3>
            <p><strong>Two-Factor Authentication</strong><p>
            <p>
                Status:
                <?php if ($admin['google2fa_enabled'] == 1): ?>
                    <span class="status enabled">Enabled</span>
                <?php else: ?>
                    <span class="status disabled">Disabled</span>
                <?php endif; ?>
            </p>

            <div class="action-row">
                <?php if ($admin['google2fa_enabled'] == 1): ?>
                    <a href="index.php?action=disable-2fa"
                    class="btn"
                    onclick="return confirm('Disable 2FA? This reduces security.')">
                        Disable 2FA
                    </a>
                <?php else: ?>
                    <a href="index.php?action=install-2fa"
                    class="btn">
                        Enable 2FA
                    </a>
                <?php endif; ?>
            
            </div>
    </div>

</div>

</body>
</html>
<?php include __DIR__ . "/../layouts/footer.php"; ?>