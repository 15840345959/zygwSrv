@extends('admin.layouts.app')

@section('content')

    <div class="page-container">
        <form class="form form-horizontal" id="form-edit">
            {{csrf_field()}}
            <div class="row cl hidden">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>id：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="id" name="id" type="text" class="input-text"
                           value="{{ isset($data->id) ? $data->id : '' }}" placeholder="产品id">
                </div>
            </div>
            <div class="row cl hidden">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>楼盘id：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="house_id" name="house_id" type="text" class="input-text" style="width:350px"
                           value="{{ isset($house->id) ? $house->id : '' }}" placeholder="楼盘id">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>楼盘名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="house_title" name="house_title" type="text" class="input-text" style="width:350px"
                           value="{{ isset($house->title) ? $house->title : '' }}" placeholder="请输入楼盘名称" disabled="">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>产品名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="name" name="name" type="text" class="input-text"
                           value="{{ isset($data->name) ? $data->name : '' }}" placeholder="请输入产品名称">
                </div>
            </div>
            <div class="row cl item c-999">
                <label class="form-label col-xs-4 col-sm-2"></label>
                <div class="formControls col-xs-8 col-sm-9">
                    <div>
                        <span>最多可以设置15个汉字</span>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>产品类型：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <span class="select-box" style="width: 200px;">
                        <select id="type_id" name="type_id" class="select">
                            <option value="">请选择</option>
                            @foreach($houseTypes as $houseType)
                                <option value="{{$houseType->id}}" {{$data->type_id==$houseType->id?'selected':''}}>{{$houseType->name}}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
            </div>
            <div class="row cl item">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>产品图片：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="image" name="image" type="text" class="input-text" style=""
                           value="{{ isset($data->image) ? $data->image : ''}}"
                           placeholder="请输入选择图片">
                    <div id="container" class="margin-top-10">
                        <img id="pickfiles"
                             src="{{ isset($data->image) ? $data->image : URL::asset('/img/upload.png') }}"
                             style="width: 350px;">
                    </div>
                    <div style="font-size: 12px;margin-top: 10px;" class="c-999">*请上传800*600比例尺寸图片</div>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>佣金类型：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <span class="select-box" style="width: 200px;">
                        <select id="yongjin_type" name="yongjin_type" class="select" {{isset($data->id)?'disabled':''}}>
                            <option value="">请选择</option>
                            @foreach(\App\Components\Value::HUXING_YONGJIN_TYPE_VAL as $key=>$value)
                                <option value="{{$key}}" {{$data->yongjin_type==$key?'selected':''}}>{{$value}}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>佣金金额/比例：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="yongjin_value" name="yongjin_value" type="number" class="input-text"
                           style="width: 200px;" {{isset($data->id)?'disabled':''}}
                           value="{{ isset($data->yongjin_value) ? $data->yongjin_value : '' }}" placeholder="请输入佣金金额">
                    <span class="ml-5">元（人民币） / ‰（千分比）</span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>面积区间（m²）：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="size_min" name="size_min" type="number" class="input-text" style="width: 120px;"
                           value="{{ isset($data->size_min) ? $data->size_min : '' }}" placeholder="">
                    <span>-</span>
                    <input id="size_max" name="size_max" type="number" class="input-text" style="width: 120px;"
                           value="{{ isset($data->size_max) ? $data->size_max : '' }}" placeholder="">
                    <span>m²</span>
                </div>
            </div>

            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>产品优点：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="benefit" name="benefit" type="text" class="input-text"
                           value="{{ isset($data->benefit) ? $data->benefit : '' }}" placeholder="请输入产品优点">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>户型描述：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="huxing" name="huxing" type="text" class="input-text"
                           value="{{ isset($data->huxing) ? $data->huxing : '' }}"
                           placeholder="请输入户型描述，例如三室一厅、二室一厅、一室一厅等">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>户型朝向：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="orientation" name="orientation" type="text" class="input-text"
                           value="{{ isset($data->orientation) ? $data->orientation : '' }}" placeholder="例如南北、西北、东南">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>推荐理由：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="reason" name="reason" type="text" class="input-text"
                           value="{{ isset($data->reason) ? $data->reason : '' }}" placeholder="请输入推荐理由，例如采光效果好、功能齐全等">
                </div>
            </div>

            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <input class="btn btn-primary radius" type="submit" value="保存产品">
                    <button onClick="layer_close();" class="btn btn-default radius" type="button">取消</button>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')
    <script type="text/javascript">


        $(function () {
            //获取七牛token
            initQNUploader();
            $("#form-edit").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    phonenum: {
                        required: true,
                        isPhone: true,
                    },
                    type_id: {
                        required: true,
                    },
                    yongjin_type: {
                        required: true,
                    },
                    yongjin_value: {
                        required: true,
                    },
                    size_min: {
                        required: true,
                    },
                    size_max: {
                        required: true,
                    },
                },
                onkeyup: false,
                focusCleanup: true,
                success: "valid",
                submitHandler: function (form) {

                    var index = layer.load(2, {time: 10 * 1000}); //加载

                    $(form).ajaxSubmit({
                        type: 'POST',
                        url: "{{ URL::asset('admin/house/huxing/edit')}}",
                        success: function (ret) {
                            consoledebug.log(JSON.stringify(ret));
                            if (ret.result) {
                                layer.msg('保存成功', {icon: 1, time: 1000});
                                setTimeout(function () {
                                    var index = parent.layer.getFrameIndex(window.name);
                                    parent.$("btn-refresh").click();
                                    parent.layer.close(index);
                                }, 500)
                            } else {
                                layer.msg(ret.message, {icon: 2, time: 1000});
                            }

                            layer.close(index);
                        },
                        error: function (XmlHttpRequest, textStatus, errorThrown) {
                            layer.msg('保存失败', {icon: 2, time: 1000});
                            consoledebug.log("XmlHttpRequest:" + JSON.stringify(XmlHttpRequest));
                            consoledebug.log("textStatus:" + textStatus);
                            consoledebug.log("errorThrown:" + errorThrown);
                        }
                    });
                }

            });
        });

        //初始化七牛上传模块
        function initQNUploader() {
            var uploader = Qiniu.uploader({
                runtimes: 'html5,flash,html4',      // 上传模式，依次退化
                browse_button: 'pickfiles',         // 上传选择的点选按钮，必需
                container: 'container',//上传按钮的上级元素ID
                // 在初始化时，uptoken，uptoken_url，uptoken_func三个参数中必须有一个被设置
                // 切如果提供了多个，其优先级为uptoken > uptoken_url > uptoken_func
                // 其中uptoken是直接提供上传凭证，uptoken_url是提供了获取上传凭证的地址，如果需要定制获取uptoken的过程则可以设置uptoken_func
                uptoken: "{{$upload_token}}", // uptoken是上传凭证，由其他程序生成
                // uptoken_url: '/uptoken',         // Ajax请求uptoken的Url，强烈建议设置（服务端提供）
                // uptoken_func: function(file){    // 在需要获取uptoken时，该方法会被调用
                //    // do something
                //    return uptoken;
                // },
                get_new_uptoken: false,             // 设置上传文件的时候是否每次都重新获取新的uptoken
                // downtoken_url: '/downtoken',
                // Ajax请求downToken的Url，私有空间时使用，JS-SDK将向该地址POST文件的key和domain，服务端返回的JSON必须包含url字段，url值为该文件的下载地址
                unique_names: true,              // 默认false，key为文件名。若开启该选项，JS-SDK会为每个文件自动生成key（文件名）
                // save_key: true,                  // 默认false。若在服务端生成uptoken的上传策略中指定了sava_key，则开启，SDK在前端将不对key进行任何处理
                domain: 'http://twst.isart.me/',     // bucket域名，下载资源时用到，必需
                max_file_size: '100mb',             // 最大文件体积限制
                flash_swf_url: 'path/of/plupload/Moxie.swf',  //引入flash，相对路径
                max_retries: 3,                     // 上传失败最大重试次数
                dragdrop: true,                     // 开启可拖曳上传
                drop_element: 'container',          // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
                chunk_size: '4mb',                  // 分块上传时，每块的体积
                auto_start: true,                   // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
                //x_vars : {
                //    查看自定义变量
                //    'time' : function(up,file) {
                //        var time = (new Date()).getTime();
                // do something with 'time'
                //        return time;
                //    },
                //    'size' : function(up,file) {
                //        var size = file.size;
                // do something with 'size'
                //        return size;
                //    }
                //},
                init: {
                    'FilesAdded': function (up, files) {
                        plupload.each(files, function (file) {
                            // 文件添加进队列后，处理相关的事情
//                                            alert(alert(JSON.stringify(file)));
                        });
                    },
                    'BeforeUpload': function (up, file) {
                        // 每个文件上传前，处理相关的事情
//                        consoledebug.log("BeforeUpload up:" + up + " file:" + JSON.stringify(file));
                    },
                    'UploadProgress': function (up, file) {
                        // 每个文件上传时，处理相关的事情
//                        consoledebug.log("UploadProgress up:" + up + " file:" + JSON.stringify(file));
                    },
                    'FileUploaded': function (up, file, info) {
                        // 每个文件上传成功后，处理相关的事情
                        // 其中info是文件上传成功后，服务端返回的json，形式如：
                        // {
                        //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
                        //    "key": "gogopher.jpg"
                        //  }
//                        consoledebug.log(JSON.stringify(info));
                        var domain = up.getOption('domain');
                        var res = JSON.parse(info);
                        //获取上传成功后的文件的Url
                        var sourceLink = domain + res.key;
                        $("#image").val(sourceLink);
                        $("#pickfiles").attr('src', sourceLink);
//                        consoledebug.log($("#pickfiles").attr('src'));
                    },
                    'Error': function (up, err, errTip) {
                        //上传出错时，处理相关的事情
                        consoledebug.log(err + errTip);
                    },
                    'UploadComplete': function () {
                        //队列文件处理完毕后，处理相关的事情
                    },
                    'Key': function (up, file) {
                        // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                        // 该配置必须要在unique_names: false，save_key: false时才生效

                        var key = "";
                        // do something with key here
                        return key
                    }
                }
            });
        }

    </script>
@endsection