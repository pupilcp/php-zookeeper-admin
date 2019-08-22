var activeRoleStatusUrl = '/role/active';
var forbidRoleStatusUrl = '/role/forbid';
var deleteRoleUrl = '/role/delete';
$(document).on('click','a.role-active',function(){
    var initStatus = $(this).data('status');
    var roleId = $(this).data('roleid');
    layer.open({
      id:'statusLayer',
      type: 1,
      title:'提示信息',
      skin:'layui-layer-rim',
      area:['300px', 'auto'],
      content: 
            '<div style="margin-left: 18px;margin-top:20px;font-size:13px;">'
            +'<span>确认'+(initStatus == 1 ? '禁用' : '启用')+'角色【'+$(this).data('rolename')+'】吗</span>'
            +'</div>'
      ,
      btn:['确认','取消'],
      btn1: function (index,layero) {
          var status = initStatus == 1 ? 0 : 1;
          $.ajax({
            url: status == 1 ? activeRoleStatusUrl : forbidRoleStatusUrl,
            data: {roleId: roleId},
            type: 'POST',
            dataType: 'json',
            success: function(result) {
                if(result.code == 1000){
                    layer.msg('更新成功',{time:2000});
                    layer.close(index);
                    location.reload();
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
});


$(document).on('click','a.role-delete',function(){
    var roleId = $(this).data('uid');
    layer.open({
      id:'deleteLayer',
      type: 1,
      title:'提示信息',
      skin:'layui-layer-rim',
      area:['300px', 'auto'],
      content: 
            '<div style="margin-left: 18px;margin-top:20px;font-size:13px;">'
            +'<span>确认删除角色【'+$(this).data('rolename')+'】吗，删除后无法恢复！</span>'
            +'</div>'
      ,
      btn:['确认','取消'],
      btn1: function (index,layero) {
          $.ajax({
            url: deleteRoleUrl,
            data: {roleId: roleId},
            type: 'POST',
            dataType: 'json',
            success: function(result) {
                if(result.code == 1000){
                    layer.msg('删除成功',{time:2000});
                    layer.close(index);
                    location.reload();
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
});
