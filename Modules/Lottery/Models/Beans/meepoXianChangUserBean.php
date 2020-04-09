<?php

namespace Modules\Lottery\Models\Beans;

class  meepoXianChangUserBean
{
    protected $avatar = '';
    protected $id = 0;
    protected $nick_name = '';
    protected $openid = '';
    protected $db_mqwyk = '';
    protected $mobile = '';

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return meepoXianChangUserBean
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return meepoXianChangUserBean
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getNickName()
    {
        return $this->nick_name;
    }

    /**
     * @param string $nick_name
     * @return meepoXianChangUserBean
     */
    public function setNickName($nick_name)
    {
        $this->nick_name = $nick_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getOpenid()
    {
        return $this->openid;
    }

    /**
     * @param string $openid
     * @return meepoXianChangUserBean
     */
    public function setOpenid($openid)
    {
        $this->openid = $openid;
        return $this;
    }

    /**
     * @return string
     */
    public function getDbMqwyk()
    {
        return $this->db_mqwyk;
    }

    /**
     * @param string $db_mqwyk
     * @return meepoXianChangUserBean
     */
    public function setDbMqwyk($db_mqwyk)
    {
        $this->db_mqwyk = $db_mqwyk;
        return $this;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     * @return meepoXianChangUserBean
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    public function getOrigin()
    {
        $data = [
            'avatar' => $this->avatar,
            'bd_data' => [
                'bd_mqwyk' => $this->db_mqwyk,
                'mobile' => $this->mobile
            ],
            'id' => $this->id,
            'nick_name' => $this->nick_name,
            'openid' => $this->openid
        ];
        $this->resetData();
        return $data;
    }

    public function resetData()
    {
        $this->avatar = $this->db_mqwyk = $this->mobile = $this->nick_name = $this->openid = '';
        $this->id = 0;
        return $this;
    }
}