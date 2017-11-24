@foreach ($fields as $k => $f)
    @if ($f['input'] != 'hidden')
        <div @if (array_key_exists('isHide', $f) && $f['isHide'] == 'yes') class="form-group hide" @else class="form-group" @endif id="{{ $k }}-form-group">
            <label for="{{ $k }}" class="control-label col-lg-2">{{ $f['label'] }} </label>
            @if ($f['input'] == 'text')
                <div @if(array_key_exists('divClass', $f)) class="{{$f['divClass']}}" @else class="col-lg-6" @endif>
                    <input type="text" name="{{ $k }}" id="{{ $k }}" value="{{ $f['default'] }}" class="form-control"
                        @if (array_key_exists('required', $f) && $f['required']) required @endif
                        @if (array_key_exists('readonly', $f) && $f['readonly']) readonly="readonly" @endif
                        @if (array_key_exists('placeholder', $f) && $f['placeholder']) placeholder={{ $f['placeholder'] }} @endif
                    ><span id="{{$k}}info" style="color:red;"></span>
                </div>
            @elseif ($f['input'] == 'email')
                <div class="col-lg-6">
                    <input type="email" name="{{ $k }}" id="{{ $k }}" value="{{ $f['default'] }}" class="form-control"
                           @if (array_key_exists('required', $f) && $f['required']) required @endif
                           @if (array_key_exists('readonly', $f) && $f['readonly']) readonly="readonly" @endif
                           @if (array_key_exists('placeholder', $f) && $f['placeholder']) placeholder={{ $f['placeholder'] }} @endif
                    >
                </div>
            @elseif ($f['input'] == 'password')
                <div class="col-lg-6">
                    <input type="password" name="{{ $k }}" id="{{ $k }}" value="{{ $f['default'] }}" class="form-control"
                           @if (array_key_exists('required', $f) && $f['required']) required @endif
                           @if (array_key_exists('readonly', $f) && $f['readonly']) readonly="readonly" @endif
                           @if (array_key_exists('placeholder', $f) && $f['placeholder']) placeholder={{ $f['placeholder'] }} @endif
                    >
                </div>
            @elseif ($f['input'] == 'num')
                <div @if(array_key_exists('divClass', $f)) class="{{$f['divClass']}}" @else class="col-lg-6" @endif>
                    <input type="text" name="{{ $k }}" id="{{ $k }}" value="{{ $f['default'] }}" class="form-control" onkeyup="this.value=this.value.replace(/[^0-9-]+/,'');"
                           @if (array_key_exists('required', $f) && $f['required']) required @endif
                           @if (array_key_exists('readonly', $f) && $f['readonly']) readonly="readonly" @endif
                           @if (array_key_exists('placeholder', $f) && $f['placeholder']) placeholder={{ $f['placeholder'] }} @endif
                    >
                </div>
            @elseif ($f['input'] == 'textarea')
               <div class="col-lg-6">
                    <textarea rows="5" id="{{ $k }}" name="{{ $k }}" class="form-control"
                          @if (array_key_exists('required', $f) && $f['required']) required @endif
                          @if (array_key_exists('placeholder', $f) && $f['placeholder']) placeholder={{ $f['placeholder'] }} @endif
                    >{{ $f['default'] }}</textarea>
                </div>
            @elseif ($f['input'] == 'ueditor')
                <div class="col-lg-10">
                    <textarea rows="5" id="{{ $k }}" name="{{ $k }}"
                              @if (array_key_exists('required', $f) && $f['required']) required @endif
                              @if (array_key_exists('placeholder', $f) && $f['placeholder']) placeholder={{ $f['placeholder'] }} @endif
                    >{{ $f['default'] }}</textarea>
                </div>
            @elseif ($f['input'] == 'radio')
                <div class="col-lg-10">
                    @foreach ($f['values'] as $kk => $vv)
                        <label class="checkbox-inline">
                            <input type="radio"  name="{{ $k }}" value="{{ $kk }}" id="{{$k}}"
                                   @if ($kk == $f['default']) checked="checked" @endif
                                   @if (array_key_exists('placeholder', $f) && $f['placeholder']) placeholder={{ $f['placeholder'] }} @endif
                            > {{ $vv }}
                        </label>
                    @endforeach
                </div>
            @elseif ($f['input'] == 'checkbox')
                <div class="col-lg-6">
                    @foreach($f['values'] as $kk => $vv)
                        <label class="checkbox-inline i-checks">
                            <input type="checkbox" value="{{ $kk }}" name="{{ $k }}[]"
                                   @if ($f['default'] && is_array($f['default']) && in_array($kk, $f['default'])) checked="checked" @endif
                            >{{ $vv }}
                        </label>
                    @endforeach
                </div>
            @elseif ($f['input'] == 'select')
                <div @if(array_key_exists('divClass', $f)) class="{{$f['divClass']}}" @else class="col-lg-6" @endif>
                    <select class="form-control has-success" id="{{ $k }}" name="{{ $k }}" @if (isset($f['linkage'])) hw-select-linkage @endif @if (array_key_exists('disabled', $f) && $f['disabled']) disabled="disabled" @endif>
                        <!-- <option value="">请选择{{$f['label']}}</option> -->
                        @foreach ($f['values'] as $kk => $vv)
                            <option value="{{$kk}}" @if ($kk == $f['default']) selected="selected" @endif>{{$vv}}</option>
                        @endforeach
                    </select>
                </div>
            @elseif ($f['input'] == 'selects')
                <div @if(array_key_exists('divClass', $f)) class="{{$f['divClass']}}" @else class="col-lg-6" @endif>
                    <select class="form-control has-success" id="{{ $k }}" name="{{ $k }}[]" multiple="multiple">
                        @foreach ($f['values'] as $kk => $vv)
                            <option value="{{$kk}}" @if (in_array($kk, $f['default'])) selected="selected" @endif>{{$vv}}</option>
                        @endforeach
                    </select>
                    </select>
                </div>
            @elseif ($f['input'] == 'shopSingle')
                <div class="col-lg-10">
                    <div id="{{ $k }}" hw_shop_container data-name="{{ $k }}" data-value="{{ $f['default'] }}" data-type="single">
                        <shop-container :value="value" :type="type" :name="name"></shop-container>
                    </div>
                </div>
            @elseif ($f['input'] == 'shopMultiple')
                <div class="col-lg-10">
                    <div id="{{ $k }}" hw_shop_container data-name="{{ $k }}" data-value="{{ $f['default'] }}" data-type="multiple">
                        <shop-container :value="value" :type="type" :name="name"></shop-container>
                    </div>
                </div>
            @elseif ($f['input'] == 'image')
                <div class="col-md-9" hw_image>
                    <input type="hidden" name="{{ $k }}" id="{{$k}}" value="{{ $f['default'] }}" hw_image_input>
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                            <img id="img" style="height: 100%" src="@if ($f['default']) {{ $f['default'] }} @else /assets/admin/img/no_image.png @endif" alt="" hw_image_src>
                        </div>
                        <div id="picker">选择图片</div>
                    </div>
                </div>
            @elseif ($f['input'] == 'zoom')
                <div class="col-md-9" hw_image>
                    <input type="hidden" name="{{ $k }}" id="{{$k}}" value="{{ $f['default'] }}" hw_zoom_input>
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                            <img id="zoom" style="height: 100%" src="@if ($f['default']) {{ $f['default'] }} @else /assets/admin/img/no_image.png @endif" alt="" hw_image_src>
                        </div>

                        <div id="picker1">选择图片</div>
                    </div>
                </div>
            @elseif ($f['input'] == 'images')
                @if (isset($goodsImg))
                    <div class="col-md-9" hw_image>
                        <input type="hidden" name="{{ $k }}" id="{{$k}}" value="{{ $goodsImg[0]->img }}" hw_image_input>
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            @foreach ($goodsImg as $v)
                                <div class="fileupload-new thumbnail" style="width: 200px;">
                                    <img  id="pic{{$v->id}}" src="{{asset($v->img)}}" width="150px" height="150px">
                                    <a class="btn btn-primary" onclick="doData({{$v->id}})" id="img{{$v->id}}">删除</a>
                                </div>
                             @endforeach
                                <div id="pickers">选择图片</div><span>多图上传最多上传5张</span>
                        </div>
                    </div>
                @else
                    <div class="col-md-9" hw_image>
                        <input type="hidden" name="{{ $k }}" id="{{$k}}" value="{{ $f['default'] }}" hw_image_input>
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            @if ($f['default'])
                                <div id="fileList" class="uploader-list" style="width:120px;">
                                    <img id="img" style="height: 120px;" src="{{ $f['default'] }}" alt="" hw_image_src>
                                </div>
                            @else
                                <div id="fileList" class="uploader-list" style="width:120px;"></div>
                            @endif
                        </div>
                        <div id="pickers">选择图片</div><span>多图上传最多上传5张</span>
                    </div>
                @endif
            @elseif ($f['input'] == 'pic')
                <div class="col-md-9" hw_image>
                    <input type="hidden" name="{{ $k }}" id="{{$k}}" value="{{ $f['default'] }}" hw_image_input>
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                            <img id="img" style="height: 100%" src="@if ($f['default']) {{ $f['default'] }} @else /assets/admin/img/no_image.png @endif" alt="" hw_image_src>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
    <div class="clearfix"></div>
