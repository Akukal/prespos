</div> <!-- Penutup untuk div.content -->

<footer class="main-footer">
    <div class="container">
        <div class="footer-content">
            <p>&copy; <?php echo date("Y"); ?> Prime POS System. All rights reserved.</p>
            <nav class="footer-nav">
                <ul>
                    <li><a href="/prespos/about.php">About</a></li>
                    <li><a href="/prespos/contact.php">Contact</a></li>
                    <li><a href="/prespos/privacy.php">Privacy Policy</a></li>
                </ul>
            </nav>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/pos/js/script.js"></script>
<?php if (isset($extraScripts)): ?>
    <?php foreach ($extraScripts as $script): ?>
        <script src="<?php echo $script; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
