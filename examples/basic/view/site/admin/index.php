<?php include(Config::getFolderView('/backend/head.php')); ?>
<script type="text/javascript" src="<?php echo Config::getAsset('backend/assets/js/custom/dashboard.js'); ?>"></script>

<body class="bodygrey">

<?php include(Config::getFolderView('/backend/nav.php')); ?>

<div class="sidebar">
	<div id="accordion">
        <h3 class="open"><!--Seja bem-vindo--></h3>
        <div class="content" style="display: block;">
        	<ul class="leftmenu"><!--
            	<li class="current"><a href="dashboard.html" class="home">General</a></li>
                <li><a href="forms.html" class="form">Form Styling</a></li>
                <li><a href="tables.html" class="table">Table Styling</a></li>
                <li><a href="gallery.html" class="gallery">Image Gallery</a></li>
                <li><a href="grid.html" class="grid">Grid Styling</a></li>
                <li><a href="calendar.html" class="calendar">Calendar</a></li>
                <li><a href="buttons.html" class="buttons">Buttons &amp; Icons</a></li>
                <li><a href="editor.html" class="editor">WYSIWYG Editor</a></li>
                <li><a href="filemanager.html" class="file">File Manager</a></li>
                <li><a href="invoice.html" class="form">Invoice</a></li>
                <li><a href="404.html" class="error">404 Page</a></li>-->
            </ul>
        </div>
        <h3 class="open"><!--Custom Text--></h3>
        <div class="content" style="display: block;"><!--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.--></div>
	</div>
	
</div><!-- leftmenu -->


