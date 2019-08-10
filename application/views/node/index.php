
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
                    <div class="layui-input-inline" style="width: 300px">
                        <input type="text" name="path" value="/" placeholder="输入节点路径" class="layui-input key">
                    </div>
                    <button type="button" class="layui-btn sou">搜索</button>
                </div>
            </div>
            <hr/>
            <div id="node-list"></div>
  
        </div>
    </div>
</div>
<script src="/static/admin/js/config.js"></script>
<script src="/static/admin/js/script.js"></script>
<script>
  layui.use("tree", function(){
    var tree = layui.tree;
    //渲染
    var inst1 = tree.render({
      elem: '#node-list'  //绑定元素
      ,click: function(obj){
        console.log(obj.data); //得到当前点击的节点数据
      },edit:['add', 'update', 'del']
      ,operate: function(obj){
    var type = obj.type; //得到操作类型：add、edit、del
    var data = obj.data; //得到当前节点的数据
    var elem = obj.elem; //得到当前节点元素
    
    //Ajax 操作
    var id = data.id; //得到节点索引
    if(type === 'add'){ //增加节点
      //返回 key 值
      return 123;
    } else if(type === 'update'){ //修改节点
      console.log(elem.find('.layui-tree-txt').html()); //得到修改后的内容
    } else if(type === 'del'){ //删除节点
      return false;
    };
  }
      ,data: [{
        title: '江西', //一级菜单
        id:'123'
        ,children: [{
          title: '南昌' //二级菜单
          ,children: [{
            title: '高新区', //三级菜单
            id: 'asdd'
            //…… //以此类推，可无限层级
          }]
        }]
      },{
        title: '陕西' //一级菜单
        ,children: [{
          title: '西安' //二级菜单
        }]
      }]
    });
  });
  </script>
</body>
</html>