@endforeach

@section('form-base-js')
    <script src="/assets/hadmin/js/plugins/iCheck/icheck.min.js"></script>
    <script src="/build/assets/js/shop_choose_build.js?id=1&v=1"></script>
<script>
    // 错误信息提示
    $(document).ready(function () {
        var submitType = false;
        var handler = function () {
            $.extend($.validator.defaults, {
                invalidHandler: function (event, validator) {
                    var errors = validator.numberOfInvalids();
                    if (errors) {
                        var message = '您有 ' + errors + ' 字段需要处理.';
                        noty({
                            text: message,
                            type: 'error',
                            timeout: 2000
                        });
                    }
                },

                submitHandler: function (form) {
                    if (submitType) {
                        return false;
                    }
                    submitType = true;
                    var postData = $(form).serialize();
                    $.post($(form).attr('action'), postData, function (data) {
                        if (data.error_code) {
                            noty({
                                text: data.error_message,
                                type: 'error',
                                timeout: 2000
                            });
                            submitType = false;
                        } else {
                            noty({
                                text: data.data,
                                type: 'success',
                                timeout: 2000,
                                callback: {
                                    afterClose: function () {
                                        window.location.reload();
                                    }
                                }
                            });
                        }
                    }, 'json');
                }
            });

            $("form").validate();
        };

        var checkInit = function () {
            if ($('label').hasClass('i-checks')) {
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green'
                });
            }
        };

        checkInit();
        handler();
    });

    <!-- webuploder -->
    var uploader = WebUploader.create({
        //选择文件后，是否自动上传。
        auto: true,
        //swf文件路径
        swf: '/assets/hadmin/js/plugins/webuploader/Uploader.swf',
        //文件接收服务端
        server: '/admin/wx/media/image/upload',
        //选择文件的按钮。可选
        //内部根据当前运行是创建，可能是input元素，也可能是flash，
        pick: '#picker',
        //不压缩image，默认是jpeg，文件上传前会压缩一把再上传！
        resize: false,
        //只允许选择图片
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeType: 'image/*'
        }
    });
    uploader.on('uploadBeforeSend', function(block, data, headers){
        $.extend(headers, { 'X-CSRF-TOKEN': $('meta[name=_token]').attr('content') });
    });
    uploader.on('fileQueued', function(file) {
        var $img = $('#img');
        //创建缩略图
        //如果为非图片文件，可以不用调用此方法。
        //thumbnailWidth * thumbnailHeight 为 100 * 100
        uploader.makeThumb(file, function(error, src) {
            if (error) {
                $img.replaceWith('<span>不能预览</span>');
                return;
            }
            $img.attr('src', src);
        }, 100, 100);
    });
    uploader.on('uploadSuccess', function (file, response) {
        if (response.error_code > 0) {
            noty({
                text: response.error_message,
                type: 'error',
                timeout: 2000
            });
        } else {
            $('[hw_image_input]').val(response.data);
        }
    });
