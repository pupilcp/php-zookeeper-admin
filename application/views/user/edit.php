<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?=isset($id)?'编辑':'添加';?>用户</title>
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
            <ul><li><a href="/manage/index">首页</a> <span>/</span></li><li><a href="/user/index">用户管理</a> <span>/</span></li><li><a href="javascript:;">编辑用户</a> <span>/</span></li></ul>
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
                <legend><?=isset($id)?'编辑':'添加';?>用户</legend>
            </fieldset>


            <form class="layui-form " action="" method="POST">
                <div class="layui-form-item">
                    <label class="layui-form-label">用户名</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" lay-verify="name" <?=isset($username)?'disabled':''?> value="<?=$username??'';?>" maxlength="20" placeholder="不超过20个字符" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">密码</label>
                    <div class="layui-input-block">
                        <input type="password" name="pwd" lay-verify="pwd" placeholder="<?=isset($id) ? '密码留空不作修改' : '不少于8个字符'?>" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">邮箱</label>
                    <div class="layui-input-block">
                        <input type="text" name="email" lay-verify="required|email" value="<?=$email??'';?>" placeholder="" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">所属角色</label>
                    <div class="layui-input-block">
                        <select name="role" lay-verify="role">
                            <option value="0">请选择角色</option>
                            <?php foreach($roles as $role):?>
                            <option value="<?=$role['id'];?>" <?=(isset($role_id) && $role_id==$role['id']) ?'selected' : '';?>><?=$role['role_name'];?></option>
                            <?php endforeach;?>
                        </select>   
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">是否启用</label>
                    <div class="layui-input-block">
                        <input type="checkbox" value="1" name="state" lay-text="启用|禁用" <?=(!isset($is_active) ||$is_active==1) ?'checked' : '';?> lay-skin="switch">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <input type="hidden" name="userId" id="userId" value="<?=$id??0;?>" />
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
                    return '用户名不能超过20个字符';
                }else if(value.length <= 0){
                    return '用户名不能为空';
                }
            },
            role : function(value) {
                if (value <= 0) {
                    return '请选择角色';
                }
            }
        };
        if($('#userId').val() <= 0){
            verifyObj.pwd = function(value) {
                if (value.length < 8) {
                    return '至少输入8个字符';
                }
            }
        }
        form.verify(verifyObj);
        form.on('submit(submit)', function(data) {   
            var url = '/user/create';
            if(data.field.userId > 0){
                url = '/user/update';
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
                            location.href = '/user/index';
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
