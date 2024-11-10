<?php
// footer.php

// Mendapatkan tahun saat ini
$currentYear = date("Y");
?>

<footer class="bg-indigo-600 shadow-lg fixed bottom-0 w-full">
    <div class="mx-10 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center justify-center py-4">
            <p class="text-white">&copy; <?php echo $currentYear; ?> RPL Prestasi Prima. Semua hak dilindungi.</p>
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
