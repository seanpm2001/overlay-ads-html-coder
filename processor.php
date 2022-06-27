<?php
$ad_size = $_POST["ad_size"];
$image_name1 = $_REQUEST["image_name1"];
$advert_text = $_REQUEST["advert_text"];
$email = $_REQUEST["email"];
$first_name = $_REQUEST["first_name"];
$last_name = $_REQUEST["last_name"];
$zip_code = $_REQUEST["zip_code"];
$checked_num = $_REQUEST["checked_num"];

/*

echo "<p style='background-color:#000;color:#fff;'>" .  gettype($checked_num) . "</p>";

echo $ad_size . PHP_EOL;
echo $image_name1 . PHP_EOL;
echo $image_name2 . PHP_EOL;
echo $email . PHP_EOL;

if ($first_name !== null) {
    echo $first_name . PHP_EOL;
}
else {
    echo "there's nothing here";
}
echo $last_name . PHP_EOL;
echo $zip_code . PHP_EOL;

die();
*/

$buttons_arr["email"] = $email;
$buttons_arr["first_name"] = $first_name;
$buttons_arr["last_name"] = $last_name;
$buttons_arr["zip_code"] = $zip_code;

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

    div#DFP_OVERLAY_LAYER {transform:
    translate3d(0,0,0);position:fixed;
        position:fixed;
        width: %1$s px; /* to control landscape & portrait */
        height: %2$s px;
        z-index:20000002;
        background-image: url('placeholder.png');/*need to replace image name */
        background-repeat: no-repeat;
        background-position: bottom;
        background-color: #FFFFFF;
        border: 1px solid #000;
    }


    -->
    </style>

    <table align="center" width="%1$s" height="%2$s" style="background-color: #f2f3ef; padding: 0px;border-spacing: 0;" border="1" cellpadding="0" cellspacing="0">
    <tr>

        <td align="center">
            <image src="https://live.cdn.renderosity.com/gallery/items/3000942/images/1969012/dfb9e29b1fb1f3c25f9fd87691a736c8_original.jpg" style="width:%1$s ; height: %2$s ;">
        </td>
    %8$s
         <td align="left">
        %9$s
    <form action="https://link.motherjones.com/s" method="post" onsubmit="return MJ_checkOverlay(this);"> <!-- Currently pointing at the live site's link; use "Preview" in DFP for tests. If you'd like to test on develop.motherjones.com change to http://linkdev.motherjones.com/s !-->

      <!-- Email !-->

      %3$s

      %4$s

      %5$s

      %6$s

      %7$s

      <input include_blank="true" type="hidden" name="lists[ads_SurveyList_forClients]" data-type="boolean" value="true" />

      <input type="hidden" id="st_timestamp" name="vars[ads_Survey_RobCathyTestApr22_Timestamp]" value="temp" data-type="date" style="display: none;"/>

      <input type="hidden" name="redirect" value="https://www.motherjones.com/thank-you-embed/">

        <input type="hidden" name="vars[ads_Survey_RobCathyTestApr22]" value="true" /> <!--IMPORTANT: Put the name of the identifying variable here !-->

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

if($ad_size !== null && $ad_size !== "") {
    //first name, last name, emaiil, zip code
    switch($ad_size) {
        case("640x380"):
            prepareHTML($ad_size, $template, $buttons_arr, $checked_num, $advert_text);
            break;
        case("320x480"):
            prepareHTML($ad_size, $template, $buttons_arr, $checked_num, $advert_text);
            break;
        default:
            echo "There's nothing here for you, young padwan";
            break;
    }
}

function prepareHTML($type, $the_template, $buttons, $checked_num,$advert_text) {
    $width = "";
    $height = "";
    $table_tr = "";
    $img_width = "";
    $img_height = "";
    $is_landscape = false;
    $btn_length = "120px;";
    $ads_text = "";

    if($type === "640x380") {
        echo "landscape you have picked, you have";
        $width = "640";
        $height = "380";
        $table_tr = "</tr><tr>";

        if($checked_num === "1") {
            $length = "461px;";
        }
        else {
            $length = "220px;";
        }

        $margin_left_top = "margin-left: 20px; margin-top: 10px;margin-right: 0;";
        $is_landscape = true;
    }
    else {
        echo "portrait you have picked, you have";
        $width = "320";
        $height = "640";
        $btn_length = $length = "265px;";
        $margin_left_top = "margin-left: 27px;margin-top: 35px;margin-right: 27px;";
        $ads_text = "<p style=\"font-size: 18px; font-family: Mallory Light; line-height: 1.5;margin-left: 20px; margin-top: 0;padding-right:20px;\">" . $advert_text . "</p>";
    }

    if($buttons["first_name"] !== null) {

        $first_name = "<input name=\"vars[first_name]\" id=\"ads_survey_first_name\" value=\"First name\" type=\"text\" style=\" $margin_left_top padding-left: 5px; width: $length float:left;border:0px solid #999999;height:43px; color:#999999; text-align: left; font-size: 16px;\" placeholder=\"First name\">";
    }
    else {
        $first_name = "";
    }

    if($buttons["last_name"] !== null) {
        $last_name = "<input name=\"vars[last_name]\" id=\"ads_survey_last_name\" value=\"Last name\" type=\"text\" style=\"$margin_left_top width: $length  float:left;border:0px solid #999999;height:43px;color:#999999; text-align: left; font-size: 16px; padding-left: 5px; \" placeholder=\"Last name\">";
    }
    else {
        $last_name = "";
    }

    if($buttons["email"] !== null) {
        $email = "<input include_blank=\"true\" name=\"email\"xds id=\"ads_survey_email\" value=\"Email address\" type=\"text\" style=\"$margin_left_top padding-left: 5px; width: $length float:left;border:0px solid #999999;height:43px;color:#999999; text-align: left; font-size: 16px;\" placeholder=\"Email address\">";
    }
    else {
        $email = "";
    }

    if($buttons["zip_code"] !== null) {
        $zip_code = "<input data-type=\"string\" type=\"text\" name=\"vars[ads_Survey_postal_code]\" id=\"ads_survey_postal_code\" value=\"Zip code\" style=\"$margin_left_top padding-left: 5px; width: $length float:left;border:0px solid #999999;height:43px;color:#999999; text-align: left; font-size: 16px; padding-left: 5px; \" placeholder=\"Zip code\">";
    }
    else {
        $zip_code = "";
    }

    $submit_btn = "<button style=\"$margin_left_top color:#fff; background: #000000 0% 0% no-repeat padding-box; border-radius: 6px; opacity: 1; width: $btn_length; height: 43px; font: normal normal bold 18px/26px Mallory; \">Submit</button>";

    echo $temp_template = sprintf($the_template, $width, $height, $first_name, $last_name, $email,$zip_code,$submit_btn,$table_tr,$ads_text);
}
?>