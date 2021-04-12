<div class="col-md-3">
    <?php $__currentLoopData = $laravelAdminMenus->menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($section->items): ?>
            <div class="card">
                <div class="card-header">
                    <?php echo e($section->section); ?>

                </div>

                <div class="card-body">
                    <ul class="nav flex-column" role="tablist">
                        <?php $__currentLoopData = $section->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" href="<?php echo e(url($menu->url)); ?>">
                                    <?php echo e($menu->title); ?>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <br/>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH C:\wamp64\www\magnussucre\resources\views\admin\sidebar.blade.php ENDPATH**/ ?>