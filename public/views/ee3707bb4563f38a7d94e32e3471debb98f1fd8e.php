

<?php $__env->startSection('shorturl.content'); ?>
    <div class="col-8 offset-2">
        <h1 class="text-center mb-5">
            <img class="logo-title" src="<?php echo e(asset('/gallib/shorturl/images/short.png')); ?>" alt="Laravel Short Url">
            Laravel Short Url
        </h1>
        <?php if(session('short_url')): ?>
            <div class="alert alert-success" role="alert">
                Your shortened url has been deleted!
            </div>
        <?php endif; ?>
        <div class="mb-2 text-right">
            <a class="btn btn-sm btn-primary" href="<?php echo e(route('shorturl.url.create')); ?>" role="button">Add url</a>
        </div>
        <table class="table">
            <tr>
                <th>Url</th>
                <th>Short Url</th>
                <th>Counter</th>
                <th>User</th>
                <th></th>
            </tr>
            <?php $__currentLoopData = $urls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($url->url); ?></td>
                    <td><a href="<?php echo e(route('shorturl.redirect', $url->code)); ?>"><?php echo e($url->code); ?></a></td>
                    <td><?php echo e($url->counter); ?></td>
                    <td><?php echo e(optional($url->user)->name); ?></td>
                    <td>
                        <button class="btn btn-sm btn-success" data-clipboard-text="<?php echo e(route('shorturl.redirect', $url->code)); ?>">Copy</button>
                        <a class="btn btn-sm btn-primary" href="<?php echo e(route('shorturl.url.edit', $url->id)); ?>" role="button">Edit</a>
                        <form method="POST" action="<?php echo e(route('shorturl.url.destroy', $url->id)); ?>">
                            <?php echo method_field('DELETE'); ?>
                            <?php echo csrf_field(); ?>
                            <button class="btn btn-sm btn-danger" href="#" role="button">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>

        <?php echo e($urls->links()); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        form {
            display: inline-block;
        }
        .wrapper {
            min-height: 100vh;
        }
        .pagination {
            justify-content: flex-end;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    <script>
        var clipboard = new ClipboardJS('.btn-success');

        clipboard.on('success', function(e) {
            e.trigger.innerText = 'Copied!';
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('shorturl::layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\vendor\shorturl\urls\index.blade.php ENDPATH**/ ?>