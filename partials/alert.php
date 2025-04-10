<?php if (isset($success)) { ?>
    <script>
        Swal.fire({
        title: "Success!",
        icon: "success",
        draggable: true
        });
    </script>
<?php } ?>

<?php if (isset($err)) { ?>
    <script>
        Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Something went wrong!"
        });
    </script>
<?php } ?>

<?php if (isset($info)) { ?>
    <script>
        Swal.fire({
            title: 'Info',
            html: '<?php echo addslashes($info); ?>',
            timer: 3500,
            icon: "info",
            showConfirmButton: false
        });
    </script>
<?php } ?>
