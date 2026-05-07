<form action="<?php echo e(route('blockcode.import')); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <input type="file" name="files[]" multiple><BR>
    <input type="submit" value="IMPORT">
</form>
<?php /**PATH C:\xampp\htdocs\voterlist-system\resources\views/front/import_blockcode.blade.php ENDPATH**/ ?>