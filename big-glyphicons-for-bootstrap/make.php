<?php
/**
*   将所有图标打包成一个png， 并且生成对应的css
**/

$icon_path = 'icons/*.png';
$icon_files = glob($icon_path);


$big_png = imagecreatetruecolor(720,720);
imagealphablending($big_png,false);
imagesavealpha($big_png,true);
$alpha = imagecolorallocatealpha($big_png,0,0,0,127);
imagefill($big_png,0,0,$alpha);

$css_file = 'icons_big.css';
$css_code = ".icon-big{background-image:url(icons_big.png);background-repeat:no-repeat;display:inline-block;height:36px; width: 36px;vertical-align:text-top;}\n";
$css_code .= ".icon-white.icon-big{background-image:url(icons_big_white.png);}\n";
$size_w = 36;
$size_h = 36;
$start_x = 0;
$start_y = 0;
foreach($icon_files as $key=>$path)
{
    list($width, $height, $type, $attr) = getimagesize($path);
    $icon = imagecreatefrompng($path);
    imagealphablending($icon,false);
    imagesavealpha($icon,true);

    $target_x = (int)(($size_w - $width)/2) + $start_x;
    $target_y = (int)(($size_h - $height)/2) + $start_y;

    imagecopyresampled($big_png,$icon,$target_x,$target_y,0,0,$width,$height,$width,$height);

    $icon_name = substr($path, 21);
    $icon_name = substr($icon_name,0, -4);
    $position_x = -$start_x;
    $position_y = -$start_y;
    $css_code .= ".icon-big.icon-{$icon_name}{background-position:{$position_x}px {$position_y}px;}\n";

    $start_x += $size_w;
    if($start_x >= 720)
    {
        $start_x = 0;
        $start_y += $size_h;
    }

    printf("DONE:\t%s\n",$path);
}

imagepng ($big_png,'icons_big.png');
imagefilter($big_png,IMG_FILTER_NEGATE);
imagepng ($big_png,'icons_big_white.png');
file_put_contents($css_file,$css_code);
echo "ALL COMPLETE!";

?>