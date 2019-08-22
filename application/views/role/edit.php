<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?=isset($id)?'编辑':'添加';?>角色</title>
    <link rel="stylesheet" href="/static/common/layui/css/layui.css">
    <link rel="stylesheet" href="/static/admin/css/style.css">
    <script src="/static/common/layui/layui.js"></script>
    <script src="/static/common/jquery-3.3.1.min.js"></script>
    <script src="/static/common/vue.min.js"></script>
    <style type="text/css">
        .module-title{
            height: 30px;line-height: 20px; vertical-align: bottom;margin-right: 10px; color: #666;
        }
    </style>
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
            <ul><li><a href="/manage/index">首页</a> <span>/</span></li><li><a href="/role/index">角色管理</a> <span>/</span></li><li><a href="javascript:;">编辑角色</a> <span>/</span></li></ul>
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
                <legend><?=isset($id)?'编辑':'添加';?>角色</legend>
            </fieldset>


            <form class="layui-form " action="" method="POST">
                <div class="layui-form-item">
                    <label class="layui-form-label">角色名</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" <?=isset($role_name)?'disabled':''?> lay-verify="name" value="<?=$role_name??'';?>" maxlength="20" placeholder="不超过20个字符" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">权限列表</label>
                    <?php foreach($acls as $acl):?>
                    <div class="layui-input-block" style="width: 1000px;">
                        <span class="module-title"><?=$acl['title'];?>：</span>
                        <?php foreach($acl['action'] as $v):?>
                        <input type="checkbox" class="acl-action" name="acl[]" value="<?=$v['acl'];?>" title="<?=$v['title'];?>" lay-skin="primary" <?php if(isset($roleAcls) && in_array($v['acl'],$roleAcls)) echo 'checked';?> />
                        <?php endforeach;?>
                    </div>
                    <?php endforeach;?>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">是否启用</label>
                    <div class="layui-input-block">
                        <input type="checkbox" value="1" name="state" lay-text="启用|禁用" <?=(!isset($is_active) ||$is_active==1) ?'checked' : '';?> lay-skin="switch">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <input type="hidden" name="roleId" id="roleId" value="<?=$id??0;?>" />
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
                if (value.length > 20) {
                    return '不能超过20个字符';
                }}
            };
        form.verify(verifyObj);
        form.on('submit(submit)', function(data) {   
            var url = '/role/create';
            if(data.field.roleId > 0){
                url = '/role/update';
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
                            location.href = '/role/index';
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
