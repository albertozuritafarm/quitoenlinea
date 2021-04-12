

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Permissions</div>
                    <div class="card-body">
                        <a href="<?php echo e(url('/admin/permissions/create')); ?>" class="btn btn-success btn-sm" title="Add New Permission">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

                        <?php echo Form::open(['method' => 'GET', 'url' => '/admin/permissions', 'class' => 'form-inline my-2 my-lg-0 float-right', 'role' => 'search']); ?>

                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search...">
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
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th><th>Name</th><th>Label</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($item->id); ?></td>
                                        <td><a href="<?php echo e(url('/admin/permissions', $item->id)); ?>"><?php echo e($item->name); ?></a></td><td><?php echo e($item->label); ?></td>
                                        <td>
                                            <a href="<?php echo e(url('/admin/permissions/' . $item->id)); ?>" title="View Permission"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                            <a href="<?php echo e(url('/admin/permissions/' . $item->id . '/edit')); ?>" title="Edit Permission"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                            <?php echo Form::open([
                                                'method' => 'DELETE',
                                                'url' => ['/admin/permissions', $item->id],
                                                'style' => 'display:inline'
                                            ]); ?>

                                                <?php echo Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn btn-danger btn-sm',
                                                        'title' => 'Delete Permission',
                                                        'onclick'=>'return confirm("Confirm delete?")'
                                                )); ?>

                                            <?php echo Form::close(); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="pagination"> <?php echo $permissions->appends(['search' => Request::get('search')])->render(); ?> </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\admin\permissions\index.blade.php ENDPATH**/ ?>