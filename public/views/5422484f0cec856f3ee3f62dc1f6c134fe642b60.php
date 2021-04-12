<div class="form-group<?php echo e($errors->has('title') ? 'has-error' : ''); ?>">
    <?php echo Form::label('title', 'Title', ['class' => 'control-label']); ?>

    <?php echo Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']); ?>

    <?php echo $errors->first('title', '<p class="help-block">:message</p>'); ?>

</div>
<div class="form-group<?php echo e($errors->has('content') ? 'has-error' : ''); ?>">
    <?php echo Form::label('content', 'Content', ['class' => 'control-label']); ?>

    <?php echo Form::textarea('content', null, ['class' => 'form-control crud-richtext']); ?>

    <?php echo $errors->first('content', '<p class="help-block">:message</p>'); ?>

</div>

<div class="form-group">
    <?php echo Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']); ?>

</div>
<?php /**PATH C:\wamp64\www\magnussucre\resources\views\admin\pages\form.blade.php ENDPATH**/ ?>