/*
 * Carbon-Forum
 * https://github.com/lincanbin/Carbon-Forum
 *
 * Copyright 2006-2017 Canbin Lin (lincanbin@hotmail.com)
 * http://www.94cb.com/
 *
 * Licensed under the Apache License, Version 2.0:
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * A high performance open-source forum software written in PHP. 
 */

function initNewTopicEditor() {

	//编辑器外Ctrl + Enter提交回复
	document.body.onkeydown = function (event) {
		ctrlAndEnter(event, true);
	};

	//话题自动补全
	// Initialize ajax autocomplete:
	$("#alternativeTag").autocomplete({
		serviceUrl: '/tag/autocomplete',
		minChars: 2,
		type: 'post'
	});
	$("#alternativeTag").keydown(function (e) {
		var e = e || event;
		switch (e.keyCode) {
			case 13:
				if ($("#alternativeTag").val().length !== 0) {
					AddTag($("#alternativeTag").val(), Math.round(new Date().getTime() / 1000));
				}
				break;
			case 8:
				if ($("#alternativeTag").val().length === 0) {
					var LastTag = $("#selectTags").children().last();
					TagRemove(LastTag.children().attr("value"), LastTag.attr("id").replace("Tag", ""));
				}
				break;
			default:
				return true;
		}
	});
}


//Ctrl + Enter操作接收函数
function ctrlAndEnter(event, isPreventDefault) {
	//console.log("keydown");
	if (event.keyCode === 13) {
		if (isPreventDefault) {
			//屏蔽Tag输入框的回车提交，阻止回车的默认操作
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
		}
		if (event.ctrlKey) {
			$("#PublishButton").click();
		}
	}
}

//提交前的检查
function CreateNewTopic() {
	if (!document.NewForm.Title.value.length) {
		alert(Lang['Title_Can_Not_Be_Empty']);
		document.NewForm.Title.focus();
		return false;
	} else if (document.NewForm.Title.value.replace(/[^\x00-\xff]/g, "***").length > MaxTitleChars) {
		alert(Lang['Title_Too_Long'].replace("{{MaxTitleChars}}", MaxTitleChars).replace("{{Current_Title_Length}}", document.NewForm.Title.value.replace(/[^\x00-\xff]/g, "***").length));
		document.NewForm.Title.focus();
		return false;
	} else if (AllowEmptyTags === false && !$("#SelectTags").html()) {
		if ($("#AlternativeTag").val().length !== 0) {
			AddTag($("#AlternativeTag").val(), Math.round(new Date().getTime() / 1000));
		} else {
			alert(Lang['Tags_Empty']);
			document.NewForm.AlternativeTag.focus();
			return false;
		}
	} else {
		$("#PublishButton").val(Lang['Submitting']);
		UE.getEditor('editor').setDisabled('fullscreen');
		$.ajax({
			url: WebsitePath + '/new',
			data: {
				FormHash: document.NewForm.FormHash.value,
				Title: document.NewForm.Title.value,
				Content: UE.getEditor('editor').getContent(),
				Tag: $("input[name='Tag[]']").map(function () {
					return $(this).val();
				}).get()
			},
			type: 'post',
			cache: false,
			dataType: 'json',
			async: true,
			success: function (data) {
				if (data.Status === 1) {
					$("#PublishButton").val(Lang['Submit_Success']);
					$.pjax({
						url: WebsitePath + "/t/" + data.TopicID,
						container: '#main'
					});
					//location.href = WebsitePath + "/t/" + data.TopicID;
					if (window.localStorage) {
						//清空草稿箱
						StopTopicAutoSave();
					}
				} else {
					alert(data.ErrorMessage);
					UE.getEditor('editor').setEnabled();
				}
			},
			error: function () {
				alert(Lang['Submit_Failure']);
				UE.getEditor('editor').setEnabled();
				$("#PublishButton").val(Lang['Submit_Again']);
			}
		});
	}
	return true;
}

