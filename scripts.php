<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.addEventListener('click', function(event) {
            if (event.target.closest('.clickable_tr')) {
                var tr = event.target.closest('.clickable_tr');
                var url = tr.getAttribute('data-url');
                if (url) {
                    window.location.href = url;
                }
            }
        });
    });
</script>