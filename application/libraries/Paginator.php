<?php

class Paginator {

    // 分页参数名
    protected $page = 'page';

    // url中分页正则
    protected $pagePattern = '/([\/&?]page[\/=])([\d]+)/';

    // url
    protected $url = null;

    // url模板
    protected $urlTheme = null;

    // href
    protected $href = null;

    // href模板
    protected $hrefTheme = 'href="{urlTheme}"';

    // 禁用页class值
    protected $disabled = 'disabled';

    // 选中页class值
    protected $active = 'active';

    // 第一页模板
    protected $firstTheme = '<li class="{class}"><a {href}>First</a></li>';

    // 上一页模板
    protected $prevTheme = '<li class="{class}"><a {href}><</a></li>';

    // 当前页模板
    protected $nowTheme = '<li class="{class}"><span>{nowPage}</span></li>';

    // 其它页模板
    protected $otherTheme = '<li class="{class}"><a {href}>{otherPage}</a></li>';

    // 下一页模板
    protected $nextTheme = '<li class="{class}"><a {href}>></a></li>';

    // 最后一页模板
    protected $lastTheme = '<li class="{class}"><a {href}>End</a></li>';

    // 后缀模板
    protected $suffixTheme = '';//'<li class="{class}">共<span>{totalPages}</span>页 </li>';

    // protected $suffixTheme = '<li class="totalPage">共<span>{totalPages}</span>页 当前<span>{nowPage}</span>页 </li>';

    // 当前页左边显示$otherTheme的个数
    protected $leftSideAmount = 3;

    // 当前页右边显示$otherTheme的个数
    protected $rightSideAmount = 3;

    // 总记录数
    protected $totalRows = 0;

    // 每页显示的行数
    protected $listRows = 10;

    // 总页数
    protected $totalPages = 0;

    // 起始页码
    protected $startPage = 1;

    // 当前页码
    protected $nowPage = 1;

    // 展示的模板
    protected $showTheme = '{getFirstLinkPage}{getPrevLinkPage}{getLeftLinkPage}{getNowLinkPage}{getRightLinkPage}{getNextLinkPage}{getLastLinkPage}{getSuffixPage}';

    // 生成各部分时替换，当存在该属性时则替换成属性值，若该属性不存在，但存在该方法，则替换成该方法返回值，否则替换为当前设置的值
    protected $replaceRule = [ 
        // '{url}' => 'url', // 动态变化
        '{urlTheme}' => 'urlTheme',
        '{href}' => 'href', // 动态变化
        '{class}' => 'class', // 动态变化
        '{nowPage}' => 'nowPage',
        '{otherPage}' => 'otherPage', // 动态变化
        '{totalPages}' => 'totalPages',
        '{getFirstLinkPage}' => 'getFirstLinkPage',
        '{getPrevLinkPage}' => 'getPrevLinkPage',
        '{getLeftLinkPage}' => 'getLeftLinkPage',
        '{getNowLinkPage}' => 'getNowLinkPage',
        '{getRightLinkPage}' => 'getRightLinkPage',
        '{getNextLinkPage}' => 'getNextLinkPage',
        '{getLastLinkPage}' => 'getLastLinkPage',
        '{getSuffixPage}' => 'getSuffixPage'
    ];

    // 是否显示无效按钮（首页、上一页、下一页、尾页）
    protected $isShowDisabled = true;

   // 定义待替换的页的字符串，如果与url中字符串存在冲突则替换
   protected $pendingReplacePage = '[pendingReplacePage]';

    public function __construct(array $config = []) {
        $this->totalRows = $config['totalRows']??0;
        $this->listRows = $config['listRows']??0;
        $this->url = $config['url']??'';
        $this->nowPage = $config['nowPage']??1;
        foreach ($config['config'] as $attribute => $value) {
            if (method_exists($this, $attribute)) {
                throw new Exception($attribute . ' 是类方法！');
            }
            $this->{$attribute} = $value;
        }
        $this->totalPages = ceil($this->totalRows / $this->listRows);
        $this->initUrlTheme();
        $this->initHrefTheme();
    }

