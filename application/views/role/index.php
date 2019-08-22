
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>角色列表</title>
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
                        <input type="text" name="keyword" value="<?=$keyword?>" placeholder="角色名" class="layui-input key">
                    </div>
                    <button type="submit" class="layui-btn sou">搜索</button>
                    </form>
                </div>
            </div>

            <table class="layui-table layui-form">

                <thead>
                <tr>
                    <th>NO</th>
                    <th>角色名</th>
                    <th>状态</th>
                    <th>创建时间</th>
                    <th>更新时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($roles)):?>
                <?php foreach($roles as $key => $role):?>
                <tr>
                    <td><?=($page-1)*$pageSize+$key+1;?></td>
                    <td><?=$role['role_name'];?></td>
                    <td><?=$role['is_active']==1?'启用':'<span style="color:red">禁用</span>';?></td>
                    <td><?=$role['create_time']?date('Y-m-d H:i:s',$role['create_time']):'';?></td>
                    <td><?=$role['update_time']?date('Y-m-d H:i:s',$role['update_time']):'';?></td>
                    <td>
                        <?php if($role['role_name'] != ADMINISTRATOR_ROLE):?>
                            <?php if(checkAcl('role_update')):?>
                            <a href="/role/update?id=<?=$role['id']?>">编辑</a>
                            <?php endif;?>
                            <?php if(checkAcl($role['is_active']==0 ? 'role_active' : 'role_forbid')):?>
                            <a href="javascript:;" data-rolename="<?=$role['role_name']?>" data-roleid="<?=$role['id']?>" data-status="<?=$role['is_active']?>" class="role-active"><?=$role['is_active']==0?'启用':'禁用';?></a>  
                            <?php endif;?>    
                            <?php if(checkAcl('role_delete')):?>      
                            <a href="javascript:;" data-rolename="<?=$role['role_name']?>" data-roleid="<?=$role['id']?>" class="role-delete">删除</a>
                            <?php endif;?>
                        <?php endif;?>
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
<script src="/static/admin/js/role.js"></script>
</body>
</html>
