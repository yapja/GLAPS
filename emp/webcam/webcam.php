<?php 
	$page_title = "Attendance";
	include_once('../../includes/header_emp.php');
?>
<div class="col-lg-12">
    <div class="col-lg-6 col-md-6">
        
        <!--WEBCAM-->
        <div id="my_photo_booth">
		<div id="my_camera"></div>
		
		<!-- First, include the Webcam.js JavaScript Library -->
		<script type="text/javascript" src="WebcamJS/webcam.min.js"></script>
		
		<!-- Configure a few settings and attach camera -->
		<script language="JavaScript">
			Webcam.set({
				// live preview size
				width: 450,
				height: 350,
				
				// device capture size
				dest_width: 640,
				dest_height: 480,
				
				// final cropped size
				crop_width: 480,
				crop_height: 480,
				
				// format and quality
				image_format: 'jpeg',
				jpeg_quality: 90,
				
				// flip horizontal (mirror mode)
				flip_horiz: true
			});
			Webcam.attach( '#my_camera' );
		</script>
		
		<!-- A button for taking snaps -->
            <br/>
		<form method="POST">
			<div id="pre_take_buttons">
				<!-- This button is shown before the user takes a snapshot -->
				<input type=button value="Take a Photo" onClick="preview_snapshot()">
			</div>
			<div id="post_take_buttons" style="display:none">
				<!-- These buttons are shown after a snapshot is taken -->
				<input type=button value="&lt; Take Another" onClick="cancel_preview()">
				<input type=button value="Save Photo &gt;" onClick="save_photo()" style="font-weight:bold;">
			</div>
		</form>
	</div>
	
	<div id="results" style="display:none">
		<!-- Your captured image will appear here... -->
	</div>
	
	<!-- Code to handle taking the snapshot and displaying it locally -->
	<script language="JavaScript">
		function preview_snapshot() {			
			// freeze camera so user can preview current frame
			Webcam.freeze();
			
			// swap button sets
			document.getElementById('pre_take_buttons').style.display = 'none';
			document.getElementById('post_take_buttons').style.display = '';
		}
		
		function cancel_preview() {
			// cancel preview freeze and return to live camera view
			Webcam.unfreeze();
			
			// swap buttons back to first set
			document.getElementById('pre_take_buttons').style.display = '';
			document.getElementById('post_take_buttons').style.display = 'none';
		}
		
		function save_photo() {
			// actually snap photo (from preview freeze) and display it
			Webcam.snap( function(data_uri) {
				// display results in page
				document.getElementById('results').innerHTML = 
					'<h4>Here is your large, cropped image:</h4>' + 
					'<img src="'+data_uri+'"/><br/></br>' + 
					'<a href="'+data_uri+'" target="_blank">Open image in new window...</a>';
				
				// Upload file to the web server
				Webcam.upload(data_uri, 'WebcamJS/upload.php', function(code, text) {
					
				});	

				// shut down camera, stop capturing
				Webcam.reset();
				
				// show results, hide photo booth
				document.getElementById('results').style.display = '';
				document.getElementById('my_photo_booth').style.display = 'none';

				$.ajax({
				type: "POST",
				url: "attendance.php",
				});
			} );
		}
	</script>
	
        <!--WEBCAM-->
        
    </div>
</div>
<?php
	include_once('../../includes/footer.php');
?>