# doctrine

## Install and Configuration

@doc ORM http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/configuration.html  
@doc DBAL http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html  

1.Obtain an entityManager: `bootstrap.php`
2.Register apps entityManager to the console tool to make use of the tasks by creating a `cli-config.php` file.

```
$ php ../../vendor/bin/doctrine
```

```
Doctrine Command Line Interface 2.6.0

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  help                               Displays help for a command
  list                               Lists commands
 dbal
  dbal:import                        Import SQL file(s) directly to Database.
  dbal:reserved-words                Checks if the current database contains identifiers that are reserved.
  dbal:run-sql                       Executes arbitrary SQL directly from the command line.
 orm
  orm:clear-cache:metadata           Clear all metadata cache of the various cache drivers
  orm:clear-cache:query              Clear all query cache of the various cache drivers
  orm:clear-cache:region:collection  Clear a second-level cache collection region
  orm:clear-cache:region:entity      Clear a second-level cache entity region
  orm:clear-cache:region:query       Clear a second-level cache query region
  orm:clear-cache:result             Clear all result cache of the various cache drivers
  orm:convert-d1-schema              [orm:convert:d1-schema] Converts Doctrine 1.x schema into a Doctrine 2.x schema
  orm:convert-mapping                [orm:convert:mapping] Convert mapping information between supported formats
  orm:ensure-production-settings     Verify that Doctrine is properly configured for a production environment
  orm:generate-entities              [orm:generate:entities] Generate entity classes and method stubs from your mapping information
  orm:generate-proxies               [orm:generate:proxies] Generates proxy classes for entity classes
  orm:generate-repositories          [orm:generate:repositories] Generate repository classes from your mapping information
  orm:info                           Show basic information about all mapped entities
  orm:mapping:describe               Display information about mapped objects
  orm:run-dql                        Executes arbitrary DQL directly from the command line
  orm:schema-tool:create             Processes the schema and either create it directly on EntityManager Storage Connection or generate the SQL output
  orm:schema-tool:drop               Drop the complete database schema of EntityManager Storage Connection or generate the corresponding SQL output
  orm:schema-tool:update             Executes (or dumps) the SQL needed to update the database schema to match the current mapping metadata
  orm:validate-schema                Validate the mapping files
```

usage explain:
```
dbal:import alconseek.sql 					把sql文件导入数据库，等同于 mysql -uroot -p alconseek << alconseek.sql
dbal:reserved-words							检查当前数据库是否包含保留的标识符.
dbal:run-sql "select * from article"		直接终端执行sql, 结果集以数组打印出来.

orm:convert-mapping -h						查看详细信息, 把 entity 信息生成支持的格式如 xml/yaml .
orm:ensure-production-settings				验证Doctrine是否工作在生产环境，isDevMode 决定是否缓存查询.
orm:generate-entities entity/				[选项也很重要] 从定义的映射信息生成 entity class 和 setter/getter 方法, 建议在有版本控制下操作，以便错误时恢复
											默认只会做 entity 更新，不是重新生成；指定的目录如果是映射文件所在，那么会在映射文件之上 update.
											如果只是作为数据访问的对象，建议使用；如果有许多其它逻辑在内不建议使用 entity-generator.
orm:generate-repositories 					生成 repository 类到指定目录.
orm:schema-tool:create [--dump-sql] 		直接执行(默认) or 打印sql，Create table article (...)
orm:schema-tool:drop --dump-sql/--force 	打印sql or 直接执行 or 两个选项都加，Drop table article.
orm:schema-tool:update --dump-sql/--force   打印sql or 直接执行 or 两个选项都加，Alter table article change ...
orm:validate-schema							验证 entity 文件是否正确，是否与数据库同步.
```

## Making class as entity, mapping its properties as columns

1.Making class as entity.  
2.Mapping class properties as columns.  

```
$ vi entity/Article.php  
$ php ../../vendor/bin/doctrine orm:info  
```

@doc 基础映射 http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/basic-mapping.html  

@doc 关系映射 http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/association-mapping.html  
	使用对象关系的要点是 从左往右 读这个关系，左边的单词表示当前Entity.  
	OneToMany: 一个当前 Entity 实例有多个相关 Entity 的实例.  
	ManyToOne: 多个当前 Entity 实例涉及一个相关 Entity 的实例.  
	OneToOne: 一个当前 Entity 实例涉及一个相关 Entity 的实例.  
	ManyToMany  

	ManyToOne, 单向  
	对象间最普通的关系。比如许多文章对应一个分类：  
	```
	/**
	 * @Entity
	 * @Table(name="article")
	 */
	class Article {
		/**
		 * 对应关系的类为 Category, 当前表中的外键名为 category_id, 关联的字段名是 id
		 *
		 * @ManyToOne(targetEntity="Category")
		 * @JoinColumn(name="category_id", referencedColumnName="id")
		 */
		private $category;
	}

	/**
	 * @Entity
	 * @Table(name="category");
	 */
	class Category {
		/**
		 * @Id
		 * @Column(type="integer")
		 * @GeneratedValue(strategy="AUTO")
		 */
		private $id;
	}

	OneToOne，单向  
	/** @Entity */
	class Product {
		// ...

		/**
		 * One Product has One Shipment.
		 * @OneToOne(targetEntity="Shipment")
		 * @JoinColumn(name="shipment_id", referencedColumnName="id")
		 */
		private $shipment;
	}

	/** @Entity */
	class Shipment {
	}


	```

## Reference Guide (1~33 chapter)

@doc http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/#reference-guide






