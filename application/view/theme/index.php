<h1>网站外观</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum, risus lacinia scelerisque facilisis, tellus felis tristique mauris, nec dignissim felis mi ac quam. Nulla at justo eget augue congue feugiat vel ut leo. Vestibulum magna sem, sollicitudin nec gravida vel, feugiat viverra eros.</p>
<p>
    <a theme-pjax href="/admin/theme/lists" class="button_link" >可以使用的外观</a>
    <a theme-pjax href="/admin/theme/edit" class="button_link" >编辑当前外观</a>
    <a theme-pjax href="/admin/theme/config" class="button_link" >设置外观</a>
</p>

<div id="theme">
    <include file="theme/lists" />
</div>
<script>
    jQuery(document).pjax('[theme-pjax] a, a[theme-pjax]', '#theme');
</script>
<!--
<a href="presentation.html" class="button_link" >Back to index</a>
-->
