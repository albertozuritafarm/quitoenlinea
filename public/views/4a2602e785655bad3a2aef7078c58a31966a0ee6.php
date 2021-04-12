

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Settings</div>
                    <div class="card-body">
                        <a href="<?php echo e(url('/admin/settings/create')); ?>" class="btn btn-success btn-sm" title="Add New Setting">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

                        <?php echo Form::open(['method' => 'GET', 'url' => '/admin/settings', 'class' => 'form-inline my-2 my-lg-0 float-right', 'role' => 'search']); ?>

                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search..." value="<?php echo e(request('search')); ?>">
                            <span class="input-group-append">
                                <button class="btn btn-secondary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                        <?php echo Form::close(); ?>


                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Key</th><th>Value</th><th>Usage</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($item->key); ?></td>
                                        <td><?php echo e($item->value); ?></td>
                                        <td><code>setting('<?php echo e($item->key); ?>')</code></td>
                                        <td>
                                            <a href="<?php echo e(url('/admin/settings/' . $item->id)); ?>" title="View Setting"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                            <a href="<?php echo e(url('/admin/settings/' . $item->id . '/edit')); ?>" title="Edit Setting"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                            <?php echo Form::open([
                                                'method' => 'DELETE',
                                                'url' => ['/admin/settings', $item->id],
                                                'style' => 'display:inline'
                                            ]); ?>

                                                <?php echo Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn btn-danger btn-sm',
                                                        'title' => 'Delete Setting',
                                                        'onclick'=>'return confirm("Confirm delete?")'
                                                )); ?>

                                            <?php echo Form::close(); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> <?php echo $settings->appends(['search' => Request::get('search')])->render(); ?> </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\admin\settings\index.blade.php ENDPATH**/ ?>