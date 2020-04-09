<?php
function pager($page,$pagesize,$rowcount,$baseurl,$params){
	$page=$page<1?1:$page;
	$pageinfo=pagerinfo($page,$pagesize,$rowcount);
	$pagerhtml='';
	$num_pages=(int)ceil($rowcount/$pagesize);
	$open_tag='<div class="dataTables_paginate paging_full_numbers" style="width: auto;" >';
	$close_tag='</div>';
	$urlparams_arr=array();
	if(count($params)>0){
		foreach($params as $k=>$v){
			if($k!='page'){
				$urlparams_arr[]=$k.'='.$v;
			}
		}
	}
	
	// $urlparams_arr[].='page='.$page;
	$urlparams=implode('&',$urlparams_arr);
	// echo var_export($urlparams);
	//first
	// $firstlink=$baseurl+'?'+($urlparams==''?'':('?'.$urlparams));
	$firstlink=$baseurl.'?page=1'.($urlparams==''?'':('&'.$urlparams));
	// echo $firstlink;
	$firstpage='<a tabindex="0" class="first paginate_button" href="'.$firstlink.'" >第一页</a>';
	//last
	$lastlink=$baseurl.'?page='.$num_pages.($urlparams==''?'':('&'.$urlparams));
	$lastpage='<a tabindex="0" class="last paginate_button" href="'.$lastlink.'">最后一页</a>';
	//preview
	$previewpagenum=$page<=1?1:($page-1);
	$previewlink=$baseurl.'?page='.$previewpagenum.($urlparams==''?'':('&'.$urlparams));
	$previewpage='<a tabindex="0" class="previous paginate_button" href="'.$previewlink.'">上一页</a>';
	//next
	$nextpagenum=$page>=$num_pages?$num_pages:$page+1;
	$nextlink=$baseurl.'?page='.$nextpagenum.($urlparams==''?'':('&'.$urlparams));
	$nextpage='<a tabindex="0" class="next paginate_button" href="'.$nextlink.'">下一页</a>';
	//page
	$page_arr=array();
	// for($i=0,$l=$num_pages;$i<$num_pages;$i++){
	// 	$page_arr[]='2';
	// }
	for($i=0,$l=$num_pages;$i<$num_pages;$i++){
		$page_arr[$i]=isset($page_arr[$i])?$page_arr[$i]:'2';
		if($num_pages<=10){
			$page_arr[$i]='1';
		}else{
			if($i<3){
				$page_arr[$i]='1';
			}
			if($num_pages-$i<=3){
				$page_arr[$i]='1';
			}
			if($i+1==$page){
				$page_arr[$i-1]='1';
				$page_arr[$i+1]='1';
				$page_arr[$i]='1';
			}
		}
	}
	for($i=0,$l=$num_pages;$i<$num_pages;$i++){
		$currentpage=$i+1;
		$pagelink=$baseurl.'?page='.$currentpage.($urlparams==''?'':('&'.$urlparams));
		$classname=$currentpage==$page?'paginate_active':'paginate_button';
		if($page_arr[$i]=='1'){
			$numpage.='<a href="'.$pagelink.'" class="'.$classname.'">'.($i+1).'</a>';
		}else{
			$numpage.='.';
		}
	}

	$pagerhtml=$open_tag.$firstpage.$previewpage.$numpage.$nextpage.$lastpage.$close_tag;
	$pager=$pageinfo.$pagerhtml;
	return $pager;
}

function pagerinfo($page,$pagesize,$rowcount){
	$page=$page<1?1:$page;
	$startrow=($page-1)*$pagesize+1;
	$endrow=$startrow+$pagesize;
	$endrow=$endrow>$rowcount?$rowcount:$endrow;
	$pageinfo='<div class="dataTables_info" >显示从 '.$startrow.' 到 '.$endrow.'　共'.$rowcount.'条</div>';
	return $pageinfo;
}