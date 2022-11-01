<?php
//getting the data from a form submit
$ad_size = $_POST["ad_size"];
$image_type = $_REQUEST["image_type"];
$email = $_REQUEST["email"];
$first_name = $_REQUEST["first_name"];
$last_name = $_REQUEST["last_name"];
$your_name = $_REQUEST["your_name"];
$zip_code = $_REQUEST["zip_code"];
$advert_text = $_REQUEST["advert_text"];
$checked_num = $_REQUEST["checked_num"];
$advert_var = $_REQUEST["advertiser_variable"];
$creative_var = $_REQUEST["creative_variable"];

//take data and put them into associative arrays
//data for input boxes and buttons (maybe I should have called the array inputs_arr)
$buttons_arr["email"] = $email;
$buttons_arr["first_name"] = $first_name;
$buttons_arr["last_name"] = $last_name;
$buttons_arr["your_name"] = $your_name;
$buttons_arr["zip_code"] = $zip_code;
//this array holds values for the advertiser text that appears in portrait mode
$advert_arr["text"] = $advert_text;
$advert_arr["advert_var"] = $advert_var;
$advert_arr["creative_var"] = $creative_var;


//This is the nowdoc to hold the template for the overlay. Nowdocs can't have regular variables like heredocs, but they can take sprintf variables for some reason
$template = <<<'TEMPLATE'
    <script type="text/javascript">
      function MJ_checkOverlay(form) {
      var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

      var address = form.ads_survey_email.value;
      if(reg.test(address) !== false) {
        form.method = "post";
        return true;
      }
      else {
        alert("Please enter a valid email address.");
        form.ads_survey_email.focus();
        return false;
      }
    }
    </script>
    <style type="text/css">
    <!--
    #container {
      margin:0;
      padding:0;
      z-index:20000001;
    }
    #container p, #container  ul, #container  table, #container fieldset {margin: 0;}
		div#DFP_OVERLAY_LAYER {transform:
    translate3d(0,0,0);
    position:fixed;
    width:640px;
    height: 480px;
    z-index:20000002;

    /*The following 3 declarations affect the text color of the placeholder values in all input fields*/
    ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: #999;
        opacity: 1; /* Firefox */
    }

    :-ms-input-placeholder { /* Internet Explorer 10-11 */
        color: #999;
    }

    ::-ms-input-placeholder { /* Microsoft Edge */
        color: #999;
    }
    -->
    </style>
    <table id="DFP_OVERLAY_LAYER" align="center" width="640" height="480" style="margin: 0;background-color: #f2f3ef; padding: 0px;border-spacing: 0;" border="0" cellpadding="0" cellspacing="0">
    <tr%16$s>

        <td valign="top" style="text-align:center;"%15$s>
            <image src="%10$s" style="width:%1$s; height: %2$s;">
        </td>
    %8$s
         <td>
        %9$s
    <form action="https://link.motherjones.com/s" method="post" onsubmit="return MJ_checkOverlay(this);"  style="margin-top:0;margin-bottom: 0;text-align:center;"> <!-- Currently pointing at the live site's link; use "Preview" in DFP for tests. If you'd like to test on develop.motherjones.com change to http://linkdev.motherjones.com/s !-->

      <!-- Email !-->
      %3$s %4$s %11$s %5$s %6$s %7$s
      <input include_blank="true" type="hidden" name="lists[ads_SurveyList_forClients]" data-type="boolean" value="true" />

      <input type="hidden" id="st_timestamp" name="%12$s" value="temp" data-type="date" style="display: none;"/>

      <input type="hidden" name="redirect" value="https://www.motherjones.com/thank-you-embed/">

        <input type="hidden" name="%13$s" value="true" /> <!--IMPORTANT: Put the name of the identifying variable here !-->

    </form>

       </td>
    </tr>
    </table>
    <script type="text/javascript">

     function dateConverter(UNIX_timestamp){
      let dateConversion = new Date().toISOString();
      var dateConversionString = dateConversion.toString();
     var shortenedDate = dateConversionString.slice(0,10);
      return shortenedDate;
    }

    document.getElementById("st_timestamp").value = dateConverter(Date.now());
    </script>
TEMPLATE;

