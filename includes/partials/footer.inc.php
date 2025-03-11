    <script src="scripts/designer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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