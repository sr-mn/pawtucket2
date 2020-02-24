<?php
/* ----------------------------------------------------------------------
 * views/Browse/browse_results_images_html.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2014 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
 
	$qr_res 			= $this->getVar('result');				// browse results (subclass of SearchResult)
	$va_facets 			= $this->getVar('facets');				// array of available browse facets
	$va_criteria 		= $this->getVar('criteria');			// array of browse criteria
	$vs_browse_key 		= $this->getVar('key');					// cache key for current browse
	$va_access_values 	= $this->getVar('access_values');		// list of access values for this user
	$vn_hits_per_block 	= (int)$this->getVar('hits_per_block');	// number of hits to display per block
	$vn_start		 	= (int)$this->getVar('start');			// offset to seek to before outputting results
	
	$va_views			= $this->getVar('views');
	$vs_current_view	= $this->getVar('view');
	$va_view_icons		= $this->getVar('viewIcons');
	$vs_current_sort	= $this->getVar('sort');
	
	$t_instance			= $this->getVar('t_instance');
	$vs_table 			= $this->getVar('table');
	$vs_pk				= $this->getVar('primaryKey');
	$o_config = $this->getVar("config");	
	
	$va_options			= $this->getVar('options');
	$vs_extended_info_template = caGetOption('extendedInformationTemplate', $va_options, null);

	$vb_ajax			= (bool)$this->request->isAjax();

	$o_icons_conf = caGetIconsConfig();
	$va_object_type_specific_icons = $o_icons_conf->getAssoc("placeholders");
	if(!($vs_default_placeholder = $o_icons_conf->get("placeholder_media_icon"))){
		$vs_default_placeholder = "<i class='fa fa-picture-o fa-2x'></i>";
	}
	$vs_default_placeholder_tag = "<div class='bResultItemImgPlaceholder'>".$vs_default_placeholder."</div>";

	
	$va_add_to_set_link_info = caGetAddToSetInfo($this->request);
	
		$vn_col_span = 12;
		$vn_col_span_sm = 12;
		$vn_col_span_xs = 12;
		$vb_refine = false;
		if(is_array($va_facets) && sizeof($va_facets)){
			$vb_refine = true;
			$vn_col_span = 12;
			$vn_col_span_sm = 12;
			$vn_col_span_xs = 12;
		}
		if ($vn_start < $qr_res->numHits()) {
			$vn_c = 0;
			$qr_res->seek($vn_start);
			
			if ($vs_table != 'ca_objects') {
				$va_ids = array();
				while($qr_res->nextHit() && ($vn_c < $vn_hits_per_block)) {
					$va_ids[] = $qr_res->get("{$vs_table}.{$vs_pk}");
				}
			
				$qr_res->seek($vn_start);
				$va_images = caGetDisplayImagesForAuthorityItems($vs_table, $va_ids, array('version' => 'small', 'relationshipTypes' => caGetOption('selectMediaUsingRelationshipTypes', $va_options, null), 'checkAccess' => $va_access_values));
			} else {
				$va_images = null;
			}
			
			$t_list_item = new ca_list_items();
			while($qr_res->nextHit() && ($vn_c < $vn_hits_per_block)) {
				$vn_id 					= $qr_res->get("{$vs_table}.{$vs_pk}");
				$vs_idno_detail_link 	= caDetailLink($this->request, $qr_res->get("{$vs_table}.idno"), '', $vs_table, $vn_id);
				$vs_uniform = null;
				if ($vs_table === 'ca_objects') {
					if ($vs_uniform = $qr_res->get('ca_objects.CCSSUSA_Uniform')) {
						$vs_label_detail_link 	= caDetailLink($this->request, $vs_uniform, '', $vs_table, $vn_id);
					} else {
						$vs_label_detail_link 	= caDetailLink($this->request, '[Short title]', '', $vs_table, $vn_id);
					}
				}else{
					$vs_label_detail_link 	= caDetailLink($this->request, $qr_res->get("{$vs_table}.preferred_labels"), '', $vs_table, $vn_id);
					#if ($vs_table === 'ca_collections') {
						#if($qr_res->get("ca_collections.preferred_labels") != $qr_res->get("ca_collections.institution", array("convertCodesToDisplayText" => true))){
							#$vs_label_detail_link .= "<br/>".$qr_res->get("ca_collections.institution", array("convertCodesToDisplayText" => true));
						#}
					#}
				}
				$vs_thumbnail = "";
				$vs_type_placeholder = "";
				$vs_typecode = "";
				$vs_image = ($vs_table === 'ca_objects') ? $qr_res->getMediaTag("ca_object_representations.media", 'small', array("checkAccess" => $va_access_values)) : $va_images[$vn_id];
				$vs_author = "";
				
				if(!$vs_image){
					if ($vs_table == 'ca_objects') {
						$t_list_item->load($qr_res->get("type_id"));
						$vs_typecode = $t_list_item->get("idno");
						if($vs_type_placeholder = caGetPlaceholder($vs_typecode, "placeholder_media_icon")){
							$vs_image = "<div class='bResultItemImgPlaceholder'>".$vs_type_placeholder."</div>";
						}else{
							$vs_image = $vs_default_placeholder_tag;
						}
					}else{
						$vs_image = $vs_default_placeholder_tag;
					}
				}
				if ($vs_table === 'ca_objects') {
					$vs_author = $qr_res->get('ca_entities.preferred_labels', array('restrictToRelationshipTypes' => array('author'), 'delimiter' => ', '));
					if($vs_author){
						$vs_author = "<b>".$vs_author."</b><br/>";
					}
					if ($va_date = $qr_res->get('ca_objects.260_date', array('delimiter' => ', '))) {

					} else {
						$va_date = null;
					}
					if ($va_pub_info = $qr_res->get('ca_objects.publication_description')) {
					
					} else {$va_pub_info = null; }
					$vs_info = "<p>".$va_date."<br/>".$va_pub_info."</p>";
				}
				$vs_rep_detail_link 	= caDetailLink($this->request, $vs_image, '', $vs_table, $vn_id);	
				if($vs_table === 'ca_collections'){
					$vs_rep_detail_link = "";
				}
				$vs_add_to_set_link = "";
				if(is_array($va_add_to_set_link_info) && sizeof($va_add_to_set_link_info)){
					$vs_add_to_set_link = "<a href='#' onclick='caMediaPanel.showPanel(\"".caNavUrl($this->request, '', $va_add_to_set_link_info["controller"], 'addItemForm', array($vs_pk => $vn_id))."\"); return false;' title='".$va_add_to_set_link_info["link_text"]."'>".$va_add_to_set_link_info["icon"]."</a>";
				}
				
				$vs_expanded_info = $qr_res->getWithTemplate($vs_extended_info_template);

				print "
	<div class='bResultListItemCol col-xs-{$vn_col_span_xs} col-sm-{$vn_col_span_sm} col-md-{$vn_col_span}'>
		<div class='bResultListItem' onmouseover='jQuery(\"#bResultListItemExpandedInfo{$vn_id}\").show();'  onmouseout='jQuery(\"#bResultListItemExpandedInfo{$vn_id}\").hide();'>
			<div class='bSetsSelectMultiple'><input type='checkbox' name='object_ids[]' value='{$vn_id}'></div>
			<div class='bResultListItemContent'>".(($vs_table != 'ca_collections') ? "<div class='text-center bResultListItemImg'>{$vs_rep_detail_link}</div>" : "")."
				<div class='bResultListItemText'>
					{$vs_author}{$vs_label_detail_link}{$vs_info}
				</div><!-- end bResultListItemText -->
			</div><!-- end bResultListItemContent -->
		</div><!-- end bResultListItem -->
	</div><!-- end col -->";
				
				$vn_c++;
			}
			
			print caNavLink($this->request, _t('Next %1', $vn_hits_per_block), 'jscroll-next', '*', '*', '*', array('s' => $vn_start + $vn_hits_per_block, 'key' => $vs_browse_key, 'view' => $vs_current_view));
		}
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		if($("#bSetsSelectMultipleButton").is(":visible")){
			$(".bSetsSelectMultiple").show();
		}
	});
</script>