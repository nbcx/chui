$(document).ready(function(){
	$("#upload_file").click(function(){ 
	doUpload(); 
	});
});
function doUpload() {
        // 上传方法
        var token=$('#token').val();
        $.upload({
            // 上传地址
            url: baseurl+'index.php/upload/qiniu',
            // 文件域名字
            fileName: 'file',
            // 其他表单数据
            params: {stb_csrf_token:token},
            // 上传完成后, 返回json, text
            dataType: 'json',
            // 上传之前回调,return true表示可继续上传
            onSend: function() {
                return true;
            },
            // 上传之后回调
            onComplate: function(data) {
                if(data.key){
                var addString = ' '+data.key +'\n';
                var textareaContain = $("#post_content").eq(0);
                $('#post_content').val(textareaContain.val()+addString);
                            //alert(data.key);
                } else {
                    alert(data.Err);
                }
            }
        });
}
