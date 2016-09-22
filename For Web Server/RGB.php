<?php
    $lines = file('RGB.txt', FILE_IGNORE_NEW_LINES);
    $submit = $lines[0];
    $red = $lines[1];
    $green = $lines[2];
    $blue = $lines[3];
    if ( isset( $_GET['submit'] ) ) {
        if ( $_GET['submit'] == "Off") {
            $submit = $_GET['submit'];
            $red = "0";
            $green = "0";
            $blue = "0";
        } else {
            if ( isset( $_GET['R'] ) ) {
                if ( ctype_digit( $_GET['R'] ) ) {
                    $red = $_GET['R'];
                }
            }
            if ( isset( $_GET['G'] ) ) {
                if ( ctype_digit( $_GET['G'] ) ) {
                    $green = $_GET['G'];
                }
            }
            if ( isset( $_GET['B'] ) ) {
                if ( ctype_digit( $_GET['B'] ) ) {
                    $blue = $_GET['B'];
                }
            }
            $submit = $_GET['submit'];
            if ( $submit == "Solid" && $red == "0" && $green == "0" && $blue == "0" ) {
                $submit = "Off";
            }
        }
    }
    $combined = $submit . "\n" . $red . "\n" . $green . "\n" . $blue . "\n";
//echo $combined;
    file_put_contents('RGB.txt', $combined);
