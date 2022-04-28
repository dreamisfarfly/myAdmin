<?php

namespace App\Admin\Core\Utils;

use App\Admin\Core\Utils\Uuid\IdUtils;
use Exception;

/**
 * 生成验证码
 *
 * @author zjj
 */
class SecurityCodeUtils
{

    /**
     * 设置验证码宽度
     * @var int
     */
    private int $width = 200;

    /**
     * 设置验证码高度
     * @var int
     */
    private int $height = 100;

    /**
     * 画布
     */
    private $image;

    /**
     * 设置验证码大小
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function setSize(int $width, int $height): SecurityCodeUtils
    {
        $this->width = $width;
        $this->height = $height;
        return $this;
    }

    /**
     * 初始化
     * @return int
     */
    public function init(): int
    {
        $this->image = imagecreatetruecolor($this->width,$this->height);// 设置画布的大小
        $bjColor = imagecolorallocate($this->image, 196, 201, 195);// 设置画布背景色
        imagefill($this->image, 0, 0, $bjColor);// 填充画布

        //边框
        $lineColor = imagecolorallocate($this->image,26,173,25);
        imageline($this->image, 0, 1, $this->width, 1, $lineColor);
        imageline($this->image, 1, 0, 1, $this->height, $lineColor);
        imageline($this->image, $this->width-2, 0, $this->width-2, $this->height, $lineColor);
        imageline($this->image, 0, $this->height-2, $this->width, $this->height-2, $lineColor);

        $data = $this->operation();

        $tcolor = imagecolorallocate($this->image, 50, 100, 150);
        imagettftext(
            $this->image,
            28,
            rand(0,30),
            40,
            ($this->height/2)+14,
            $tcolor,
            public_path().'/font/Gabriola.ttf',
            $data['oneNumber']
        );
        imagettftext(
            $this->image,
            28,
            rand(0,30),
            40+26,
            ($this->height/2)+14,
            $tcolor,
            public_path().'/font/Gabriola.ttf',
            $data['symbol']
        );
        imagettftext(
            $this->image,
            28,
            rand(0,30),
            40+52,
            ($this->height/2)+14,
            $tcolor,
            public_path().'/font/Gabriola.ttf',
            $data['towNumber']
        );
        imagettftext(
            $this->image,
            28,
            rand(0,30),
            40+78,
            ($this->height/2)+14,
            $tcolor,
            public_path().'/font/Gabriola.ttf',
            '='
        );
        imagettftext(
            $this->image,
            28,
            rand(0,30),
            40+110,
            ($this->height/2)+14,
            $tcolor,
            public_path().'/font/Gabriola.ttf',
            '?'
        );

        return $data['calculatedValue'];

    }

    /**
     * 生成
     * @throws Exception
     */
    public function outputImage()
    {
        if($this->image == null){
            throw new Exception('请先初始化验证码');
        }
        $uuid = IdUtils::fastUUID();
        imagejpeg($this->image, $uuid.'.jpg');
        $file = public_path().'/'.$uuid.'.jpg';
        if($fp = fopen($file,"rb", 0))
        {
            $gambar = fread($fp,filesize($file));
            fclose($fp);
            $base64 = base64_encode($gambar);
            unlink($file);
            return $base64;
        }
    }

    /**
     * 计算
     * @return array
     */
    private function operation(): array
    {
        $symbol = rand(0,3);
        $data = [];
        switch ($symbol){
            case 0: //加
                $data['oneNumber'] = rand(0,9);
                $data['towNumber'] = rand(0,9);
                $data['symbol'] = '+';
                $data['calculatedValue'] = $data['oneNumber'] + $data['towNumber'];
                break;
            case 1: //减
                $data['oneNumber'] = rand(0,9);
                $data['towNumber'] = rand(0,$data['oneNumber']);
                $data['symbol'] = '-';
                $data['calculatedValue'] = $data['oneNumber'] - $data['towNumber'];
                break;
            case 2: //乘
                $data['oneNumber'] = rand(0,9);
                $data['towNumber'] = rand(0,9);
                $data['symbol'] = '*';
                $data['calculatedValue'] = $data['oneNumber'] * $data['towNumber'];
                break;
            case 3: //除
                $data['oneNumber'] = rand(0,9);
                if($data['oneNumber'] % 2 == 0 && $data['oneNumber'] != 0){
                    $data['towNumber'] = floor($data['oneNumber'] / 2);
                }else{
                    if($data['oneNumber'] == 0){
                        $data['towNumber'] = rand(1,9);
                    }else{
                        rand(0,1)==0?$data['towNumber'] = 1:$data['towNumber'] = $data['oneNumber'];
                    }
                }
                $data['symbol'] = '/';
                $data['calculatedValue'] = $data['oneNumber'] / $data['towNumber'];
                break;
        }
        return $data;
    }

}
