<?php
namespace util;

class AvatarResize {

    public $dir;
    public $path;

    public function __construct($prefix) {
        $uid = \util\Auth::init()->id;

        $this->dir = 'avatar/' . $uid . '/';
        $this->path = $prefix . $this->dir;

        if (!file_exists($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }


    /**
     * resize用户头像
     * @param   $source 源文件路径
     * @param   $width  宽度
     * @param   $height 高度
     * @param   $size   size名称
     */
    public function resize($source, $width, $height, $size) {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $source;
        $config['maintain_ratio'] = false;
        $config['width'] = $width;
        $config['height'] = $height;
        $config['new_image'] = $this->path . $size . '.png';//. $this->uid . '_'
        $config['master_dim'] = 'auto';

        $img = new Image($config);
        $resize = $img->resize();
        $img->clear();
        return $resize;
    }
}

/* End of file AvatarResize.php */
/* Location: ./application/libraries/AvatarResize.php */
