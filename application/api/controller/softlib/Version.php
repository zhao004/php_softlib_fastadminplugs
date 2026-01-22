<?php

namespace app\api\controller\softlib;

use addons\softlib\library\IpInfo;
use app\common\controller\Api;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class Version extends Api
{

    protected $noNeedLogin = ['*'];
    private \app\admin\model\softlib\Version $versionModel;
    private \app\admin\model\softlib\AccessLogs $accessLogs;

    public function __construct()
    {
        parent::__construct();
        $this->versionModel = new \app\admin\model\softlib\Version();
        $this->accessLogs = new \app\admin\model\softlib\AccessLogs();
        $oldVersion = $this->request->param('version', '', 'trim');
        $ip = $this->request->param('ip', $this->request->ip());
        if (!empty($oldVersion)) {
            $ipInfo = new IpInfo($ip);
            $ipInfo = $ipInfo->getInfo();
            if ($ipInfo->getCode() != 200) {
                $this->accessLogs->save([
                    'ip' => $ip,
                    'address' => '未知',
                    'network' => '未知',
                ]);
            } else {
                $ipInfo = $ipInfo->getIpInfo();
                $country = empty($ipInfo->getCountry()) ? '未知' : $ipInfo->getCountry();
                $province = empty($ipInfo->getProvince()) ? '未知' : $ipInfo->getProvince();
                $city = empty($ipInfo->getCity()) ? '未知' : $ipInfo->getCity();
                $district = empty($ipInfo->getDistrict()) ? '未知' : $ipInfo->getDistrict();
                $this->accessLogs->save([
                    'ip' => $ip,
                    'address' => $country . '_' . $province . '_' . $city . '_' . $district,
                    'network' => $ipInfo->getOperator(),
                ]);
            }
        }

    }

    /**
     * 显示资源列表
     *
     * @return void
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function index(): void
    {
        $oldVersion = $this->request->param('version', '', 'trim');
        // 查询所有启用版本（顺序无所谓）
        $result = $this->versionModel
            ->where('enable_switch', 1)
            ->order('version', 'desc')
            ->field([
                'version',
                'title',
                'content',
                'dow_url',
                'forced_switch',
            ])
            ->select();
        if (empty($result)) {
            $this->success('ok', null);
        }
        // 没传版本号，返回全部版本（转换布尔）
        if (empty($oldVersion)) {
            foreach ($result as &$item) {
                $item['forced_switch'] = boolval($item['forced_switch'] ?? false);
            }
            $this->success('ok', $result);
        }
        // 过滤出版本号更高的记录
        $higherVersions = array_filter((array)$result, function ($item) use ($oldVersion) {
            return version_compare($item['version'], $oldVersion, '>');
        });
        if (empty($higherVersions)) {
            $this->success('ok', null);
        }
        // 重新按 version 从大到小排序
        usort($higherVersions, function ($a, $b) {
            return version_compare($b['version'], $a['version']);
        });
        // 返回最新的版本
        $latest = $higherVersions[0];
        $latest['forced_switch'] = boolval($latest['forced_switch'] ?? false);
        $this->success('ok', $latest);
    }
}
