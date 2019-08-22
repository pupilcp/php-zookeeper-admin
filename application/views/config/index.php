<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>配置列表</title>
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
                        <input type="text" name="keyword" value="<?=$keyword?>" placeholder="配置名/描述" class="layui-input key">
                    </div>
                    <button type="submit" class="layui-btn sou">搜索</button>
                    </form>
                </div>
            </div>

            <table class="layui-table layui-form">

                <thead>
                <tr>
                    <th>NO</th>
                    <th>配置名</th>
                    <th>配置内容</th>
                    <th>配置描述</th>
                    <th>创建时间</th>
                    <th>更新时间</th>
                    <th>更新人</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($configs)):?>
                <?php foreach($configs as $key => $config):?>
                <tr>
                    <td><?=($page-1)*$pageSize+$key+1;?></td>
                    <td><?=$config['name'];?></td>
                    <td><?=$config['content'];?></td>
                    <td><?=$config['intro'];?></td>
                    <td><?=$config['create_time']?date('Y-m-d H:i:s',$config['create_time']):'';?></td>
                    <td><?=$config['update_time']?date('Y-m-d H:i:s',$config['update_time']):'';?></td>
                    <td><?=$config['update_user'];?></td>
                    <td>
                        <?php if(checkAcl('config_update')):?>
                        <a href="/config/update?id=<?=$config['id']?>">编辑</a>   
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
</body>
</html>
