<?php
namespace app\controller;

use support\Request;
use support\Response;
use app\common\exception\BusinessException;
use app\common\Config as Conf;
use app\common\Constant;
use app\domain\Config;
/**
 * Class Status
 * @package app\controller
 */
class Status extends BaseController
{
    /**
     * 系统日志
     * @param Request $request
     * @return Response
     */
    public function syslog(Request $request): Response
    {
        $rs = self::RS;
        $config = config('server');
        $log_file = Conf::get($config['log_file'], 'raw', '', true);
        $stdout_file = Conf::get($config['stdout_file'], 'raw', '', true);
        $rs['data'] = [
            'log_file'      => $log_file,
            'stdout_file'   => $stdout_file,
        ];
        return json($rs);
    }

    /**
     * 欢迎页
     * @param Request $request
     * @return Response
     */
    public function welcome(Request $request): Response
    {
        $rs = self::RS;
        $cron = Config::getCrontab();
        $user_sites = Config::getUserSites();
        $sites = Config::getSites();
        $clients = Config::getClients();
        $version = 'v'.IYUU_VERSION();
        $system_info = sprintf('%s / %s', PHP_OS, PHP_OS_FAMILY);
        //读取git信息
        $updated_at = get_current_git_filemtime() . (get_current_git_commit() ?  ' (' . get_current_git_commit() . ')' : '');
        $updated_at = strlen($updated_at) > 10 ? $updated_at : '点此查看';

        $rs['data'] = [
            'cron_total'    => count($cron),
            'sites_total'   => count($user_sites),
            'sites_conut'   => count($sites),
            'clients_total' => count($clients),
            'project'       => iyuu_name(),
            'version'       => $version,
            'updated_at'    => $updated_at,
            'system_info'   => $system_info,
            'PHP_VERSION'   => PHP_VERSION,
            'PHP_BINARY'    => PHP_BINARY,
            'RUNTIME_PATH'  => runtime_path(),
        ];
        return json($rs);
    }
}
