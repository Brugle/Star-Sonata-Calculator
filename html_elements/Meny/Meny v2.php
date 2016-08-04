<!--
//
//		Her starter HTML til menyen
//

-->
<?php
if (isset($_GET['module'])) {
    $_SESSION['module'] = $_GET['module'];
}
?>
<div>
    <div id="menu">
				<div id="table">
						<ul class="level1">
							<li class="level1-li menuLeft"><a class="level1-a" href="?module=0&amp;content=Forside"><b>Welcome <?php echo ucwords($_SESSION['User']); ?></b></a></li>
							<li class="level1-li menuLeft"><a class="level1-a" href="?module=skills&amp;content=character">Characters</a></li>
							<li class="level1-li menuLeft"><a class="level1-a" href="?module=skills&amp;content=skills">Skills</a></li>
							<li class="level1-li menuLeft"><a class="level1-a" href="?module=skills&amp;content=Calculator">Ship Calculator</a></li>
							<li class="level1-li menuLeft"><a class="level1-a" href="?module=skills&amp;content=Station_Calculator">Station Calculator</a></li>
							<li class="level1-li menuLeft"><a class="level1-a" href="?module=skills&amp;content=buildviewer">Build viewer</a></li>
							<li class="level1-li menuLeft"><a class="level1-a" href="?module=skills&amp;content=item_viewer">Item viewer</a></li>
							<li class="level1-li menuLeft"><a class="level1-a" href="?module=skills&amp;content=G_Importer">Data import</a></li>
              <li class="level1-li menuLeft menuRight"><a class="level1-a" href="?content=logout&amp;signature=<?php echo $_SESSION['signature']; ?>"><b>Sign out</b></a></li>
            </ul>
        </div>
    </div>
</div>