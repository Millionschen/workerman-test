<?php
/**
 * Created by PhpStorm.
 * User: millions
 * Date: 15/12/14
 * Time: 下午1:08
 */
namespace Workerman\Protocols;

/**
 * struct JsonProtocol
 * {
 *     unsigned int        pack_len,
 *     unsigned int        cmd,//命令字
 *     unsigned int        user_id 用户id
 *     unsigned int        timestamp,
 *     char[64]            verify 使用 hmac sha256加密
 *     char[pack_length-HEAD_LEN] body//包体
 * }
 * NNNN
 */
class Json
{

    /**
     * 包头长度
     * @var integer
     */
    const HEAD_LEN = 16;
    const VERIFY_LEN = 64;

    public static $empty = array(
        'cmd' => 0,
        'body' => '',
    );

    /**
     * 返回包长度
     * @param string $buffer
     * @return int return current package length
     */
    public static function input($buffer)
    {
        if(strlen($buffer) < self::HEAD_LEN)
        {
            return 0;
        }
        $data = unpack("Npack_len", $buffer);
        return $data['pack_len'];
    }

    /**
     * 获取整个包的buffer
     * @param array $data
     * @return string
     */
    public static function encode($data)
    {
        $data['body'] = json_encode($data['body']);
        $package_len = self::HEAD_LEN + strlen($data['body']);
        return pack("NNNN",
            $package_len,
            $data['user_id'],
            $data['cmd'],
            $data['return_code'])
        . $data['body'];
    }

    /**
     * 从二进制数据转换为数组
     * @param string $buffer
     * @return array
     */
    public static function decode($buffer)
    {
        $data = unpack("Npack_len/Nuser_id/Ncmd/Ntimestamp", $buffer);
        $data['verify'] = substr($buffer, self::HEAD_LEN, self::VERIFY_LEN);
        $data['body'] = json_decode(substr($buffer, self::HEAD_LEN+self::VERIFY_LEN), true);
        return $data;
    }
}
