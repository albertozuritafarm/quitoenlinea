

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Role</div>
                    <div class="card-body">

                        <a href="<?php echo e(url('/admin/roles')); ?>" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="<?php echo e(url('/admin/roles/' . $role->id . '/edit')); ?>" title="Edit Role"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        <?php echo Form::open([
                            'method' => 'DELETE',
                            'url' => ['/admin/roles', $role->id],
                            'style' => 'display:inline'
                        ]); ?>

                            <?php echo Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm',
                                    'title' => 'Delete Role',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            )); ?>

                        <?php echo Form::close(); ?>

                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID.</th> <th>Name</th><th>Label</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo e($role->id); ?></td> <td> <?php echo e($role->name); ?> </td><td> <?php echo e($role->label); ?> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\admin\roles\show.blade.php ENDPATH**/ ?>