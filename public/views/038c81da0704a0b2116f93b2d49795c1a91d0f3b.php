<?php if($paginator->hasPages()): ?>
    <ul class="pagination dataTables_paginate paging_simple_numbers" role="navigation">
        
        <?php if($paginator->onFirstPage()): ?>
            <li class="paginate_button previous disabled paginationFont paginationLi newPaginationStyleNotSelected" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">
                <span class="paginate_button previous disabled paginationFont " aria-hidden="true">Anterior</span>
            </li>
        <?php else: ?>
            <li class="paginate_button previous paginationFont paginationLi newPaginationStyleNotSelected">
                <a class="paginate_button previous paginationFont newPaginationStyle" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">Anterior</a>
            </li>
        <?php endif; ?>

        
        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <?php if(is_string($element)): ?>
                <li class="paginate_button paginationFont disabled paginationLi" aria-disabled="true"><span class="newPaginationStyle"><?php echo e($element); ?></span></li>
            <?php endif; ?>

            
            <?php if(is_array($element)): ?>
                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $paginator->currentPage()): ?>
                        <li class="paginate_button paginationFont active paginationLi" aria-current="page"><span class="newPaginationStyle"  style="border-radius:6px;color:#333;border:1px solid #979797;background:linear-gradient(to bottom, #fff 0%, #dcdcdc 100%)"><?php echo e($page); ?></span></li>
                    <?php else: ?>
                        <li class="paginate_button paginationFont paginationLi newPaginationStyleNotSelected"><a class="newPaginationStyle" href="<?php echo e($url); ?>" style="background-color: transparent;"><?php echo e($page); ?></a></li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($paginator->hasMorePages()): ?>
            <li class="paginate_button next paginationFont paginationLi newPaginationStyleNotSelected">
                <a class="paginate_button next paginationFont newPaginationStyle " href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">Siguiente</a>
            </li>
        <?php else: ?>
            <li class="paginate_button next paginationFont disabled paginationLi newPaginationStyleNotSelected" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">
                <span class="paginate_button next paginationFont disabled  " aria-hidden="true">Siguiente</span>
            </li>
        <?php endif; ?>
    </ul>
<?php endif; ?>
<?php /**PATH C:\wamp64\www\magnussucre\resources\views\vendor\pagination\bootstrap-4.blade.php ENDPATH**/ ?>