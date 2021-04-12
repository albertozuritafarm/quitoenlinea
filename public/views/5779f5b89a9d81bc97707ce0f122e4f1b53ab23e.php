

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Activity <?php echo e($activitylog->id); ?></div>
                    <div class="card-body">

                        <a href="<?php echo e(url('/admin/activitylogs')); ?>" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <?php echo Form::open([
                            'method'=>'DELETE',
                            'url' => ['admin/activitylogs', $activitylog->id],
                            'style' => 'display:inline'
                        ]); ?>

                            <?php echo Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm',
                                    'title' => 'Delete Activity',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            )); ?>

                        <?php echo Form::close(); ?>

                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td><?php echo e($activitylog->id); ?></td>
                                    </tr>
                                    <tr>
                                        <th> Activity </th><td> <?php echo e($activitylog->description); ?> </td>
                                    </tr>
                                    <tr>
                                        <th> Actor </th>
                                        <td>
                                            <?php if($activitylog->causer): ?>
                                                <a href="<?php echo e(url('/admin/users/' . $activitylog->causer->id)); ?>"><?php echo e($activitylog->causer->name); ?></a>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Date </th><td> <?php echo e($activitylog->created_at); ?> </td>
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

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\admin\activitylogs\show.blade.php ENDPATH**/ ?>