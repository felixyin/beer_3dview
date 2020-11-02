-- +----------------------------------------------------------------------
-- | Author: LSQ <2545644408@qq.com>
-- +----------------------------------------------------------------------
--
-- 表的结构 `cmf_im_ticket`
--

CREATE TABLE IF NOT EXISTS `cmf_im_ticket` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'ticket 创建时间',
  `out_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'ticket 过期时间',
  `ticket` varchar(32) NOT NULL DEFAULT '' COMMENT '及时通讯ticket',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='即时通讯---ticket表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------
--
-- 表的结构 `cmf_im_token`
--

CREATE TABLE IF NOT EXISTS `cmf_im_token` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户表id',
  `token` varchar(32) NOT NULL DEFAULT '' COMMENT '及时通讯token',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `device_type` varchar(32) NOT NULL DEFAULT '' COMMENT '设备类型;mobile,android,iphone,ipad,web,pc,mac,wxapp',

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='即时通讯---token表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------


