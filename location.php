<?php 
 //echo $_SERVER['QUERY_STRING'];
  if($_SERVER['QUERY_STRING']=="area_name=barbican-")
 {
	
@header("HTTP/1.1 301 Moved Permanently");
?>
<script type="text/javascript">
window.location.href='http://www.short-let-apartments.com/location/barbican-&-farringdon';
</script>
<?php
 }

 include_once("includes/header.php");
 $areaName=$_REQUEST['area_name'];
 $areaIDbyName=$areaobj->funGetAreaID($areaName);
 $areaId=$areaIDbyName['area_id'];
 if($areaId=="")
  {
	?>
<script type="text/javascript">
	window.location.href='<?php echo SITE_URL;?>404.php';
	</script>
<?php  
  }
 $areaDetails=$areaobj->funGetAreaInfo($areaId);
 $areaSiteSpecificDetails=$areaobj->funGetAreaSpecificInfo($areaId);
 $cityDetails= $areaobj->funGetCityByID($areaDetails['city_id']);
 $cityName=str_replace(" ","-",$cityDetails['city_name']);
 $cityName=strtolower($cityName);
 ?>
<div id="main">
  <div id="mainwrap">
    <div id="breadcrumb"><a href="<?php echo SITE_URL;?>">Home</a> | Serviced apartments in <?php echo $areaDetails['area_name'];?> </div>
    <div id="location_detail">
      <h1><?php echo $areaDetails['area_name'];?> Short stay apartments</h1>
      <div id="locationimage_big">
        <?php 
                	if($areaDetails['area_image_path'])
            	{
            	if(file_exists(SITE_IMAGES_AREA_WS.$areaDetails['area_image_path'])){
            		?>
        <img src="<?php echo SITE_IMAGES_AREA_MEDIUM.$areaDetails['area_image_path'];?>" border="0" title="<?php echo $areaSiteSpecificDetails['area_image_title'];?>" alt="<?php echo $areaSiteSpecificDetails['area_image_alt'];?>"/>
        <?php }}
            	?>
      </div>
      <div id="locationintro"><?php echo $areaSiteSpecificDetails['area_description'];?></div>
    </div>
    <div style="clear:both;"> </div>
  </div>
