# doctrine

## Set up command line tool:

@doc http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/configuration.html  
1.obtain an entityManager.  
2.need to register apps entityManager to the console tool to make use of the tasks by creating a `cli-config.php` file.  

```
php ../../vendor/bin/doctrine
```

## Making class as entity, mapping its properties as columns

@doc http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/basic-mapping.html  
1.Making class as entity.  
2.Mapping class properties as columns.  

```
vi entity/Article.php  
php ../../vendor/bin/doctrine orm:info  
```


