    <script src="scripts/jquery.js"></script>
    <script src="scripts/designer.js"></script>
</body>

<footer>
    <?php if(isset($_SESSION['error'])) : ?>
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <?=$_SESSION['error']?>
            </div>
        </div>
        <?=$_SESSION['error']?>
        <?php unset($_SESSION['error'])?>
    <?php endif; ?>
</footer>
</html>
