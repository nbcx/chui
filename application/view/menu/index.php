<h1>后台菜单管理</h1>
<p>
    菜单分为三个深度，一个是最上面的级别，称之为节点，节点不能加入dash,不能跳转本页面，你可以修改菜单的名称等信息<br/>
    你也可以拖过拖动对菜单的顺序进行重新排列
</p>
<div class="clearboth"></div>

<link rel="stylesheet" href="_pub_plugin/ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">

<style type="text/css">
    .ztree li span.button.pIcon01_ico_open{margin-right:2px; background: url(_pub_plugin/ztree/css/zTreeStyle/img/diy/1_open.png) no-repeat scroll 0 0 transparent; vertical-align:top; *vertical-align:middle}
    .ztree li span.button.pIcon01_ico_close{margin-right:2px; background: url(_pub_plugin/ztree/css/zTreeStyle/img/diy/1_close.png) no-repeat scroll 0 0 transparent; vertical-align:top; *vertical-align:middle}
    .ztree li span.button.pIcon02_ico_open, .ztree li span.button.pIcon02_ico_close{margin-right:2px; background: url(_pub_plugin/ztree/css/zTreeStyle/img/diy/2.png) no-repeat scroll 0 0 transparent; vertical-align:top; *vertical-align:middle}
    .ztree li span.button.icon01_ico_docu{margin-right:2px; background: url(_pub_plugin/ztree/css/zTreeStyle/img/diy/3.png) no-repeat scroll 0 0 transparent; vertical-align:top; *vertical-align:middle}
    .ztree li span.button.icon02_ico_docu{margin-right:2px; background: url(_pub_plugin/ztree/css/zTreeStyle/img/diy/4.png) no-repeat scroll 0 0 transparent; vertical-align:top; *vertical-align:middle}
    .ztree li span.button.icon03_ico_docu{margin-right:2px; background: url(_pub_plugin/ztree/css/zTreeStyle/img/diy/5.png) no-repeat scroll 0 0 transparent; vertical-align:top; *vertical-align:middle}
    .ztree li span.button.icon04_ico_docu{margin-right:2px; background: url(_pub_plugin/ztree/css/zTreeStyle/img/diy/6.png) no-repeat scroll 0 0 transparent; vertical-align:top; *vertical-align:middle}
    .ztree li span.button.icon05_ico_docu{margin-right:2px; background: url(_pub_plugin/ztree/css/zTreeStyle/img/diy/7.png) no-repeat scroll 0 0 transparent; vertical-align:top; *vertical-align:middle}
    .ztree li span.button.icon06_ico_docu{margin-right:2px; background: url(_pub_plugin/ztree/css/zTreeStyle/img/diy/8.png) no-repeat scroll 0 0 transparent; vertical-align:top; *vertical-align:middle}

    div.content_wrap {width: 860px;height:380px;}
    div.content_wrap div.left{float: left;width: 150px;margin-top: 10px;}
    div.content_wrap div.right{float: right;width: 690px;}
    div.zTreeDemoBackground {width:250px;height:362px;text-align:left;}
</style>
<div class="content_wrap">
    <div class="zTreeDemoBackground left">
        <ul id="treeDemo" class="ztree"></ul>
    </div>
    <div class="right">
        <form action="/admin/menu/info" method="post" id="menu-edit">
            <fieldset id="pjax-menu">
                <include file="menu/info" />
            </fieldset>
        </form>
    </div>
</div>

