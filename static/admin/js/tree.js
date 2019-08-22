$(function(e) { 
    var TreeName = 'TreeList';//树状ID 
    var PrentNodeClass = 'ParentNode';//父节点的标识 
    var ChildNodeClass = 'ChildNode';//没有下级子节点的标识 
    var ChildrenListClass = 'Row';//子节点被包着的外层样式 
    var NewNodeName = '新建目录';//默认新建节点的名称 
    var Orititle = 'Temptitle';//保存原来名称的属性名称
    var getNodesInfoUrl = '/node/getNodesInfo';
    var createNodeUrl = '/node/createNode';
    var updateNodeUrl = '/node/updateNode';
    var deleteNodeUrl = '/node/deleteNode';
    var getNodeDetailUrl = '/node/getNodeDetail';
     
    var TModuleNode,TChildNode,TModuleNodeName; 
    TModuleNode = $('#TreeList .'+PrentNodeClass);//顶层节点 
    TChildNode = $('.'+ChildNodeClass); 
    TModuleNodeName = $('#TreeList .' + PrentNodeClass + ' .title');//顶层节点名称 
    TModuleNode.removeClass('show').addClass('hidden'); 
    if(TModuleNode.next().hasClass(ChildrenListClass)){ 
        TModuleNode.next().css('display','none');//紧跟的下一个是子节点 
    } 
     
    //========================编辑区域的HTML源码============================== 
    function EditHTML(NewName){ 
      var str = '<div class="title">' + NewName + '</div>'; 
      str    += '<div class="editBT"></div>'; 
      str    += '<div class="editArea"><span>[编辑]</span><span></span><span>[添加]</span><span>[删除]</span></div>';
      return str; 
    }

    //========================请求url==============================
    function requestUrl(url, data, successCb, failCb, method, extendData, completeCb){
        $.ajax({
            url: url,
            data: data,
            type: !method ? 'POST' : method,
            dataType: 'json',
            success: function(result) {
                if(result.code == 1000 || result.code == 999){
                    //成功
                    successCb(result, extendData);
                }else{
                    //失败
                    failCb(result);
                }
            },
            error: function (e) {
                alert('请求出现异常');
                console.log(e);
            },
            complete: function () {
                if(completeCb){
                    completeCb();
                }
            }
        });
    }

    //==========================获取节点信息============================
    var nodeInfoRequesting = 0;
    function getNodesInfo(Obj){
        if(nodeInfoRequesting == 1){
            return false;
        }
        nodeInfoRequesting = 1;
        requestUrl(getNodesInfoUrl,{'path': Obj.attr('node-path')}, getNodesInfoSuccessCb, reqFailCb, 'GET', {'Obj': Obj},function(){
            nodeInfoRequesting = 0;
        });
    }
    //==========================获取节点信息成功回调方法============================
    function getNodesInfoSuccessCb(result, extendData){
        var Obj = extendData.Obj;
        var nextObj = Obj.parent().next();
        if(nextObj.hasClass('Row')){
            nextObj.remove();
        }
        var operopts = '';
        if(actionAcl.update ==1){
          operopts += '<span>[编辑]</span>';
        }else{
          operopts += '<span></span>';
        }
        operopts += '<span></span>';
        if(actionAcl.create ==1){
          operopts += '<span>[添加]</span>';
        }else{
          operopts += '<span></span>';
        }
        if(actionAcl.delete ==1){
          operopts += '<span>[删除]</span>';
        }else{
          operopts += '<span></span>';
        }
        operopts += '<span class="nodeDetail">[详细]</span>';
        var html = '<div class="Row NewRow">';
        for (i in result.data){
            var node = result.data[i];
            html += '<div class="'+(node.childNum > 0 ? 'ParentNode hidden' : 'ChildNode')+'">';
            html += '<div class="title" node-path="'+node.nodePath+'" node-name="'+node.nodeName+'" node-value="'+node.nodeVal+'">'+node.nodeName+'</div>';
            html += '<div class="editBT"></div>';
            html += '<div class="editArea">'+operopts+'</div>';
            html += '</div>';
        }
        html += '</div>';
        Obj.parent().removeClass('hidden').addClass('show');
        Obj.parent().removeClass('ChildNode').addClass('ParentNode');
        Obj.parent().after(html);
        Obj.parent().next('.NewRow').find('.editArea').each(function(){
            EditArea_Event($(this));
        });
    }
    function reqFailCb(result){
        alert(result.message);
    }
     
    //==========================树状展开收缩的效果============================
    $(document).on('click','#TreeList .ParentNode .title',function() {
        TModuleNodeName_Click($(this));
    });
    //-------------------------------(定义)---------------------------------- 
    function TModuleNodeName_Click(Obj,forceReq){
        if(Obj.has('input').length==0){//非编辑模式下进行
            var tempNode = Obj.parent();
            if(tempNode.next('div.Row').length <= 0 || forceReq == 1){
                //未请求过节点信息
                getNodesInfo(Obj);
            }else{
              if(tempNode.hasClass('hidden')){  //当前在收缩状态
                  tempNode.removeClass('hidden').addClass('show');
                  if(tempNode.next().hasClass(ChildrenListClass)){
                      tempNode.next().css('display','');
                  }
              }
              else{ //当前在展开状态
                  tempNode.removeClass('show').addClass('hidden');
                  if(tempNode.next().hasClass(ChildrenListClass)){
                      tempNode.next().css('display','none');
                  }
              }
            }
        } 
    }     
    //==========================鼠标经过、离开节点的效果============================     
    // with(TModuleNode){ 
    //   mouseover(function(){ 
    //     TNode_MouseOver($(this)); 
    //   }); 
       
    //   mouseout(function(){ 
    //     TNode_MouseOut($(this)); 
    //   }); 
    // } 
     
    // with(TChildNode){ 
    //   mouseover(function(){ 
    //     TNode_MouseOver($(this)); 
    //   }); 
       
    //   mouseout(function(){ 
    //     TNode_MouseOut($(this)); 
    //   }); 
    // } 
     
    //-------------------------------(定义)---------------------------------- 
    function TNode_MouseOver(Obj){ 
      if(!(Obj.hasClass('show'))){ 
          Obj.addClass('mouseOver'); 
      } 
    } 
     
    function TNode_MouseOut(Obj){ 
      Obj.removeClass('mouseOver'); 
    } 
         
  //==========================编辑区操作============================     
    $('.editArea').each(function(){ 
        EditArea_Event($(this)); 
    }); 
    //-------------------------------(定义)---------------------------------- 
    function EditArea_Event(Obj){ 
        var objParent = Obj.parent(); 
        var objTitle = objParent.find('.title');//节点名称 
       //-----------------编辑区的鼠标效果------------------  
        Obj.children().each(function(){ 
          with($(this)){ 
              mouseover(function(){ 
                $(this).addClass('mouseOver'); 
              }); 
              mouseout(function(){ 
                $(this).removeClass('mouseOver'); 
              }); 
          } 
        }); 
       //-------------------------------------------------  
        Obj.children().each(function(index, element) { 
            $(this).click(function(){
              if($('#TreeList').has('input').length==0){
                switch(index){
                    case 0:    break;//EditNode(objTitle,objTitle.html());break;//编辑
                    case 1:    break;//AddNode(0,objParent,NewNodeRename(0,objTitle));break;//添加同级目录
                    case 2:    break;//AddNode(1,objParent,NewNodeRename(1,objTitle));break;//添加下级目录
                    case 3:    DelNode(objParent);break;//删除
                }
              }
              else{
                alert('请先取消编辑状态！');
              }
            }); 
        }); 
    } 
    //************************************************************************************************************ 
    //************************************************************************************************************ 
     
    //===============================验证编辑结果================================ 
    function CheckEdititon(pNode,text){ 
        var SameLevelTags    = new Array(PrentNodeClass,ChildNodeClass); 
        var SameLevelTag    = ''; 
        for(var i=0;i<SameLevelTags.length;i++){ 
            if(pNode.parent().attr('class').indexOf(SameLevelTags[i]) > -1){ 
                SameLevelTag = SameLevelTags[i]; 
                break; 
            } 
        } 
        if(SameLevelTag!=''){ 
          if(text!=''){ 
            //---------------- 根据节点样式遍历同级节点 -------------------- 
            var IsExsit = false; 
            pNode.parent().parent().children('div').children('.title').each(function(){ 
                if(pNode.find('input').val()==$(this).html()){ 
                    IsExsit = true; 
                    alert('抱歉！同级已有相同名称！'); 
                    return false; 
                } 
            }); 
            return !IsExsit; 
          } 
           
          else{ 
              alert('节点信息不能为空!'); 
              return false; 
          } 
        } 
    } 
     
    //=================================自动命名================================ 
    function NewNodeRename(tag,pNode){ 
        //---------------- 根据节点样式遍历同级节点 -------------------- 
        var MaxNum = 0; 
        var TObj; 
        if(tag==0){//添加同级目录 
            if(pNode.attr('id')==TreeName){ 
                TObj = pNode.children('div').children('.title'); 
            } 
            else{ 
                TObj = pNode.parent().parent().children('div').children('.title'); 
            } 
        } 
        else{//添加下级目录 
            if(pNode.parent().next().html()!=null){//原来已有子节点 
                TObj = pNode.parent().next().children('div').children('.title'); 
            } 
            else{//没有子节点 
                TObj = null; 
            } 
        } 
         
        if(TObj){ 
          TObj.each(function(){ 
              var CurrStr = $(this).html(); 
              var temp; 
              if(CurrStr.indexOf(NewNodeName)>-1){ 
                  temp = parseInt(CurrStr.replace(NewNodeName,'')); 
                  if(!isNaN(temp)){ 
                      if(!(temp<MaxNum)){ 
                          MaxNum = temp + 1; 
                      } 
                  } 
                  else{ 
                    MaxNum = 1;   
                  } 
              } 
          }); 
        } 
         
        var TempNewNodeName = NewNodeName; 
        if(MaxNum>0){ 
            TempNewNodeName    += MaxNum; 
        } 
        return TempNewNodeName; 
    } 
     
    //=============================== 编辑定义 ================================ 
    function EditNode(obj, text, newNode){
        obj.attr(Orititle,text);//将原来的text保存到Orititle中
        var nodeName = "<a style='float:left;'>"+obj.attr('node-name')+"&nbsp;</a>";
        var nodeValue = obj.attr('node-value');
        if(newNode == 1){ //添加
            nodeName = "<input type='text' class='input nodeName' placeholder='节点名称' value=''>";
            nodeValue = '';
        }
        obj.html(nodeName + "<input type='text' class='input nodeValue' placeholder='节点值' value=" + nodeValue + ">");//切换成编辑模式
        obj.parent().find('.editBT').html("<div class=ok title=确定></div><div class=cannel title=取消></div>"); 
        obj.has('input').children().first().focusEnd();//聚焦到编辑框内
   
        obj.parent().find('.ok').click(function(){ 
            Edit_OK(obj, newNode);
        }); 
         
        obj.parent().find('.cannel').click(function(){ 
            Edit_Cannel(obj, newNode);
        }); 
    } 

    function getNodesInfoSuccessCbAdd(result,extendData){
          
          var obj = extendData.Obj.parent();
          var newNode = $('<div class=' + ChildNodeClass + '></div>');  
          if(!obj.next() || !obj.next().hasClass('Row')){//最后一个节点和class含有ChildrenListClass都表示没有子节点
              if(result.code == 1000){
                  getNodesInfoSuccessCb(result,extendData);
              }else{
                  var ChildrenList = $('<div class=' + ChildrenListClass + '></div>');
                  ChildrenList.insertAfter(obj);//将子节点的”外壳“加入到对象后面
                  newNode.appendTo(ChildrenList);//将子节点加入到”外壳“内 
              }
              newNode.appendTo(obj.next());//将子节点加入到”外壳“内 
          } 
          else{ 
              newNode.appendTo(obj.next());//将子节点加入到”外壳“内 
          } 
          obj.attr('class',PrentNodeClass + ' show');//激活父节点展开状态模式 
          obj.next().css('display','');//展开子节点列表 
          with(newNode){ 
              html(EditHTML(extendData.nameStr)); 
              //---------------------------------动态添加事件------------------------------- 
              mouseover(function(){ 
                TNode_MouseOver($(this)); 
              }); 
               
              mouseout(function(){ 
                TNode_MouseOut($(this)); 
              }); 
               
              find('.title').click(function(){ 
                  TModuleNodeName_Click($(this)); 
              }); 
               
              find('.editArea').each(function(){ 
                  EditArea_Event($(this)); 
              }); 
              //--------------------------------------------------------------------------- 
          } 
          EditNode(newNode.find('.title'),newNode.find('.title').html(), 1);//添加后自动切换到编辑状态
    }
     
    //=============================== 添加 ================================ 
    function AddNode(tag,obj,nameStr){ 
        if(tag==0 || tag==1){ 
          var currentObj = obj.find('div.title');
          requestUrl(getNodesInfoUrl,{'path': currentObj.attr('node-path')}, getNodesInfoSuccessCbAdd, reqFailCb, 'GET', 
              {'Obj': currentObj,'nameStr': nameStr});
        } 
    } 
     
    //=============================== [删除]按钮 ================================ 
    function DelNode(obj){ //childNode
        layer.open({
            id:'deleteLayer',
            type: 1,
            title:'删除节点',
            skin:'layui-layer-rim',
            area:['300px', 'auto'],
            content: 
                  '<div style="margin-left: 18px;margin-top:20px;font-size:13px;">'
                  +'<div>'
                  +'<span>删除后无法恢复，确认删除'+obj.find('.title').attr('node-name')+'吗？</span>'
                  +'</div>'
                  +'</div>'
            ,
            btn:['确认','取消'],
            btn1: function (index,layero) {
                requestUrl(deleteNodeUrl,{'path': obj.find('.title').attr('node-path')}, function(result,extendData){
                    var objParent = obj.parent(); 
                    var objChildren = obj.next('.Row'); 
                    obj.remove();//基于Jquery是利用析构函数，所以“删除”后其相关属性仍然存在，除非针对ID来操作就可以彻底删除 
                    objChildren.remove();//删除对象所有子节点 
                    ChangeParent(objParent); 
                    layer.close(extendData.index);
                }, function(result){
                    layer.msg(result.message);
                }, 'POST', {'obj': obj,'index' : index});
            },
            btn2:function (index,layero) {
                 layer.close(index);
            }
        });
    } 
     
    //=============================== 编辑[确定]按钮 ================================ 
    function Edit_OK(obj, newNode){
        var nodeValue = $.trim(obj.find('input.nodeValue').val());
        if(CheckEdititon(obj,nodeValue)){
            //校验通过
            var nodeName = $.trim(obj.attr('node-name'));
            var nodePath = '';
            if(newNode == 1){
              //新增节点
              nodeName = obj.find('input.nodeName').val();
              pNodePath = obj.parent().parent().prev().find('.title').attr('node-path');
              nodePath = (pNodePath == '/' ? '' : pNodePath) + '/' + nodeName;
              //请求创建节点
              var extendData = {'obj': obj,'nodePath':nodePath,'nodeValue':nodeValue,'nodeName':nodeName};
              requestUrl(createNodeUrl,{'path': nodePath, 'val': nodeValue},function(result,extendData){
                  var obj = extendData.obj;
                  obj.html(nodeName + ': ' + nodeValue);
                  obj.attr('node-value', nodeValue);
                  obj.attr('node-name', nodeName);
                  obj.attr('node-path', nodePath);
                  obj.parent().find('.editBT').html(''); 
              },reqFailCb,'POST',extendData);
            }else{
              //编辑节点
              nodePath = obj.attr('node-path');
              //请求更新节点
              var extendData = {'obj': obj,'nodePath':nodePath,'nodeValue':nodeValue,'nodeName':nodeName};
              requestUrl(updateNodeUrl,{'path': nodePath, 'val': nodeValue},function(result,extendData){
                  var obj = extendData.obj;
                  obj.html(nodeName + ': ' + nodeValue);
                  obj.attr('node-value', nodeValue);
                  obj.attr('node-name', nodeName);
                  obj.attr('node-path', nodePath);
                  obj.parent().find('.editBT').html(''); 
              },reqFailCb,'POST',extendData);
            }
        }
        else{
            if(newNode == 1){
                return false;
            }
            obj.html(obj.attr('node-name') + ': ' + obj.attr('node-value'));
            obj.removeAttr(Orititle); 
            obj.parent().find('.editBT').html(''); 
        } 
    } 
     
    //=============================== 编辑[取消]按钮 ================================ 
    function Edit_Cannel(obj, newNode){
        if(newNode == 1){
            var pObj = obj.parent('.ChildNode').parent();
            if(pObj.children().length <= 1){ //子节点为空
                pObj.prev('.ParentNode').removeClass('ParentNode').addClass('ChildNode');
                pObj.remove();
            }else{
                obj.parent('.ChildNode').remove();
            }         
        }else{
            obj.html(obj.attr('node-name') + ': ' + obj.attr('node-value'));
            obj.removeAttr(Orititle);
            obj.parent().find('.editBT').html('');
        }
    } 
     
    //=============================== 改变父节点样式 ================================ 
    function ChangeParent(obj){ 
        if(obj.find('.ChildNode').length==0){//没有子节点 
            obj.prev('.'+PrentNodeClass).attr('class',ChildNodeClass); 
            obj.remove(); 
        } 
    } 
     
    //************************************************************************************************************ 
    //************************************************************************************************************ 
    //************************************************************************************************************ 
     
    //=============================== 设置聚焦并使光标设置在文字最后 ================================ 
    $.fn.setCursorPosition = function(position){   
        if(this.lengh == 0) return this;   
        return $(this).setSelection(position, position);   
    }   
       
    $.fn.setSelection = function(selectionStart, selectionEnd) {   
        if(this.lengh == 0) return this;   
        input = this[0];   
       
        if (input.createTextRange) { 
            var range = input.createTextRange();   
            range.collapse(true);  
            range.moveEnd('character', selectionEnd);   
            range.moveStart('character', selectionStart);   
            range.select();  
        } else if (input.setSelectionRange) {   
            input.focus();  
            input.setSelectionRange(selectionStart, selectionEnd);   
        }   
        return this;   
    }   
       
    $.fn.focusEnd = function(){ 
        this.setCursorPosition(this.val().length);
    } 
     
    //查看节点详细信息========================================================================================= 
    layui.use('layer',function(){
        var layer=layui.layer;
        $(document).on('click','span.nodeDetail',function(){
              
        });
    });

    //点击编辑/添加，弹出编辑框
    layui.use('layer',function(){
        var layer=layui.layer;
        $(document).on('click','div.editArea span',function(){
              var spanIndex = $(this).index();
              var node = $(this).parent().parent().find('.title');
              var nodePath = node.attr('node-path');
              if(spanIndex == 0){
                  updateNode(layer,nodePath);
              }else if(spanIndex == 2){
                  createNode(layer, nodePath, node);
              }else if(spanIndex == 4){
                  getNodeDetail(layer,nodePath);
              }
        });
    });

    //获取节点详情
    function getNodeDetail(layer,path){
        $.ajax({
          url: getNodeDetailUrl,
          data: {path: path},
          type: 'GET',
          dataType: 'json',
          success: function(result) {
              if(result.code == 1000){
                  //成功
                  var content = '<p><b style="color:#009688;">[nodePath]:</b> ' + path + '</p>';
                  for(i in result.data){
                      content += '<p><b style="color:#009688;">[' + i + ']:</b> ' + result.data[i] + '</p>';
                  }
                  layer.open({
                      title: '节点信息',
                      type:0,
                      area:['300px', 'auto'],
                      btn:[],
                      skin:'layui-layer-rim',
                      content:content,
                      offset: '200px',
                      shadeClose: true,
                  });
              }else{
                  //失败
                  layer.msg(result.message);
              }
          },
          error: function (e) {
              layer.msg('请求出现异常');
              console.log(e);
          }
      });
    }

    //创建节点
    function createNode(layer,nodePath, node){
        layer.open({
            id:'createLayer',
            type: 1,
            title:'添加节点',
            skin:'layui-layer-rim',
            area:['450px', 'auto'],
            content: 
                  '<div style="margin-left: 18px;margin-top:20px;font-size:13px;">'
                  +'<div>'
                  +'<span>父级路径：'+nodePath+'</span>'
                  +'</div>'
                  +'<div style="margin-top:10px;">'
                  +'<span>节点名称：</span>'
                  +'<input type="text" name="nodeName" value="" style="padding:2px;"/>'
                  +'</div>'
                  +'<div style="margin-top:10px;">'
                  +'<span>节点属性：</span>'
                  +'<label><input type="checkbox" name="nodeAttr" value="1" style="vertical-align:middle;margin-bottom:2px;"/> 序列化</label>'
                  +'</div>'
                  +'<div style="margin-top:10px;">'
                  +'<span>节点内容：</span>'
                  +'<textarea rows="10" cols="40" style="vertical-align: top; padding:3px; font-size:13px;"></textarea>'
                  +'</div>'
                  +'</div>'
            ,
            btn:['保存','取消'],
            btn1: function (index,layero) {
                var nodeVal = $.trim($('#createLayer textarea').val());
                var nodeName = $.trim($('#createLayer input[name="nodeName"]').val());
                var nodeAttr = 0;
                if(!nodeName){
                    layer.msg('请输入节点名称');
                    return false;
                }
                if(!nodeVal){
                    layer.msg('请输入节点内容');
                    return false;
                }
                if($('#createLayer input[name="nodeAttr"]').is(':checked')){
                    nodeAttr = 1;
                }
                $.ajax({
                  url: createNodeUrl,
                  data: {path: ((nodePath == '/' ? '' : nodePath) + '/' + nodeName), val: nodeVal, attr: nodeAttr},
                  type: 'POST',
                  dataType: 'json',
                  success: function(result) {
                      if(result.code == 1000){
                        layer.close(index);
                        //触发请求父级节点列表
                        TModuleNodeName_Click(node, 1);
                      }else{
                        layer.msg(result.message);
                      } 
                  },
                  error:function(){
                    layer.msg('请求出现异常');
                    console.log(e);
                  }
                });
            },
            btn2:function (index,layero) {
                 layer.close(index);
            }
      });
    }

    //更新节点
    function updateNode(layer,nodePath){
        $.ajax({
          url: getNodeDetailUrl,
          data: {path: nodePath},
          type: 'GET',
          dataType: 'json',
          success: function(result) {
              if(result.code == 1000){
                  layer.open({
                      id:'updateLayer',
                      type: 1,
                      title:'修改节点',
                      skin:'layui-layer-rim',
                      area:['450px', 'auto'],
                      content: 
                            '<div style="margin-left: 18px;margin-top:20px;font-size:13px;">'
                            +'<div>'
                            +'<span>节点路径：'+nodePath+'</span>'
                            +'</div>'
                            +'<div style="margin-top:10px;">'
                            +'<span>节点内容：</span>'
                            +'<textarea rows="10" cols="40" style="vertical-align: top; padding:3px; font-size:13px;">'+result.data.nodeVal+'</textarea>'
                            +'</div>'
                            +'</div>'
                      ,
                      btn:['保存','取消'],
                      btn1: function (index,layero) {
                          var nodeVal = $('#updateLayer textarea').val();
                          if(!nodeVal){
                              layer.msg('请输入节点内容');
                              return false;
                          }
                          $.ajax({
                            url: updateNodeUrl,
                            data: {path: nodePath, val: nodeVal},
                            type: 'POST',
                            dataType: 'json',
                            success: function(result) {
                                 layer.close(index);
                            },
                            error:function(){
                              layer.msg('请求出现异常');
                              console.log(e);
                            }
                          });
                      },
                      btn2:function (index,layero) {
                           layer.close(index);
                      }
                });
              }else{
                  //失败
                  layer.msg(result.message);
              }
          },
          error: function (e) {
              layer.msg('请求出现异常');
              console.log(e);
          }
      });
    }
}); 