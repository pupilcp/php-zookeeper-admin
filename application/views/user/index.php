<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>用户列表</title>
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
            <ul>
                <li v-for="vo in address">
                    <a  v-text="vo.name" :href="vo.url" ></a> <span>/</span>
                </li>
            </ul>
            <?php $this->load->view('public/userinfo');?>
        </div>
    </header>

    <div class="main" id="app">
        <!--左栏-->
        <div class="left">
            <ul class="cl" >
                <!--顶级分类-->
                <li v-for="vo,index in menu" :class="{hidden:vo.hidden}">
                    <a href="javascript:;"  :class="{active:vo.active}"  @click="onActive(index)">
                        <i class="layui-icon" v-html="vo.icon"></i>
                        <span v-text="vo.name"></span>
                        <i class="layui-icon arrow" v-show="vo.url.length==0">&#xe61a;</i> <i v-show="vo.active" class="layui-icon active">&#xe623;</i>
                    </a>
                    <!--子级分类-->
                    <div v-for="vo2,index2 in vo.list">
                        <a href="javascript:;" :class="{active:vo2.active}" @click="onActive(index,index2)" v-text="vo2.name"></a>
                        <i v-show="vo2.active" class="layui-icon active">&#xe623;</i>
                    </div>
                </li>
            </ul>
        </div>
        <!--右侧-->
        <div class="right">
            <div class="layui-row">
                <div  class="layui-col-lg4">
                    <form>
                    <div class="layui-input-inline">
                        <input type="text" name="keyword" value="<?=$keyword?>" placeholder="用户名/邮箱" class="layui-input key">
                    </div>
                    <button type="submit" class="layui-btn sou">搜索</button>
                    </form>
                </div>
            </div>

            <table class="layui-table layui-form">

                <thead>
                <tr>
                    <th>NO</th>
                    <th>用户名</th>
                    <th>邮箱</th>
                    <th>角色</th>
                    <th>状态</th>
                    <th>注册时间</th>
                    <th>登录时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($users)):?>
                <?php foreach($users as $key => $user):?>
                <tr>
                    <td><?=($page-1)*$pageSize+$key+1;?></td>
                    <td><?=$user['username'];?></td>
                    <td><?=$user['email'];?></td>
                    <td><?=isset($roles[$user['role_id']])?$roles[$user['role_id']]['role_name']:''?></td>
                    <td><?=$user['is_active']==1?'启用':'<span style="color:red">禁用</span>';?></td>
                    <td><?=$user['create_time']?date('Y-m-d H:i:s',$user['create_time']):'';?></td>
                    <td><?=$user['login_time']?date('Y-m-d H:i:s',$user['login_time']):'';?></td>
                    <td>
                        <?php if(checkAcl('user_update')):?><a href="/user/update?id=<?=$user['id']?>">编辑</a><?php endif;?>
                        <?php if(checkAcl($user['is_active']==0 ? 'user_active' : 'user_forbid')):?>
                        <a href="javascript:;" data-username="<?=$user['username']?>" data-uid="<?=$user['id']?>" data-status="<?=$user['is_active']?>" class="user-active"><?=$user['is_active']==0?'启用':'禁用';?></a>
                        <?php endif;?>
                        <?php if(checkAcl('user_delete')):?><a href="javascript:;" data-username="<?=$user['username']?>" data-uid="<?=$user['id']?>" class="user-delete">删除</a><?php endif;?>
                    </td>
                </tr>
                <?php endforeach;?>
                <?php endif;?>
                </tbody>
            </table>
            <div class="page">
                <ul class="pagination">
                    <?=$pageLink?>
                </ul>
            </div>
        </div>
    </div>
</div>
<script src="/static/admin/js/config.js"></script>
<script src="/static/admin/js/script.js"></script>
<script src="/static/admin/js/user.js"></script>
</body>
</html>