//This is the main and only function in this script. This does everything necessary, including calling sprintf to structurize the HTML correctly.
function prepareHTML($type, $the_template, $buttons, $checked_num,$advert_text, $image) {
    //These variables are the main ones that control the layout of the overlay HTML
    $width = "";
    $height = "";
    $table_tr = "";
    $table_tr_height = "";
    $img_width = "";
    $img_height = "";
    $is_landscape = false;
    $btn_length = "120px;";
    $button_margin_bottom = "";
    $ads_text = $advert_text["text"];
    $ads_advertiser_var = $advert_text["advert_var"];
    $ads_creative_var = $advert_text["creative_var"];
    $rowspan_control = "";

    //Variables to hold ops tracking variables
    $ads_creative_var = "vars[ads_Survey" . $ads_advertiser_var . "_" . $ads_creative_var . "]";
    $ads_advertiser_var = "vars[ads_Survey_" . $ads_advertiser_var . "_Timestamp]";

    //JPG or PNG image type variable
    if($image === "JPG") {
        $image_name = "%%FILE:JPG1%%";
    }
    else {
        $image_name = "%%FILE:PNG1%%";
    }

    //logic to assign values depending on if overlay is landscape or portrait format
    if($type === "640x380") {
        //this is landscape mode
        $width = "640px";
        $height = "380px";
        //for landscape view, the $table_tr variable adds a </tr><tr> combo between two <td>s so it would split the table vertically, with the inputs and buttons on the bottom <tr>
        $table_tr = "</tr><tr>";
        $table_tr_height = " style=\"height: $height;\"";

        if($checked_num === "1") {
            $length = "440px;";
        }
        else {
            $length = "200px;";
        }

        $button_margin_bottom = "margin-left:20px;height:43px;margin-right:20px;width: $btn_length;";
        $margin_left_top = "margin-left:20px;width: $length border:1px solid #707070;height:43px;color:#000; text-align: left; font: normal normal 100 18px/26px Mallory;padding-left:10px;";
        $ads_text = "";
        $is_landscape = true;
    }
    else {
        //this is portrait mode;
        $width = "320px";
        $height = "480px";
        $length = "260px;";
        $btn_length = "270px;";
        $margin_left_top = "margin-left: 20px;margin-bottom: 20px;margin-right: 20px;width: $length;border:1px solid #707070;height:52px;color:#000; text-align: left; font: normal normal 100 18px/26px Mallory;padding-left:10px;";
        $button_margin_bottom = "margin-left:20px;margin-right:20px;margin-bottom: 20px;height:52px;padding-right:5px;padding-left:5px;width: $btn_length;";
        $ads_text = "<div style=\"font-size: 18px; font-family: Mallory Light; line-height: 1.5;margin-left: 27px; margin-top: 20px;margin-bottom10px;width:260px;\">" . $ads_text . "</div></td></tr><tr><td style=\"text-align:center;\" valign=\"middle\">";
        $rowspan_control = " rowspan=\"2\"";
        if($checked_num > 3) {
            $ads_text = "";
            $rowspan_control = "";
        }
    }

    //The following logic statements assign the HTML for input fields to each variable that will be passed to a sprintf function that will put together the HTML for the overlay.

    //first name field
    if($buttons["first_name"] !== null) {

        $first_name = "<input name=\"vars[first_name]\" id=\"ads_survey_first_name\" value=\"\" type=\"text\" style=\"$margin_left_top \" placeholder=\"First name\">";
    }
    else {
        $first_name = "";
    }

    //last name field
    if($buttons["last_name"] !== null) {
        $last_name = "<input name=\"vars[last_name]\" id=\"ads_survey_last_name\" value=\"\" type=\"text\" style=\"$margin_left_top \" placeholder=\"Last name\">";
    }
    else {
        $last_name = "";
    }

    if($buttons["your_name"] !== null) {
        $your_name = "<input name=\"vars[last_name]\" id=\"ads_survey_your_name\" value=\"\" type=\"text\" style=\"$margin_left_top \" placeholder=\"Your name\">";
    }

    //email field (this is a required field)
    if($buttons["email"] !== null) {
        $email = "<input include_blank=\"true\" name=\"email\" id=\"ads_survey_email\" value=\"\" type=\"text\" style=\"$margin_left_top \" placeholder=\"Email address\">";
    }
    else {
        $email = "";
    }

    //zip code field
    if($buttons["zip_code"] !== null) {
        $zip_code = "<input data-type=\"string\" type=\"text\" name=\"vars[ads_Survey_postal_code]\" id=\"ads_survey_postal_code\" value=\"\" style=\"$margin_left_top \" placeholder=\"Zip code\">";
    }
    else {
        $zip_code = "";
    }

    //submit button (unlike the other input fields, this was made into a button component)
    $submit_btn = "<button style=\"$button_margin_bottom color:#fff; background: #000000 0% 0% no-repeat padding-box; border:none; border-radius: .375rem; opacity: 1;font: normal normal bold 18px/26px Mallory; \">Submit</button>";

    //to keep track of all the variables passed to the sprintf function, I made a list below to keep track off all of them. At least most of the variables are self-descriptive
    //1) $width,2) $height,3) $first_name,4) $last_name,5) $email,6) $zip_code,7) $submit_btn,8) $table_tr,9) $ads_text,10) $image_name,11) $your_name,12) $ads_advertiser_var,13) $ads_creative_var,14) $button_margin_bottom,15) $rowspan_control,16)$table_tr_height
    echo $temp_template = sprintf($the_template,
                                  $width,
                                  $height,
                                  $first_name,
                                  $last_name,
                                  $email,
                                  $zip_code,
                                  $submit_btn,
                                  $table_tr,
                                  $ads_text,
                                  $image_name,
                                  $your_name,
                                  $ads_advertiser_var,
                                  $ads_creative_var,
                                  $button_margin_bottom,
                                  $rowspan_control,
                                  $table_tr_height) . "<br>";
}
?>
<html>
<head><title>HTML</title></head>
<body>
<div style-"position:absolute;top:5px;"><?php prepareHTML($ad_size, $template, $buttons_arr, $checked_num, $advert_arr, $image_type); ?></div>
<p style="position:absolute;top:520px;">Code is below:</p>
<textarea style="position:absolute;top:600px;" rows="30" cols="120">

<?php prepareHTML($ad_size, $template, $buttons_arr, $checked_num, $advert_arr, $image_type); ?>

</textarea>
</body>

</html>