</script>
<script>
    <!-- webuploder -->
    var uploader = WebUploader.create({
    //选择文件后，是否自动上传。
    auto: true,
    //swf文件路径
    swf: '/assets/hadmin/js/plugins/webuploader/Uploader.swf',
    //文件接收服务端
    server: '/admin/setting/media/image/upload',
    //选择文件的按钮。可选
    //内部根据当前运行是创建，可能是input元素，也可能是flash，
    pick: '#picker1',
    //不压缩image，默认是jpeg，文件上传前会压缩一把再上传！
    resize: false,
    //只允许选择图片
    accept: {
    title: 'Images',
    extensions: 'gif,jpg,jpeg,bmp,png',
    mimeType: 'image/*'
    }
    });
    uploader.on('fileQueued', function(file) {
    var $img = $('#zoom');
    //创建缩略图
    //如果为非图片文件，可以不用调用此方法。
    //thumbnailWidth * thumbnailHeight 为 100 * 100
    uploader.makeThumb(file, function(error, src) {
    if (error) {
    $img.replaceWith('<span>不能预览</span>');
    return;
    }
    $img.attr('src', src);
    }, 100, 100);
    });
    uploader.on('uploadSuccess', function (file, response) {
    if (response.error_code > 0) {
    noty({
    text: response.error_message,
    type: 'error',
    timeout: 2000
    });
    } else {
    $('[hw_zoom_input]').val(response.data);
    }
    });
    </script>
@endsection

