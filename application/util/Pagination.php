<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 17/9/12 上午9:54
 */
namespace common;

class Pagination {

    public $total=0;//总条数为150
    public $size=20;//每页显示的内容条数
    public $bthNum=10;//分页按钮个数
    public $pageParam;//页码的参数名为'p'，默认为'page'
    public $className;//分页的样式,
    public $prevButton='«';//上一页按钮上的文字
    public $nextButton='»';//下一页按钮上的文字
    public $firstButton;
    public $lastButton;
    public $href;

    public function __construct($current,$total,$size=20) {
        $this->total = $total;
        $this->size=$size;
    }

    private function _make(){
        if($this->total < $this->size) {
            return false;
        }
        return  [
            'totalCount' => $this->total,
            'pageSize' => $this->size,
            'buttonSize' => $this->bthNum,
            'pageParam' => $this->pageParam,
            'className' => $this->className,
            'prevButton' => $this->prevButton,
            'nextButton' => $this->nextButton,
        ];
    }

    public function fetch(){
        $pager = '<nav class="text-center">';
        if($page = $this->_make()) {
            $pager .= '<script type="text/javascript" src="'.tplreplace('_static_js/bootstrapPager.js').'"></script>';
            $pager .= '<script>document.write(Pager(';
            $pager .= json_encode($page);
            $pager .= '))</script>';
        }
        $pager .= '</nav>';
        return $pager;
    }

    public function show(){
        echo '<nav class="text-center">';
        if($page = $this->_make()) {
            echo '<script type="text/javascript" src="'.tplreplace('_static_js/bootstrapPager.js').'"></script>';
            echo '<script>document.write(Pager(';
            echo json_encode($page);
            echo '))</script>';
        }
        echo '</nav>';
    }
}