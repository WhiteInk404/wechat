<script src="/assets/admin/wechat/js/bootbox.min.js"></script>
<script src="/assets/admin/wechat/js/jquery.noty.min.js"></script>
<script src="/assets/admin/wechat/js/jquery.validate.min.js"></script>
<script src="/js/plugins/webuploader/webuploader.js"></script>

<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });
</script>