<div class="maincontent">
	<div class="two_third maincontent_inner ">
    	<div class="left">
        	<!--
        	<div class="notification msginfo">
            	<a class="close"></a>
            	There are 3 submitted items from users. <a href="">Click to approve</a>
            </div><!-- notification info -->
        
            <!-- START WIDGET LIST -->
            <!--
            <ul class="widgetlist">
                <li><a href=""><img src="<?php echo Config::getAsset('backend/assets/images/icons/document.png'); ?>" alt="Document Icon" /><span>Add New Article</span></a></li>
                <li><a href=""><img src="<?php echo Config::getAsset('backend/assets/images/icons/createreport.png'); ?>" alt="Report Icon" /><span>Create Report</span></a></li>
                <li><a href=""><img src="<?php echo Config::getAsset('backend/assets/images/icons/mail.png'); ?>" alt="Mail Icon" /><span>Compose Mail</span></a></li>
                <li><a href=""><img src="<?php echo Config::getAsset('backend/assets/images/icons/calendar.png'); ?>" alt="Events Icon" /><span>Manage Events</span></a></li>
                <li><a href=""><img src="<?php echo Config::getAsset('backend/assets/images/icons/media.png'); ?>" alt="Media Icon" /><span>Media Library</span></a></li>
            </ul>
            <!-- END WIDGET LIST -->
            
            <div class="clear"></div>
             
            <div class="widgetbox">
            	<!--
            	<h3><span>Sample Chart</span></h3>
                <div class="content nopadding ohidden">
                	<table cellpadding="0" cellspacing="0" class="sTable3" width="100%">
                        <thead>
                            <tr>
                                <td>Column 1</td>
                                <td>Column 2</td>
                                <td>Column 3</td>
                                <td align="right">Impressions</td>
                                <td align="right">Percentage</td>
                                <td>Column 6</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Row Text 1</td>
                                <td>Row Text 2</td>
                                <td>Row Text 3</td>
                                <td align="right">2 100.00</td>
                                <td align="right">20%</td>
                                <td>Row Text 6</td>
                            </tr>
                            <tr class="even">
                                <td>Row Text 1</td>
                                <td>Row Text 2</td>
                                <td>Row Text 3</td>
                                <td align="right">2 100.00</td>
                                <td align="right">20%</td>
                                <td>Row Text 6</td>
                            </tr>
                            <tr>
                                <td>Row Text 1</td>
                                <td>Row Text 2</td>
                                <td>Row Text 3</td>
                                <td align="right">2 100.00</td>
                                <td align="right">20%</td>
                                <td>Row Text 6</td>
                            </tr>
                            <tr class="even">
                                <td>Row Text 1</td>
                                <td>Row Text 2</td>
                                <td>Row Text 3</td>
                                <td align="right">2 100.00</td>
                                <td align="right">20%</td>
                                <td>Row Text 6</td>
                            </tr>
                            <tr>
                                <td>Row Text 1</td>
                                <td>Row Text 2</td>
                                <td>Row Text 3</td>
                                <td align="right">2 100.00</td>
                                <td align="right">20%</td>
                                <td>Row Text 6</td>
                            </tr>
                        </tbody>
                    </table>
                </div><!-- content -->
            </div><!-- widgetbox2 -->
            
            <div class="widgetbox">
            	<!--
            	<h3><span>Sample Chart</span></h3>
                <div class="content">
                	<div id="chartplace" style="height:300px;"></div>
                </div><!-- content -->
            </div><!-- widgetbox2 -->
            
            <div class="widgetbox">
            	<!--
                <h3><span>Buttons</span></h3>
                <div class="content">
                	<button class="button button_white">Button</button> &nbsp;
                    <button class="button button_blue">Button</button> &nbsp;
                    <button class="button button_black">Button</button> &nbsp;
                    <button class="button button_red">Button</button> &nbsp;
                    <button class="button button_yellow">Button</button> &nbsp;
                    <button class="button button_green">Button</button> &nbsp;
                    <button class="button button_brown">Button</button> &nbsp;
                    <button class="button button_lblue">Button</button> <br />
                </div><!-- conten t-->
            </div><!-- widgetbox -->
            
            <div class="widgetbox"><!--
            	<h3><span><!--Form with validation--></span></h3>
            	<!--
                <div class="content">
                	<form id="form" action="" method="post">
                	<!--
                    <div class="form_default">
                            
                            <p>
                                <label for="name">Name</label>
                                <input type="text" name="name"  id="name" class="sf" />
                            </p>
                            
                            <p>
                                <label for="email">Email</label>
                                <input type="text" name="email"  id="email" class="sf" />
                            </p>
                            
                            <p>
                                <label for="location">Location</label>
                                <textarea name="location" class="mf" rows="" cols=""></textarea>
                            </p>
                            
                            <p>
                                <label for="gender" class="nopadding">Gender</label>
                                <input type="radio" name="gender" value="0" /> Male &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="gender" value="1" /> Female
                            </p>
                            
                            <p>
                                <label for="language" class="nopadding">Language</label>
                                <input type="checkbox" name="language[]" value="0" /> English &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="checkbox" name="language[]" value="1" /> Mandarin &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="checkbox" name="language[]" value="1" /> German
                            </p>
                            
                            <p>
                                <label for="occupation">Occupation</label>
                                <select name="occupation" id="occupation">
                                  <option value="">Choose One</option>
                                  <option value="0">Web Designer</option>
                                  <option value="1">Web Developer</option>
                                  <option value="2">Software Engineer</option>
                                  <option value="3">Application Engineer</option>
                                  <option value="4">Programmer</option>
                                  <option value="5">Analyst</option>
                                        
                                </select>
                            </p>
                            
                            <p>
                                <button>Submit</button>
                            </p>
        
                    </div><!--form-->
                	</form>
                    
                </div><!-- content -->
            </div><!-- widgetbox -->
            
            <br />
                        
        </div><!-- left -->            
    </div><!-- two_third -->
    
    <div class="one_third last">
    	<!--
    	<div class="right">
        
            <div class="widgetbox">
                <h3><span>EARNINGS</span></h3>
                <div class="content">
                    
                    <h1 class="prize">$232.45</h1>
                    <p>Estimate earnings by the end of the day: <strong>$300.00</strong></p>
                	
                    <br />
                    
                	<div class="one_half bright">
                    	<h2 class="prize">$412.30</h2>
                        <small>Yesterday's earnings</small>
                    </div><!--one_half-->
                    <!--
                    <div class="one_half last">
                    	<h2 class="prize">$2,796.98</h2>
                        <small>This month's earnings</small>
                    </div><!--one_half-->
                    
                    
                </div><!-- content -->
            </div><!-- widgetbox -->
            
            <div class="widgetbox">
            	<!--
            	<h3><span>PROGRESS BAR</span></h3>
                <div class="content">
                	
                    <div class="progress">
                        Storage (60%)
                        <div class="bar"><div class="value bluebar" style="width: 60%;"></div></div>
                    </div><!-- progress -->
                    <!--
                    <div class="progress">
                        Bandwidth (86%)
                        <div class="bar"><div class="value orangebar" style="width: 86%;"></div></div>
                    </div><!-- progress -->
                    <!--
                    <div class="progress">
                        Impression (34%)
                        <div class="bar"><div class="value redbar" style="width: 34%;"></div></div>
                    </div><!-- progres s-->
                    <!--
                </div><!-- content -->
            </div><!-- widgetbox -->
            
            <div class="widgetbox2">
            	<!--
                <h3><span>Widget Box 2</span></h3>
                <div class="content">
                    
                    <p><img src="<?php echo Config::getAsset('backend/assets/images/assets/image1.png'); ?>" alt="" class="imgleft" />Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium.</p>
                    
                    
                </div><!--content-->
            </div><!--widgetbox2 -->
            
            <!--
            <div id="tabs" class="tabs2">
                <ul>
                    <li><a href="#tabs-1">Tab A</a></li>
                    <li><a href="#tabs-2">Tab B</a></li>
                    <li><a href="#tabs-3">Tab C</a></li>
                </ul>
                <div id="tabs-1">
                    <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
                </div>
                <div id="tabs-2">
                    <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
                </div>
                <div id="tabs-3">
                    <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
                </div>
                
            </div><!-- tabs -->
            
            <br />
            <!--
            <div class="accordion">
                <h3><a href="#">First header</a></h3>
                <div>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</div>
                <h3><a href="#">Second header</a></h3>
                <div>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</div>
                <h3><a href="#">Third header</a></h3>
                <div>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</div>
            </div><!-- accordion -->
            
    	</div><!--right-->
    </div><!--one_third last-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<br />
<div class="footer footer_float">
	<div class="footerinner">
    	&copy; 2011. Fernando Schroeder. All Rights Reserved.
    </div><!-- footerinner -->
</div><!-- footer -->

</body>
</html>
