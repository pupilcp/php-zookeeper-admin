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