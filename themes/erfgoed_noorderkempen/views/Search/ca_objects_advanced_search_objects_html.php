
<div class="row">
	<div class="col-sm-12 col-md-8">
		<h1>Objects Advanced Search</h1>

<p>{{{advanced_search_intro}}}</p>

{{{form}}}


<div class='advancedContainer'>
	
	<!--<div class='row'>
		<div class="advancedSearchField col-sm-12">
			<span class='formLabel' data-toggle="popover" data-trigger="hover">Collectie</span>
			{{{ca_objects.object_collection%width=200px&height=40px}}}
		</div>
	</div>-->			
	<div class='row'>
		<div class="advancedSearchField col-sm-12">
			<span class='formLabel' data-toggle="popover" data-trigger="hover">Titel</span>
			{{{ca_objects.preferred_labels.name%width=220px}}}
		</div>
	</div>	
	<div class='row'>
		<div class="advancedSearchField col-sm-12">
			<span class='formLabel' data-toggle="popover" data-trigger="hover">Inventarisnummer</span>
			{{{ca_objects.idno%width=220px}}}
		</div>
	</div>		
	<div class='row'>
		<div class="advancedSearchField col-sm-12">
			<span class='formLabel' data-toggle="popover" data-trigger="hover">Inhoudelijke Beschrijving</span>
			{{{ca_objects.content_description%width=220px}}}
		</div>
	</div>			
	<div class='row'>
		<div class="advancedSearchField col-sm-12">
			<span class='formLabel' data-toggle="popover" data-trigger="hover">Plaatsen die verwant zijn met de inhoud</span>
			{{{ca_places.preferred_labels.name%width=220px}}}
		</div>
	</div>		
	<!--<div class='row'>
		<div class="advancedSearchField col-sm-12">
			<span class='formLabel' data-toggle="popover" data-trigger="hover">Vervaardigers</span>
			{{{ca_objects.maker%width=220px}}}
		</div>
	</div>-->		
	<div class='row'>
		<div class="advancedSearchField col-sm-12">
			<span class='formLabel' data-toggle="popover" data-trigger="hover">Periode</span>
			{{{ca_objects.content_date%width=220px}}}
		</div>
	</div>
	<br style="clear: both;"/>
	<div class='advancedFormSubmit'>
		<span class='btn btn-default'>{{{reset%label=Reset}}}</span>
		<span class='btn btn-default' style="margin-left: 20px;">{{{submit%label=Search}}}</span>
	</div>
</div>	

{{{/form}}}

	</div>
</div><!-- end row -->

<script>
	jQuery(document).ready(function() {
		$('.advancedSearchField .formLabel').popover(); 
	});
	
</script>