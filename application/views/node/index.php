<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>节点列表</title>
    <link rel="stylesheet" href="/static/common/layui/css/layui.css">
    <link rel="stylesheet" href="/static/admin/css/style.css">
    <link rel="stylesheet" href="/static/admin/css/tree.css"/>
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
    <div class="main" style="font-size: 13px;">
        <!--左栏-->
        <?php $this->load->view('public/menu');?>
        <!--右侧-->
        <div class="right">
            <div class="layui-row">
                <div  class="layui-col-lg4">
                    <form>
                        <div class="layui-input-inline" style="width: 200px">
                            <input type="text" name="path" value="<?=$nodePath?>" placeholder="输入节点路径" class="layui-input key"> 
                        </div>
                        <button type="submit" class="layui-btn sou">搜索</button>
                    </form>
                </div>
            </div>
            <hr/>
            <div class="layui-row">
                <div  class="layui-col-lg4">
                    <div class="layui-input-inline" style="width: 200px">
                        <?php if($nodeVal !== false): ?>
                        当前搜索节点路径：<?=$nodePath?>
                        <?php else: ?>
                        <b style="color: red; font-size: 13px;">错误信息：节点路径 <?=$nodePath?> 不存在</b>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if($nodeVal !== false): ?>
            <!-- tree begin -->
            <div id="TreeList"> 

                <div class="<?php if($childNum > 0): echo 'ParentNode'; endif;?> hidden">
                    <div class="title" node-path="<?=$nodePath?>" node-name="<?=$nodeName?>" node-value="<?=$nodeVal?>"><?=$nodeName?></div>
                    <div class="editBT"></div>
                    <div class="editArea">
                        <?php if(checkAcl('node_updatenode')):?>
                        <span>[编辑]</span>
                        <?php endif;?>
                        <span></span>
                        <?php if(checkAcl('node_createnode')):?>
                        <span>[添加]</span>
                        <?php endif;?>
                        <span></span>
                        <?php if(checkAcl('node_index')):?>
                        <span class="nodeDetail">[详细]</span>
                        <?php endif;?>
                    </div>
                </div> 
            </div> 
            <!-- tree end -->
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    var actionAcl = <?=json_encode($actionAcl);?>;
</script>
<script src="/static/admin/js/tree.js"></script>
<script src="/static/admin/js/config.js"></script>
<script src="/static/admin/js/script.js"></script>
</body>
</html>