function checkTag(tagName, IsAdd) {
	tagName = $.trim(tagName);
	var show = true;
	var i = 1;
	$("input[name='tag[]']").each(function (index) {
		if (IsAdd && i >= MaxTagNum) {
			alert("最多只能插入{{MaxTagNum}}个话题！".replace("{{MaxTagNum}}", MaxTagNum));
			show = false;
		}
		if (tagName === $(this).val() || tagName === '') {
			show = false;
		}
		//简单的前端过滤，后端有更严格的白名单过滤所以这里随便写个正则应付下了。
		if (tagName.match(/[&|<|>|"|']/g) !== null) {
			//alert('Invalid input! ')
			show = false;
		}
		i++;
	});
	return show;
}

function getTags() {
	return;
	var CurrentContentHash = md5(document.NewForm.Title.value + UE.getEditor('editor').getContentTxt());
	//取Title与Content 联合Hash值，与之前input的内容比较，不同则开始获取话题，随后保存进hidden input。
	if (CurrentContentHash !== document.NewForm.ContentHash.value) {
		if (document.NewForm.Title.value.length || UE.getEditor('editor').getContentTxt().length) {
			$.ajax({
				url: WebsitePath + '/json/get_tags',
				data: {
					title: document.form_topic.title.value,
					content: UE.getEditor('editor').getContentTxt()
				},
				type: 'post',
				dataType: 'json',
				success: function (data) {
					if (data.status) {
						$("#tagsList").html('');
						for (var i = 0; i < data.lists.length; i++) {
							if (CheckTag(data.lists[i], 0)) {
								TagsListAppend(data.lists[i], i);
							}
						}
						//$("#TagsList").append('<div class="c"></div>');
					}
				}
			});
		}
		document.form_topic.ContentHash.value = CurrentContentHash;
	}
}

function tagsListAppend(tagName, id) {
	$("#tagsList").append('<a href="###" onclick="javascript:addTag(\'' + tagName + '\',' + id + ');getTags();" id="tagsList' + id + '">' + tagName + '&nbsp;+</a>');
	//document.NewForm.AlternativeTag.focus();
}

function addTag(tagName, id) {
	if (checkTag(tagName, 1)) {
		$("#selectTags").append('<a href="###" onclick="javascript:tagRemove(\'' + tagName + '\',' + id + ');" id="tag' + id + '">' + tagName + '&nbsp;×<input type="hidden" name="tag[]" value="' + tagName + '" /></a>');
		$("#tagsList" + id).remove();
	}
	//document.NewForm.AlternativeTag.focus();
	$("#alternativeTag").val("");
	if ($("input[name='tag[]']").length === MaxTagNum) {
		$("#alternativeTag").attr("disabled", true);
		$("#alternativeTag").attr("placeholder", "最多只能插入{{MaxTagNum}}个话题！".replace("{{MaxTagNum}}", MaxTagNum));
	}
}


function tagRemove(tagName, id) {
	$("#tag" + id).remove();
	tagsListAppend(tagName, id);
	if ($("input[name='tag[]']").length < MaxTagNum) {
		$("#alternativeTag").attr("disabled", false);
		$("#alternativeTag").attr("placeholder", '添加话题(按Enter添加)');
	}
	document.form_topic.alternativeTag.focus();
}

//Save Draft
function SaveTopicDraft() {
	try {
		var tagsList = JSON.stringify($("input[name='Tag[]']").map(function () {
			return $(this).val();
		}).get());
		if (document.NewForm.Title.value.length >= 4) {
			localStorage.setItem(Prefix + "TopicTitle", document.NewForm.Title.value);
		}
		if (UE.getEditor('editor').getContent().length >= 10) {
			localStorage.setItem(Prefix + "TopicContent", UE.getEditor('editor').getContent());
		}
		if (tagsList) {
			localStorage.setItem(Prefix + "TopicTagsList", TagsList);
		}
	}
	catch (oException) {
		if (oException.name === 'QuotaExceededError') {
			console.log('Draft Overflow! ');
			localStorage.clear();//Clear all draft
			saveTopicDraft();//Save draft again
		}
	}

}

function stopTopicAutoSave() {
	clearInterval(SaveDraftTimer); //停止保存
	localStorage.removeItem(Prefix + "TopicTitle"); //清空标题
	localStorage.removeItem(Prefix + "TopicContent"); //清空内容
	localStorage.removeItem(Prefix + "TopicTagsList"); //清空标签
	UE.getEditor('editor').execCommand("clearlocaldata"); //清空Ueditor草稿箱
}

function recoverTopicContents() {
	var DraftTitle = localStorage.getItem(Prefix + "TopicTitle");
	var DraftContent = localStorage.getItem(Prefix + "TopicContent");
	var DraftTagsList = JSON.parse(localStorage.getItem(Prefix + "TopicTagsList"));
	if (DraftTitle) {
		document.NewForm.Title.value = DraftTitle;
	}
	if (DraftContent) {
		UE.getEditor('editor').setContent(DraftContent);
	} else {
		UE.getEditor('editor').execCommand('cleardoc');
	}
	if (DraftTagsList) {
		for (var i = DraftTagsList.length - 1; i >= 0; i--) {
			AddTag(DraftTagsList[i], Math.round(new Date().getTime() / 1000) + i * 314159);
		}
	}
}