<script type="text/javascript">
    setting = {
        edit: {
            drag: {
                autoExpandTrigger: false,
                prev: dropPrev,
                inner: dropInner,
                next: dropNext
            },
            enable: true,
            showRemoveBtn: false,
            showRenameBtn: false
        },
        data: {
            simpleData: {
                enable: true
            }
        },
        callback: {
            onClick: onClick,
            beforeDrag: beforeDrag,
            beforeDrop: beforeDrop,
            beforeDragOpen: beforeDragOpen,
            onDrag: onDrag,
            onDrop: onDrop,
            onExpand: onExpand
        }
    };

    function onClick(event, treeId, treeNode, clickFlag) {
        if(treeNode) {
            $.pjax({url: '/admin/menu/info?id='+treeNode.id, container: '#pjax-menu'})
        }
        showLog("[ "+getTime()+" onClick ]&nbsp;&nbsp;clickFlag = " + clickFlag + " (" + (clickFlag===1 ? "普通选中": (clickFlag===0 ? "<b>取消选中</b>" : "<b>追加选中</b>")) + ")");
    }

    function dropPrev(treeId, nodes, targetNode) {
        //禁止拖到父节点前面去
        return !targetNode.isParent;
    }

    function dropInner(treeId, nodes, targetNode) {
        if (targetNode && targetNode.dropInner === false) {
            return false;
        }
        else {
            for (var i=0,l=curDragNodes.length; i<l; i++) {
                if (!targetNode && curDragNodes[i].dropRoot === false) {
                    return false;
                }
                else if (curDragNodes[i].parentTId && curDragNodes[i].getParentNode() !== targetNode && curDragNodes[i].getParentNode().childOuter === false) {
                    return false;
                }
            }
        }
        return true;
    }
    function dropNext(treeId, nodes, targetNode) {
        //禁止拖到父节点后面去
        return !targetNode.isParent;
    }

    var log, className = "dark", curDragNodes, autoExpandNode;
    //用于捕获节点被拖拽之前的事件回调函数，并且根据返回值确定是否允许开启拖拽操作
    function beforeDrag(treeId, treeNodes) {
        showLog("[ "+getTime()+" beforeDrag ] drag: " + treeNodes.length + " nodes." );
        for (var i=0,l=treeNodes.length; i<l; i++) {
            if (treeNodes[i].drag === false) {
                curDragNodes = null;
                return false;
            } else if (treeNodes[i].parentTId && treeNodes[i].getParentNode().childDrag === false) {
                curDragNodes = null;
                return false;
            }
        }
        curDragNodes = treeNodes;
        return true;
    }
    //用于捕获拖拽节点移动到折叠状态的父节点后，
    //即将自动展开该父节点之前的事件回调函数，并且根据返回值确定是否允许自动展开操作
    function beforeDragOpen(treeId, treeNode) {
        autoExpandNode = treeNode;
        return true;
    }
    //用于捕获节点拖拽操作结束之前的事件回调函数，
    //并且根据返回值确定是否允许此拖拽操作
    function beforeDrop(treeId, treeNodes, targetNode, moveType, isCopy) {
        showLog("[ "+getTime()+" beforeDrop ] moveType:" + moveType);
        showLog("target: " + (targetNode ? targetNode.name : "root") + "  -- is "+ (isCopy==null? "cancel" : isCopy ? "copy" : "move"));
        return true;
    }
    //用于捕获节点被拖拽的事件回调函数
    //如果设置了 setting.callback.beforeDrag 方法，且返回 false，
    //将无法触发 onDragMove 和 onDrag 事件回调函数。
    function onDrag(event, treeId, treeNodes) {
        showLog("[ "+getTime()+" onDrag ] drag: " + treeNodes.length + " nodes." );
    }
    //用于捕获节点拖拽操作结束的事件回调函数
    //如果设置了 setting.callback.beforeDrop 方法，且返回 false，将无法触发 onDrop 事件回调函数。
    function onDrop(event, treeId, treeNodes, targetNode, moveType, isCopy) {
        console.log('treeId',treeId);
        console.log('treeNodes',treeNodes);
        console.log('targetNode',targetNode);
        //showLog("[ "+getTime()+" onDrop ] moveType:" + moveType);
        //showLog("target: " + (targetNode ? targetNode.name : "root") + "  -- is "+ (isCopy==null? "cancel" : isCopy ? "copy" : "move"))
    }
    //用于捕获节点被展开的事件回调函数
    //如果设置了 setting.callback.beforeExpand 方法，且返回 false，将无法触发 onExpand 事件回调函数。
    function onExpand(event, treeId, treeNode) {
        if (treeNode === autoExpandNode) {
            showLog("[ "+getTime()+" onExpand ]" + treeNode.name);
        }
    }

    function showLog(str) {
        console.log(str);
    }
    function getTime() {
        var now= new Date(),
            h=now.getHours(),
            m=now.getMinutes(),
            s=now.getSeconds(),
            ms=now.getMilliseconds();
        return (h+":"+m+":"+s+ " " +ms);
    }

    $(document).ready(function(){
        $.fn.zTree.init($("#treeDemo"), setting, {$ztree});
        $(document).on('submit', '#menu-edit', function (event) {
            $.pjax.submit(event, '#pjax-menu');
        });
    });
    /*
   [
       { id:1, pId:0, name:"控制台", open:true, iconSkin:"pIcon01", drag:false, dropInner:false, dropPrev:false},
       { id:11, pId:1, name:"叶子节点1", iconSkin:"icon01", dropRoot:false, dropInner:false},
       { id:12, pId:1, name:"叶子节点2", iconSkin:"icon02", dropRoot:false, dropInner:false},
       { id:13, pId:1, name:"叶子节点3", iconSkin:"icon03", dropRoot:false, dropInner:false},

       { id:2, pId:0, name:"板块&帖子", open:true, iconSkin:"pIcon02", drag:false, dropPrev:false},


       { id:231, pId:22, name:"叶子节点1", iconSkin:"icon06", dropRoot:false, dropInner:false},
       { id:232, pId:22, name:"叶子节点2", iconSkin:"icon06", dropRoot:false, dropInner:false},


       { id:22, pId:2, name:"板块&帖子2", iconSkin:"icon05", dropRoot:false, dropInner:false},
       { id:21, pId:2, name:"板块&帖子1", iconSkin:"icon04", dropRoot:false, dropInner:false},
       { id:23, pId:2, name:"板块&帖子3", iconSkin:"icon06", dropRoot:false, dropInner:false},


       { id:3, pId:0, name:"管理", open:true, drag:false},
       { id:31, pId:3, name:"叶子节点1", dropRoot:false, dropInner:false},
       { id:32, pId:3, name:"叶子节点2", dropRoot:false, dropInner:false},
       { id:33, pId:3, name:"叶子节点3", dropRoot:false, dropInner:false},

       { id:4, pId:0, name:"用户&组", open:true , drag:false},
       { id:41, pId:4, name:"叶子节点1", dropRoot:false, dropInner:false},
       { id:42, pId:4, name:"叶子节点2", dropRoot:false, dropInner:false},
       { id:43, pId:4, name:"叶子节点3", dropRoot:false, dropInner:false},

       { id:5, pId:0, name:"设置", open:true},
       { id:51, pId:5, name:"叶子节点1", dropRoot:false, dropInner:false},
       { id:52, pId:5, name:"叶子节点2", dropRoot:false, dropInner:false},
       { id:53, pId:5, name:"叶子节点3", dropRoot:false, dropInner:false}
   ]*/
</script>