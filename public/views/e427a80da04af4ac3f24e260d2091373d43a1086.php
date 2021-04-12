<div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
    <?php echo Form::label('name', 'Name: ', ['class' => 'control-label']); ?>

    <?php echo Form::text('name', null, ['class' => 'form-control', 'required' => 'required']); ?>

    <?php echo $errors->first('name', '<p class="help-block">:message</p>'); ?>

</div>
<div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
    <?php echo Form::label('email', 'Email: ', ['class' => 'control-label']); ?>

    <?php echo Form::email('email', null, ['class' => 'form-control', 'required' => 'required']); ?>

    <?php echo $errors->first('email', '<p class="help-block">:message</p>'); ?>

</div>
<div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
    <?php echo Form::label('password', 'Password: ', ['class' => 'control-label']); ?>

    <?php
        $passwordOptions = ['class' => 'form-control'];
        if ($formMode === 'create') {
            $passwordOptions = array_merge($passwordOptions, ['required' => 'required']);
        }
    ?>
    <?php echo Form::password('password', $passwordOptions); ?>

    <?php echo $errors->first('password', '<p class="help-block">:message</p>'); ?>

</div>
<div class="form-group<?php echo e($errors->has('roles') ? ' has-error' : ''); ?>">
    <?php echo Form::label('role', 'Role: ', ['class' => 'control-label']); ?>

    <?php echo Form::select('roles[]', $roles, isset($user_roles) ? $user_roles : [], ['class' => 'form-control', 'multiple' => true]); ?>

</div>
<div class="form-group">
    <?php echo Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']); ?>

</div>
<?php /**PATH C:\wamp64\www\magnussucre\resources\views\admin\users\form.blade.php ENDPATH**/ ?>