<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	
	<title>Hello! Admin</title>

	<link type="text/css" href="style.css" rel="stylesheet" /> <!-- the layout css file -->
	<link type="text/css" href="css/jquery.cleditor.css" rel="stylesheet" />
	
	<script type='text/javascript' src='js/jquery-1.4.2.min.js'></script>	<!-- jquery library -->
	<script type='text/javascript' src='js/jquery-ui-1.8.5.custom.min.js'></script> <!-- jquery UI -->
	<script type='text/javascript' src='js/cufon-yui.js'></script> <!-- Cufon font replacement -->
	<script type='text/javascript' src='js/ColaborateLight_400.font.js'></script> <!-- the Colaborate Light font -->
	<script type='text/javascript' src='js/easyTooltip.js'></script> <!-- element tooltips -->
	<script type='text/javascript' src='js/jquery.tablesorter.min.js'></script> <!-- tablesorter -->
	
	<!--[if IE 8]>
		<script type='text/javascript' src='js/excanvas.js'></script>
		<link rel="stylesheet" href="css/IEfix.css" type="text/css" media="screen" />
	<![endif]--> 
 
	<!--[if IE 7]>
		<script type='text/javascript' src='js/excanvas.js'></script>
		<link rel="stylesheet" href="css/IEfix.css" type="text/css" media="screen" />
	<![endif]--> 
	
	<script type='text/javascript' src='js/visualize.jQuery.js'></script> <!-- visualize plugin for graphs / statistics -->
	<script type='text/javascript' src='js/iphone-style-checkboxes.js'></script> <!-- iphone like checkboxes -->
	<script type='text/javascript' src='js/jquery.cleditor.min.js'></script> <!-- wysiwyg editor -->

	<script type='text/javascript' src='js/custom.js'></script> <!-- the "make them work" script -->
</head>

