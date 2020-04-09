<?php
/**
 * 单页模块model
 * PHP version 5.5+
 *
 * @category Modules
 *
 * @package Page
 *
 * @author fy <jhfangying@qq.com>
 *
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 *
 * @link link('官网','http://www.deeja.top');
 * */
namespace Modules\Page\Models;

use Modules\Page\Models\PageThemeFactory;

/**
 * 奖品模块model
 * PHP version 5.5+
 *
 * @category Modules
 *
 * @package Prize
 *
 * @author fy <jhfangying@qq.com>
 *
 * @license Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * Copyright (c) 2017 金华迪加网络科技有限公司 版权所有
 * 未经许可，任何单位及个人不得做营利性使用
 *
 * @link link('官网','http://www.deeja.top');
 * */
class Pages_model
{
    public $_pages_m = null;
    public $_cache = null;
    public $_cacheprefix = '';
    public $_cachename = 'pages';
    /**
     * 构造函数
     *
     * @return void
     */
    public function __construct()
    {
        $this->_pages_m = new \M('pages');
        if (CACHEMODE == "Redis") {
            $this->_cache = new \Predis\Client(
                array(
                    'scheme' => 'tcp',
                    'host' => REDIS_HOST,
                    'port' => REDIS_PORT,
                    'password' => REDIS_PASSWORD,
                )
            );
            $this->_cacheprefix = CACHEPREFIX;
            $this->_cachename = 'pages';
        }
    }

    public function getAll($type = 0)
    {
        $where = '1';
        if ($type > 0) {
            $where .= ' and type=' . $type;
        }
        $where .= ' order by id asc';
        $data = $this->_pages_m->select($where);
        return $data;
    }

    public function getPcPages(){
        $where='1';
        $where.=' and type=1 or type=3';
        $where.=' order by id asc';
        $data=$this->_pages_m->select($where);
        return $data;
    }
    public function getMobilePages()
    {
        return $this->getAll(2);
    }

    public function getFirstPcPage(){
        $where='1';
        // if($type>0){
        $where.=' and type=1 or type=3';
        // }
        $where.=' order by id asc';
        // echo $where;
        $data=$this->_pages_m->find($where);
        $theme=PageThemeFactory::create($data['type']);
        $theme->data(unserialize($data['pagedata']));
        $data['pagedata']=$theme->toArray();
        return $data;

    }
    public function getFirstMobilePage()
    {
        return $this->getFirst(2);
    }
    public function getFirst($type = 0)
    {
        $where = '1';
        if ($type > 0) {
            $where .= ' and type=' . $type;
        }
        $where .= ' order by id asc';

        $data = $this->_pages_m->find($where);
        if(!$data){
            return null;
        }
        $theme = PageThemeFactory::create($data['type']);
        $theme->data(unserialize($data['pagedata']));
        $data['pagedata'] = $theme->toArray();
        return $data;
    }
    public function save($data)
    {
        $id = 0;
        if (isset($data['id'])) {
            $id = intval($data['id']);
        }
        unset($data['id']);
        if ($id > 0) {
            //修改
            $olddata = $this->getById($id);

            if (isset($data['type']) && $olddata['type'] != $data['type']) {
                $data['pagedata'] = "";
            } else {
                $data['pagedata'] = is_array($data['pagedata']) ? $data['pagedata'] : [];
                $data['pagedata'] = array_merge($olddata['pagedata'], $data['pagedata']);
                $theme = PageThemeFactory::create($olddata['type']);
                $theme->data($data['pagedata']);
                $data['pagedata'] = $theme->serialize();
            }
            $result = $this->_pages_m->update('id=' . $id, $data);
            if ($this->_cache != null) {
                $return = $this->_cache->hdel($this->_cacheprefix . $this->_cachename, $id);
            }
            if ($result) {
                return $id;
            }
        } else {
            unset($data['id']);
            //添加
            $data['pagedata'] = '';
            return $this->_pages_m->add($data);
        }
    }


    public function getById($id)
    {
        if ($this->_cache != null) {
            $data = $this->_cache->hget($this->_cacheprefix . $this->_cachename, $id);
            if (!$data) {
                $data = $this->_pages_m->find('id=' . $id);
                $theme = PageThemeFactory::create($data['type']);
                $theme->data(unserialize($data['pagedata']));
                $data['pagedata'] = $theme->toArray();
                $this->_cache->hset($this->_cacheprefix . $this->_cachename, $id, serialize($data));
            } else {
                $data = unserialize($data);
            }
            return $data;
        } else {
            $data = $this->_pages_m->find('id=' . $id);
            $theme = PageThemeFactory::create($data['type']);
            $theme->data(unserialize($data['pagedata']));
            $data['pagedata'] = $theme->toArray();
            return $data;
        }

    }

    public function delById($id)
    {
        if ($this->_cache != null) {
            $this->_cache->hdel($this->_cacheprefix . $this->_cachename, $id);
        }
        return $this->_pages_m->delete('id=' . $id);
    }
}
