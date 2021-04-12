<div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
    <?php echo Form::label('name', 'Name: ', ['class' => 'control-label']); ?>

    <?php echo Form::text('name', null, ['class' => 'form-control', 'required' => 'required']); ?>

    <?php echo $errors->first('name', '<p class="help-block">:message</p>'); ?>

</div>
<div class="form-group<?php echo e($errors->has('label') ? ' has-error' : ''); ?>">
    <?php echo Form::label('label', 'Label: ', ['class' => 'control-label']); ?>

    <?php echo Form::text('label', null, ['class' => 'form-control']); ?>

    <?php echo $errors->first('label', '<p class="help-block">:message</p>'); ?>

</div>
<div class="form-group">
    <?php echo Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']); ?>

</div>
<?php /**PATH C:\wamp64\www\magnussucre\resources\views\admin\permissions\form.blade.php ENDPATH**/ ?>