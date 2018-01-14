<?php

/**
 * Stream context which is used whenever file operations are called without a context parameter.
 *
 * stream_context_set_default(array $options) can be used to set the default context.
 *
 * stream_context_get_default([array $options]) 的参数与 stream_context_create() 的第一个参数用法一致，
 * 用于设置默认的上下文，只不过没有第二个参数 - 上下文参数；
 * php5.3之后, 增加了 stream_context_set_default() 功能与 stream_context_get_default() 相同
 * stream_context_create() 用于将返回值传参指定上下文的情况。
 */

$default = stream_context_get_default();

var_dump($default);
