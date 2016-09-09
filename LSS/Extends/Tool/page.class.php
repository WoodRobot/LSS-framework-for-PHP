<?php
class page{
	/*
	<div class="pagination">
    <a href="#" class="first" data-action="first">&laquo;</a> 
	<a href="#"class="previous" data-action="previous">&lsaquo;</a> 
	<input type="text"readonly="readonly" data-max-page="40" style='color:black'> 
	<a href="#" class="next" data-action="next">&rsaquo;</a> 
	<a href="#" class="last" data-action="last">&raquo;</a>
	</div>
	
	<script>
	$(document).ready(function() {
		$(".pagination").jqPagination({
			{/literal}
			link_string	: "index.php?a=info_log&type={$type}&tid={$tid}&page={literal}{page_number}",
			current_page:{/literal}"{$page}"{literal} ,
			max_page : {/literal}{$maxPage}{literal}, //设置最大页 默认为1
			paged		: function(page) {
				{/literal}
				window.location.href = "index.php?a=info_log&type={$type}&tid={$tid}&page={literal}"+page;
			}
		});

	});
	</script>
	*/
	
    public function outPutPage($pageNum,$pageCount){
    	$pageNum--;
    	return $pageNum*$pageCount.','.$pageCount;
    }
}