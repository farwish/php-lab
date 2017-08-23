# Stream Classes

> 用户自定义封装器可以用 stream_wrapper_register()  

> 用户自定义过滤器用 stream_filter_register()  

Stream函数分类
```
stream_bucket_*  - 资源流bucket
stream_context_* - 资源流上下文
stream_filter_*  - 资源流过滤器
stream_get_*     - 获取资源流的content/filter/line/meta_data/wrappers/...
stream_set_*     - 设置资源流的chunk_size/read_buffer/...
stream_socket_*  - 套接字流操作( 8个函数，配合 stream_set/get_* 设置 socket 流的行为 )
stream_wrapper_* - 资源流封装器

stream_copy_to_stream - 拷贝一个流的数据到另一个流( 参见copy() )
stream_encoding - 设置数据流的字符集
stream_is_local - 检测流是否是一个本地流
stream_notification_callback - 作为 stream_context_set_params 参数的回调函数
stream_resolve_include_path - 根据include路径解析文件名（返回绝对路径）
stream_select - 等同在传入的指定了秒和微妙超时的 streams 数组上运行 select() 系统调用
stream_supports_lock - 检测传入的流是否支持锁
```
