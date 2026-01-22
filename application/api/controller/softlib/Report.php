<?php

namespace app\api\controller\softlib;

use app\common\controller\Api;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Request;

class Report extends Api
{
    protected $noNeedLogin = ['*'];
    private \app\admin\model\softlib\Report $report;
    private \app\admin\model\softlib\ReportCat $reportCat;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->report = new \app\admin\model\softlib\Report();
        $this->reportCat = new \app\admin\model\softlib\ReportCat();
    }


    /**
     * 显示资源列表
     *
     * @return void
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function index()
    {
        $catId = $this->request->param('catId', 0, 'intval');
        $articleID = $this->request->param('articleID', 0, 'intval');
        $pages = $this->request->param('pages', 1, 'intval');
        // 如果文章id不为空，则更新当前文章的阅读量，并返回文章内容
        if (!empty($articleID)) {
            $result = $this->report
                ->where('id', $articleID)
                ->field(['id', 'image', 'title', 'content', 'views', 'createtime'])
                ->find();
            if (empty($result)) {
                $this->error('没有找到该文章');
            }
            $this->report
                ->where('id', $articleID)
                ->setInc('views');
            $result['views'] = $result['views'] + 1;
            $result['image'] = cdnurl($result['image'], true);
            $this->success('ok', $result);
        }
        // 如果分类id不为空，则返回该分类下的文章列表
        if (!empty($catId)) {
            $results = $this->report
                ->where('cat_id', $catId)
                ->order('id', 'desc')
                ->field(['id', 'image', 'title', 'views', 'createtime'])
                ->page($pages, 15)
                ->select();
            if (!empty($results)) {
                foreach ($results as &$result) {
                    $result['image'] = cdnurl($result['image'], true);
                }
            }
            $this->success('ok', $results);
        }
        // 如果分类id为空，则返回所有分类
        $results = $this->reportCat
            ->order('id', 'desc')
            ->field(['id', 'title'])
            ->select();
        $this->success('ok', $results);
        //
    }
}
