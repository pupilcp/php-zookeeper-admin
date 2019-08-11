
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>用户列表</title>
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
            <!-- tree begin -->
            <div id="TreeList"> 
                <div class="ParentNode show"> 
                    <div class="title">酷站欣赏</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                </div> 
                <div class="Row"> 
                  <div class="ChildNode"> 
                    <div class="title">欧美酷站</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                  <div class="ChildNode"> 
                    <div class="title">日韩酷站</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                  <div class="ChildNode"> 
                    <div class="title">国内酷站</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                  <div class="ChildNode"> 
                    <div class="title">婚庆摄影</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                  <div class="ChildNode"> 
                    <div class="title">餐饮食品</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                </div> 
                  <div class="ParentNode hidden"> 
                  <div class="title">设计网址</div> 
                  <div class="editBT"></div> 
                  <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                </div> 
                <div class="Row"> 
                  <div class="ParentNode"> 
                    <div class="title">综合设计</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                  <div class="Row"> 
                    <div class="ChildNode"> 
                      <div class="title">Arting365</div> 
                      <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                    </div> 
                    <div class="ChildNode"> 
                      <div class="title">视觉中国</div> 
                      <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                    </div> 
                    <div class="ChildNode"> 
                      <div class="title">蓝色理想</div> 
                      <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                    </div> 
                    <div class="ChildNode"> 
                      <div class="title">网页设计师联盟</div> 
                      <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                    </div> 
                    <div class="ChildNode"> 
                      <div class="title">创意在线</div> 
                      <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                    </div> 
                    <div class="ChildNode"> 
                      <div class="title">视觉同盟</div> 
                      <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                    </div> 
                  </div> 
                  <div class="ParentNode hidden"> 
                    <div class="title">平面设计</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                  <div class="Row"> 
                    <div class="ChildNode"> 
                      <div class="title">优艾网</div> 
                      <div class="editBT"></div> 
                      <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                    </div> 
                    <div class="ChildNode"> 
                      <div class="title">中国设计网</div> 
                      <div class="editBT"></div> 
                      <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                    </div> 
                    <div class="ChildNode"> 
                      <div class="title">设计酷</div> 
                      <div class="editBT"></div> 
                      <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                    </div> 
                    <div class="ChildNode"> 
                      <div class="title">平面设计在线</div> 
                      <div class="editBT"></div> 
                      <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                    </div> 
                  </div> 
                </div> 
                  <div class="ParentNode show"> 
                  <div class="title">设计欣赏</div> 
                  <div class="editBT"></div> 
                  <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                </div> 
                <div class="Row"> 
                  <div class="ChildNode"> 
                    <div class="title">网页设计</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                  <div class="ChildNode"> 
                    <div class="title">UI设计</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                  <div class="ChildNode"> 
                    <div class="title">VI设计</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                  <div class="ChildNode"> 
                    <div class="title">Logo设计</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                  <div class="ChildNode"> 
                    <div class="title">广告设计</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                  <div class="ChildNode"> 
                    <div class="title">摄影艺术</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                  <div class="ChildNode"> 
                    <div class="title">工艺设计</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                </div> 
                  <div class="ParentNode show"> 
                  <div class="title">设计交流</div> 
                  <div class="editBT"></div> 
                  <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                </div> 
              <div class="Row"> 
                  <div class="ChildNode"> 
                    <div class="title">设计理念</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                  <div class="ChildNode"> 
                    <div class="title">Photoshop教程</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                  <div class="ChildNode"> 
                    <div class="title">Flash教程</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                  <div class="ChildNode"> 
                    <div class="title">HTML/CSS教程</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                  <div class="ChildNode"> 
                    <div class="title">JS/Jquery教程</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                  </div> 
                <div class="ChildNode"> 
                    <div class="title">SEO及建站推广</div> 
                    <div class="editBT"></div> 
                    <div class="editArea"><span>[编辑]</span><span>[添加同级目录]</span><span>[添加下级目录]</span><span>[删除]</span></div> 
                </div> 
            </div> 
          </div> 
        <!-- tree end -->
        </div>
    </div>
</div>
<script src="/static/admin/js/tree.js"></script>
<script src="/static/admin/js/config.js"></script>
<script src="/static/admin/js/script.js"></script>
</body>
</html>
