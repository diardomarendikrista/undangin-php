<footer class="footer">
  <small>&copy; 2025 UndangIn. All rights reserved.</small>
</footer>

<?php $basePath = isset($path) ? $path : ''; ?>
<script src="<?= $basePath ?>assets/js/script.js"></script>
<?php if ($basePath == "") : ?>
  <script src="<?= $basePath ?>assets/js/heroImageScript.js"></script>
<?php endif; ?>

</body>

</html>