<?php
    $lines = file('RGB.txt');
    $red = $lines[0];
    $green = $lines[1];
    $blue = $lines[2];
    if ( isset( $_POST['R'] ) ) {
        if ( ctype_digit( $_POST['R'] ) ) {
            $red = $_POST['R'] . "\n";
        }
    }
    if ( isset( $_POST['G'] ) ) {
        if ( ctype_digit( $_POST['G'] ) ) {
            $green = $_POST['G'] . "\n";
        }
    }
    if ( isset( $_POST['B'] ) ) {
        if ( ctype_digit( $_POST['B'] ) ) {
            $blue = $_POST['B'] . "\n";
        }
    }
    $combined = $red . $green . $blue;
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
            body,div,p,span,input {
                text-align:center;
                color: #FFFFFF;
            }
            p {
                margin: 10px auto;
            }
            body {
                background-color:#000000;
            }
            .hccp-rgbval {
                max-width: 64px;
                background: transparent;
                border: none;
                font-size:36px;
            }
            .hccp-rgbsubmit {
                background: transparent;
                font-size: 36px;
                width: 300px;
                height:100px;
            }
            .hccp-outerdiv {
                display:inline;
                position:inherit;
            }
            .hccp-canvas {
                border:1px solid #aaaaaa;
            }
            .hccp-innerdiv {
		font-size: 36px;
                font-family:Verdana, Geneva, sans-serif;
                min-width: 650px;
                width:50%;
                margin: 0px auto;
            }
            .hccp-colorbar {
                min-width:150px;
            }
            .hccp-resetdiv {
                clear:both;
            }
            #hccp-clickColorBackground {
                border: 1px solid;
            }
        </style>
    </head>
    <body>
        <form method="post" action="RGB.php">
            <div class="hccp-outerdiv">
                <canvas class="hccp-canvas" id="hccp-FindCanvasColor"></canvas>
                <div class="hccp-innerdiv">
                    <div style="float:left;">
                        <p class="hccp-colorbar">Selected Color: <span id="hccp-clickColorBackground"><span id="hccp-clickColorFont">Color</span></span></p>
                        <p>R: <input type="text" name="R" class="hccp-rgbval" id="hccp-clickColorR" /> G: <input type="text" name="G" class="hccp-rgbval" id="hccp-clickColorG" /> B: <input type="text" name="B" class="hccp-rgbval" id="hccp-clickColorB" /></p>
                    </div>
                    <div style="float:right;">
                        <p><input type="submit" value="Set Color" name="submitRGB" class="hccp-rgbsubmit" /></p>
                    </div>
                </div><br />
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
                    document.getElementById('hccp-clickColorFont').style.color = hex;
                    document.getElementById('hccp-clickColorBackground').style.backgroundColor = hex;
                    document.getElementById('hccp-clickColorR').value = R;
                    document.getElementById('hccp-clickColorG').value = G;
                    document.getElementById('hccp-clickColorB').value = B;
                }
            });
            var hex = RGBtoHex(<?php echo $red; ?>,<?php echo $green; ?>,<?php echo $blue; ?>);
            document.getElementById('hccp-clickColorFont').style.color = hex;
            document.getElementById('hccp-clickColorBackground').style.backgroundColor = hex;
            document.getElementById('hccp-clickColorR').value = <?php echo $red; ?>;
            document.getElementById('hccp-clickColorG').value = <?php echo $green; ?>;
            document.getElementById('hccp-clickColorB').value = <?php echo $blue; ?>;
        </script>
    </body>
</html>