<body>

	<div id="container">
		<div id="bgwrap">
			<div id="primary_left">
        
				<div id="logo">
					<a href="dashboard.html" title="Dashboard"><img src="assets/logo.png" alt="" /></a>
				</div> <!-- logo end -->

				<div id="menu"> <!-- navigation menu -->
					<ul>
						<li class="current"><a href="#" class="dashboard"><img src="assets/icons/small_icons_3/dashboard.png" alt=""/><span class="current">Dashboard</span></a></li>
						<li><a href="#"><img src="assets/icons/small_icons_3/posts.png" alt=""/><span>Posts</span></a></li>
						<li><a href="#"><img src="assets/icons/small_icons_3/media.png" alt=""/><span>Media</span></a>
							<ul>
								<li><a href="#">Upload</a></li>
								<li><a href="#">Add new</a></li>
								<li><a href="#">Categories</a></li>
							</ul>
						</li>
						<li class="tooltip" title="Menu items can also have a tooltip!"><a href="#"><img src="assets/icons/small_icons_3/notes.png" alt=""/><span>My notes</span></a></li>
						<li><a href="#"><img src="assets/icons/small_icons_3/coin.png" alt=""/><span>Earnings</span></a></li>
						<li><a href="#"><img src="assets/icons/small_icons_3/users.png" alt=""/><span>Users</span></a></li>
						<li><a href="#"><img src="assets/icons/small_icons_3/settings.png" alt=""/><span>Settings</span></a></li>
					</ul>
				</div> <!-- navigation menu end -->
			</div> <!-- sidebar end -->

			<div id="primary_right">
				<div class="inner">
				
					<h1>A simple data table</h1>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum, risus lacinia scelerisque facilisis, tellus felis tristique mauris, nec dignissim felis mi ac quam. Nulla at justo eget augue congue feugiat vel ut leo. Vestibulum magna sem, sollicitudin nec gravida vel, feugiat viverra eros.</p>
					<table class="normal tablesorter">
						<thead>
							<tr>
								<th>Select</th>
								<th>No</th>
								<th>User Image</th>
								<th>Full Name</th>
								<th>Subscription type</th>
								<th>Joined date</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr class="odd">
								<td><input type="checkbox" class="iphone" /></td>
								<td>211</td>
								<td><img src="_pub_assets/avatar.png" alt="" /></td>
								<td>Johnatan Doe</td>
								<td>6 months membership</td>
								<td>09-12-2011</td>
								<td>
									<a href="#" title="Edit this user" class="tooltip table_icon"><img src="assets/icons/actions_small/Pencil.png" alt="" /></a> 
									<a href="#" title="Preferences" class="tooltip table_icon"><img src="assets/icons/actions_small/Preferences.png" alt="" /></a>
									<a href="#" title="Delete this user" class="tooltip table_icon"><img src="assets/icons/actions_small/Trash.png" alt="" /></a>
								</td>
							</tr>
							<tr>
								<td><input type="checkbox" checked="checked" class="iphone" /></td>
								<td>107</td>
								<td><img src="_pub_assets/avatar.png" alt="" /></td>
								<td>Johnatan Doe</td>
								<td>1 month membership</td>
								<td>09-12-2011</td>
								<td>
									<a href="#" title="Edit this user" class="tooltip table_icon"><img src="assets/icons/actions_small/Pencil.png" alt="" /></a> 
									<a href="#" title="Preferences" class="tooltip table_icon"><img src="assets/icons/actions_small/Preferences.png" alt="" /></a>
									<a href="#" title="Delete this user" class="tooltip table_icon"><img src="assets/icons/actions_small/Trash.png" alt="" /></a>
								</td>
							</tr>
							<tr class="odd">
								<td><input type="checkbox" class="iphone" /></td>
								<td>34</td>
								<td><img src="_pub_assets/avatar.png" alt="" /></td>
								<td>Johnatan Doe</td>
								<td>9 months membership</td>
								<td>09-12-2011</td>
								<td>
									<a href="#" title="Edit this user" class="tooltip table_icon"><img src="assets/icons/actions_small/Pencil.png" alt="" /></a> 
									<a href="#" title="Preferences" class="tooltip table_icon"><img src="assets/icons/actions_small/Preferences.png" alt="" /></a>
									<a href="#" title="Delete this user" class="tooltip table_icon"><img src="assets/icons/actions_small/Trash.png" alt="" /></a>
								</td>
							</tr>
							<tr>
								<td><input type="checkbox" class="iphone" /></td>
								<td>48</td>
								<td><img src="_pub_assets/avatar.png" alt="" /></td>
								<td>Johnatan Doe</td>
								<td>3 months membership</td>
								<td>09-12-2011</td>
								<td>
									<a href="#" title="Edit this user" class="tooltip table_icon"><img src="assets/icons/actions_small/Pencil.png" alt="" /></a> 
									<a href="#" title="Preferences" class="tooltip table_icon"><img src="assets/icons/actions_small/Preferences.png" alt="" /></a>
									<a href="#" title="Delete this user" class="tooltip table_icon"><img src="assets/icons/actions_small/Trash.png" alt="" /></a>
								</td>
							</tr>
						</tbody>
					</table>
					<a href="presentation.html" class="button_link" >Back to index</a>
					
					<div class="clearboth"></div>
					<hr />
					
					<h1>A full width table</h1>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum, risus lacinia scelerisque facilisis, tellus felis tristique mauris, nec dignissim felis mi ac quam. Nulla at justo eget augue congue feugiat vel ut leo. Vestibulum magna sem, sollicitudin nec gravida vel, feugiat viverra eros.</p>
					
					<table class="normal tablesorter fullwidth">
						<thead>
							<tr>
								<th>Select</th>
								<th>No</th>
								<th>User Image</th>
								<th>Full Name</th>
								<th>Subscription type</th>
								<th>Joined date</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr class="odd">
								<td><input type="checkbox" class="iphone" /></td>
								<td>211</td>
								<td><img src="_pub_assets/avatar.png" alt="" /></td>
								<td>Johnatan Doe</td>
								<td>6 months membership</td>
								<td>09-12-2011</td>
								<td>
									<a href="#" title="Edit this user" class="tooltip table_icon"><img src="assets/icons/actions_small/Pencil.png" alt="" /></a> 
									<a href="#" title="Preferences" class="tooltip table_icon"><img src="assets/icons/actions_small/Preferences.png" alt="" /></a>
									<a href="#" title="Delete this user" class="tooltip table_icon"><img src="assets/icons/actions_small/Trash.png" alt="" /></a>
								</td>
							</tr>
							<tr>
								<td><input type="checkbox" checked="checked" class="iphone" /></td>
								<td>107</td>
								<td><img src="assets/avatar.png" alt="" /></td>
								<td>Johnatan Doe</td>
								<td>1 month membership</td>
								<td>09-12-2011</td>
								<td>
									<a href="#" title="Edit this user" class="tooltip table_icon"><img src="assets/icons/actions_small/Pencil.png" alt="" /></a> 
									<a href="#" title="Preferences" class="tooltip table_icon"><img src="assets/icons/actions_small/Preferences.png" alt="" /></a>
									<a href="#" title="Delete this user" class="tooltip table_icon"><img src="assets/icons/actions_small/Trash.png" alt="" /></a>
								</td>
							</tr>
							<tr class="odd">
								<td><input type="checkbox" class="iphone" /></td>
								<td>34</td>
								<td><img src="assets/avatar.png" alt="" /></td>
								<td>Johnatan Doe</td>
								<td>9 months membership</td>
								<td>09-12-2011</td>
								<td>
									<a href="#" title="Edit this user" class="tooltip table_icon"><img src="assets/icons/actions_small/Pencil.png" alt="" /></a> 
									<a href="#" title="Preferences" class="tooltip table_icon"><img src="assets/icons/actions_small/Preferences.png" alt="" /></a>
									<a href="#" title="Delete this user" class="tooltip table_icon"><img src="assets/icons/actions_small/Trash.png" alt="" /></a>
								</td>
							</tr>
							<tr>
								<td><input type="checkbox" class="iphone" /></td>
								<td>48</td>
								<td><img src="assets/avatar.png" alt="" /></td>
								<td>Johnatan Doe</td>
								<td>3 months membership</td>
								<td>09-12-2011</td>
								<td>
									<a href="#" title="Edit this user" class="tooltip table_icon"><img src="assets/icons/actions_small/Pencil.png" alt="" /></a> 
									<a href="#" title="Preferences" class="tooltip table_icon"><img src="assets/icons/actions_small/Preferences.png" alt="" /></a>
									<a href="#" title="Delete this user" class="tooltip table_icon"><img src="assets/icons/actions_small/Trash.png" alt="" /></a>
								</td>
							</tr>
						</tbody>
					</table>
					<a href="presentation.html" class="button_link" >Back to index</a>
					
					<hr />
					
					<h1>A fancy table</h1>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum, risus lacinia scelerisque facilisis, tellus felis tristique mauris, nec dignissim felis mi ac quam. Nulla at justo eget augue congue feugiat vel ut leo. Vestibulum magna sem, sollicitudin nec gravida vel, feugiat viverra eros.</p>
					
					<table class="fancy">
						<thead>
							<tr>
								<th>Header 1</th>
								<th>Header 2</th>
								<th>Header 3</th>
								<th>Header 4</th>
								<th>Header 5</th>
								<th>Header 6</th>
							</tr>
						</thead>
						<tbody>
							<tr class="odd">
								<td>Data block 1</td>
								<td>Data block 2</td>
								<td>Data block 3</td>
								<td>Data block 4</td>
								<td>Data block 5</td>
								<td>Data block 6</td>
							</tr>
							<tr>
								<td>Data block 1</td>
								<td>Data block 2</td>
								<td>Data block 3</td>
								<td>Data block 4</td>
								<td>Data block 5</td>
								<td>Data block 6</td>
							</tr>
							<tr class="odd">
								<td>Data block 1</td>
								<td>Data block 2</td>
								<td>Data block 3</td>
								<td>Data block 4</td>
								<td>Data block 5</td>
								<td>Data block 6</td>
							</tr>
							<tr>
								<td>Data block 1</td>
								<td>Data block 2</td>
								<td>Data block 3</td>
								<td>Data block 4</td>
								<td>Data block 5</td>
								<td>Data block 6</td>
							</tr>
						</tbody>
					</table>
					<a href="presentation.html" class="button_link" >Back to index</a>
				
					<hr />
					
					<h1>A full-width fancy table</h1>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum, risus lacinia scelerisque facilisis, tellus felis tristique mauris, nec dignissim felis mi ac quam. Nulla at justo eget augue congue feugiat vel ut leo. Vestibulum magna sem, sollicitudin nec gravida vel, feugiat viverra eros.</p>
					
					<table class="fancy fullwidth">
						<thead>
							<tr>
								<th>Header 1</th>
								<th>Header 2</th>
								<th>Header 3</th>
								<th>Header 4</th>
								<th>Header 5</th>
								<th>Header 6</th>
							</tr>
						</thead>
						<tbody>
							<tr class="odd">
								<td>Data block 1</td>
								<td>Data block 2</td>
								<td>Data block 3</td>
								<td>Data block 4</td>
								<td>Data block 5</td>
								<td>Data block 6</td>
							</tr>
							<tr>
								<td>Data block 1</td>
								<td>Data block 2</td>
								<td>Data block 3</td>
								<td>Data block 4</td>
								<td>Data block 5</td>
								<td>Data block 6</td>
							</tr>
							<tr class="odd">
								<td>Data block 1</td>
								<td>Data block 2</td>
								<td>Data block 3</td>
								<td>Data block 4</td>
								<td>Data block 5</td>
								<td>Data block 6</td>
							</tr>
							<tr>
								<td>Data block 1</td>
								<td>Data block 2</td>
								<td>Data block 3</td>
								<td>Data block 4</td>
								<td>Data block 5</td>
								<td>Data block 6</td>
							</tr>
						</tbody>
					</table>
					<a href="presentation.html" class="button_link" >Back to index</a>
				
				</div> <!-- inner -->
			</div> <!-- primary_right -->
		</div> <!-- bgwrap -->
	</div> <!-- container -->
</body>
</html>
