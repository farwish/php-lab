<?php

/**
 * 如果不使用 Table 描述，默认表名将使用类名。
 *
 * Basic Mapping:
 * @doc http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/basic-mapping.html
 *
 * Column 拥有的属性：type, name, length, unique, nullable, precision, scale, columnDefinition, options
 * 属性 type 支持的映射：string, integer, smallint, bigint, boolean, decimal, date, time, datetime, datetimetz, text, object, array, simple_array, json_array, float, guid, blob
 * 自定义 type 类型的映射：http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/cookbook/custom-mapping-types.html
 * 使用类型为 DateTime 时：http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/cookbook/working-with-datetime.html
 * 每个实体类必须有一个主键, with Id annotation; 自增id使用 GeneratedValue 的默认策略 AUTO.
 * id 生成策略有：AUTO， SEQUENCE， IDENTITY， UUID， TABLE， NONE， CUSTOM
 *
 * `php ../../vendor/bin/doctrine orm:info`
 *
 * @Entity
 * @Table(name="article")
 */
class Article
{
    /**
	 * @Id
     * @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(type="string", length=255)
     */
    private $title;

    /**
     * @Column(type="string", length=3000)
     */
    private $content;

    /**
     * @Column(type="string", length=30)
     */
    private $author;

    /**
     * createTime property map to the create_time column with the datetime type.
     *
     * @Column(type="datetime", name="create_time")
     */
    private $createTime;

    /**
     * updateTime property map to the update_time column with the datetime type.
     *
     * @Column(type="datetime", name="update_time")
     */
    private $updateTime;
}

