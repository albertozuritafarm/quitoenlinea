<div class="form-group<?php echo e($errors->has('key') ? 'has-error' : ''); ?>">
    <?php echo Form::label('key', 'Key', ['class' => 'control-label']); ?>

    <?php echo Form::text('key', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']); ?>

    <?php echo $errors->first('key', '<p class="help-block">:message</p>'); ?>

</div>
<div class="form-group<?php echo e($errors->has('value') ? 'has-error' : ''); ?>">
    <?php echo Form::label('value', 'Value', ['class' => 'control-label']); ?>

    <?php echo Form::text('value', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']); ?>

    <?php echo $errors->first('value', '<p class="help-block">:message</p>'); ?>

</div>

<div class="form-group">
    <?php echo Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']); ?>

</div>
<?php /**PATH C:\wamp64\www\magnussucre\resources\views\admin\settings\form.blade.php ENDPATH**/ ?>