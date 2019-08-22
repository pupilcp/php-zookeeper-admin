<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?=isset($id)?'编辑':'添加';?>配置</title>
    <link rel="stylesheet" href="/static/common/layui/css/layui.css">
    <link rel="stylesheet" href="/static/admin/css/style.css">
    <script src="/static/common/layui/layui.js"></script>
    <script src="/static/common/jquery-3.3.1.min.js"></script>
    <script src="/static/common/vue.min.js"></script>
</head>
<body>
<div id="app">
    <!--顶栏-->
    <header>
        <h1 v-text="webname"></h1>
        <div class="breadcrumb">
            <i class="layui-icon">&#xe715;</i>
            <?php if(!isset($id)):?>
            <ul>
                <li v-for="vo in address">
                    <a  v-text="vo.name" :href="vo.url" ></a> <span>/</span>
                </li>
            </ul>
            <?php else:?>
            <ul><li><a href="/manage/index">首页</a> <span>/</span></li><li><a href="/config/index">配置管理</a> <span>/</span></li><li><a href="javascript:;">编辑配置</a> <span>/</span></li></ul>
            <?php endif;?>
            <?php $this->load->view('public/userinfo');?>
        </div>
    </header>

    <div class="main" id="app">
        <!--左栏-->
        <?php $this->load->view('public/menu');?>
        <!--右侧-->
        <div class="right">
            <fieldset class="layui-elem-field layui-field-title">
                <legend><?=isset($id)?'编辑':'添加';?>配置</legend>
            </fieldset>


            <form class="layui-form " action="" method="POST">
                <div class="layui-form-item">
                    <label class="layui-form-label">配置名</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" <?=isset($name)?'disabled':''?> lay-verify="name" value="<?=$name??'';?>" maxlength="30" placeholder="由英文字母/数字/下划线组成，不超过30个字符" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">配置内容</label>
                    <div class="layui-input-block">
                        <textarea name="content" placeholder="请输入配置内容" class="layui-textarea" lay-verify="required"><?=$content??'';?></textarea>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">配置描述</label>
                    <div class="layui-input-block">
                        <textarea name="intro" placeholder="不超过100个字符" class="layui-textarea" maxlength="100" lay-verify="intro"><?=$intro??'';?></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <input type="hidden" name="configId" id="configId" value="<?=$id??0;?>" />
                        <button class="layui-btn" lay-submit lay-filter="submit">保存</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<script src="/static/admin/js/config.js"></script>
<script src="/static/admin/js/script.js"></script>
<script type="text/javascript">
    
    layui.use('form', function() {
        var form = layui.form;
        var layer = layui.layer;
        var verifyObj = {   
            name : function(value) {
                if (value.length > 30) {
                    return '配置名不能超过30个字符';
                }else if(value.length <= 0){
                    return '配置名不能为空';
                }else if(!value.match(/^[\w_]+$/)){
                    return '配置名由英文字母/数字/下划线组成';
                }
            },
            intro : function(value) {
                if (value.length > 100) {
                    return '配置描述不能超过100个字符';
                }else if(value.length <= 0){
                    return '配置描述不能为空';
                }
            }
        };
        form.verify(verifyObj);
        form.on('submit(submit)', function(data) {   
            var url = '/config/create';
            if(data.field.configId > 0){
                url = '/config/update';
            }
            $.ajax({
                type : 'POST',
                url : url,
                data : data.field,
                dataType : 'json',
                success : function(data) {
                    if(data.code != 1000){
                        layer.msg(data.message);
                    }else{
                        layer.msg('保存成功');
                        setTimeout(function(){
                            location.href = '/config/index';
                        },1000);
                    }
                },
                error: function(e){
                    layer.msg('请求异常');
                    console.log(e);
                }
            });
            return false;
        });
    });
</script>
</body>
</html>