    public function __get(string $name) {
        return $this->{$name} ?? null;
    }

    protected function initUrlTheme() {
        $this->url = $this->url ?? $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $this->checkUrl();
        if (!empty($this->pagePattern) && preg_match($this->pagePattern, $this->url, $matches) && isset($matches[1]) && isset($matches[2])) {
            if (intval($matches[2])) {
                $this->nowPage = $matches[2] > $this->totalPages ? $this->totalPages : $matches[2];
            }
            $this->urlTheme = str_replace($matches[0], $matches[1] . $this->pendingReplacePage, $this->url);
        } else {
            $this->urlTheme = $this->url . (strpos($this->url, '?') ? '&' : '?') . $this->page . '=' . $this->pendingReplacePage;
        }
        $this->urlTheme = urldecode($this->urlTheme);
    }

    protected function initHrefTheme() {
        $this->hrefTheme = $this->replace($this->hrefTheme);
    }

    private function checkUrl() {
        check_url:
        if(strpos($this->url, $this->pendingReplacePage) !== false){
            $this->pendingReplacePage = '[' . mt_rand(1, 1000000) . ']';
            goto check_url;
        }
    }

     /**
      * @desc 设置替换规则
      * @param array $replaceRule 替换规则
      * @param  bool $cover 是否覆盖现有规则
      * @return: null
      */
    public function setReplaceRule(array $replaceRule, bool $cover = false) {
        $this->replaceRule = $cover ? $replaceRule : array_merge($this->replaceRule, $replaceRule);
    }

     /**
      * @desc 模板内容替换
      * @param string $subject 待替换的字符串
      * @param array $replaceRule 替换规则
      * @return: string
      */
    private function replace(string $subject, array $replaceRule = null) {
        $replaceRule = $replaceRule ?? $this->replaceRule;
        foreach ($replaceRule as $search => $replace) {
            if (strpos($subject, $search) !== false) {
                if (isset($this->$replace)) {
                    $replace = $this->{$replace};
                } else if (method_exists($this, $replace)) {
                    $replace = $this->{$replace}();
                }
                $subject = str_replace($search, $replace, $subject);
            }
        }
        return $subject;
    }

    /**
     * @desc 获取首页
     */
    public function getFirstLinkPage() {
        $this->otherPage = $this->startPage;
        if ($this->nowPage <= $this->startPage) {
            $this->href = '';
            $this->class = $this->disabled;
            $this->url = '';
            if (!$this->isShowDisabled) {
                return '';
            }
        } else {
            $this->href = str_replace($this->pendingReplacePage, $this->startPage, $this->hrefTheme);
            $this->class = '';
            $this->url = str_replace($this->pendingReplacePage, $this->startPage, $this->urlTheme);
        }
        return $this->replace($this->firstTheme);
    }

    /**
     * @desc 获取上一页
     */
    public function getPrevLinkPage() {
        if ($this->nowPage <= $this->startPage) {
            $this->otherPage = '';
            $this->href = '';
            $this->class = $this->disabled;
            $this->url = '';
            if (!$this->isShowDisabled) {
                return '';
            }
        } else {
            $this->otherPage = $this->nowPage - 1;
            $this->href = str_replace($this->pendingReplacePage, $this->otherPage, $this->hrefTheme);
            $this->class = '';
            $this->url = str_replace($this->pendingReplacePage, $this->otherPage, $this->hrefTheme);
        }
        return $this->replace($this->prevTheme);
    }