</div>
<?php 
 
    if(!empty($_GET['limit'])){
	     $limit = $_GET['limit'];
    }else{
	     $limit = 20;
    }

    if(!empty($_GET['page'])){
	     $page = $_GET['page'];
	     $n=(($page-1)*$limit)+1;
    }else{
	     $page = 1;
	     $n=1;
    }
    $start = ($page - 1) * $limit;
	
	 $sql = "SELECT ".TABLE_LOCATION." .* FROM " . TABLE_LOCATION." JOIN " . TABLE_LOC_META." WHERE ".TABLE_LOCATION.".location_id=".TABLE_LOC_META.".location_id AND ".TABLE_LOC_META.".website_id='".WEBSITE_ID."' AND ".TABLE_LOC_META.".status='1' AND ".TABLE_LOCATION.".area_id='".$areaId."'";
    $cateResult = $dbObj->fun_db_query($sql);
    $totRecords = $dbObj->fun_db_get_num_rows($cateResult);
    if($totRecords>$limit)
    {
      $pagelinks = paginateList($limit, $totRecords);
    }else
    {
      $pagelinks=1;
    }
  
    $sql .= " ORDER BY ".TABLE_LOCATION.".location_name  DESC LIMIT $start, $limit";
    $cateResult = $dbObj->fun_db_query($sql);
	
    if($limit*$page<$totRecords){
      $j=$limit*$page;
    }
    else {
	     $j=$totRecords;
    }
        if($totRecords>0)
    {
 ?>
<div id="featured">
  <div id="featured_block">
    <div class="list_box">
      <div class="displaying">Displaying <?php echo $n;?> - <?php echo $j;?> of <?php echo $totRecords;?> results</div>
    </div>
    <div class="list_box"><?php echo $pagelinks;?></div>
    <div class="list_box">
      <div class="results">Results per page <a href="<?php echo SITE_URL;?>uk/<?php echo $cityName."/".$areaName;?>&limit=10">10</a> <a href="<?php echo SITE_URL;?>uk/<?php echo $cityName."/".$areaName;?>&limit=25">25</a> <a href="<?php echo SITE_URL;?>uk/<?php echo $cityName."/".$areaName;?>&limit=30">30</a></div>
    </div>
    <div class="list_box">
      <div class="email_results"><a class="various" href="<?php echo SITE_URL;?>result-email.php?area_id=<?php echo $areaId;?>">Send results to your email</a></div>
    </div>
    <div style="clear:both;"> </div>
  </div>
</div>
<div id="results">
  <?php
$cnt=1;
  while($rowsLocation=$dbObj->fun_db_fetch_rs_object($cateResult))
  {
	$locationSiteSpecificDetails=$locationobj->funGetMetaLocationInfo($rowsLocation->location_id);	
	$LocationName=str_replace(" ","-",$rowsLocation->location_name);	
	   if($rowsLocation->template_id==1){
		  $DetailUrl="detail";
		  } else {
                $DetailUrl="new-detail";
		  }
		  $areaname=str_replace(" ","-",$areaDetails['area_name']);
		  $areaname=strtolower($areaname);
		  $LocationName=strtolower($LocationName);
  ?>
  <div <?php if($cnt % 2 == 0){?> class="results_block_grey" <?php } else {?>class="results_block"<?php }?> >
    <div class="results_wrap">
    <?php if($rowsLocation->discount_rate!="") {?>
      <div class="offers-icon">
      <img src="<?php echo SITE_IMAGES;?>special_offer.png" alt="Special Offer" title="Short Let Apartments"  />
      </div>
      <?php }?>
      <div class="results_image"> <a href="<?php echo SITE_URL;?>apartment/<?php echo $areaname."/".$LocationName;?>">
        <?php 
	if($rowsLocation->location_main_image)
	{
	if(file_exists(SITE_IMAGES_LOCATION_SMALL_WS.$rowsLocation->location_main_image)){
		?>
        <img src="<?php echo SITE_IMAGES_LOCATION_SMALL.$rowsLocation->location_main_image;?>" border="0" title="<?php echo $locationSiteSpecificDetails['location_main_image_title'];?>" alt="<?php echo $locationSiteSpecificDetails['location_main_image_alt'];?>"/>
        <?php }}
	else
	{?>
        <img src="<?php echo SITE_IMAGES;?>NO-IMG.JPG">
        <?php	}
	?>
        </a></div>
      <div class="results_name">
        <h3>
          <?php 
		  $locationTitle=str_replace("Earls","Earl's",$rowsLocation->location_name);
		  if($rowsLocation->location_display_name!="")
		  {
			  echo $rowsLocation->location_display_name;
		  }
		  else
		  {
		  echo $locationTitle;
		  }?>
        </h3>
      </div>
      <div class="results_area">Area: <a href="<?php echo SITE_URL;?>uk/<?php echo $cityName."/".$areaname;?>"><?php echo $areaDetails['area_name'];?></a><br/>
        <?php $sqlPrice="SELECT * FROM ".TABLE_ROOM." WHERE location_id= '".$rowsLocation->location_id."' AND status='1'";
		 $ResultPrice=mysql_query($sqlPrice);
		 $totalRooms= mysql_num_rows($ResultPrice);
		 $i=1;
		 while($RowsPrice=mysql_fetch_array($ResultPrice))
		 
		 {
			 
			$RoomType=$locationobj->funGetRoomTypeInfo($RowsPrice['room_type_id']);
										echo $RoomType['room_type_name']." ";
										if($i<$totalRooms)
										echo ", ";
										$i=$i+1;
										
		 }
		 ?>
      </div>
      <div class="results_rates">
        <?php $lowPrice=$roomPriceobj->funGetLowStandardPrice($rowsLocation->location_id,'');?>
        <?php  $sqlLowPrice="SELECT min(min_stay) FROM ".TABLE_ROOM." WHERE location_id= '".$rowsLocation->location_id."' AND status='1'";
		 $ResultLowPrice=mysql_query($sqlLowPrice);
		 $RowsLowPrice=mysql_fetch_array($ResultLowPrice);
		 $lowstay=$RowsLowPrice['min(min_stay)'];
		?>
        <div class="price_in">Rates from as low as</div>
        <div class="price_gbp"><?php echo $lowPrice;?> GBP</div>
        <div class="minstaye">Min Stay : <?php echo $lowstay;?> Night(s)</div>
      </div>
      <div class="results_description"><?php echo substr($locationSiteSpecificDetails['location_information'],0,300);?></div>
      <div class="results_select"><a href="<?php echo SITE_URL;?>apartment/<?php echo $areaname."/".$LocationName;?>">More Details</a></div>
      <div style="clear:both;"> </div>
    </div>
  </div>
  <?php 
	$cnt=$cnt+1;
	}?>
</div>
<div id="featured">
  <div id="featured_block">
    <div class="list_box">
      <div class="displaying">Displaying <?php echo $n;?> - <?php echo $j;?> of <?php echo $totRecords;?> results</div>
    </div>
    <div class="list_box"> <?php echo $pagelinks;?></div>
    <div class="list_box">
      <div class="results">Results per page <a href="<?php echo SITE_URL;?>uk/<?php echo $cityName."/".$areaName;?>&limit=10">10</a> <a href="<?php echo SITE_URL;?>uk/<?php echo $cityName."/".$areaName;?>&limit=25">25</a> <a href="<?php echo SITE_URL;?>uk/<?php echo $cityName."/".$areaName;?>&limit=30">30</a></div>
    </div>
    <div class="list_box">
      <div class="email_results"><a class="various" href="<?php echo SITE_URL;?>result-email.php?area_id=<?php echo $areaId;?>">Send results to your email</a></div>
    </div>
    <div style="clear:both;"> </div>
  </div>
</div>
<?php } else
  {
	  
	  echo "<div class=\"featured\"><div class=\"featured_block\"><div align=\"center\" style=\"padding: 20px 0px; color: #fff;\"><b>No Results Found according to your search. You may ineterested in other properties</b></div></div></div>";
	  
	  $sqlFeatured= "SELECT * FROM ".TABLE_LOCATION." JOIN ".TABLE_LOCATION_FEATURE." WHERE ".TABLE_LOCATION_FEATURE.".location_id= ".TABLE_LOCATION.".location_id LIMIT 3";
		$resultFeatured= mysql_query($sqlFeatured);?>
<div id="results">
  <div class="results_block">
    <div class="results_wrap">
      <h2>Featured Apartments</h2>
    </div>
  </div>
  <?php
		while($ResultFeatureLocation= mysql_fetch_array($resultFeatured))
		{
				$LocationName=str_replace(" ","-",$ResultFeatureLocation['location_name']);	
				$areaDetails=$areaobj->funGetAreaInfo($ResultFeatureLocation['area_id']);
				$locationSiteSpecificDetails=$locationobj->funGetMetaLocationInfo($ResultFeatureLocation['location_id']);
				$areaname=str_replace(" ","-",$areaDetails['area_name']);
				$areaname=strtolower($areaname);
				$LocationName=strtolower($LocationName);
				$cityDetails= $areaobj->funGetCityByID($areaDetails['city_id']);
	  			$cityName=str_replace(" ","-",$cityDetails['city_name']);
	  			$cityName=strtolower($cityName);

			 if($ResultFeatureLocation['template_id']==1){
		  $DetailFeatureUrl="detail";
		  } else {
                $DetailFeatureUrl="new-detail";
		  }
  ?>
  <div class="results_block">
    <div class="results_wrap">
      <div class="results_image"><a href="<?php echo SITE_URL;?>apartment/<?php echo $areaname."/".$LocationName;?>">
        <?php 
	if($ResultFeatureLocation['location_main_image'])
	{
	if(file_exists(SITE_IMAGES_LOCATION_SMALL_WS.$ResultFeatureLocation['location_main_image'])){
		?>
        <img src="<?php echo SITE_IMAGES_LOCATION_SMALL.$ResultFeatureLocation['location_main_image'];?>" border="0" title="<?php echo $locationSiteSpecificDetails['location_main_image_title'];?>" alt="<?php echo $locationSiteSpecificDetails['location_main_image_alt'];?>"/>
        <?php }}
	else
	{?>
        <img src="<?php echo SITE_IMAGES;?>NO-IMG.JPG">
        <?php	}
	?>
        </a></div>
      <div class="results_name">
        <h3><?php echo $ResultFeatureLocation['location_name']?></h3>
      </div>
      <?php  ?>
      <div class="results_area">Area: <a href="<?php echo SITE_URL;?>uk/<?php echo $cityName."/".$areaname;?>"><?php echo $areaDetails['area_name'];?></a></div>
      <div class="results_rates">
        <?php $lowPrice=$roomPriceobj->funGetLowStandardPrice($ResultFeatureLocation['location_id'],'');?>
        <div class="price_in">Rates from as low as</div>
        <?php echo $lowPrice;?> GBP</div>
      <div class="results_description"><?php echo substr($locationSiteSpecificDetails['location_information'],0,300);?></div>
      <div class="results_select"><a href="<?php echo SITE_URL;?>apartment/<?php echo $areaname."/".$LocationName;?>">More Details</a></div>
      <div style="clear:both;"> </div>
    </div>
  </div>
  <?php }?>
</div>
<?php 
  }?>
</div>
<?php include_once("includes/footer-contact.php");?>
<?php include_once("includes/footer.php");?>

<!-- ###DOKUMENT### -->
</body></html>
dffffffffffffffffff