

<?php $__env->startSection('shorturl.content'); ?>
    <div class="col-12">
        <h1 class="text-center mb-5">
            <img class="logo-title" src="<?php echo e(asset('/gallib/shorturl/images/short.png')); ?>" alt="Laravel Short Url">
            Laravel Short Url
        </h1>
        <?php if(session('short_url')): ?>
            <div class="alert alert-success" role="alert">
                Your shortened url is: <a class="font-weight-bold" href="<?php echo e(session('short_url')); ?>" title="your shortened url"><?php echo e(session('short_url')); ?></a> (<a class="copy-clipboard" href="javascript:void(0);" data-clipboard-text="<?php echo e(session('short_url')); ?>">Copy link to clipboard</a>)
            </div>
        <?php endif; ?>
        <form method="POST" action="<?php echo e(route('shorturl.url.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="input-group">
                <input type="text" class="form-control form-control-lg <?php echo e($errors->has('url') ? 'is-invalid' : ''); ?>" id="url" name="url" placeholder="Paste an url" aria-label="Paste an url" value="<?php echo e(old('url')); ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Shorten</button>
                </div>
            </div>
            <?php if($errors->has('url')): ?>
                <small id="url-error" class="form-text text-danger">
                    <?php echo e($errors->first('url')); ?>

                </small>
            <?php endif; ?>
            <div class="row mt-3">
                <div class="col-4">
                    <div class="form-group">
                        <label for="code">Custom alias (optional)</label>
                        <input type="text" class="form-control <?php echo e($errors->has('code') ? 'is-invalid' : ''); ?>" id="code" name="code" placeholder="Set your custom alias" value="<?php echo e(old('code')); ?>">
                        <?php if($errors->has('code')): ?>
                            <small id="code-error" class="form-text text-danger">
                                <?php echo e($errors->first('code')); ?>

                            </small>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="expires_at">Expires at (optional)</label>
                        <input type="datetime-local" class="form-control <?php echo e($errors->has('expires_at') ? 'is-invalid' : ''); ?>" id="expires_at" name="expires_at" placeholder="Set your expiration date" value="<?php echo e(old('expires_at')); ?>">
                        <?php if($errors->has('expires_at')): ?>
                            <small id="code-error" class="form-text text-danger">
                                <?php echo e($errors->first('expires_at')); ?>

                            </small>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-4 text-right">By <a href="https://github.com/gallib/laravel-short-url" title="by gallib/laravel-short-url" target="_blank">Gallib/laravel-short-url</a></div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    <script>
        var clipboard = new ClipboardJS('.copy-clipboard');

        clipboard.on('success', function(e) {
            e.trigger.innerText = 'Copied!';
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('shorturl::layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\vendor\shorturl\urls\create.blade.php ENDPATH**/ ?>