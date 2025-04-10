<!-- Init Sweet Alerts -->
<?php if (isset($success)) { ?>
    <!-- Pop Success Alert -->
    <script>
        Swal.fire({
            title: 'Success',
            html: '<?php echo $success; ?>',
            timer: 3500,
            type: "success",
            showConfirmButton: false,
            onBeforeOpen: () => {
                timerInterval = setInterval(() => {
                    Swal.getContent().querySelector('strong')
                        .textContent = Swal.getTimerLeft()
                }, 2000)
            },
            onClose: () => {
                clearInterval(timerInterval)
            }
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('<?php echo $success; ?>')
            }
        })
    </script>

<?php }
if (isset($err)) { ?>
    <script>
        Swal.fire({
            title: 'Failed',
            html: '<?php echo $err; ?>',
            timer: 3500,
            type: "error",
            showConfirmButton: false,
            onBeforeOpen: () => {
                timerInterval = setInterval(() => {
                    Swal.getContent().querySelector('strong')
                        .textContent = Swal.getTimerLeft()
                }, 2000)
            },
            onClose: () => {
                clearInterval(timerInterval)
            }
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('<?php echo $err; ?>')
            }
        })
    </script>

<?php }
if (isset($info)) { ?>
    <script>
        Swal.fire({
            title: 'Failed',
            html: '<?php echo $info; ?>',
            timer: 3500,
            type: "info",
            showConfirmButton: false,
            onBeforeOpen: () => {
                timerInterval = setInterval(() => {
                    Swal.getContent().querySelector('strong')
                        .textContent = Swal.getTimerLeft()
                }, 2000)
            },
            onClose: () => {
                clearInterval(timerInterval)
            }
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('<?php echo $info; ?>')
            }
        })
    </script>

<?php } ?>