?>
<!doctype html>
<html>
    <head>
        <title>RGB LED Controller</title>
        <link rel="icon" type="image/png" href="favicon.png" />
        <meta name="description" content="RGB LED Controller for light strips connected to a Raspberry Pi" />
        <meta name="author" content="surfrock66" />
        <meta name="email" content="surfrock66@surfrock66.com" />
        <meta name="website" content="https://github.com/surfrock66/RPi-RGB-Web" />
        <style>
            body,div,p,span,input,table,tr,td {
                font-family:Verdana, Geneva, sans-serif;
                text-align:center;
                color: #FFFFFF;
                font-size: 36px;
            }
            p {
                margin: 5px;
            }
            body {
                background-color:#000000;
            }
            .hccp-rgbCurrentval,.hccp-rgbClickval {
                background: transparent;
                border: none;
            }
            .hccp-rgbCurrentval {
                max-width: 68px;
            }
            .hccp-rgbClickval {
                max-width: 45px;
            }
            .hccp-rgbsubmit {
                background: transparent;
                width: 300px;
                height:100px;
                padding: 0px 10px;
            }
            .hccp-outerdiv {
                display:inline;
                position:inherit;
            }
            .hccp-canvas {
                border:1px solid #aaaaaa;
            }
            .hccp-innerdiv {
                min-width: 650px;
                width: 90%;
                margin: 0px auto;
            }
            .hccp-colorbar {
                min-width:150px;
            }
            .hccp-resetdiv {
                clear:both;
            }
            .hccp-rgbClickval,.hccp-colorbar,#hccp-clickColor {
                font-size: 24px;
            }
            #hccp-clickColor,#hccp-currentColor {
                border: 1px solid #FFFFFF;
            }
            #hccp-clickColor {
                width: 90px;
            }
            #hccp-currentColor {
                width: 150px;
            }
        </style>
    </head>
    <body>
        <form method="GET" action="RGB.php">
            <div class="hccp-outerdiv">
                <div style="margin-bottom:10px;">
                    Current State: <input type="text" id="hccp-currentColor" value="" />
                    R: <input type="text" name="R" class="hccp-rgbCurrentval" id="hccp-currentColorR" /> 
                    G: <input type="text" name="G" class="hccp-rgbCurrentval" id="hccp-currentColorG" /> 
                    B: <input type="text" name="B" class="hccp-rgbCurrentval" id="hccp-currentColorB" />
                </div>
                <canvas class="hccp-canvas" id="hccp-FindCanvasColor"></canvas>
                <table border="0" class="hccp-innerdiv">
                    <tr>
                        <td colspan="2">
                            <p class="hccp-colorbar">Set Color: <input type="text" id="hccp-clickColor" value="" /></p>
                            <p class="hccp-colorbar">
                                R: <input type="text" name="R" class="hccp-rgbClickval" id="hccp-clickColorR" /> 
                                G: <input type="text" name="G" class="hccp-rgbClickval" id="hccp-clickColorG" /> 
                                B: <input type="text" name="B" class="hccp-rgbClickval" id="hccp-clickColorB" />
                            </p>
                        </td>
                        <td colspan="2">
                            <p><input type="submit" value="Solid" name="submit" class="hccp-rgbsubmit" /></p>
                        </td>
                        <td colspan="2">
                            <p><input type="submit" value="Strobe" name="submit" class="hccp-rgbsubmit" /></p>
                        </td> 
                    </tr>
                    <tr>
                        <td colspan="6">
                            <p>Patterns</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p><input type="submit" value="Party" name="submit" class="hccp-rgbsubmit" /></p>
                        </td> 
                        <td colspan="2">
                            <p><input type="submit" value="Fade" name="submit" class="hccp-rgbsubmit" /></p> 
                        </td> 
                        <td colspan="2">
                            <p><input type="submit" value="Step" name="submit" class="hccp-rgbsubmit" /></p> 
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2">
                            <p><input type="submit" value="Off" name="submit" class="hccp-rgbsubmit" /></p> 
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </table> 
                <div class="hccp-resetdiv"></div>
            </div>
        </form>
        <script src="http://code.jquery.com/jquery-latest.min.js"></script>
        <script>
            /*!
            * Raving Roo - HTML5 Canvas Color Picker (HCCP) v1.0
            * Copyright 2014 by David K. Sutton
            * http://ravingroo.com
            *
            * With the help of:
            * Rodrigo Siqueira - http://js.do/blog/canvas-color-picker/
            * Tony Down - http://stackoverflow.com/a/5624139
            *
            * You are free to use this script on your website.
            * While not required, it would be much appreciated if you could link back to http://ravingroo.com
            *
            * Requires jQuery 1.7 for .on() event handler (http://api.jquery.com/on/).
            * You can substitute .bind() in place of .on() for legacy jQuery support.
            */
            
            // set initial variables and grab image
            var img = new Image();
            img.src = 'RGB.png';
            var canvas = document.getElementById('hccp-FindCanvasColor');
            var context = canvas.getContext('2d');
            
            // draw image onto HTML5 canvas
            img.onload = function() {
                canvas.width = img.width;
                canvas.height = img.height;
                context.drawImage(img,0,0);
            };
            
            // convert individual RGB components
            function unitConversion(unit) {
                var hexconv = unit.toString(16);
                return hexconv.length == 1 ? '0' + hexconv : hexconv;
            }
            
            // convert RGB to Hex
            function RGBtoHex(r, g, b) {
                return "#" + unitConversion(r) + unitConversion(g) + unitConversion(b);
            }
            
            // find pixel color value on jQuery mousemove or click event
            // use document.getElementById to return values to user and change CSS
            $('#hccp-FindCanvasColor').on('click', function(event){
                var x = event.pageX - canvas.offsetLeft;
                var y = event.pageY - canvas.offsetTop;
                var img_data = context.getImageData(x, y, 1, 1).data;
                var R = img_data[0];
                var G = img_data[1];
                var B = img_data[2];
                var rgb = 'rgb('+R+','+G+','+B+')';
                var hex = RGBtoHex(R,G,B);
                if(event.type=='click') {
                    if(hex=='#000000') {
                        document.getElementById('hccp-clickColor').style.color = '#FFFFFF';
                        document.getElementById('hccp-clickColor').value = 'Off';
                    } else {
                        document.getElementById('hccp-clickColor').style.color = hex;
                    }
                    document.getElementById('hccp-clickColor').style.backgroundColor = hex;
                    document.getElementById('hccp-clickColorR').value = R;
                    document.getElementById('hccp-clickColorG').value = G;
                    document.getElementById('hccp-clickColorB').value = B;
                }
            });

            var hex = RGBtoHex(<?php echo $red; ?>,<?php echo $green; ?>,<?php echo $blue; ?>);
            <?php
                if ( $submit == "Solid" ) { 
                    $clickColorColor = "hex";
                    $clickColorBackground = "hex";
                } else { 
                    $clickColorColor = "'#FFFFFF'"; 
                    $clickColorBackground = "'#000000'";
                }
            ?>
            document.getElementById('hccp-clickColor').style.color = <?php echo $clickColorColor; ?>;
            document.getElementById('hccp-clickColor').style.backgroundColor = <?php echo $clickColorBackground; ?>;
            document.getElementById('hccp-clickColor').value = <?php echo "'".$submit."'"; ?>;
            document.getElementById('hccp-clickColorR').value = <?php echo $red; ?>;
            document.getElementById('hccp-clickColorG').value = <?php echo $green; ?>;
            document.getElementById('hccp-clickColorB').value = <?php echo $blue; ?>;

            document.getElementById('hccp-currentColor').style.color = <?php echo $clickColorColor; ?>;
            document.getElementById('hccp-currentColor').style.backgroundColor = <?php echo $clickColorBackground; ?>;
            document.getElementById('hccp-currentColor').value = <?php echo "'".$submit."'"; ?>;
            document.getElementById('hccp-currentColorR').value = <?php echo $red; ?>;
            document.getElementById('hccp-currentColorG').value = <?php echo $green; ?>;
            document.getElementById('hccp-currentColorB').value = <?php echo $blue; ?>;
        </script>
    </body>
</html>
