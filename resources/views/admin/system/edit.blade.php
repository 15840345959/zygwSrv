@extends('admin.layouts.app')

@section('content')

    <div class="page-container">
        <form class="form form-horizontal" id="form-edit">
            {{csrf_field()}}
            <div class="row cl hidden">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>id：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="id" name="id" type="text" class="input-text"
                           value="{{ isset($data->id) ? $data->id : '' }}" placeholder="配置id">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>签到积分：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="qd_jifen" name="qd_jifen" type="number" class="input-text"
                           value="{{ isset($data->qd_jifen) ? $data->qd_jifen : '' }}" placeholder="请输入签到积分">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>推荐积分：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="tj_jifen" name="tj_jifen" type="number" class="input-text"
                           value="{{ isset($data->tj_jifen) ? $data->tj_jifen : '' }}" placeholder="请输入推荐积分">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>到访积分：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="df_jifen" name="df_jifen" type="number" class="input-text"
                           value="{{ isset($data->df_jifen) ? $data->df_jifen : '' }}" placeholder="请输入到访积分">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>成交积分：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="cj_jifen" name="cj_jifen" type="number" class="input-text"
                           value="{{ isset($data->cj_jifen) ? $data->cj_jifen : '' }}" placeholder="请输入成交积分">
                </div>
            </div>

            <div class="row cl" style="padding-top: 20px;">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <input class="btn btn-primary radius" type="submit" value="保存积分规则">
                    <button onClick="layer_close();" class="btn btn-default radius" type="button">取消</button>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')

    <script type="text/javascript">

        $(function () {
            $("#form-edit").validate({
                rules: {
                    qd_jifen: {
                        required: true,
                    },
                    tj_jifen: {
                        required: true,
                    },
                    df_jifen: {
                        required: true,
                    },
                    cj_jifen: {
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
                        url: "{{ URL::asset('/admin/system/edit')}}",
                        success: function (ret) {
                            console.log(JSON.stringify(ret));
                            if (ret.result) {
                                layer.msg('保存成功', {icon: 1, time: 1000});
                                setTimeout(function () {
                                    var index = parent.layer.getFrameIndex(window.name);
                                    parent.$(".btn-refresh").click();
                                    parent.layer.close(index);
                                }, 500)
                            } else {
                                layer.msg(ret.message, {icon: 2, time: 1000});
                            }

                            layer.close(index);
                        },
                        error: function (XmlHttpRequest, textStatus, errorThrown) {
                            layer.msg('保存失败', {icon: 2, time: 1000});
                            console.log("XmlHttpRequest:" + JSON.stringify(XmlHttpRequest));
                            console.log("textStatus:" + textStatus);
                            console.log("errorThrown:" + errorThrown);
                        }
                    });
                }

            });
        });

    </script>
@endsection