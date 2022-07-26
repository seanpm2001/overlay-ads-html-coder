<?php
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

$buttons_arr["email"] = $email;
$buttons_arr["first_name"] = $first_name;
$buttons_arr["last_name"] = $last_name;
$buttons_arr["your_name"] = $your_name;
$buttons_arr["zip_code"] = $zip_code;

$advert_arr["text"] = $advert_text;
$advert_arr["advert_var"] = $advert_var;
$advert_arr["creative_var"] = $creative_var;

$test_template = <<<'TEST'
    <style>
     .test_class {
     	width: %1$s;
     	height: %2$s;
        border: 1px solid #000;
     }
    </style>

    <div class="test_class">This is a template</div>
TEST;

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

    -->
    </style>

    <table align="center" width="%1$s" height="%2$s" style="background-color: #f2f3ef; padding: 0px;border-spacing: 0;" border="0" cellpadding="0" cellspacing="0">
    <tr>

        <td align="center"%15$s>
            <image src="%10$s" style="width:%1$s ; height: %2$s ;">
        </td>
    %8$s
         <td align="left">
        %9$s
    <form action="https://link.motherjones.com/s" method="post" onsubmit="return MJ_checkOverlay(this);" style="margin-top:0;"> <!-- Currently pointing at the live site's link; use "Preview" in DFP for tests. If you'd like to test on develop.motherjones.com change to http://linkdev.motherjones.com/s !-->

      <!-- Email !-->

      %3$s

      %4$s

      %11$s

      %5$s

      %6$s

      %7$s

      <input include_blank="true" type="hidden" name="lists[ads_SurveyList_forClients]" data-type="boolean" value="true" />

      <input type="hidden" id="st_timestamp" name="%12$s" value="temp" data-type="date" style="display: none;"/>

      <input type="hidden" name="redirect" value="https://www.motherjones.com/thank-you-embed/">

        <input type="hidden" name="%13$s" value="true" /> <!--IMPORTANT: Put the name of the identifying variable here !-->

    </form>

       </td>
    </tr>
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

function prepareHTML($type, $the_template, $buttons, $checked_num,$advert_text, $image) {
    $width = "";
    $height = "";
    $table_tr = "";
    $img_width = "";
    $img_height = "";
    $is_landscape = false;
    $btn_length = "120px;";
    $button_margin_bottom = "";
    $ads_text = $advert_text["text"];
    $ads_advertiser_var = $advert_text["advert_var"];
    $ads_creative_var = $advert_text["creative_var"];
    $rowspan_control = "";

    $ads_creative_var = "vars[ads_Survey" . $ads_advertiser_var . "_" . $ads_creative_var . "]";
    $ads_advertiser_var = "vars[ads_Survey_" . $ads_advertiser_var . "_Timestamp]";

    if($image === "JPG") {
        $image_name = "%%FILE:JPG1%%";
    }
    else {
        $image_name = "%%FILE:PNG1%%";
    }

    if($type === "640x380") {
        $width = "640";
        $height = "380";
        $table_tr = "</tr><tr>";

        if($checked_num === "1") {
            $length = "461px;";
        }
        else {
            $length = "220px;";
        }

        $margin_left_top = "margin-left: 20px; margin-top: 20px;margin-right: 0;margin-bottom:0;";
        $ads_text = "";
        $is_landscape = true;
    }
    else {
        //echo "portrait you have picked, you have";
        $width = "320";
        $height = "480";
        $btn_length = $length = "260px;";
        $margin_left_top = "margin-left: 20px;margin-top: 30px;margin-right: 27px;";
        $button_margin_bottom = "margin-bottom: 25px;";
        $ads_text = "<div style=\"font-size: 18px; font-family: Mallory Light; line-height: 1.5;margin-left: 20px; margin-top: 20px;margin-bottom:20px;width:260px;\">" . $ads_text . "</div></td></tr><tr><td align=\"center\" valign=\"bottom\">";
        $rowspan_control = " rowspan=\"2\"";
        if($checked_num > 3) {
            $ads_text = "";
            $rowspan_control = "";
        }
    }

    if($buttons["first_name"] !== null) {

        $first_name = "<input name=\"vars[first_name]\" id=\"ads_survey_first_name\" value=\"\" type=\"text\" style=\" $margin_left_top padding-left: 15px; width: $length float:left;border:1px solid #999999;height:52px; color:#999999; text-align: left; font: normal normal 300 18px/26px Mallory;\" placeholder=\"First name\">";
    }
    else {
        $first_name = "";
    }

    if($buttons["last_name"] !== null) {
        $last_name = "<input name=\"vars[last_name]\" id=\"ads_survey_last_name\" value=\"\" type=\"text\" style=\"$margin_left_top width: $length  float:left;border:1px solid #999999;height:52px;color:#999999; text-align: left; font: normal normal 300 18px/26px Mallory; padding-left: 15px; \" placeholder=\"Last name\">";
    }
    else {
        $last_name = "";
    }

    if($buttons["your_name"] !== null) {
        $your_name = "<input name=\"vars[last_name]\" id=\"ads_survey_your_name\" value=\"\" type=\"text\" style=\"$margin_left_top width: $length  float:left;border:1px solid #999999;height:52px;color:#999999; text-align: left; font: normal normal 300 18px/26px Mallory; padding-left: 15px; \" placeholder=\"Your name\">";
    }

    if($buttons["email"] !== null) {
        $email = "<input include_blank=\"true\" name=\"email\"xds id=\"ads_survey_email\" value=\"\" type=\"text\" style=\"$margin_left_top padding-left: 15px; width: $length float:left;border:1px solid #999999;height:52px;color:#999999; text-align: left; font: normal normal 300 18px/26px Mallory;\" placeholder=\"Email address\">";
    }
    else {
        $email = "";
    }

    if($buttons["zip_code"] !== null) {
        $zip_code = "<input data-type=\"string\" type=\"text\" name=\"vars[ads_Survey_postal_code]\" id=\"ads_survey_postal_code\" value=\"Zip code\" style=\"$margin_left_top padding-left: 5px; width: $length float:leftx`;border:1px solid #999999;height:52px;color:#999999; text-align: left; font-size: 16px; padding-left: 15px; \" placeholder=\"Zip code\">";
    }
    else {
        $zip_code = "";
    }

    $submit_btn = "<button style=\"$margin_left_top $button_margin_bottom color:#fff; background: #000000 0% 0% no-repeat padding-box; border-radius: 6px; opacity: 1; width: $btn_length; height: 52px; font: normal normal bold 18px/26px Mallory;padding-left:15px; \">Submit</button>";

    echo $temp_template = sprintf($the_template, $width, $height, $first_name, $last_name, $email,$zip_code,$submit_btn,$table_tr,$ads_text, $image_name, $your_name,$ads_advertiser_var,$ads_creative_var,$button_margin_bottom,$rowspan_control) . "<br>";
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