    /**
     * @desc 获取当前页左边页
     */
    public function getLeftLinkPage() {
        $leftLinkPage = '';
        $leftSideAmount = $this->leftSideAmount;
        $rightSideAmount = $this->rightSideAmount;
        $subPage = $this->totalPages - $this->nowPage;
        if ($subPage < $rightSideAmount) {
            $leftSideAmount += $rightSideAmount - $subPage;
        }
        for ($i = 1; $i <= $leftSideAmount; $i ++) {
            $leftPage = $this->nowPage - $i;
            if ($leftPage < $this->startPage) {
                break;
            }
            $this->href = str_replace($this->pendingReplacePage, $leftPage, $this->hrefTheme);
            $this->class = '';
            $this->otherPage = $leftPage;
            $this->url = str_replace($this->pendingReplacePage, $leftPage, $this->urlTheme);
            $leftLinkPage = $this->replace($this->otherTheme) . $leftLinkPage;
        }
        return $leftLinkPage;
    }

    /**
     * @desc 获取当前页
     */
    public function getNowLinkPage() {
        $this->otherPage = $this->nowPage;
        $this->href = str_replace($this->pendingReplacePage, $this->nowPage, $this->hrefTheme);
        $this->class = $this->active;
        $this->url = str_replace($this->pendingReplacePage, $this->nowPage, $this->urlTheme);
        return $this->replace($this->nowTheme);
    }

    /**
     * @desc 获取当前页右边页
     */
    public function getRightLinkPage() {
        $rightLinkPage = '';
        $rightSideAmount = $this->rightSideAmount;
        $leftSideAmount = $this->leftSideAmount;
        $subPage = $this->nowPage - $this->startPage;
        if ($subPage < $leftSideAmount) {
            $rightSideAmount += $leftSideAmount - $subPage;
        }
        for ($i = 1; $i <= $rightSideAmount; $i ++) {
            $rightPage = $this->nowPage + $i;
            if ($rightPage > $this->totalPages) {
                break;
            }
            $this->href = str_replace($this->pendingReplacePage, $rightPage, $this->hrefTheme);
            $this->class = '';
            $this->otherPage = $rightPage;
            $this->url = str_replace($this->pendingReplacePage, $rightPage, $this->urlTheme);
            $rightLinkPage .= $this->replace($this->otherTheme);
        }
        return $rightLinkPage;
    }

    /**
     * @desc 获取下一页
     */
    public function getNextLinkPage() {
        if ($this->nowPage >= $this->totalPages) {
            $this->otherPage = '';
            $this->href = '';
            $this->class = $this->disabled;
            $this->url = '';
            if (!$this->isShowDisabled) {
                return '';
            }
        } else {
            $this->otherPage = $this->nowPage + 1;
            $this->href = str_replace($this->pendingReplacePage, $this->otherPage, $this->hrefTheme);
            $this->class = '';
            $this->url = str_replace($this->pendingReplacePage, $this->otherPage, $this->urlTheme);
        }
        return $this->replace($this->nextTheme);
    }

    /**
     * @desc 获取最后一页
     */
    public function getLastLinkPage() {
        $this->otherPage = $this->totalPages;
        if ($this->nowPage >= $this->totalPages) {
            $this->href = '';
            $this->class = $this->disabled;
            $this->url = '';
            if (!$this->isShowDisabled) {
                return '';
            }
        } else {
            $this->href = str_replace($this->pendingReplacePage, $this->totalPages, $this->hrefTheme);
            $this->class = '';
            $this->url = str_replace($this->pendingReplacePage, $this->totalPages, $this->urlTheme);
        }
        return $this->replace($this->lastTheme);
    }

    /**
     * @desc 获取后缀（or 前缀）
     */
    public function getSuffixPage() {
        return $this->replace($this->suffixTheme);
    }

    /**
     * @desc 合成页
     */
    public function show() {
        return $this->totalPages < 2 ? '' : $this->replace($this->showTheme, $this->replaceRule);
    }

    public function getLimit(bool $offset = false) {
        return $offset ? ($this->nowPage - 1) * $this->listRows . ',' . $this->listRows : $this->listRows;
    }

    public function getOffset() {
        return ($this->nowPage - 1) * $this->listRows;
    }
}